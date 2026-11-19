<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Enrollment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Metrics (same as registrar)
        $totalApplications = Admission::where('is_draft', false)->count();
        $totalEnrolled = Enrollment::where('status', 'enrolled')->count();
        $totalPending = Admission::where('is_draft', false)->where('status', 'pending')->count();
        $totalRejected = Admission::where('is_draft', false)->where('status', 'rejected')->count();
        $totalPendingEnrollment = Enrollment::whereIn('status', ['pending'])->count();

        // Bar Chart: Applicants per Grade Level
        $applicantsByGrade = Admission::where('is_draft', false)
            ->whereNotNull('year_level')
            ->selectRaw('year_level, count(*) as count')
            ->groupBy('year_level')
            ->orderByRaw("CASE 
                WHEN year_level = 'Grade 7' THEN 1
                WHEN year_level = 'Grade 8' THEN 2
                WHEN year_level = 'Grade 9' THEN 3
                WHEN year_level = 'Grade 10' THEN 4
                WHEN year_level = 'Grade 11' THEN 5
                WHEN year_level = 'Grade 12' THEN 6
                ELSE 7
            END")
            ->pluck('count', 'year_level')
            ->toArray();

        // Pie Chart: Distribution of Students by Status
        $studentDistribution = [
            'Enrolled' => Enrollment::where('status', 'enrolled')->count(),
            'Pending Enrollment' => Enrollment::where('status', 'pending')->count(),
            'Cancelled' => Enrollment::where('status', 'cancelled')->count(),
        ];

        // Line Chart: Daily/Weekly Applicant Count (Last 30 days)
        $dailyApplicants = [];
        $weeklyApplicants = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $count = Admission::where('is_draft', false)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();
            
            $dailyApplicants[$date->format('M d')] = $count;
        }

        // Weekly data (last 8 weeks)
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $count = Admission::where('is_draft', false)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count();
            
            $weeklyApplicants['Week ' . (8 - $i)] = $count;
        }

        return view('admin.index', compact(
            'totalApplications',
            'totalEnrolled',
            'totalPending',
            'totalRejected',
            'totalPendingEnrollment',
            'applicantsByGrade',
            'studentDistribution',
            'dailyApplicants',
            'weeklyApplicants'
        ));
    }
}