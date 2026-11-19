<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Notifications\EnrollmentStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Section;

class AdminEnrollmentController extends Controller
{
    /**
     * Display all enrollments (list).
     */
    public function index(Request $request)
    {
        $query = Enrollment::with('admission');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('admission', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%");
            })->orWhere('grade_level', 'like', "%{$search}%")
              ->orWhere('section', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sort by strand, grade level, and section (then by school year)
        $enrollments = $query
            ->orderBy('strand')
            ->orderBy('grade_level')
            ->orderBy('section')
            ->orderByDesc('school_year')
            ->paginate(10);
        $sections = Section::orderBy('name')->pluck('name')->unique()->values()->all();
        if (empty($sections)) {
            $sections = ['A', 'B', 'C'];
        }
        return view('admin.enrollment.index', compact('enrollments', 'sections'));
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
        $enrollment->load('user', 'admission');
        return view('admin.enrollment.show', compact('enrollment'));
    }

    /**
     * (Optional) Update enrollment status (by admin).
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
        // Soft delete enrollment
        $enrollment->delete();

        return back()->with('success', 'Enrollment deleted successfully.');
    }

    /**
     * Permanently delete enrollment (admin only).
     */
    public function forceDelete($id)
    {
        $enrollment = Enrollment::withTrashed()->findOrFail($id);
        $enrollment->forceDelete();

        return back()->with('success', 'Enrollment permanently deleted.');
    }


    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,enrolled,cancelled,completed',
            'cancellation_remarks' => 'required_if:status,cancelled|nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        // Only save remarks if status is cancelled
        if ($request->status === 'cancelled') {
            $updateData['cancellation_remarks'] = $request->cancellation_remarks;
        } else {
            // Clear remarks if status is not cancelled
            $updateData['cancellation_remarks'] = null;
        }

        $enrollment->update($updateData);

        $enrollment->user->notify(new EnrollmentStatusNotification($enrollment->status));

        return redirect()->route('admin.enrollment.index')
            ->with('success', 'Enrollment status updated successfully.');
    }

    public function assignSection(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:255',
        ]);

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
    public function downloadPDF($gradeLevel, $section)
     {
        $students = Student::where('grade_level', $gradeLevel)
                          ->where('section', $section)
                          ->with('admission')
                          ->get();
        
        $pdf = Pdf::loadView('enrollment-pdf', [
            'students' => $students,
            'gradeLevel' => $gradeLevel,
            'section' => $section
        ]);
        
        return $pdf->download("enrollment-{$gradeLevel}-{$section}.pdf");
    }
}
