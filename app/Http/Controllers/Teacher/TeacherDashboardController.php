<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\TeacherSectionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        // Get teacher's assignments
        $assignments = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->get()
            ->groupBy('grade_level');
        
        // Get assigned grade levels
        $assignedGradeLevels = $assignments->keys()->toArray();
        
        $totalPendingEnrollment = Enrollment::whereIn('status', ['pending'])->count();
        $totalEnrolled = Enrollment::where('status', 'enrolled')->count();
        $totalPendingAdmission = Admission::where('status', 'pending')->count();

        return view('teacher.index', compact(
            'totalPendingEnrollment', 
            'totalEnrolled', 
            'totalPendingAdmission',
            'assignments',
            'assignedGradeLevels'
        ));
    }
}
