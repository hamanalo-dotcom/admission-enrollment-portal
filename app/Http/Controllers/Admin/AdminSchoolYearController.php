<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminSchoolYearController extends Controller
{
    /**
     * Display school years with search functionality
     */
    public function index(Request $request)
    {
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
                // Skip auto-creating if a soft-deleted record exists
                $existingRecord = SchoolYear::withTrashed()->where('school_year', $sy)->first();
                if ($existingRecord) {
                    if ($existingRecord->trashed()) {
                        continue;
                    }
                    $schoolYearRecord = $existingRecord;
                } else {
                    // Create database record for school years that only exist in enrollments
                    // This ensures all school years have an id and can be managed (edit, deactivate, hide)
                    $schoolYearRecord = SchoolYear::create([
                        'school_year' => $sy,
                        'is_active' => false, // Default to inactive
                        'is_hidden' => false, // Default to not hidden
                    ]);
                }
                
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

        return view('admin.school-years.index', compact('schoolYears'));
    }

    /**
     * Show enrolled students for a specific school year
     */
    public function show(Request $request, $schoolYear)
    {
        $enrollments = Enrollment::with(['user', 'admission'])
            ->where('school_year', $schoolYear)
            ->orderBy('grade_level')
            ->orderBy('section')
            ->orderBy('user_id')
            ->get();

        // Get school year record if exists
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();

        // Group by grade level and section
        $grouped = $enrollments->groupBy(function($enrollment) {
            return $enrollment->grade_level . ' - ' . ($enrollment->section ?? 'No Section');
        });

        return view('admin.school-years.show', compact('enrollments', 'schoolYear', 'grouped', 'schoolYearRecord'));
    }

    /**
     * Download enrolled students data as CSV
     */
    public function download(Request $request, $schoolYear)
    {
        $enrollments = Enrollment::with(['user', 'admission'])
            ->where('school_year', $schoolYear)
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
     * Create or update school year record
     */
    public function store(Request $request)
    {
        $validated = $this->validateSchoolYear($request);

        // Convert checkbox value to boolean
        $isActive = $request->has('is_active') && $request->is_active == '1';

        // If setting this as active, deactivate all others
        if ($isActive) {
            SchoolYear::where('is_active', true)->update(['is_active' => false]);
        }

        // If a soft-deleted record exists for this school year, restore it instead of creating a new one
        $existingTrashed = SchoolYear::withTrashed()
            ->where('school_year', $validated['school_year'])
            ->first();

        if ($existingTrashed && $existingTrashed->trashed()) {
            $existingTrashed->restore();
            $existingTrashed->update([
                'is_active' => $isActive,
                'is_hidden' => false,
            ]);
        } else {
            SchoolYear::create([
                'school_year' => $validated['school_year'],
                'is_active' => $isActive,
            ]);
        }

        return back()->with('success', 'School year created successfully!');
    }

    /**
     * Show form for editing school year
     */
    public function edit($schoolYear)
    {
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();
        
        if (!$schoolYearRecord) {
            return back()->with('error', 'School year not found.');
        }

        return view('admin.school-years.edit', compact('schoolYearRecord'));
    }

    /**
     * Update school year
     */
    public function update(Request $request, $schoolYear)
    {
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();
        
        if (!$schoolYearRecord) {
            return back()->with('error', 'School year not found.');
        }

        $validated = $this->validateSchoolYear($request, $schoolYearRecord->id);

        // Convert checkbox value to boolean
        $isActive = $request->has('is_active') && $request->is_active == '1';

        // If setting this as active, deactivate all others
        if ($isActive) {
            SchoolYear::where('is_active', true)
                ->where('id', '!=', $schoolYearRecord->id)
                ->update(['is_active' => false]);
        }

        $schoolYearRecord->update([
            'school_year' => $validated['school_year'],
            'is_active' => $isActive,
        ]);

        // Determine the correct route based on the current route
        $routeName = $request->routeIs('registrar.*') ? 'registrar.school-years.index' : 'admin.school-years.index';
        
        return redirect()->route($routeName)
            ->with('success', 'School year updated successfully!');
    }

    /**
     * Validate the school year input with formatting rules.
     */
    private function validateSchoolYear(Request $request, $ignoreId = null)
    {
        $rules = [
            'school_year' => [
                'required',
                'regex:/^\d{4}-\d{4}$/',
                Rule::unique('school_years', 'school_year')
                    ->ignore($ignoreId)
                    ->whereNull('deleted_at'),
            ],
            'is_active' => 'nullable',
        ];

        $messages = [
            'school_year.regex' => 'School year must follow the YYYY-YYYY format (e.g., 2025-2026).',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function($validator) use ($request) {
            $schoolYear = $request->input('school_year');

            if ($schoolYear && preg_match('/^(\d{4})-(\d{4})$/', $schoolYear, $matches)) {
                $start = (int) $matches[1];
                $end = (int) $matches[2];

                if ($end !== $start + 1) {
                    $validator->errors()->add(
                        'school_year',
                        'End year must be exactly 1 greater than the start year (e.g., 2025-2026).'
                    );
                }
            }
        });

        return $validator->validate();
    }

    /**
     * Toggle active status of school year
     */
    public function toggleActive($schoolYear)
    {
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();
        
        if (!$schoolYearRecord) {
            return back()->with('error', 'School year not found.');
        }

        // Get the current status before updating
        $currentStatus = $schoolYearRecord->is_active;
        $newStatus = !$currentStatus;

        // If activating this one, deactivate all others
        if ($newStatus) {
            SchoolYear::where('is_active', true)->update(['is_active' => false]);
        }

        // Update the school year record
        $schoolYearRecord->update([
            'is_active' => $newStatus,
        ]);

        // Refresh the model to get the updated value
        $schoolYearRecord->refresh();

        $status = $newStatus ? 'activated' : 'deactivated';
        return back()->with('success', "School year {$status} successfully!");
    }

    /**
     * Toggle hidden status of school year
     */
    public function toggleHidden(Request $request, $schoolYear)
    {
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();
        
        if (!$schoolYearRecord) {
            return back()->with('error', 'School year not found.');
        }

        $schoolYearRecord->update([
            'is_hidden' => !$schoolYearRecord->is_hidden,
        ]);

        $status = $schoolYearRecord->is_hidden ? 'hidden' : 'unhidden';
        
        // Preserve show_hidden parameter if it was set
        $redirectParams = [];
        if ($request->has('show_hidden') && $request->show_hidden) {
            $redirectParams['show_hidden'] = 1;
        }
        
        // Determine the correct route based on the current route
        $routeName = $request->routeIs('registrar.*') ? 'registrar.school-years.index' : 'admin.school-years.index';
        
        return redirect()->route($routeName, $redirectParams)
            ->with('success', "School year {$status} successfully!");
    }

    /**
     * Soft-delete a school year (move to recycle bin)
     */
    public function destroy(Request $request, $schoolYear)
    {
        $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();

        if (!$schoolYearRecord) {
            return back()->with('error', 'School year not found.');
        }

        if ($schoolYearRecord->is_active) {
            return back()->with('error', 'Cannot delete an active school year. Deactivate it first.');
        }

        $schoolYearRecord->delete(); // soft delete

        $routeName = $request->routeIs('registrar.*') ? 'registrar.school-years.index' : 'admin.school-years.index';

        return redirect()->route($routeName)
            ->with('success', 'School year moved to recycle bin.');
    }

    /**
     * Show soft-deleted (trashed) school years
     */
    public function trashed(Request $request)
    {
        $schoolYears = SchoolYear::onlyTrashed()
            ->orderBy('school_year', 'desc')
            ->get();

        return view('admin.school-years.trashed', compact('schoolYears'));
    }

    /**
     * Restore a soft-deleted school year
     */
    public function restore(Request $request, $schoolYear)
    {
        $schoolYearRecord = SchoolYear::onlyTrashed()
            ->where('school_year', $schoolYear)
            ->firstOrFail();

        $schoolYearRecord->restore();

        $routeName = $request->routeIs('registrar.*') ? 'registrar.school-years.trashed' : 'admin.school-years.trashed';

        return redirect()->route($routeName)
            ->with('success', 'School year restored successfully.');
    }

    /**
     * Permanent deletion of school years is disabled to preserve all historical records.
     * All school-year records are retained; use restore() to unarchive a soft-deleted record.
     */
    public function forceDelete(Request $request, $schoolYear)
    {
        // Permanent deletion is intentionally disabled.
        // No school year record may be permanently destroyed.
        $routeName = $request->routeIs('registrar.*') ? 'registrar.school-years.trashed' : 'admin.school-years.trashed';

        return redirect()->route($routeName)
            ->with('error', 'Permanent deletion of school years is not allowed. All records are preserved for historical purposes.');
    }
}
