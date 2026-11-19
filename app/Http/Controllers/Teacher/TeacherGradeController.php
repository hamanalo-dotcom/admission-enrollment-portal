<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\TeacherSectionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $assignment = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('school_year', $schoolYear)
            ->when($subjectId, function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->first();
        
        if (!$assignment) {
            return redirect()->route('teacher.index')
                ->with('error', 'You are not assigned to this section/subject.');
        }
        
        // Get students enrolled in this section
        $enrollments = Enrollment::where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('status', 'enrolled')
            ->with('admission', 'user')
            ->get();
        
        // Get subject
        $subject = $subjectId ? Subject::findOrFail($subjectId) : null;
        
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
        
        // Get existing grades for this section/subject/grading period
        $existingGrades = Grade::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('school_year', $schoolYear)
            ->where('grading_period', $gradingPeriod)
            ->when($subjectId, function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->get()
            ->keyBy('student_id');
        
        // Get available subjects for this teacher assignment
        // If subject_id is not in assignment, get all subjects for this grade level/strand
        $subjects = Subject::where(function($q) use ($gradeLevel) {
            $q->whereNull('grade_level')->orWhere('grade_level', $gradeLevel);
        })
        ->when($assignment->strand, function($q) use ($assignment) {
            $q->where(function($query) use ($assignment) {
                $query->whereNull('strand')->orWhere('strand', $assignment->strand);
            });
        })
        ->orderBy('name')
        ->get();
        
        // If no subjects found, create a default list or get all subjects
        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('name')->get();
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
        
        // Get strand if SHS
        $strand = $assignment->strand;
        
        $savedCount = 0;
        $updatedCount = 0;
        
        foreach ($validated['grades'] as $gradeData) {
            // Check if grade already exists
            $existingGrade = Grade::where('student_id', $gradeData['student_id'])
                ->where('subject_id', $validated['subject_id'])
                ->where('grading_period', $validated['grading_period'])
                ->where('school_year', $validated['school_year'])
                ->first();
            
            // Handle delete request
            $shouldDelete = isset($gradeData['delete']) && (int)$gradeData['delete'] === 1;
            if ($shouldDelete) {
                if ($existingGrade) {
                    $existingGrade->delete();
                    $updatedCount++; // count deletes as updates for messaging simplicity
                }
                continue;
            }

            // If no grade value provided and not deleting, skip
            if ($gradeData['grade_value'] === null || $gradeData['grade_value'] === '') {
                continue;
            }

            if ($existingGrade) {
                // Update existing grade
                $existingGrade->update([
                    'grade_value' => $gradeData['grade_value'],
                    'remarks' => $gradeData['remarks'] ?? null,
                ]);
                $updatedCount++;
            } else {
                // Create new grade
                Grade::create([
                    'student_id' => $gradeData['student_id'],
                    'teacher_id' => $teacher->id,
                    'subject_id' => $validated['subject_id'],
                    'grade_level' => $validated['grade_level'],
                    'section' => $validated['section'],
                    'strand' => $strand,
                    'school_year' => $validated['school_year'],
                    'grading_period' => $validated['grading_period'],
                    'grade_value' => $gradeData['grade_value'],
                    'remarks' => $gradeData['remarks'] ?? null,
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
