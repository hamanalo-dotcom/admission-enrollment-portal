<?php

namespace App\Http\Controllers\Teacher;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Enrollment;
use App\Models\TeacherSectionAssignment;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;


class TeacherJuniorHighController
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
        
        return $query->pluck('section')->toArray();
    }
    
    public function jhsGradeSeven()
    {
        $assignedSections = $this->getTeacherAssignments('Grade 7');
        $activeSchoolYear = $this->getActiveSchoolYear();
        
        $sectionsByName = collect();
        if (!empty($assignedSections)) {
            $query = Enrollment::where('grade_level', 'Grade 7')
                ->whereIn('section', $assignedSections)
                ->where('status', 'enrolled');
            
            // Filter by active school year if it exists
            if ($activeSchoolYear) {
                $query->where('school_year', $activeSchoolYear);
            }
            
            $sectionsByName = $query->with('admission')
                ->get()
                ->groupBy('section');
        }
        
        return view('teacher.junior-high.grade_seven', compact('sectionsByName', 'assignedSections'));
    }

    public function jhsGradeEight()
    {
        $assignedSections = $this->getTeacherAssignments('Grade 8');
        $activeSchoolYear = $this->getActiveSchoolYear();
        
        $sectionsByName = collect();
        if (!empty($assignedSections)) {
            $query = Enrollment::where('grade_level', 'Grade 8')
                ->whereIn('section', $assignedSections)
                ->where('status', 'enrolled');
            
            // Filter by active school year if it exists
            if ($activeSchoolYear) {
                $query->where('school_year', $activeSchoolYear);
            }
            
            $sectionsByName = $query->with('admission')
                ->get()
                ->groupBy('section');
        }
        
        return view('teacher.junior-high.grade_eight', compact('sectionsByName', 'assignedSections'));
    }

    public function jhsGradeNine()
    {
        $assignedSections = $this->getTeacherAssignments('Grade 9');
        $activeSchoolYear = $this->getActiveSchoolYear();
        
        $sectionsByName = collect();
        if (!empty($assignedSections)) {
            $query = Enrollment::where('grade_level', 'Grade 9')
                ->whereIn('section', $assignedSections)
                ->where('status', 'enrolled');
            
            // Filter by active school year if it exists
            if ($activeSchoolYear) {
                $query->where('school_year', $activeSchoolYear);
            }
            
            $sectionsByName = $query->with('admission')
                ->get()
                ->groupBy('section');
        }
        
        return view('teacher.junior-high.grade_nine', compact('sectionsByName', 'assignedSections'));
    }

    public function jhsGradeTen()
    {
        $assignedSections = $this->getTeacherAssignments('Grade 10');
        $activeSchoolYear = $this->getActiveSchoolYear();
        
        $sectionsByName = collect();
        if (!empty($assignedSections)) {
            $query = Enrollment::where('grade_level', 'Grade 10')
                ->whereIn('section', $assignedSections)
                ->where('status', 'enrolled');
            
            // Filter by active school year if it exists
            if ($activeSchoolYear) {
                $query->where('school_year', $activeSchoolYear);
            }
            
            $sectionsByName = $query->with('admission')
                ->get()
                ->groupBy('section');
        }
        
        return view('teacher.junior-high.grade_ten', compact('sectionsByName', 'assignedSections'));
    }

    public function downloadSectionPdf($grade, $section)
    {
        $grade = (int) $grade;
        $gradeLevel = "Grade {$grade}";
        $section = strtoupper($section);
        $activeSchoolYear = $this->getActiveSchoolYear();

        // Verify teacher has assignment to this section
        $assignedSections = $this->getTeacherAssignments($gradeLevel);
        
        if (!in_array($section, $assignedSections)) {
            return back()->with('error', "You are not assigned to section {$section}.");
        }

        $query = Enrollment::where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->where('status', 'enrolled');
        
        // Filter by active school year if it exists
        if ($activeSchoolYear) {
            $query->where('school_year', $activeSchoolYear);
        }
        
        $students = $query->with('admission')
            ->orderBy('created_at')
            ->get();

        if ($students->isEmpty()) { 
            return back()->with('error', "No students found for {$gradeLevel} - {$section}.");
        }

        $pdf = Pdf::loadView('teacher.section-pdf', [
            'students' => $students,
            'gradeLevel' => $gradeLevel,
            'section' => $section,
        ]);

        $filename = "Grade_{$grade}_Section_{$section}_" . now()->format('Y_m_d') . '.pdf';
        return $pdf->download($filename);
    }
}