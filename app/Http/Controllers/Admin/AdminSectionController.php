<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminSectionController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('grade_level')
            ->orderBy('strand')
            ->orderBy('name')
            ->get();

        $gradeLevels = [
            'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
        ];

        $strands = ['GAS', 'TVL'];

        // Get current school year (most recent from enrollments or default to current year)
        $latestEnrollment = Enrollment::orderBy('school_year', 'desc')->first();
        $currentSchoolYear = $latestEnrollment ? $latestEnrollment->school_year : date('Y') . '-' . (date('Y') + 1);

        return view('admin.sections.index', compact('sections', 'gradeLevels', 'strands', 'currentSchoolYear'));
    }

    public function store(Request $request)
    {
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands = ['GAS', 'TVL'];

        $validated = $request->validate([
            'grade_level' => ['required', Rule::in($gradeLevels)],
            'name' => ['required', 'string', 'max:255'],
            'strand' => ['nullable', Rule::in($strands)],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Enforce: strand allowed only for Grade 11/12
        if (!in_array($validated['grade_level'], ['Grade 11', 'Grade 12'])) {
            $validated['strand'] = null;
        }

        // Default capacity if not provided
        if (!isset($validated['capacity'])) {
            $validated['capacity'] = 45;
        }

        // Unique per grade_level + name + strand
        $exists = Section::where('grade_level', $validated['grade_level'])
            ->where('name', $validated['name'])
            ->where('strand', $validated['strand'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'This section already exists for the selected grade/strand.'])
                ->withInput();
        }

        Section::create($validated);

        // Redirect based on user role
        $redirectRoute = auth()->user()->role === 'registrar' 
            ? 'registrar.sections.index' 
            : 'admin.sections.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Section added successfully.');
    }

    /**
     * Download student list PDF for a specific section (same format as teachers)
     */
    public function downloadSectionPdf($grade, $section)
    {
        $grade = (int) $grade;
        $gradeLevel = "Grade {$grade}";
        // Convert URL parameter (with hyphens) back to match database format
        // Try both with hyphens and with spaces
        $sectionWithHyphens = strtoupper(str_replace('-', ' ', $section));
        $sectionWithSpaces = strtoupper($section);

        // Get current school year
        $latestEnrollment = Enrollment::orderBy('school_year', 'desc')->first();
        $schoolYear = $latestEnrollment ? $latestEnrollment->school_year : date('Y') . '-' . (date('Y') + 1);

        // Get the actual section name from the sections table to match exactly
        $sectionRecord = Section::where('grade_level', $gradeLevel)
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

    /**
     * Download student list PDF for SHS strand (same format as teachers)
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
            $sectionRecord = Section::where('grade_level', $gradeLevel)
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

    /**
     * Add all default sections to the database
     */
    public function addAllDefaultSections()
    {
        $defaultSections = [
            // Grade 7
            ['grade_level' => 'Grade 7', 'name' => 'HOPE', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 7', 'name' => 'FAITH', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 7', 'name' => 'LOVE', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 7', 'name' => 'WISDOM', 'strand' => null, 'capacity' => 45],
            
            // Grade 8
            ['grade_level' => 'Grade 8', 'name' => 'CASILI', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 8', 'name' => 'LANDONG', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 8', 'name' => 'MORA', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 8', 'name' => 'SAN JUAN', 'strand' => null, 'capacity' => 45],
            
            // Grade 9
            ['grade_level' => 'Grade 9', 'name' => 'ACACIA', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 9', 'name' => 'LAUAN', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 9', 'name' => 'NARRA', 'strand' => null, 'capacity' => 45],
            
            // Grade 10
            ['grade_level' => 'Grade 10', 'name' => 'BERYL', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 10', 'name' => 'DIAMOND', 'strand' => null, 'capacity' => 45],
            ['grade_level' => 'Grade 10', 'name' => 'JADE', 'strand' => null, 'capacity' => 45],
            
            // Grade 11 - GAS
            ['grade_level' => 'Grade 11', 'name' => 'ZAMORA', 'strand' => 'GAS', 'capacity' => 45],
            
            // Grade 11 - TVL CSS
            ['grade_level' => 'Grade 11', 'name' => 'PADOLINA', 'strand' => 'TVL', 'capacity' => 45],
            
            // Grade 11 - TVL EIM
            ['grade_level' => 'Grade 11', 'name' => 'ZARA', 'strand' => 'TVL', 'capacity' => 45],
            ['grade_level' => 'Grade 11', 'name' => 'ALCARAZ', 'strand' => 'TVL', 'capacity' => 45],
            
            // Grade 12 - GAS
            ['grade_level' => 'Grade 12', 'name' => 'AMALTHEA', 'strand' => 'GAS', 'capacity' => 45],
            
            // Grade 12 - TVL CSS
            ['grade_level' => 'Grade 12', 'name' => 'TITANIA', 'strand' => 'TVL', 'capacity' => 45],
            
            // Grade 12 - TVL EIM
            ['grade_level' => 'Grade 12', 'name' => 'PROTEUS', 'strand' => 'TVL', 'capacity' => 45],
        ];

        $added = 0;
        $skipped = 0;

        foreach ($defaultSections as $sectionData) {
            // Check if section already exists
            $exists = Section::where('grade_level', $sectionData['grade_level'])
                ->where('name', $sectionData['name'])
                ->where('strand', $sectionData['strand'])
                ->exists();

            if (!$exists) {
                Section::create($sectionData);
                $added++;
            } else {
                $skipped++;
            }
        }

        // Redirect based on user role
        $redirectRoute = auth()->user()->role === 'registrar' 
            ? 'registrar.sections.index' 
            : 'admin.sections.index';

        return redirect()->route($redirectRoute)
            ->with('success', "Successfully added {$added} sections. {$skipped} sections already existed and were skipped.");
    }
}


