<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function create()
    {
        abort(403, 'Unauthorized action. Subject catalog management is restricted to Registrars.');
    }

    public function store(Request $request)
    {
        abort(403, 'Unauthorized action. Subject catalog management is restricted to Registrars.');
    }
}


