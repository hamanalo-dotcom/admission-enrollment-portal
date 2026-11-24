<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherSchoolYearController extends Controller
{
    /**
     * Display school years with search functionality
     */
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        // Get all unique school years from enrollments
        $schoolYearsFromEnrollments = Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        // Get school years from school_years table
        $query = SchoolYear::query();

        // Filter out school years that are both deactivated AND hidden (unless show_hidden is requested)
        if (!$request->has('show_hidden') || !$request->show_hidden) {
            $query->where(function($q) {
                $q->where('is_active', true)  // Show active school years (even if hidden)
                  ->orWhere('is_hidden', false); // Or show inactive but not hidden school years
            });
        }

        // Search by school year
        if ($request->has('search') && $request->search) {
            $query->where('school_year', 'like', "%{$request->search}%");
        }

        $schoolYears = $query->orderBy('school_year', 'desc')->get();

        // Get existing school year values
        $existingSchoolYearValues = $schoolYears->pluck('school_year')->toArray();

        // Merge with enrollments that don't have a school_years record
        foreach ($schoolYearsFromEnrollments as $sy) {
            if (!in_array($sy, $existingSchoolYearValues)) {
                // Create database record for school years that only exist in enrollments
                // This ensures all school years have an id and can be managed (edit, deactivate, hide)
                // Using firstOrCreate to avoid duplicates if record was created between queries
                $schoolYearRecord = SchoolYear::firstOrCreate(
                    ['school_year' => $sy],
                    [
                        'is_active' => false, // Default to inactive
                        'is_hidden' => false, // Default to not hidden
                    ]
                );
                
                // Check if it should be shown based on filters
                $shouldAdd = true;
                if (!$request->has('show_hidden') || !$request->show_hidden) {
                    // Hide if both deactivated AND hidden
                    if (!$schoolYearRecord->is_active && $schoolYearRecord->is_hidden) {
                        $shouldAdd = false;
                    }
                }
                
                if ($shouldAdd) {
                    $schoolYears->push($schoolYearRecord);
                }
            }
        }

        // Filter out school years that are both deactivated AND hidden (for already merged records)
        if (!$request->has('show_hidden') || !$request->show_hidden) {
            $schoolYears = $schoolYears->filter(function($sy) {
                // If it has an id, check the database record
                if (isset($sy->id)) {
                    return $sy->is_active || !$sy->is_hidden;
                }
                // Virtual records (no id) are always shown
                return true;
            });
        }

        // Sort by school year descending
        $schoolYears = $schoolYears->sortByDesc('school_year')->values();

        return view('teacher.school-years.index', compact('schoolYears'));
    }

    /**
     * Show enrolled students for a specific school year (for teacher's assigned sections)
     */
    public function show(Request $request, $schoolYear)
    {
        $teacherId = Auth::id();

        // Get teacher's assigned sections for this school year
        $assignments = \App\Models\TeacherSectionAssignment::where('teacher_id', $teacherId)
            ->where('school_year', $schoolYear)
            ->get();

        // Get enrollments for teacher's assigned sections
        if ($assignments->isEmpty()) {
            $enrollments = collect();
        } else {
            $enrollments = Enrollment::with(['user', 'admission'])
                ->where('school_year', $schoolYear)
                ->where('status', 'enrolled')
                ->where(function($q) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $q->orWhere(function($query) use ($assignment) {
                            $query->where('grade_level', $assignment->grade_level)
                                ->where('section', $assignment->section);
                            if ($assignment->strand) {
                                $query->where('strand', $assignment->strand);
                            }
                        });
                    }
                })
                ->orderBy('grade_level')
                ->orderBy('section')
                ->orderBy('user_id')
                ->get();
        }

        // Get school year record if exists
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();

        // Group by grade level and section
        $grouped = $enrollments->groupBy(function($enrollment) {
            return $enrollment->grade_level . ' - ' . ($enrollment->section ?? 'No Section');
        });

        return view('teacher.school-years.show', compact('enrollments', 'schoolYear', 'grouped', 'schoolYearRecord', 'assignments'));
    }

    /**
     * Download enrolled students data as CSV (for teacher's assigned sections)
     */
    public function download(Request $request, $schoolYear)
    {
        $teacherId = Auth::id();

        // Get teacher's assigned sections for this school year
        $assignments = \App\Models\TeacherSectionAssignment::where('teacher_id', $teacherId)
            ->where('school_year', $schoolYear)
            ->get();

        // Get enrollments for teacher's assigned sections
        if ($assignments->isEmpty()) {
            return back()->with('error', 'You have no assigned sections for this school year.');
        }

        $enrollments = Enrollment::with(['user', 'admission'])
            ->where('school_year', $schoolYear)
            ->where('status', 'enrolled')
            ->where(function($q) use ($assignments) {
                foreach ($assignments as $assignment) {
                    $q->orWhere(function($query) use ($assignment) {
                        $query->where('grade_level', $assignment->grade_level)
                            ->where('section', $assignment->section);
                        if ($assignment->strand) {
                            $query->where('strand', $assignment->strand);
                        }
                    });
                }
            })
            ->orderBy('grade_level')
            ->orderBy('section')
            ->orderBy('user_id')
            ->get();

        $filename = 'enrollments_' . $schoolYear . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($enrollments) {
            $file = fopen('php://output', 'w');
            
            // Check if any enrollment is SHS to determine if we need strand column
            $hasShs = $enrollments->contains(function($enrollment) {
                return in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
            });

            // CSV Headers
            $headers = [
                'Student ID',
                'Name',
                'Grade Level',
                'Section',
            ];
            
            if ($hasShs) {
                $headers[] = 'Strand';
            }
            
            $headers = array_merge($headers, [
                'Status',
                'Guardian Name',
                'Guardian Contact',
                'Email',
                'Contact No'
            ]);
            
            fputcsv($file, $headers);

            // CSV Data
            foreach ($enrollments as $enrollment) {
                $user = $enrollment->user;
                $admission = $enrollment->admission;
                
                // Use parent details if guardian details are missing
                $guardianName = $enrollment->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? 'N/A';
                $guardianContact = $enrollment->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? 'N/A';
                
                $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
                
                $row = [
                    $user->id,
                    ($user->firstname ?? '') . ' ' . ($user->lastname ?? ''),
                    $enrollment->grade_level,
                    $enrollment->section ?? 'N/A',
                ];
                
                if ($hasShs) {
                    $row[] = $isShs ? ($enrollment->strand ?? 'N/A') : 'N/A';
                }
                
                $row = array_merge($row, [
                    $enrollment->status,
                    $guardianName,
                    $guardianContact,
                    $user->email ?? 'N/A',
                    $admission->contact_no ?? 'N/A',
                ]);
                
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Toggle hidden status of school year (Restricted to Registrar)
     */
    public function toggleHidden(Request $request, $schoolYear)
    {
        abort(403, 'Unauthorized action. Managing school year visibility is restricted to Registrars.');
    }
}
