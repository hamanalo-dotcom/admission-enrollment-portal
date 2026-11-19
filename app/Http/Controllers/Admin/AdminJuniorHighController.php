<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AdminJuniorHighController extends Controller
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
        return view('admin.junior-high.grade_seven', compact('gradeSevenSectionA', 'gradeSevenSectionB', 'gradeSevenSectionC', 'gradeSevenSectionD'));
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
        return view('admin.junior-high.grade_eight', compact('gradeEightSectionA', 'gradeEightSectionB', 'gradeEightSectionC', 'gradeEightSectionD'));
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
        return view('admin.junior-high.grade_nine', compact('gradeNineSectionA', 'gradeNineSectionB', 'gradeNineSectionC'));
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
        return view('admin.junior-high.grade_ten', compact('gradeTenSectionA', 'gradeTenSectionB', 'gradeTenSectionC'));
    }
}
