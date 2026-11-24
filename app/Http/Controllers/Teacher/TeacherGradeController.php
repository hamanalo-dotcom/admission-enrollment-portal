<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\TeacherSectionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeacherGradeController extends Controller
{
    /**
     * Display the form to submit grades for a specific section/subject
     */
    public function create(Request $request)
    {
        $teacher = Auth::user();
        
        // Get grade level, section, and subject from request
        $gradeLevel = $request->input('grade_level');
        $section = $request->input('section');
        $subjectId = $request->input('subject_id');
        $schoolYear = $request->input('school_year', date('Y') . '-' . (date('Y') + 1));
        $gradingPeriod = $request->input('grading_period', 'First Quarter');
        
        // Validate that teacher is assigned to this section/subject
        $assignmentQuery = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('school_year', $schoolYear);

        if ($subjectId) {
            $assignmentQuery->where('subject_id', $subjectId);
        }

        $assignment = $assignmentQuery->first();
        
        if (!$assignment) {
            return redirect()->route('teacher.index')
                ->with('error', 'You are not assigned to this section/subject.');
        }

        // Verify the selected subject belongs to the authenticated teacher's assignment
        if ($subjectId && $assignment->subject_id != $subjectId) {
            return redirect()->route('teacher.index')
                ->with('error', 'You are not assigned to this section/subject.');
        }

        if (!$subjectId && $assignment->subject_id) {
            $subjectId = $assignment->subject_id;
        }
        
        // Retrieve only enrolled students matching the selected teacher assignment
        $enrollmentsQuery = Enrollment::where('grade_level', $assignment->grade_level)
            ->where('section', $assignment->section)
            ->where('school_year', $assignment->school_year)
            ->where('status', 'enrolled');

        if (!empty($assignment->strand)) {
            $enrollmentsQuery->where('strand', $assignment->strand);
        } else {
            $enrollmentsQuery->whereNull('strand');
        }

        if (!empty($assignment->tvl_branch)) {
            $enrollmentsQuery->where('tvl_branch', $assignment->tvl_branch);
        } else {
            $enrollmentsQuery->whereNull('tvl_branch');
        }

        $enrollments = $enrollmentsQuery->with('admission', 'user')->get();
        
        // Get subject
        $subject = $subjectId ? Subject::find($subjectId) : null;
        
        // Determine grading periods based on grade level
        $isShs = in_array($gradeLevel, ['Grade 11', 'Grade 12']);
        $gradingPeriods = [];
        $semester = null;
        
        if ($isShs) {
            // For SHS: Get semester from enrollments
            // Check if all students are in the same semester
            $semesters = $enrollments->pluck('semester')->filter()->unique()->values();
            
            if ($semesters->count() === 1) {
                // All students in same semester
                $semester = $semesters->first();
            } elseif ($semesters->count() > 1) {
                // Mixed semesters - show both options
                $semester = 'Mixed';
            } else {
                // No semester set - default to 1st
                $semester = '1st Semester';
            }
            
            // SHS grading periods based on semester
            if ($semester === 'Mixed' || $semesters->count() > 1) {
                // Show all semester options if mixed
                $gradingPeriods = [
                    '1st Semester Midterm', 
                    '1st Semester Final', 
                    '2nd Semester Midterm', 
                    '2nd Semester Final'
                ];
            } elseif (stripos($semester, '1st') !== false || stripos($semester, 'first') !== false) {
                $gradingPeriods = ['1st Semester Midterm', '1st Semester Final'];
            } elseif (stripos($semester, '2nd') !== false || stripos($semester, 'second') !== false) {
                $gradingPeriods = ['2nd Semester Midterm', '2nd Semester Final'];
            } else {
                // Default to 1st semester if unclear
                $gradingPeriods = ['1st Semester Midterm', '1st Semester Final'];
            }
        } else {
            // For JHS (Grade 7-10): Four grading periods
            $gradingPeriods = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];
        }
        
        // Set default grading period if not provided or invalid
        if (!in_array($gradingPeriod, $gradingPeriods)) {
            $gradingPeriod = $gradingPeriods[0];
        }
        
        // Get existing grades for this section/subject/grading period (scoped to this teacher)
        $existingGradesQuery = Grade::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('school_year', $schoolYear)
            ->where('grading_period', $gradingPeriod);

        if ($subjectId) {
            $existingGradesQuery->where('subject_id', $subjectId);
        }

        $existingGrades = $existingGradesQuery->get()->keyBy('student_id');
        
        // Get available subjects for this teacher assignment in this exact class (strand + tvl_branch scoped)
        $subjectAssignmentsQuery = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('school_year', $schoolYear);

        if (!empty($assignment->strand)) {
            $subjectAssignmentsQuery->where('strand', $assignment->strand);
        } else {
            $subjectAssignmentsQuery->whereNull('strand');
        }

        if (!empty($assignment->tvl_branch)) {
            $subjectAssignmentsQuery->where('tvl_branch', $assignment->tvl_branch);
        } else {
            $subjectAssignmentsQuery->whereNull('tvl_branch');
        }

        $assignedSubjectIds = $subjectAssignmentsQuery->pluck('subject_id')->filter()->unique();

        $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
        if ($subjects->isEmpty() && $assignment->subject) {
            $subjects = collect([$assignment->subject]);
        }
        
        // Semester is already determined above for SHS
        
        return view('teacher.grades.create', compact(
            'enrollments', 
            'gradeLevel', 
            'section', 
            'subject', 
            'subjects',
            'schoolYear', 
            'gradingPeriod', 
            'gradingPeriods',
            'existingGrades',
            'assignment',
            'isShs',
            'semester'
        ));
    }

    /**
     * Store grades for students
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();
        
        $validated = $request->validate([
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'school_year' => 'required|string',
            'grading_period' => 'required|string',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            // Allow nullable when removing; numeric when provided
            'grades.*.grade_value' => 'nullable|numeric|min:0|max:100',
            'grades.*.delete' => 'nullable|boolean',
            'grades.*.remarks' => 'nullable|string|max:500',
        ]);
        
        // Verify teacher is assigned to this section/subject
        $assignment = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->where('grade_level', $validated['grade_level'])
            ->where('section', $validated['section'])
            ->where('subject_id', $validated['subject_id'])
            ->where('school_year', $validated['school_year'])
            ->first();
        
        if (!$assignment) {
            return back()->withErrors(['error' => 'You are not assigned to this section/subject.'])->withInput();
        }

        // Validate that every submitted student ID belongs to an enrolled student in the exact assigned class
        $validEnrollmentsQuery = Enrollment::where('grade_level', $assignment->grade_level)
            ->where('section', $assignment->section)
            ->where('school_year', $assignment->school_year)
            ->where('status', 'enrolled');

        if (!empty($assignment->strand)) {
            $validEnrollmentsQuery->where('strand', $assignment->strand);
        } else {
            $validEnrollmentsQuery->whereNull('strand');
        }

        if (!empty($assignment->tvl_branch)) {
            $validEnrollmentsQuery->where('tvl_branch', $assignment->tvl_branch);
        } else {
            $validEnrollmentsQuery->whereNull('tvl_branch');
        }

        $validStudentIds = $validEnrollmentsQuery->pluck('user_id')->all();

        // --- Two-pass atomic write wrapped in a DB transaction ---
        // Pass 1 (pre-flight): validate all student IDs and grade ownership BEFORE any write.
        // Pass 2: perform all creates/updates.
        return DB::transaction(function () use ($validated, $teacher, $assignment, $validStudentIds) {
            $strand = $assignment->strand;

            // Pass 1: pre-flight validation — no writes yet
            foreach ($validated['grades'] as $gradeData) {
                if (!in_array($gradeData['student_id'], $validStudentIds)) {
                    throw ValidationException::withMessages([
                        'error' => 'Cannot submit grades for students outside your assigned class.'
                    ]);
                }

                $existingGrade = Grade::where('student_id', $gradeData['student_id'])
                    ->where('subject_id', $validated['subject_id'])
                    ->where('grading_period', $validated['grading_period'])
                    ->where('school_year', $validated['school_year'])
                    ->first();

                if ($existingGrade) {
                    if ($existingGrade->teacher_id != $teacher->id) {
                        throw ValidationException::withMessages([
                            'error' => 'You cannot modify grades submitted by another teacher.'
                        ]);
                    }
                    if ($existingGrade->grade_level !== $validated['grade_level'] || $existingGrade->section !== $validated['section']) {
                        throw ValidationException::withMessages([
                            'error' => 'You cannot modify grades for another class section.'
                        ]);
                    }
                    if ($existingGrade->strand !== $assignment->strand) {
                        throw ValidationException::withMessages([
                            'error' => 'You cannot modify grades for another strand.'
                        ]);
                    }
                }
            }

            // Pass 2: writes
            $savedCount = 0;
            $updatedCount = 0;

            foreach ($validated['grades'] as $gradeData) {
                // Handle delete request — preserve records; skip instead of deleting
                $shouldDelete = isset($gradeData['delete']) && (int)$gradeData['delete'] === 1;
                if ($shouldDelete) {
                    continue;
                }

                // Skip if no grade value provided
                if ($gradeData['grade_value'] === null || $gradeData['grade_value'] === '') {
                    continue;
                }

                $existingGrade = Grade::where('student_id', $gradeData['student_id'])
                    ->where('subject_id', $validated['subject_id'])
                    ->where('grading_period', $validated['grading_period'])
                    ->where('school_year', $validated['school_year'])
                    ->first();

                if ($existingGrade) {
                    $existingGrade->update([
                        'grade_value' => $gradeData['grade_value'],
                        'remarks'     => $gradeData['remarks'] ?? null,
                    ]);
                    $updatedCount++;
                } else {
                    Grade::create([
                        'student_id'     => $gradeData['student_id'],
                        'teacher_id'     => $teacher->id,
                        'subject_id'     => $validated['subject_id'],
                        'grade_level'    => $validated['grade_level'],
                        'section'        => $validated['section'],
                        'strand'         => $strand,
                        'school_year'    => $validated['school_year'],
                        'grading_period' => $validated['grading_period'],
                        'grade_value'    => $gradeData['grade_value'],
                        'remarks'        => $gradeData['remarks'] ?? null,
                    ]);
                    $savedCount++;
                }
            }

            $message = '';
            if ($savedCount > 0) {
                $message .= "{$savedCount} grade(s) saved. ";
            }
            if ($updatedCount > 0) {
                $message .= "{$updatedCount} grade(s) updated.";
            }

            return back()->with('success', $message ?: 'No grades were saved.');
        });
    }

    /**
     * List all sections/subjects the teacher can submit grades for
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Get all teacher assignments with subjects
        $assignments = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->with('subject')
            ->orderBy('school_year', 'desc')
            ->orderBy('grade_level')
            ->orderBy('section')
            ->get()
            ->groupBy(function($assignment) {
                return $assignment->school_year . '|' . $assignment->grade_level . '|' . $assignment->section;
            });
        
        $currentSchoolYear = date('Y') . '-' . (date('Y') + 1);
        
        return view('teacher.grades.index', compact('assignments', 'currentSchoolYear'));
    }
}
