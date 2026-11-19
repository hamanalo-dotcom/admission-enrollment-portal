<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrarSeniorHighController extends Controller
{
    public function shsGradeEleven()
    {
        $gradeElevenGas = Enrollment::with('admission')
            ->where('grade_level', 'Grade 11')
            ->where('strand', 'GAS')
            ->where('status', 'enrolled')
            ->get();

        $gradeElevenCss = Enrollment::with('admission')
            ->where('grade_level', 'Grade 11')
            ->where('strand', 'CSS')
            ->where('status', 'enrolled')
            ->get();

        $gradeElevenEim = Enrollment::with('admission')
            ->where('grade_level', 'Grade 11')
            ->where('strand', 'EIM')
            ->where('status', 'enrolled')
            ->get();

        return view('registrar.senior-high.grade_eleven', compact('gradeElevenGas', 'gradeElevenCss', 'gradeElevenEim'));
    }

    public function shsGradeTwelve()
    {
        $gradeTwelveGas = Enrollment::with('admission')
            ->where('grade_level', 'Grade 12')
            ->where('strand', 'GAS')
            ->where('status', 'enrolled')
            ->get();

        $gradeTwelveCss = Enrollment::with('admission')
            ->where('grade_level', 'Grade 12')
            ->where('strand', 'CSS')
            ->where('status', 'enrolled')
            ->get();

        $gradeTwelveEim = Enrollment::with('admission')
            ->where('grade_level', 'Grade 12')
            ->where('strand', 'EIM')
            ->where('status', 'enrolled')
            ->get();

        return view('registrar.senior-high.grade_twelve', compact('gradeTwelveGas', 'gradeTwelveCss', 'gradeTwelveEim'));
    }

    /**
     * Download student list PDF for SHS strand
     */
    public function downloadStrandPdf($grade, $strand, $section = null)
    {
        $grade = (int) $grade;
        $gradeLevel = "Grade {$grade}";
        $strand = strtoupper($strand);

        // Get current school year
        $latestEnrollment = Enrollment::orderBy('school_year', 'desc')->first();
        $schoolYear = $latestEnrollment ? $latestEnrollment->school_year : date('Y') . '-' . (date('Y') + 1);

        // Query enrollments
        $query = Enrollment::where('grade_level', $gradeLevel)
            ->where('strand', $strand)
            ->where('school_year', $schoolYear)
            ->where('status', 'enrolled')
            ->with('admission');

        if ($section) {
            // Convert URL parameter (with hyphens) back to match database format
            $sectionName = strtoupper(str_replace('-', ' ', $section));
            // Try to get actual section name from sections table
            $sectionRecord = \App\Models\Section::where('grade_level', $gradeLevel)
                ->where('strand', $strand)
                ->where(function($q) use ($sectionName, $section) {
                    $q->where('name', $sectionName)
                      ->orWhere('name', strtoupper($section))
                      ->orWhere('name', str_replace(' ', '-', $sectionName))
                      ->orWhere('name', str_replace('-', ' ', strtoupper($section)));
                })
                ->first();
            $actualSectionName = $sectionRecord ? $sectionRecord->name : $sectionName;
            $query->where('section', $actualSectionName);
        }

        $students = $query->orderBy('section')
            ->orderBy('created_at')
            ->get();

        if ($students->isEmpty()) {
            $sectionText = $section ? " - Section {$section}" : '';
            return back()->with('error', "No students found for {$gradeLevel} - {$strand}{$sectionText}.");
        }

        $pdf = Pdf::loadView('admin.strand-pdf', [
            'students' => $students,
            'gradeLevel' => $gradeLevel,
            'strand' => $strand,
            'section' => $section,
            'schoolYear' => $schoolYear,
        ]);

        $sectionPart = $section ? "_Section_{$section}" : '';
        $filename = "{$gradeLevel}_{$strand}{$sectionPart}_" . now()->format('Y_m_d') . '.pdf';
        return $pdf->download($filename);
    }
}
