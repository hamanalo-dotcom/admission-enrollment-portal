<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\TeacherSectionAssignment;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $user = Auth::user();

        // Get current enrolled record (for active school year, if any)
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $currentEnrollment = Enrollment::where('user_id', $user->id)
            ->when($activeSchoolYear, function ($q) use ($activeSchoolYear) {
                $q->where('school_year', $activeSchoolYear->school_year);
            })
            ->where('status', 'enrolled')
            ->orderByDesc('id')
            ->first();

        // Fetch enrolled subjects for the current enrollment
        $enrolledSubjects = collect();
        if ($currentEnrollment) {
            $enrolledSubjects = Subject::where(function($q) use ($currentEnrollment) {
                    $q->whereNull('grade_level')->orWhere('grade_level', $currentEnrollment->grade_level);
                })
                ->when($currentEnrollment->strand, function($q) use ($currentEnrollment) {
                    $q->where(function($query) use ($currentEnrollment) {
                        $query->whereNull('strand')->orWhere('strand', $currentEnrollment->strand);
                    });
                })
                ->when($currentEnrollment->section && $currentEnrollment->grade_level, function($q) use ($currentEnrollment) {
                    $assignmentSubjectIds = TeacherSectionAssignment::where('grade_level', $currentEnrollment->grade_level)
                        ->where('section', $currentEnrollment->section)
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

                    if ($assignmentSubjectIds->isNotEmpty()) {
                        $q->orWhereIn('id', $assignmentSubjectIds);
                    }
                })
                ->orderBy('name')
                ->get();
        }

        return view('student.index', compact(
            'currentEnrollment',
            'enrolledSubjects',
            'activeSchoolYear'
        ));
    }
}
