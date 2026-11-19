<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AdminSeniorHighController extends Controller
{
    public function shsGradeEleven()
    {
        $gradeElevenGasZamora = Enrollment::with('admission')
            ->where('grade_level', 'Grade 11')
            ->where('strand', 'GAS')
            ->where('section', 'ZAMORA')
            ->where('status', 'enrolled')
            ->get();

        $gradeElevenTvlZara = Enrollment::with('admission')
            ->where('grade_level', 'Grade 11')
            ->where('strand', 'TVL')
            ->where('section', 'ZARA')
            ->where('status', 'enrolled')
            ->get();

        $gradeElevenTvlAlcaraz = Enrollment::with('admission')
            ->where('grade_level', 'Grade 11')
            ->where('strand', 'TVL')
            ->where('section', 'ALCARAZ')
            ->where('status', 'enrolled')
            ->get();

        return view('admin.senior-high.grade_eleven', compact('gradeElevenGasZamora', 'gradeElevenTvlZara', 'gradeElevenTvlAlcaraz'));
    }

    public function shsGradeTwelve()
    {
        $gradeTwelveGasAmalthea = Enrollment::with('admission')
            ->where('grade_level', 'Grade 12')
            ->where('strand', 'GAS')
            ->where('section', 'AMALTHEA')
            ->where('status', 'enrolled')
            ->get();

        $gradeTwelveTvlProteus = Enrollment::with('admission')
            ->where('grade_level', 'Grade 12')
            ->where('strand', 'TVL')
            ->where('section', 'PROTEUS')
            ->where('status', 'enrolled')
            ->get();

        return view('admin.senior-high.grade_twelve', compact('gradeTwelveGasAmalthea', 'gradeTwelveTvlProteus'));
    }
}
