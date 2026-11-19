<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        $admissionsEnabled = filter_var(Setting::getValue('admissions_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        return view('home', compact('admissionsEnabled'));
    }
}

