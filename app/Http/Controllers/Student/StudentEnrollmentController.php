<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Admission;
use App\Models\Subject;
use App\Models\TeacherSectionAssignment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StudentEnrollmentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display all enrollments (list).
     */
    public function index()
    {
        // Block access when student has no admission yet
        $admission = Admission::where('user_id', Auth::id())->first();
        if (!$admission) {
            return redirect()->route('student.admissions.create')
                ->with('error', 'Please submit an admission application before accessing enrollment.');
        }

        // Block access if admission is not approved
        if ($admission->status !== 'approved') {
            return redirect()->route('student.admissions.index')
                ->with('error', 'Your admission must be approved before you can access enrollment.');
        }

        $enrollments = Enrollment::with('admission')
            ->where('user_id', Auth::id())
            ->orderByDesc('school_year')
            ->orderBy('strand')
            ->orderBy('grade_level')
            ->orderBy('section')
            ->paginate(10);

        return view('student.enrollment.index', compact('enrollments'));
    }

    /**
     * Show form for creating enrollment.
     */
    public function create()
    {
        // Check if student already has enrollment for current school year
        $currentYear = $this->getCurrentSchoolYear();
        $existingEnrollment = Enrollment::where('user_id', auth()->id())
            ->where('school_year', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->first();
            
        if ($existingEnrollment) {
            return redirect()->route('student.enrollment.edit', $existingEnrollment->id)
                ->with('info', 'You already have an enrollment for ' . $currentYear . '. You can edit it below.');
        }

        $admission = Admission::where('user_id', auth()->id())->first();
        if (!$admission) {
            return redirect()->route('student.admissions.create')
                ->with('error', 'Please submit an admission application before enrolling.');
        }

        // Block access if admission is not approved
        if ($admission->status !== 'approved') {
            return redirect()->route('student.admissions.index')
                ->with('error', 'Your admission must be approved before you can enroll.');
        }

        // Get available subjects for transferee selection
        // Note: We'll load subjects dynamically based on selected grade level in the form
        $isTransferee = $admission->student_type === 'transferee';
        $availableSubjects = collect();
        $currentSubjects = collect();
        
        // Get initial subjects based on admission year level (will be updated when grade level is selected)
        if ($isTransferee) {
            $availableSubjects = Subject::where(function($q) use ($admission) {
                    $q->whereNull('grade_level')->orWhere('grade_level', $admission->year_level);
                })
                ->when($admission->strand, function($q) use ($admission) {
                    $q->where(function($query) use ($admission) {
                        $query->whereNull('strand')->orWhere('strand', $admission->strand);
                    });
                })
                ->orderBy('name')
                ->get();
        }

        // Get active school year (excluding hidden)
        $activeSchoolYear = SchoolYear::where('is_active', true)->where('is_hidden', false)->first();

        return view('student.enrollment.create', compact('admission', 'isTransferee', 'availableSubjects', 'currentSubjects', 'activeSchoolYear'));
    }

    /**
     * Get current school year (e.g., 2025-2026)
     */
    private function getCurrentSchoolYear()
    {
        $y = (int) now()->year;
        $m = (int) now()->month;
        // Academic year: if month >= June, use current-next; else previous-current
        $start = $m >= 6 ? $y : ($y - 1);
        return $start . '-' . ($start + 1);
    }

    /**
     * Store enrollment record.
     */
    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'admission_id'     => 'required|exists:admissions,id',
            'student_type'     => 'required|in:new,old,transferee',
            'grade_level'      => 'required|string|max:50',
            'strand'           => 'nullable|string|max:100',
            'tvl_branch'       => 'nullable|in:CSS,EIM',
            'section'          => 'nullable|string|max:100',
            'school_year'      => 'nullable|string|max:20', // Made nullable to allow default
            'semester'         => 'nullable|string|max:20',
            'guardian_name'    => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
            'selected_subjects' => 'nullable|array',
            'selected_subjects.*' => 'exists:subjects,id',
            'dropped_subjects' => 'nullable|array',
            'dropped_subjects.*' => 'exists:subjects,id',
        ]);

        // Set default school year to active school year if not provided
        if (empty($validated['school_year'])) {
            $activeSchoolYear = SchoolYear::where('is_active', true)->where('is_hidden', false)->first();
            if ($activeSchoolYear) {
                $validated['school_year'] = $activeSchoolYear->school_year;
            } else {
                return back()->withErrors(['school_year' => 'No active school year found. Please contact administrator.'])->withInput();
            }
        }

        // Ensure strand is empty for Grade 7-10
        $gradeLevel = $validated['grade_level'] ?? null;
        if ($gradeLevel && in_array($gradeLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        if (($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            return back()->withErrors(['tvl_branch' => 'Please select a TVL branch.'])->withInput();
        }

        // Validate subject selection for transferees
        if ($validated['student_type'] === 'transferee') {
            [$validated['selected_subjects'], $validated['dropped_subjects']] = $this->validateTransfereeSubjects(
                $validated['selected_subjects'] ?? [],
                $validated['dropped_subjects'] ?? [],
                $validated['grade_level'],
                $validated['strand'] ?? null
            );
        }

        // Ensure the submitted admission belongs to the currently authenticated student
        $admission = Admission::where('id', $validated['admission_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$admission) {
            abort(403, 'Unauthorized action. This admission record does not belong to you.');
        }

        // Ensure the admission is approved before creating an enrollment
        if ($admission->status !== 'approved') {
            return redirect()->route('student.admissions.index')
                ->with('error', 'Your admission must be approved before you can enroll.');
        }

        // Check if student already has enrollment for this school year
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('school_year', $validated['school_year'])
            ->where('status', '!=', 'cancelled')
            ->first();
            
        if ($existingEnrollment) {
            return redirect()->route('student.enrollment.edit', $existingEnrollment->id)
                ->with('error', 'You already have an enrollment for ' . $validated['school_year'] . '. Please edit your existing enrollment instead.');
        }

        // Update admission's student_type if changed
        if ($admission->student_type !== $validated['student_type']) {
            $admission->update(['student_type' => $validated['student_type']]);
        }

        // No requirement uploads in Enrollment; all requirements were collected in Admission

        // Determine section assignment based on GWA (JHS only).
        // Higher GWA -> first sections, lower GWA -> later sections.
        $grade = $validated['grade_level'];
        $isShs = in_array($grade, ['Grade 11','Grade 12']);
        $jhsDefaults = [
            'Grade 7' => ['HOPE', 'FAITH', 'LOVE', 'WISDOM'],
            'Grade 8' => ['CASILI', 'LANDONG', 'MORA', 'SAN JUAN'],
            'Grade 9' => ['ACACIA', 'LAUAN', 'NARRA'],
            'Grade 10' => ['BERYL', 'DIAMOND', 'JADE'],
        ];
        $assignedSection = null;
        if (!$isShs && isset($jhsDefaults[$grade])) {
            $pool = $jhsDefaults[$grade];
            $gwa = $admission->gwa;

            if ($gwa !== null) {
                // Map GWA to an index in the section pool
                if ($gwa >= 90) {
                    $index = 0;
                } elseif ($gwa >= 85) {
                    $index = 1;
                } elseif ($gwa >= 80) {
                    $index = 2;
                } else {
                    $index = count($pool) - 1;
                }
                $index = max(0, min($index, count($pool) - 1));
                $assignedSection = $pool[$index];
            } else {
                // If no GWA provided, fall back to random section
                $assignedSection = $pool[array_rand($pool)];
            }
        }

        // Apply fallback logic for guardian: provided -> admission guardian -> father -> mother
        $guardianName = $validated['guardian_name'] 
            ?? $admission->guardian_name 
            ?? $admission->father_name 
            ?? $admission->mother_name 
            ?? null;
        $guardianContact = $validated['guardian_contact'] 
            ?? $admission->guardian_contact 
            ?? $admission->father_contact 
            ?? $admission->mother_contact 
            ?? null;

        // ✅ Create Enrollment (student cannot choose section; admin can edit later)
        $enrollmentData = [
            'admission_id'     => $admission->id,
            'user_id'          => Auth::id(), // current logged in user
            'grade_level'      => $validated['grade_level'],
            'strand'           => $validated['strand'] ?? null,
            'tvl_branch'       => $validated['tvl_branch'] ?? null,
            'selected_subjects' => $validated['selected_subjects'] ?? null,
            'dropped_subjects' => $validated['dropped_subjects'] ?? null,
            'section'          => $assignedSection,
            'school_year'      => $validated['school_year'],
            'semester'         => $validated['semester'] ?? null,
            'guardian_name'    => $guardianName,
            'guardian_contact' => $guardianContact,
            'status'           => 'pending', // default status
        ];
        
        $enrollment = Enrollment::create($enrollmentData);

        // ✅ Redirect with success message
        return redirect()->route('student.enrollment.index')
            ->with('success', 'Enrollment submitted successfully!');
    }

    /**
     * Show form for editing enrollment.
     */
    public function edit(Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);
        
        $admission = $enrollment->admission;
        if (!$admission) {
            return redirect()->route('student.enrollment.index')
                ->with('error', 'Admission record not found.');
        }

        // Block access if admission is not approved
        if ($admission->status !== 'approved') {
            return redirect()->route('student.admissions.index')
                ->with('error', 'Your admission must be approved before you can access enrollment.');
        }
        
        // Get available subjects for transferee selection
        $isTransferee = $admission->student_type === 'transferee';
        $availableSubjects = collect();
        $currentSubjects = collect();
        
        if ($isTransferee) {
            // Get subjects based on enrollment grade level and strand
            $availableSubjects = Subject::where(function($q) use ($enrollment) {
                    $q->whereNull('grade_level')->orWhere('grade_level', $enrollment->grade_level);
                })
                ->when($enrollment->strand, function($q) use ($enrollment) {
                    $q->where(function($query) use ($enrollment) {
                        $query->whereNull('strand')->orWhere('strand', $enrollment->strand);
                    });
                })
                ->orderBy('name')
                ->get();
            
            // Get currently selected and dropped subjects
            $currentSubjects = [
                'selected' => $enrollment->selected_subjects ?? [],
                'dropped' => $enrollment->dropped_subjects ?? [],
            ];
        } else {
            $availableSubjects = collect();
            $currentSubjects = collect();
        }
        
        return view('student.enrollment.create', [
            'enrollment' => $enrollment,
            'admission' => $admission,
            'editing' => true,
            'isTransferee' => $isTransferee,
            'availableSubjects' => $availableSubjects,
            'currentSubjects' => $currentSubjects,
        ]);
    }


    /**
     * Show single enrollment record.
     */
    public function show(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        $admission = $enrollment->admission;
        
        // Block access if admission is not approved
        if (!$admission || $admission->status !== 'approved') {
            return redirect()->route('student.admissions.index')
                ->with('error', 'Your admission must be approved before you can view enrollment details.');
        }

        // Get assigned subjects for this enrollment
        $subjects = Subject::where(function($q) use ($enrollment) {
                $q->whereNull('grade_level')->orWhere('grade_level', $enrollment->grade_level);
            })
            ->when($enrollment->strand, function($q) use ($enrollment) {
                $q->where(function($query) use ($enrollment) {
                    $query->whereNull('strand')->orWhere('strand', $enrollment->strand);
                });
            })
            ->when($enrollment->section && $enrollment->grade_level, function($q) use ($enrollment) {
                // Also get subjects from teacher assignments for this section
                $assignmentSubjectIds = TeacherSectionAssignment::where('grade_level', $enrollment->grade_level)
                    ->where('section', $enrollment->section)
                    ->when($enrollment->strand, function($query) use ($enrollment) {
                        $query->where(function($q) use ($enrollment) {
                            $q->where('strand', $enrollment->strand)
                              ->orWhereNull('strand');
                        });
                    })
                    ->when($enrollment->tvl_branch, function($query) use ($enrollment) {
                        $query->where(function($q) use ($enrollment) {
                            $q->where('tvl_branch', $enrollment->tvl_branch)
                              ->orWhereNull('tvl_branch');
                        });
                    })
                    ->pluck('subject_id')
                    ->unique();
                
                if ($assignmentSubjectIds->isNotEmpty()) {
                    $q->orWhereIn('id', $assignmentSubjectIds);
                }
            })
            ->with(['teacherAssignments' => function($q) use ($enrollment) {
                $q->where('grade_level', $enrollment->grade_level)
                  ->where('section', $enrollment->section)
                  ->when($enrollment->strand, function($query) use ($enrollment) {
                      $query->where(function($q) use ($enrollment) {
                          $q->where('strand', $enrollment->strand)->orWhereNull('strand');
                      });
                  })
                  ->when($enrollment->tvl_branch, function($query) use ($enrollment) {
                      $query->where(function($q) use ($enrollment) {
                          $q->where('tvl_branch', $enrollment->tvl_branch)->orWhereNull('tvl_branch');
                      });
                  })
                  ->with('teacher');
            }])
            ->orderBy('name')
            ->get();

        return view('student.enrollment.show', compact('enrollment', 'subjects'));
    }

    /**
     * Update enrollment details (students can edit their own pending enrollments).
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);
        
        $admission = $enrollment->admission;
        
        // Block access if admission is not approved
        if (!$admission || $admission->status !== 'approved') {
            return redirect()->route('student.admissions.index')
                ->with('error', 'Your admission must be approved before you can update enrollment.');
        }
        
        // Students can only edit pending or cancelled enrollments
        if (!in_array($enrollment->status, ['pending', 'cancelled']) && Auth::user()->role === 'student') {
            return back()->with('error', 'You can only edit enrollments with pending or cancelled status.');
        }

        // ✅ Validate input
        $validated = $request->validate([
            'student_type'     => 'required|in:new,old,transferee',
            'grade_level'      => 'required|string|max:50',
            'strand'           => 'nullable|string|max:100',
            'tvl_branch'       => 'nullable|in:CSS,EIM',
            'semester'         => 'nullable|string|max:20',
            'guardian_name'    => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
            'selected_subjects' => 'nullable|array',
            'selected_subjects.*' => 'exists:subjects,id',
            'dropped_subjects' => 'nullable|array',
            'dropped_subjects.*' => 'exists:subjects,id',
        ]);

        // Apply fallback logic for guardian: provided -> existing enrollment -> admission guardian -> father -> mother
        $admission = $enrollment->admission;
        $guardianName = $validated['guardian_name'] 
            ?? $enrollment->guardian_name 
            ?? $admission->guardian_name 
            ?? $admission->father_name 
            ?? $admission->mother_name 
            ?? null;
        $guardianContact = $validated['guardian_contact'] 
            ?? $enrollment->guardian_contact 
            ?? $admission->guardian_contact 
            ?? $admission->father_contact 
            ?? $admission->mother_contact 
            ?? null;

        // Ensure strand is empty for Grade 7-10
        $gradeLevel = $validated['grade_level'] ?? null;
        if ($gradeLevel && in_array($gradeLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        // Update admission's student_type if changed
        if ($admission->student_type !== $validated['student_type']) {
            $admission->update(['student_type' => $validated['student_type']]);
        }

        // Validate subject selection for transferees
        if ($validated['student_type'] === 'transferee') {
            [$validated['selected_subjects'], $validated['dropped_subjects']] = $this->validateTransfereeSubjects(
                $validated['selected_subjects'] ?? [],
                $validated['dropped_subjects'] ?? [],
                $validated['grade_level'],
                $validated['strand'] ?? null
            );
        }

        // Students cannot change school_year or section (admin/registrar only)
        // But we can update other fields
        $updateData = [
            'grade_level'      => $validated['grade_level'],
            'strand'           => $validated['strand'] ?? null,
            'tvl_branch'       => $validated['tvl_branch'] ?? null,
            'semester'         => $validated['semester'] ?? null,
            'guardian_name'    => $guardianName,
            'guardian_contact' => $guardianContact,
            'selected_subjects' => $validated['selected_subjects'] ?? null,
            'dropped_subjects' => $validated['dropped_subjects'] ?? null,
        ];

        // Store original status before updating
        $originalStatus = $enrollment->status;
        
        // If enrollment was cancelled, reset status to pending when edited
        if ($originalStatus === 'cancelled') {
            $updateData['status'] = 'pending';
            $updateData['cancellation_remarks'] = null; // Clear cancellation remarks
        }

        $enrollment->update($updateData);

        $message = $originalStatus === 'cancelled' 
            ? 'Enrollment updated and resubmitted for review!'
            : 'Enrollment updated successfully!';

        return redirect()->route('student.enrollment.index')
            ->with('success', $message);
    }

    /**
     * Validate transferee subject selections (require at least one to take,
     * prevent overlap with dropped subjects, and ensure subjects match grade/strand).
     *
     * @return array{0: array<int, int>, 1: ?array<int, int>}
     */
    private function validateTransfereeSubjects(array $selected, array $dropped, string $gradeLevel, ?string $strand): array
    {
        $selectedIds = array_values(array_unique(array_map('intval', $selected)));
        $droppedIds = array_values(array_unique(array_map('intval', $dropped)));

        if (empty($selectedIds)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'selected_subjects' => 'Please select at least one subject to take.',
            ]);
        }

        if (!empty(array_intersect($selectedIds, $droppedIds))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'dropped_subjects' => 'A subject cannot be selected in both Subjects to Take and Subjects to Drop.',
            ]);
        }

        $allowedIds = Subject::where(function ($q) use ($gradeLevel) {
                $q->whereNull('grade_level')->orWhere('grade_level', $gradeLevel);
            })
            ->when($strand, function ($q) use ($strand) {
                $q->where(function ($query) use ($strand) {
                    $query->whereNull('strand')->orWhere('strand', $strand);
                });
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (!empty(array_diff($selectedIds, $allowedIds))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'selected_subjects' => 'One or more selected subjects to take do not belong to your grade level or strand.',
            ]);
        }

        if (!empty(array_diff($droppedIds, $allowedIds))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'dropped_subjects' => 'One or more selected subjects to drop do not belong to your grade level or strand.',
            ]);
        }

        return [$selectedIds, !empty($droppedIds) ? $droppedIds : null];
    }

    /**
     * Delete enrollment (if needed).
     */
    // Delete functionality removed to preserve all data
    public function destroy(Enrollment $enrollment)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('student.enrollment.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }
}
