<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\TeacherSectionAssignment;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    /**
     * Display the reports dashboard with comprehensive lists
     */
    public function index(Request $request)
    {
        // Get all students
        $studentsQuery = User::where('role', 'student');
        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $studentsQuery->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        // Sort students by latest enrollment grade level, then section, then name
        $students = $studentsQuery
            ->orderByRaw("COALESCE((SELECT e.grade_level FROM enrollments e WHERE e.user_id = users.id ORDER BY e.id DESC LIMIT 1), 'ZZZ')")
            ->orderByRaw("COALESCE((SELECT e.section FROM enrollments e WHERE e.user_id = users.id ORDER BY e.id DESC LIMIT 1), 'ZZZ')")
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->paginate(20, ['*'], 'students_page');

        // Get all sections
        $sectionsQuery = Section::query();
        if ($request->filled('section_grade_level')) {
            $sectionsQuery->where('grade_level', $request->section_grade_level);
        }
        // Sort sections by grade level, then strand, then name
        $sections = $sectionsQuery
            ->orderBy('grade_level')
            ->orderByRaw("COALESCE(strand, '')")
            ->orderBy('name')
            ->get();

        // Get all school years
        $schoolYearsFromEnrollments = Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');
        
        $schoolYearsFromTable = SchoolYear::orderBy('school_year', 'desc')->get();
        $existingValues = $schoolYearsFromTable->pluck('school_year')->toArray();
        
        foreach ($schoolYearsFromEnrollments as $sy) {
            if (!in_array($sy, $existingValues)) {
                $virtualSchoolYear = new SchoolYear();
                $virtualSchoolYear->school_year = $sy;
                $virtualSchoolYear->is_active = false;
                $schoolYearsFromTable->push($virtualSchoolYear);
            }
        }
        $schoolYears = $schoolYearsFromTable->sortByDesc('school_year')->values();

        // Get all instructors (teachers)
        $instructorsQuery = User::where('role', 'teacher');
        if ($request->filled('instructor_search')) {
            $search = $request->instructor_search;
            $instructorsQuery->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $instructors = $instructorsQuery->orderBy('lastname')->orderBy('firstname')->paginate(20, ['*'], 'instructors_page');

        // Get all subjects
        $subjectsQuery = Subject::query();
        if ($request->filled('subject_grade_level')) {
            $subjectsQuery->where('grade_level', $request->subject_grade_level);
        }
        if ($request->filled('subject_strand')) {
            $subjectsQuery->where('strand', $request->subject_strand);
        }
        // Sort subjects by grade level, then strand, then name
        $subjects = $subjectsQuery
            ->orderBy('grade_level')
            ->orderByRaw("COALESCE(strand, '')")
            ->orderBy('name')
            ->get();

        // Get grade levels for filters
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands = ['GAS', 'TVL'];

        return view('admin.reports.index', compact(
            'students',
            'sections',
            'schoolYears',
            'instructors',
            'subjects',
            'gradeLevels',
            'strands'
        ));
    }

    /**
     * Generate enrollment report
     */
    public function enrollment(Request $request)
    {
        $query = Enrollment::with(['user', 'admission']);

        // Search by student name or email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchLower = strtolower($search);
            $query->whereHas('user', function($q) use ($search, $searchLower) {
                $q->where(function($subQuery) use ($search, $searchLower) {
                    $subQuery->where('firstname', 'like', "%{$search}%")
                             ->orWhere('lastname', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhereRaw("LOWER(CONCAT(firstname, ' ', lastname)) LIKE ?", ["%{$searchLower}%"])
                             ->orWhereRaw("LOWER(CONCAT(lastname, ', ', firstname)) LIKE ?", ["%{$searchLower}%"])
                             ->orWhereRaw("LOWER(CONCAT(lastname, ' ', firstname)) LIKE ?", ["%{$searchLower}%"]);
                });
            });
        }

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by school year
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        // Filter by strand (for SHS)
        if ($request->filled('strand')) {
            $query->where('strand', $request->strand);
        }

        $enrollments = $query->with('user')
            ->get()
            ->sortBy(function($enrollment) {
                return $enrollment->user->lastname ?? '';
            })
            ->values();

        // Get all school years for dropdown
        $schoolYearsFromEnrollments = Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');
        
        $schoolYearsFromTable = SchoolYear::orderBy('school_year', 'desc')->get();
        $existingValues = $schoolYearsFromTable->pluck('school_year')->toArray();
        
        foreach ($schoolYearsFromEnrollments as $sy) {
            if (!in_array($sy, $existingValues)) {
                $virtualSchoolYear = new SchoolYear();
                $virtualSchoolYear->school_year = $sy;
                $virtualSchoolYear->is_active = false;
                $schoolYearsFromTable->push($virtualSchoolYear);
            }
        }
        $schoolYears = $schoolYearsFromTable->sortByDesc('school_year')->values();

        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $statuses = ['pending', 'enrolled', 'cancelled'];
        $strands = ['GAS', 'TVL'];
        $currentSchoolYear = $request->school_year ?? date('Y') . '-' . (date('Y') + 1);

        return view('admin.reports.enrollment', compact(
            'enrollments',
            'gradeLevels',
            'statuses',
            'strands',
            'currentSchoolYear',
            'schoolYears'
        ));
    }

    /**
     * Generate admission report
     */
    public function admission(Request $request)
    {
        $query = Admission::with('user')->where('is_draft', false);

        // Search by student name or email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchLower = strtolower($search);
            $query->whereHas('user', function($q) use ($search, $searchLower) {
                $q->where(function($subQuery) use ($search, $searchLower) {
                    $subQuery->where('firstname', 'like', "%{$search}%")
                             ->orWhere('lastname', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhereRaw("LOWER(CONCAT(firstname, ' ', lastname)) LIKE ?", ["%{$searchLower}%"])
                             ->orWhereRaw("LOWER(CONCAT(lastname, ', ', firstname)) LIKE ?", ["%{$searchLower}%"])
                             ->orWhereRaw("LOWER(CONCAT(lastname, ' ', firstname)) LIKE ?", ["%{$searchLower}%"]);
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by year level
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $admissions = $query->orderBy('created_at', 'desc')->get();

        $statuses = ['pending', 'approved', 'rejected'];
        $yearLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

        return view('admin.reports.admission', compact(
            'admissions',
            'statuses',
            'yearLevels'
        ));
    }

    /**
     * Generate grade report
     */
    public function grade(Request $request)
    {
        $query = Grade::with(['student', 'teacher', 'subject']);

        // Search by student name or email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchLower = strtolower($search);
            $query->whereHas('student', function($q) use ($search, $searchLower) {
                $q->where(function($subQuery) use ($search, $searchLower) {
                    $subQuery->where('firstname', 'like', "%{$search}%")
                             ->orWhere('lastname', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhereRaw("LOWER(CONCAT(firstname, ' ', lastname)) LIKE ?", ["%{$searchLower}%"])
                             ->orWhereRaw("LOWER(CONCAT(lastname, ', ', firstname)) LIKE ?", ["%{$searchLower}%"])
                             ->orWhereRaw("LOWER(CONCAT(lastname, ' ', firstname)) LIKE ?", ["%{$searchLower}%"]);
                });
            });
        }

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

        $grades = $query->with(['student', 'teacher', 'subject'])
            ->get()
            ->sortBy(function($grade) {
                return ($grade->student->lastname ?? '') . ' ' . ($grade->student->firstname ?? '');
            })
            ->values();

        // Group by student for summary
        $gradeSummary = $grades->groupBy('student_id')->map(function($studentGrades) {
            $avg = $studentGrades->avg('grade_value');
            return [
                'student' => $studentGrades->first()->student,
                'grades' => $studentGrades,
                'average' => round($avg, 2),
                'count' => $studentGrades->count()
            ];
        });

        // Get all school years for dropdown
        $schoolYearsFromEnrollments = Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');
        
        $schoolYearsFromTable = SchoolYear::orderBy('school_year', 'desc')->get();
        $existingValues = $schoolYearsFromTable->pluck('school_year')->toArray();
        
        foreach ($schoolYearsFromEnrollments as $sy) {
            if (!in_array($sy, $existingValues)) {
                $virtualSchoolYear = new SchoolYear();
                $virtualSchoolYear->school_year = $sy;
                $virtualSchoolYear->is_active = false;
                $schoolYearsFromTable->push($virtualSchoolYear);
            }
        }
        $schoolYears = $schoolYearsFromTable->sortByDesc('school_year')->values();

        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        // Include both JHS and SHS grading periods
        $gradingPeriods = [
            'First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading', // JHS
            '1st Semester Midterm', '1st Semester Final', '2nd Semester Midterm', '2nd Semester Final' // SHS
        ];
        $subjects = Subject::orderBy('name')->get();
        $currentSchoolYear = $request->school_year ?? date('Y') . '-' . (date('Y') + 1);

        return view('admin.reports.grade', compact(
            'grades',
            'gradeSummary',
            'gradeLevels',
            'gradingPeriods',
            'subjects',
            'currentSchoolYear',
            'schoolYears'
        ));
    }

    /**
     * Generate teacher assignment report
     */
    public function teacherAssignment(Request $request)
    {
        $query = TeacherSectionAssignment::with(['teacher', 'subject']);

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        // Filter by school year
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        // Filter by strand
        if ($request->filled('strand')) {
            $query->where('strand', $request->strand);
        }

        $assignments = $query->orderBy('school_year', 'desc')
            ->orderBy('grade_level')
            ->orderBy('section')
            ->get();

        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands = ['GAS', 'TVL'];
        $currentSchoolYear = $request->school_year ?? date('Y') . '-' . (date('Y') + 1);

        return view('admin.reports.teacher-assignment', compact(
            'assignments',
            'gradeLevels',
            'strands',
            'currentSchoolYear'
        ));
    }

    /**
     * Generate student statistics report
     */
    public function statistics(Request $request)
    {
        // Build school years list (from SchoolYear table + enrollments)
        $schoolYearsFromEnrollments = Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        $schoolYearsFromTable = SchoolYear::orderBy('school_year', 'desc')->get();
        $existingValues = $schoolYearsFromTable->pluck('school_year')->toArray();

        foreach ($schoolYearsFromEnrollments as $sy) {
            if (!in_array($sy, $existingValues)) {
                $virtualSchoolYear = new SchoolYear();
                $virtualSchoolYear->school_year = $sy;
                $virtualSchoolYear->is_active = false;
                $schoolYearsFromTable->push($virtualSchoolYear);
            }
        }
        $schoolYears = $schoolYearsFromTable->sortByDesc('school_year')->values();

        $selectedSchoolYear = $request->filled('school_year') ? $request->school_year : null;

        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();

        // Filter enrollments by selected school year (if any)
        $enrolledQuery = Enrollment::where('status', 'enrolled');
        if ($selectedSchoolYear) {
            $enrolledQuery->where('school_year', $selectedSchoolYear);
        }
        $totalEnrolled = $enrolledQuery->count();

        // Total pending admissions (keeps existing behavior)
        $totalPendingAdmissions = Admission::where('is_draft', false)->where('status', 'pending')->count();

        // Enrollment by grade level (respect selected school year)
        $enrollmentByGradeQuery = Enrollment::where('status', 'enrolled');
        if ($selectedSchoolYear) {
            $enrollmentByGradeQuery->where('school_year', $selectedSchoolYear);
        }
        $enrollmentByGrade = $enrollmentByGradeQuery
            ->selectRaw('grade_level, count(*) as count')
            ->groupBy('grade_level')
            ->pluck('count', 'grade_level');

        // Enrollment by strand (SHS only) (respect selected school year)
        $enrollmentByStrandQuery = Enrollment::where('status', 'enrolled')
            ->whereIn('grade_level', ['Grade 11', 'Grade 12'])
            ->whereNotNull('strand');
        if ($selectedSchoolYear) {
            $enrollmentByStrandQuery->where('school_year', $selectedSchoolYear);
        }
        $enrollmentByStrand = $enrollmentByStrandQuery
            ->selectRaw('strand, count(*) as count')
            ->groupBy('strand')
            ->pluck('count', 'strand');

        // Admission status breakdown (not filtered by year)
        $admissionStatus = Admission::where('is_draft', false)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.reports.statistics', compact(
            'totalStudents',
            'totalTeachers',
            'totalEnrolled',
            'totalPendingAdmissions',
            'enrollmentByGrade',
            'enrollmentByStrand',
            'admissionStatus',
            'schoolYears',
            'selectedSchoolYear'
        ));
    }
}
