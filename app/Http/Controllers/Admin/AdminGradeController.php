<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class AdminGradeController extends Controller
{
    /**
     * Display a listing of grades
     */
    public function index(Request $request)
    {
        $query = Grade::with(['student', 'teacher', 'subject']);

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        // Filter by section
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by school year
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        // Filter by grading period
        if ($request->filled('grading_period')) {
            $query->where('grading_period', $request->grading_period);
        }

        // Filter by strand (for SHS)
        if ($request->filled('strand')) {
            $query->where('strand', $request->strand);
        }

        // Search by student name
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('student', function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $grades = $query->orderBy('school_year', 'desc')
            ->orderBy('grade_level')
            ->orderBy('section')
            ->orderBy('grading_period')
            ->paginate(20);

        // Get filter options
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        // Include both JHS and SHS grading periods
        $gradingPeriods = [
            'First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading', // JHS
            '1st Semester Midterm', '1st Semester Final', '2nd Semester Midterm', '2nd Semester Final' // SHS
        ];
        $strands = ['GAS', 'TVL'];
        $subjects = Subject::orderBy('name')->get();

        // Available school years from school_years table (active + inactive, exclude hidden if needed?)
        $schoolYears = SchoolYear::orderBy('school_year', 'desc')->get();
        $activeSchoolYear = $schoolYears->firstWhere('is_active', true);
        $defaultSchoolYear = $activeSchoolYear->school_year
            ?? optional($schoolYears->first())->school_year
            ?? date('Y') . '-' . (date('Y') + 1);

        // If user selected a school year use that, else default to active/current
        $currentSchoolYear = $request->school_year ?? $defaultSchoolYear;

        return view('admin.grades.index', compact(
            'grades',
            'gradeLevels',
            'gradingPeriods',
            'strands',
            'subjects',
            'currentSchoolYear',
            'schoolYears'
        ));
    }

    /**
     * Display the specified grade
     */
    public function show(Grade $grade)
    {
        $grade->load(['student', 'teacher', 'subject']);
        return view('admin.grades.show', compact('grade'));
    }

    /**
     * Display complete academic record for a student
     */
    public function showStudentRecord(User $student)
    {
        
        // Get all grades for this student, ordered by school year, subject, and grading period
        $allGrades = Grade::where('student_id', $student->id)
            ->with(['subject', 'teacher'])
            ->orderBy('school_year', 'desc')
            ->orderBy('subject_id')
            ->orderByRaw("CASE 
                WHEN grading_period = 'First Grading' THEN 1
                WHEN grading_period = 'Second Grading' THEN 2
                WHEN grading_period = 'Third Grading' THEN 3
                WHEN grading_period = 'Fourth Grading' THEN 4
                WHEN grading_period = '1st Semester Midterm' THEN 5
                WHEN grading_period = '1st Semester Final' THEN 6
                WHEN grading_period = '2nd Semester Midterm' THEN 7
                WHEN grading_period = '2nd Semester Final' THEN 8
                ELSE 9
            END")
            ->get();

        // Group grades by school year, then by subject
        $gradesBySchoolYear = $allGrades->groupBy('school_year');
        
        // Calculate averages per subject per school year
        $averages = [];
        foreach ($gradesBySchoolYear as $schoolYear => $grades) {
            $gradesBySubject = $grades->groupBy('subject_id');
            foreach ($gradesBySubject as $subjectId => $subjectGrades) {
                $averages[$schoolYear][$subjectId] = $subjectGrades->avg('grade_value');
            }
        }

        return view('admin.grades.student-record', compact('student', 'gradesBySchoolYear', 'averages'));
    }

    /**
     * Show the form for editing the specified grade
     */
    public function edit(Grade $grade)
    {
        $grade->load(['student', 'teacher', 'subject']);
        // Determine grading periods based on grade level
        $isShs = in_array($grade->grade_level, ['Grade 11', 'Grade 12']);
        if ($isShs) {
            // For SHS, check if it's 1st or 2nd semester based on existing grade
            if (stripos($grade->grading_period, '1st') !== false) {
                $gradingPeriods = ['1st Semester Midterm', '1st Semester Final'];
            } elseif (stripos($grade->grading_period, '2nd') !== false) {
                $gradingPeriods = ['2nd Semester Midterm', '2nd Semester Final'];
            } else {
                $gradingPeriods = ['1st Semester Midterm', '1st Semester Final', '2nd Semester Midterm', '2nd Semester Final'];
            }
        } else {
            // For JHS
            $gradingPeriods = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];
        }
        return view('admin.grades.edit', compact('grade', 'gradingPeriods'));
    }

    /**
     * Update the specified grade
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'grade_value' => ['required', 'numeric', 'min:0', 'max:100'],
            'grading_period' => ['required', 'string'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $grade->update($validated);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified grade
     */
    // Delete functionality removed to preserve all data
    public function destroy(Grade $grade)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.grades.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }
}

