<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Admission;
use App\Models\SchoolYear;
use App\Notifications\EnrollmentStatusNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;

class RegistrarEnrollmentController extends Controller
{
    /**
     * Display all enrollments (list).
     */
    public function index(Request $request)
    {
        // Check if showing deleted enrollments
        $showDeleted = $request->has('show_deleted') && $request->show_deleted == '1';
        
        if ($showDeleted) {
            $query = Enrollment::withTrashed()->whereNotNull('deleted_at')->with('admission');
        } else {
            $query = Enrollment::with('admission');
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('admission', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%");
            })->orWhere('grade_level', 'like', "%{$search}%")
              ->orWhere('section', 'like', "%{$search}%");
        }

        // Filter by status (only for non-deleted)
        if (!$showDeleted && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort by strand, grade level, and section (then by school year)
        $enrollments = $query->orderBy('strand')
            ->orderBy('grade_level')
            ->orderBy('section')
            ->orderByDesc('school_year')
            ->paginate(10);

        $sections = Section::orderBy('name')->pluck('name')->unique()->values()->all();
        if (empty($sections)) {
            $sections = ['A', 'B', 'C'];
        }
        return view('registrar.enrollment.index', compact('enrollments', 'sections', 'showDeleted'));
    }

    /**
     * Show form for creating enrollment.
     */
    public function create()
    {
        // Get approved admissions only
        // $admissions = Admission::where('status', 'approved')->get();

        $admission = Admission::where('user_id', auth()->id())->first();
        
        // Get active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        return view('student.enrollment.create', compact('admission', 'activeSchoolYear'));
    }

    /**
     * Store enrollment record.
     */
    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'admission_id'     => 'required|exists:admissions,id',
            'grade_level'      => 'required|string|max:50',
            'strand'           => 'nullable|string|max:100',
            'section'          => 'nullable|string|max:100',
            'school_year'      => 'nullable|string|max:20', // Made nullable to allow default
            'semester'         => 'nullable|string|max:20',
            'guardian_name'    => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
        ]);

        // Set default school year to active school year if not provided
        if (empty($validated['school_year'])) {
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();
            if ($activeSchoolYear) {
                $validated['school_year'] = $activeSchoolYear->school_year;
            } else {
                return back()->withErrors(['school_year' => 'No active school year found. Please set an active school year first.'])->withInput();
            }
        }

        // ✅ Find Admission (auto 404 if not found)
        $admission = Admission::findOrFail($validated['admission_id']);

        // Ensure strand is empty for Grade 7-10
        $gradeLevel = $validated['grade_level'] ?? null;
        if ($gradeLevel && in_array($gradeLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        // ✅ Create Enrollment
        $enrollment = Enrollment::create([
            'admission_id'     => $admission->id,
            'user_id'          => Auth::id(), // current logged in user
            'grade_level'      => $validated['grade_level'],
            'strand'           => $validated['strand'] ?? null,
            'section'          => $validated['section'] ?? null,
            'school_year'      => $validated['school_year'],
            'semester'         => $validated['semester'] ?? null,
            'guardian_name'    => $validated['guardian_name'] ?? $admission->guardian_name,
            'guardian_contact' => $validated['guardian_contact'] ?? $admission->guardian_contact,
            'status'           => 'pending', // default status
        ]);

        // ✅ Redirect with success message
        return redirect()->route('student.enrollment.index')
            ->with('success', 'Enrollment submitted successfully!');
    }


    /**
     * Show single enrollment record.
     */
    public function show(Enrollment $enrollment)
    {
        return view('registrar.enrollment.show', compact('enrollment'));
    }

    /**
     * (Optional) Update enrollment status (by registrar).
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        // $request->validate([
        //     'status' => 'required|in:pending,enrolled,cancelled,completed',
        // ]);

        // $enrollment->update([
        //     'status' => $request->status,
        // ]);

        return back()->with('success', 'Enrollment status updated.');
    }

    /**
     * Delete enrollment (if needed).
     */
    public function destroy(Enrollment $enrollment)
    {
        // Soft delete the enrollment record
        $enrollment->delete();

        return back()->with('success', 'Enrollment deleted successfully.');
    }

    /**
     * Restore a soft-deleted enrollment.
     */
    public function restore($id)
    {
        $enrollment = Enrollment::withTrashed()->findOrFail($id);
        $enrollment->restore();

        return back()->with('success', 'Enrollment restored successfully.');
    }


    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,enrolled,cancelled,completed',
        ]);

        $enrollment->update([
            'status' => $request->status,
        ]);

        $enrollment->user->notify(new EnrollmentStatusNotification($enrollment->status));

        return redirect()->route('registrar.enrollment.index')
            ->with('success', 'Enrollment status updated successfully.');
    }

    public function assignSection(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:255',
        ]);

        // Find configured capacity for this section and grade/strand if exists
        $sectionCfg = Section::where('name', $validated['section'])
            ->where(function ($q) use ($enrollment) {
                $q->whereNull('strand')->orWhere('strand', $enrollment->strand);
            })
            ->where('grade_level', $enrollment->grade_level)
            ->first();

        if ($sectionCfg) {
            $currentCount = Enrollment::where('grade_level', $enrollment->grade_level)
                ->where('section', $validated['section'])
                ->whereIn('status', ['pending', 'enrolled'])
                ->count();

            if ($currentCount >= $sectionCfg->capacity) {
                return back()->withErrors(['section' => 'Section is full (capacity '.$sectionCfg->capacity.').']);
            }
        }

        $enrollment->update(['section' => $validated['section']]);

        return back()->with('success', 'Section updated successfully.');
    }
}
