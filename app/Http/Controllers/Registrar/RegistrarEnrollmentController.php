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

        // Search functionality - grouped so orWhere conditions do not bypass soft-delete or status filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('admission', function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%");
                })
                ->orWhere('grade_level', 'like', "%{$search}%")
                ->orWhere('section', 'like', "%{$search}%");
            });
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
     * Note: create() and store() have been removed from RegistrarEnrollmentController.
     * Enrollment applications are submitted by students via StudentEnrollmentController
     * to ensure accurate student identity. The Registrar reviews, manages, assigns
     * sections, updates status, and restores enrollment records.
     */


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
        $sectionQuery = Section::where('name', $validated['section'])
            ->where('grade_level', $enrollment->grade_level);

        if (!empty($enrollment->strand)) {
            $sectionQuery->where(function ($q) use ($enrollment) {
                $q->where('strand', $enrollment->strand)->orWhereNull('strand');
            });
        } else {
            $sectionQuery->whereNull('strand');
        }

        $sectionCfg = $sectionQuery->orderByRaw('CASE WHEN strand IS NOT NULL THEN 0 ELSE 1 END')->first();

        if ($sectionCfg) {
            $countQuery = Enrollment::where('grade_level', $enrollment->grade_level)
                ->where('section', $validated['section'])
                ->whereIn('status', ['pending', 'enrolled']);

            // Appropriate school year
            if (!empty($enrollment->school_year)) {
                $countQuery->where('school_year', $enrollment->school_year);
            }

            // Appropriate strand where applicable
            if (!empty($enrollment->strand)) {
                $countQuery->where('strand', $enrollment->strand);
            }

            // Appropriate TVL branch where applicable
            if (!empty($enrollment->tvl_branch)) {
                $countQuery->where('tvl_branch', $enrollment->tvl_branch);
            }

            // Exclude current enrollment so re-assigning same section does not count student against capacity
            $countQuery->where('id', '!=', $enrollment->id);

            $currentCount = $countQuery->count();

            if ($currentCount >= $sectionCfg->capacity) {
                return back()->withErrors(['section' => 'Section is full (capacity '.$sectionCfg->capacity.').']);
            }
        }

        $enrollment->update(['section' => $validated['section']]);

        return back()->with('success', 'Section updated successfully.');
    }
}
