<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function create()
    {
        $gradeLevels = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
        $strands = ['GAS','TVL'];
        return view('teacher.subjects.create', compact('gradeLevels','strands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code',
            'grade_level' => 'nullable|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'strand' => 'nullable|in:GAS,TVL',
            'description' => 'nullable|string|max:2000',
        ]);

        if (in_array($validated['grade_level'] ?? null, ['Grade 7','Grade 8','Grade 9','Grade 10'])) {
            $validated['strand'] = null;
        }

        Subject::create($validated);

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Subject added successfully. You can now assign it in Teacher Assignments.');
    }
}


