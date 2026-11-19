<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrarJuniorHighController extends Controller
{
    public function jhsGradeSeven()
    {
        $gradeSevenSectionA = Enrollment::with('admission')
            ->where('grade_level', 'Grade 7')
            ->where('section', 'HOPE')
            ->where('status', 'enrolled')
            ->get();

        $gradeSevenSectionB = Enrollment::with('admission')
            ->where('grade_level', 'Grade 7')
            ->where('section', 'FAITH')
            ->where('status', 'enrolled')
            ->get();

        $gradeSevenSectionC = Enrollment::with('admission')
            ->where('grade_level', 'Grade 7')
            ->where('section', 'LOVE')
            ->where('status', 'enrolled')
            ->get();
        $gradeSevenSectionD = Enrollment::with('admission')
            ->where('grade_level', 'Grade 7')
            ->where('section', 'WISDOM')
            ->where('status', 'enrolled')
            ->get();
        return view('registrar.junior-high.grade_seven', compact('gradeSevenSectionA', 'gradeSevenSectionB', 'gradeSevenSectionC', 'gradeSevenSectionD'));
    }



    public function jhsGradeEight()
    {
        $gradeEightSectionA = Enrollment::with('admission')
            ->where('grade_level', 'Grade 8')
            ->where('section', 'CASILI')
            ->where('status', 'enrolled')
            ->get();

        $gradeEightSectionB = Enrollment::with('admission')
            ->where('grade_level', 'Grade 8')
            ->where('section', 'LANDONG')
            ->where('status', 'enrolled')
            ->get();
        $gradeEightSectionC = Enrollment::with('admission')
            ->where('grade_level', 'Grade 8')
            ->where('section', 'MORA')
            ->where('status', 'enrolled')
            ->get();
        $gradeEightSectionD = Enrollment::with('admission')
            ->where('grade_level', 'Grade 8')
            ->where('section', 'SAN JUAN')
            ->where('status', 'enrolled')
            ->get();
        return view('registrar.junior-high.grade_eight', compact('gradeEightSectionA', 'gradeEightSectionB', 'gradeEightSectionC', 'gradeEightSectionD'));
    }


    public function jhsGradeNine()
    {
        $gradeNineSectionA = Enrollment::with('admission')
            ->where('grade_level', 'Grade 9')
            ->where('section', 'ACACIA')
            ->where('status', 'enrolled')
            ->get();

        $gradeNineSectionB = Enrollment::with('admission')
            ->where('grade_level', 'Grade 9')
            ->where('section', 'LAUAN')
            ->where('status', 'enrolled')
            ->get();
        $gradeNineSectionC = Enrollment::with('admission')
            ->where('grade_level', 'Grade 9')
            ->where('section', 'NARRA')
            ->where('status', 'enrolled')
            ->get();
        return view('registrar.junior-high.grade_nine', compact('gradeNineSectionA', 'gradeNineSectionB', 'gradeNineSectionC'));
    }

    public function jhsGradeTen()
    {
        $gradeTenSectionA = Enrollment::with('admission')
            ->where('grade_level', 'Grade 10')
            ->where('section', 'BERYL')
            ->where('status', 'enrolled')
            ->get();

        $gradeTenSectionB = Enrollment::with('admission')
            ->where('grade_level', 'Grade 10')
            ->where('section', 'DIAMOND')
            ->where('status', 'enrolled')
            ->get();
        $gradeTenSectionC = Enrollment::with('admission')
            ->where('grade_level', 'Grade 10')
            ->where('section', 'JADE')
            ->where('status', 'enrolled')
            ->get();
        return view('registrar.junior-high.grade_ten', compact('gradeTenSectionA', 'gradeTenSectionB', 'gradeTenSectionC'));
    }

    /**
     * Download student list PDF for a specific section
     */
    public function downloadSectionPdf($grade, $section)
    {
        $grade = (int) $grade;
        $gradeLevel = "Grade {$grade}";
        // Convert URL parameter (with hyphens) back to match database format
        $sectionWithHyphens = strtoupper(str_replace('-', ' ', $section));
        $sectionWithSpaces = strtoupper($section);

        // Get current school year
        $latestEnrollment = Enrollment::orderBy('school_year', 'desc')->first();
        $schoolYear = $latestEnrollment ? $latestEnrollment->school_year : date('Y') . '-' . (date('Y') + 1);

        // Get the actual section name from the sections table to match exactly
        $sectionRecord = \App\Models\Section::where('grade_level', $gradeLevel)
            ->where(function($query) use ($sectionWithHyphens, $sectionWithSpaces) {
                $query->where('name', $sectionWithHyphens)
                      ->orWhere('name', $sectionWithSpaces)
                      ->orWhere('name', str_replace(' ', '-', $sectionWithHyphens))
                      ->orWhere('name', str_replace('-', ' ', $sectionWithSpaces));
            })
            ->first();
        
        $actualSectionName = $sectionRecord ? $sectionRecord->name : $sectionWithHyphens;
        
        // Query enrollments for this section
        $students = Enrollment::where('grade_level', $gradeLevel)
            ->where('section', $actualSectionName)
            ->where('school_year', $schoolYear)
            ->where('status', 'enrolled')
            ->with('admission')
            ->orderBy('created_at')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', "No students found for {$gradeLevel} - {$actualSectionName}.");
        }

        $pdf = Pdf::loadView('admin.section-pdf', [
            'students' => $students,
            'gradeLevel' => $gradeLevel,
            'section' => $actualSectionName,
            'schoolYear' => $schoolYear,
        ]);

        $filename = "Grade_{$grade}_Section_{$actualSectionName}_" . now()->format('Y_m_d') . '.pdf';
        return $pdf->download($filename);
    }
}
