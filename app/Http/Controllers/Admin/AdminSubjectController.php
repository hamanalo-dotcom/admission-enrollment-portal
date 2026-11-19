<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminSubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }
        if ($request->filled('strand')) {
            $query->where('strand', $request->strand);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('code', 'like', "%$s%");
            });
        }

        $subjects = $query->orderBy('grade_level')->orderBy('strand')->orderBy('name')->paginate(15);
        $gradeLevels = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
        $strands = ['GAS','TVL'];
        return view('admin.subjects.index', compact('subjects','gradeLevels','strands'));
    }

    public function create()
    {
        $gradeLevels = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
        $strands = ['GAS','TVL'];
        
        // Get all school years for selection
        $schoolYears = \App\Models\SchoolYear::orderBy('school_year', 'desc')->get();
        // Also get school years from enrollments that might not be in the table
        $enrollmentSchoolYears = \App\Models\Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');
        
        $allSchoolYears = $schoolYears->pluck('school_year')->merge($enrollmentSchoolYears)->unique()->sort()->reverse()->values();
        
        return view('admin.subjects.create', compact('gradeLevels','strands', 'allSchoolYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code',
            'grade_level' => 'nullable|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'strand' => 'nullable|in:GAS,TVL',
            'school_year' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
        ]);

        // For JHS, ensure strand is null
        if (in_array($validated['grade_level'] ?? null, ['Grade 7','Grade 8','Grade 9','Grade 10'])) {
            $validated['strand'] = null;
        }

        Subject::create($validated);
        
        // Detect which route was used (admin or registrar)
        $routeName = $request->route()->getName();
        $redirectRoute = str_contains($routeName, 'registrar') ? 'registrar.subjects.index' : 'admin.subjects.index';
        
        return redirect()->route($redirectRoute)->with('success','Subject created successfully');
    }

    public function edit(Subject $subject)
    {
        $gradeLevels = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
        $strands = ['GAS','TVL'];
        
        // Get all school years for selection
        $schoolYears = \App\Models\SchoolYear::orderBy('school_year', 'desc')->get();
        // Also get school years from enrollments that might not be in the table
        $enrollmentSchoolYears = \App\Models\Enrollment::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');
        
        $allSchoolYears = $schoolYears->pluck('school_year')->merge($enrollmentSchoolYears)->unique()->sort()->reverse()->values();
        
        return view('admin.subjects.edit', compact('subject','gradeLevels','strands', 'allSchoolYears'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code,' . $subject->id,
            'grade_level' => 'nullable|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'strand' => 'nullable|in:GAS,TVL',
            'school_year' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
        ]);

        if (in_array($validated['grade_level'] ?? null, ['Grade 7','Grade 8','Grade 9','Grade 10'])) {
            $validated['strand'] = null;
        }

        $subject->update($validated);
        
        // Detect which route was used (admin or registrar)
        $routeName = $request->route()->getName();
        $redirectRoute = str_contains($routeName, 'registrar') ? 'registrar.subjects.index' : 'admin.subjects.index';
        
        return redirect()->route($redirectRoute)->with('success','Subject updated successfully');
    }

    // Delete functionality removed to preserve all data
    public function destroy(Subject $subject)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.subjects.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }
}
