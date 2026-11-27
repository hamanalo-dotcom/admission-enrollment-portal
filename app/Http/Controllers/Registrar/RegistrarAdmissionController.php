<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Region;
use App\Models\Setting;
use App\Notifications\AdmissionStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RegistrarAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admissionsEnabled = filter_var(Setting::getValue('admissions_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        $query = Admission::where('is_draft', false);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort by grade level, then by section (from enrollment), then by name
        $admissions = $query
            ->with(['enrollment' => function($q) {
                $q->where('status', 'enrolled');
            }])
            ->orderByRaw("CASE 
                WHEN year_level = 'Grade 7' THEN 1
                WHEN year_level = 'Grade 8' THEN 2
                WHEN year_level = 'Grade 9' THEN 3
                WHEN year_level = 'Grade 10' THEN 4
                WHEN year_level = 'Grade 11' THEN 5
                WHEN year_level = 'Grade 12' THEN 6
                ELSE 7
            END")
            ->orderByRaw("(SELECT section FROM enrollments WHERE enrollments.admission_id = admissions.id AND enrollments.status = 'enrolled' LIMIT 1)")
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);
        
        return view('registrar.admissions.index', compact('admissions', 'admissionsEnabled'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = collect();
        if (Schema::hasTable('refregion')) {
            $regions = Region::all()->map(fn($r) => [
                'code' => $r->regCode,
                'desc' => $r->regDesc,
            ]);
        }

        // Reuse the modal-step admission management form.
        return view('admin.admissions.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:admissions,student_id',
            'student_type' => 'required|in:new,old,transferee',

            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthDate = Carbon::parse($value);
                    if ($birthDate->age < 11) {
                        $fail('The student must be at least 11 years old to register.');
                    }
                },
            ],
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|max:50',
            'nationality' => 'required|string|max:100',
            'religion' => 'nullable|string|max:100',

            'region_code' => 'required|string|max:255',
            'province_code' => 'nullable|string|max:255',
            'city_code' => 'nullable|string|max:255',
            'barangay_code' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'contact_no' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:admissions,email',

            'father_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:50',
            'mother_name' => 'required|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',

            'year_level' => 'required|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'strand' => 'nullable|in:GAS,TVL',
            'tvl_branch' => 'nullable|in:CSS,EIM',
            'previous_school' => 'required|string|max:255',
            'previous_school_address' => 'nullable|string|max:255',
            'lrn' => 'required|string|max:255|unique:admissions,lrn',

            'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'id_picture' => 'required|file|mimes:jpg,jpeg,png|max:4096',
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'form_137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $yearLevel = $validated['year_level'];
        $isShs = in_array($yearLevel, ['Grade 11', 'Grade 12'], true);

        if ($isShs && empty($validated['strand'])) {
            return back()->withErrors(['strand' => 'Strand is required for Grade 11 and Grade 12.'])->withInput();
        }

        if (($validated['strand'] ?? null) === 'TVL' && empty($validated['tvl_branch'])) {
            return back()->withErrors(['tvl_branch' => 'Please select a TVL branch.'])->withInput();
        }

        if (!$isShs) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        foreach (['report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $validated[$fileField] = $request->file($fileField)->store('uploads/admissions', 'public');
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['is_draft'] = false;
        $validated['status'] = 'pending';

        Admission::create($validated);

        return redirect()->route('registrar.admissions.index')
            ->with('success', 'Admission created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admission $admission)
    {
        return view('registrar.admissions.show', compact('admission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admission $admission)
    {
        return view('registrar.admissions.create', compact('admission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'student_id'       => 'required|unique:admissions,student_id,' . $admission->id,
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'last_name'        => 'required|string|max:100',
            'suffix'           => 'nullable|string|max:20',
            'address'          => 'required|string|max:255',
            'email'            => 'required|email|unique:admissions,email,' . $admission->id,
            'contact'          => 'required|string|max:20',
            'guardian_name'    => 'required|string|max:150',
            'guardian_contact' => 'required|string|max:20',
            'year'             => 'required|string',
            'strand'           => 'required|string',
            'tvl_branch'       => 'nullable|in:CSS,EIM',
            'last_school_attended' => 'nullable|string|max:255',
        ]);

        $admission->update($validated);

        return redirect()->route('registrar.admissions.index')->with('success', 'Admission updated successfully!');
    }

    /**
     * Toggle admissions availability (enable/disable) by registrar.
     */
    public function toggleAdmissions(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean',
        ]);

        Setting::setValue('admissions_enabled', $request->boolean('enabled') ? '1' : '0');

        return redirect()->back()->with('success', 'Admission applications have been ' . ($request->boolean('enabled') ? 'enabled' : 'disabled') . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Delete functionality removed to preserve all data
    public function destroy(Admission $admission)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('registrar.admissions.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }

    public function updateAdmissionStatus(Request $request, Admission $admission)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,approved,rejected',
        'rejection_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
    ]);

    // Required documents that must be accepted before approval.
    $requiredDocuments = [
        'birth_certificate' => 'Birth Certificate',
        'id_picture' => '2x2 ID Picture',
    ];

    // Prevent approval if a required document is missing or not accepted.
    if ($validated['status'] === 'approved') {
        $reviewStatuses = $admission->file_review_status ?? [];
        $unacceptedDocuments = [];

        foreach ($requiredDocuments as $field => $label) {
            if (
                empty($admission->{$field}) ||
                ($reviewStatuses[$field] ?? 'pending') !== 'accepted'
            ) {
                $unacceptedDocuments[] = $label;
            }
        }

        if (!empty($unacceptedDocuments)) {
            return back()
                ->withErrors([
                    'status' => 'Cannot approve this admission. The following documents must be uploaded and accepted first: '
                        . implode(', ', $unacceptedDocuments) . '.',
                ])
                ->withInput();
        }
    }

    // Prepare the admission status update.
    $updateData = [
        'status' => $validated['status'],
        'rejection_remarks' => $validated['status'] === 'rejected'
            ? $validated['rejection_remarks']
            : null,
    ];

    $admission->update($updateData);

    // Notify the applicant about the updated admission status.
    if ($admission->user) {
        $admission->user->notify(
            new AdmissionStatusNotification(
                $admission->status,
                $admission
            )
        );
    } elseif (!empty($admission->email)) {
        \Illuminate\Support\Facades\Notification::route(
            'mail',
            $admission->email
        )->notify(
            new AdmissionStatusNotification(
                $admission->status,
                $admission
            )
        );
    }

    return redirect()
        ->route('registrar.admissions.index')
        ->with('success', 'Admission status updated successfully.');
}

    /**
     * Update review status for a single uploaded file (report_card, form_137, etc.).
     */
    public function updateFileStatus(Request $request, Admission $admission, string $field)
    {
        $validFields = ['report_card', 'form_137', 'birth_certificate', 'good_moral', 'medical_certificate', 'id_picture'];
        if (! in_array($field, $validFields, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid document field.',
                ], 404);
            }
            abort(404);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $current = $admission->file_review_status ?? [];
        $current[$field] = $request->input('status');

        $admission->file_review_status = $current;
        $admission->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'field' => $field,
                'status' => $request->input('status'),
                'message' => 'Document review saved successfully.',
            ]);
        }

        return back()->with('success', ucfirst(str_replace('_', ' ', $field)).' marked as '.$request->input('status').'.');
    }
}
