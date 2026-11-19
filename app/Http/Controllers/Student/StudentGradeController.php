<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\TeacherSectionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    /**
     * Display student's grades
     */
    public function index(Request $request)
    {
        $student = Auth::user();
        
        // Get student's current enrollment
        $currentEnrollment = Enrollment::where('user_id', $student->id)
            ->where('status', 'enrolled')
            ->latest()
            ->first();
        
        if (!$currentEnrollment) {
            return view('student.grades.index', [
                'grades' => collect(),
                'subjects' => collect(),
                'currentEnrollment' => null,
                'selectedSubject' => null,
            ])->with('info', 'You are not currently enrolled.');
        }
        
        // Get filter options
        $schoolYear = $request->input('school_year', $currentEnrollment->school_year);
        $subjectId = $request->input('subject_id');
        
        // Get all grades for this student
        $gradesQuery = Grade::where('student_id', $student->id)
            ->where('grade_level', $currentEnrollment->grade_level)
            ->where('section', $currentEnrollment->section)
            ->where('school_year', $schoolYear)
            ->with('subject', 'teacher')
            ->orderBy('subject_id')
            ->orderBy('grading_period');
        
        // Filter by subject if selected
        if ($subjectId) {
            $gradesQuery->where('subject_id', $subjectId);
        }
        
        $grades = $gradesQuery->get();
        
        // Group grades by subject
        $gradesBySubject = $grades->groupBy('subject_id');

        // Get all subjects assigned to this student for the given school year,
        // even if no grades have been entered yet.
        // This should match the logic from StudentSubjectController to show all enrolled subjects
        $subjects = Subject::where(function($q) use ($currentEnrollment) {
                $q->whereNull('grade_level')->orWhere('grade_level', $currentEnrollment->grade_level);
            })
            ->when($currentEnrollment->strand, function($q) use ($currentEnrollment) {
                $q->where(function($query) use ($currentEnrollment) {
                    $query->whereNull('strand')->orWhere('strand', $currentEnrollment->strand);
                });
            })
            ->when($currentEnrollment->school_year, function($q) use ($currentEnrollment) {
                $q->where(function($query) use ($currentEnrollment) {
                    $query->whereNull('school_year')->orWhere('school_year', $currentEnrollment->school_year);
                });
            })
            ->when($currentEnrollment->section && $currentEnrollment->grade_level, function($q) use ($currentEnrollment, $schoolYear) {
                // Include subjects from teacher assignments if they exist
                $assignmentSubjectIds = TeacherSectionAssignment::where('grade_level', $currentEnrollment->grade_level)
                    ->where('section', $currentEnrollment->section)
                    ->where('school_year', $schoolYear)
                    ->when($currentEnrollment->strand, function($query) use ($currentEnrollment) {
                        $query->where(function($q) use ($currentEnrollment) {
                            $q->where('strand', $currentEnrollment->strand)
                              ->orWhereNull('strand');
                        });
                    })
                    ->when($currentEnrollment->tvl_branch, function($query) use ($currentEnrollment) {
                        $query->where(function($q) use ($currentEnrollment) {
                            $q->where('tvl_branch', $currentEnrollment->tvl_branch)
                              ->orWhereNull('tvl_branch');
                        });
                    })
                    ->pluck('subject_id')
                    ->unique();

                // If there are teacher assignments, include those subjects
                // But also include all subjects matching grade level and strand even without assignments
                if ($assignmentSubjectIds->isNotEmpty()) {
                    $q->orWhereIn('id', $assignmentSubjectIds);
                }
            })
            ->orderBy('name')
            ->get();
        
        $selectedSubject = $subjectId ? Subject::find($subjectId) : null;
        
        // Determine grading periods based on grade level
        $isShs = in_array($currentEnrollment->grade_level, ['Grade 11', 'Grade 12']);
        if ($isShs) {
            $gradingPeriods = [
                ['key' => '1st Semester Midterm', 'label' => '1st Sem Midterm'],
                ['key' => '1st Semester Final', 'label' => '1st Sem Final'],
                ['key' => '2nd Semester Midterm', 'label' => '2nd Sem Midterm'],
                ['key' => '2nd Semester Final', 'label' => '2nd Sem Final'],
            ];
        } else {
            $gradingPeriods = [
                ['key' => 'First Grading', 'label' => '1st Quarter'],
                ['key' => 'Second Grading', 'label' => '2nd Quarter'],
                ['key' => 'Third Grading', 'label' => '3rd Quarter'],
                ['key' => 'Fourth Grading', 'label' => '4th Quarter'],
            ];
        }
        
        return view('student.grades.index', compact(
            'grades',
            'gradesBySubject',
            'subjects',
            'selectedSubject',
            'currentEnrollment',
            'schoolYear',
            'gradingPeriods',
            'isShs'
        ));
    }

    /**
     * Show detailed grade view for a specific subject
     */
    public function show(Subject $subject, Request $request)
    {
        $student = Auth::user();
        
        // Get student's current enrollment
        $currentEnrollment = Enrollment::where('user_id', $student->id)
            ->where('status', 'enrolled')
            ->latest()
            ->first();
        
        if (!$currentEnrollment) {
            return redirect()->route('student.grades.index')
                ->with('error', 'You are not currently enrolled.');
        }
        
        $schoolYear = $request->input('school_year', $currentEnrollment->school_year);
        
        $isShs = in_array($currentEnrollment->grade_level, ['Grade 11', 'Grade 12']);
        $gradingOrder = $isShs
            ? ['1st Semester Midterm', '1st Semester Final', '2nd Semester Midterm', '2nd Semester Final']
            : ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];

        // Get all grades for this subject
        $grades = Grade::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->where('grade_level', $currentEnrollment->grade_level)
            ->where('section', $currentEnrollment->section)
            ->where('school_year', $schoolYear)
            ->with('teacher')
            ->get()
            ->sortBy(function($grade) use ($gradingOrder) {
                $index = array_search($grade->grading_period, $gradingOrder, true);
                return $index === false ? PHP_INT_MAX : $index;
            })
            ->values();
        
        // Calculate average
        $average = $grades->isNotEmpty() ? $grades->avg('grade_value') : null;
        
        return view('student.grades.show', compact('subject', 'grades', 'currentEnrollment', 'schoolYear', 'average', 'isShs'));
    }
}
