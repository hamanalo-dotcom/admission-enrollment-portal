<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TeacherSectionAssignment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherSeniorHighController extends Controller
{
    private function getActiveSchoolYear()
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        return $activeSchoolYear ? $activeSchoolYear->school_year : null;
    }

    private function getTeacherAssignments($gradeLevel)
    {
        $teacher = Auth::user();
        $activeSchoolYear = $this->getActiveSchoolYear();
        
        $query = TeacherSectionAssignment::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel);
        
        // Filter by active school year if it exists
        if ($activeSchoolYear) {
            $query->where('school_year', $activeSchoolYear);
        }
        
        return $query->get();
    }

    private function buildShsGroups(string $gradeLevel): array
    {
        $assignments = $this->getTeacherAssignments($gradeLevel);
        $activeSchoolYear = $this->getActiveSchoolYear();
        $groups = [];
        foreach ($assignments as $asgmt) {
            if (empty($asgmt->strand)) {
                continue; // skip JHS
            }
            $label = $asgmt->strand === 'TVL'
                ? sprintf('TVL-%s - %s', strtoupper($asgmt->tvl_branch ?? ''), $asgmt->section)
                : sprintf('GAS - %s', $asgmt->section);

            $query = Enrollment::with('admission')
                ->where('grade_level', $gradeLevel)
                ->where('strand', $asgmt->strand)
                ->where('section', $asgmt->section)
                ->where('status', 'enrolled');
            
            // Filter by active school year if it exists
            if ($activeSchoolYear) {
                $query->where('school_year', $activeSchoolYear);
            }
            
            if ($asgmt->strand === 'TVL' && !empty($asgmt->tvl_branch)) {
                $query->where('tvl_branch', $asgmt->tvl_branch);
            }
            $groups[$label] = $query->get();
        }
        ksort($groups);
        return $groups;
    }

    public function shsGradeEleven()
    {
        $groups = $this->buildShsGroups('Grade 11');
        return view('teacher.senior-high.grade_eleven', compact('groups'));
    }

    public function shsGradeTwelve()
    {
        $groups = $this->buildShsGroups('Grade 12');
        return view('teacher.senior-high.grade_twelve', compact('groups'));
    }

    public function downloadStrandPdf($grade, $strand)
    {
        $grade = (int) $grade;
        $gradeLevel = "Grade {$grade}";
        $activeSchoolYear = $this->getActiveSchoolYear();
        
        // Parse strand parameter - could be "TVL", "TVL-ICT", "GAS", etc.
        $strandParts = explode('-', strtoupper($strand));
        $strandType = $strandParts[0]; // "TVL" or "GAS"
        $tvlBranch = isset($strandParts[1]) ? trim($strandParts[1]) : null;

        // Get teacher's assigned sections for this grade level
        $assignments = $this->getTeacherAssignments($gradeLevel);
        
        // Filter assignments by strand
        $filteredAssignments = $assignments->filter(function($asgmt) use ($strandType, $tvlBranch) {
            if ($asgmt->strand !== $strandType) {
                return false;
            }
            if ($strandType === 'TVL' && $tvlBranch) {
                return strtoupper($asgmt->tvl_branch ?? '') === $tvlBranch;
            }
            return true;
        });

        if ($filteredAssignments->isEmpty()) {
            return back()->with('error', "You are not assigned to {$strand}.");
        }

        // Build query for students
        $query = Enrollment::where('grade_level', $gradeLevel)
            ->where('strand', $strandType)
            ->where('status', 'enrolled')
            ->with('admission');

        // Filter by active school year if it exists
        if ($activeSchoolYear) {
            $query->where('school_year', $activeSchoolYear);
        }

        // Filter by assigned sections
        $sections = $filteredAssignments->pluck('section')->unique()->toArray();
        $query->whereIn('section', $sections);

        // For TVL, filter by branch if specified
        if ($strandType === 'TVL' && $tvlBranch) {
            $query->where('tvl_branch', $tvlBranch);
        }

        $students = $query->orderBy('created_at')->get();

        if ($students->isEmpty()) {
            return back()->with('error', "No students found for {$gradeLevel} - {$strand}.");
        }

        $pdf = Pdf::loadView('teacher.strand-pdf', [
            'students' => $students,
            'gradeLevel' => $gradeLevel,
            'strand' => $strand,
        ]);

        $filename = "{$gradeLevel}_{$strand}_" . now()->format('Y_m_d') . '.pdf';
        return $pdf->download($filename);
    }
}
