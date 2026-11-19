<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;
use App\Notifications\AdmissionStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admission::query();

        // Exclude drafts - only show submitted admissions
        $query->where('is_draft', false);

        // Search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('lrn', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Show recently updated admissions first (those updated by students within 24 hours)
        // Priority: Recently updated > Updated > New
        $admissions = $query->orderByRaw('
            CASE 
                WHEN updated_at > created_at AND updated_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 0
                WHEN updated_at > created_at THEN 1
                ELSE 2
            END
        ')->orderBy('updated_at', 'desc')->paginate(10);
        
        return view('admin.admissions.index', compact('admissions'));
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
                'desc' => $r->regDesc
            ]);
        }
        return view('admin.admissions.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Use the same schema as student admissions so all columns align
        $validated = $request->validate([
            'user_id'          => 'nullable|exists:users,id',
            'student_id'       => 'required|unique:admissions,student_id',
            'student_type'     => 'nullable|in:new,old,transferee',

            'first_name'       => 'required|string|max:255',
            'middle_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'suffix'           => 'nullable|string|max:10',
            'date_of_birth'    => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $birthDate = Carbon::parse($value);
                        $age = $birthDate->age;
                        if ($age < 11) {
                            $fail('The student must be at least 11 years old to register.');
                        }
                    }
                },
            ],
            'place_of_birth'   => 'nullable|string|max:255',
            'gender'           => 'nullable|string|max:50',
            'nationality'      => 'nullable|string|max:100',
            'religion'         => 'nullable|string|max:100',

            'region_code'      => 'nullable|string|max:255',
            'province_code'    => 'nullable|string|max:255',
            'city_code'        => 'nullable|string|max:255',
            'barangay_code'    => 'nullable|string|max:255',
            'street'           => 'nullable|string|max:255',
            'contact_no'       => 'required|string|max:50',
            'email'            => 'required|email|unique:admissions,email',

            'father_name'      => 'nullable|string|max:255',
            'father_occupation'=> 'nullable|string|max:255',
            'father_contact'   => 'nullable|string|max:50',
            'mother_name'      => 'nullable|string|max:255',
            'mother_occupation'=> 'nullable|string|max:255',
            'mother_contact'   => 'nullable|string|max:50',
            'guardian_name'    => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',

            'year_level'       => 'nullable|string',
            'strand'           => 'nullable|string',
            'tvl_branch'       => 'nullable|in:CSS,EIM',
            'previous_school'  => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string|max:255',
            'lrn'              => 'nullable|string',

            // File uploads
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'form_137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'id_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',

            'status'           => 'nullable|in:pending,approved,rejected',
        ]);

        // Handle file uploads to public disk and save relative paths in DB
        $fileFields = ['report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('uploads/admissions', 'public');
            }
        }

        if (empty($validated['status'])) {
            $validated['status'] = 'pending';
        }
        if (empty($validated['user_id'])) {
            $validated['user_id'] = auth()->id();
        }

        // Ensure strand is empty for Grade 7-10
        $yearLevel = $validated['year_level'] ?? null;
        if ($yearLevel && in_array($yearLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        if (($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            return back()->withErrors(['tvl_branch' => 'Please select a TVL branch.'])->withInput();
        }

        Admission::create($validated);

        return redirect()->route('admin.admissions.index')->with('success', 'Admission created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admission $admission)
    {
        // Refresh the admission model to ensure we have the latest data including updated files
        $admission->refresh();
        
        // Fetch address names from reference tables
        $regionName = 'N/A';
        $provinceName = 'N/A';
        $cityName = 'N/A';
        $barangayName = 'N/A';

        if (Schema::hasTable('refregion') && $admission->region_code) {
            $region = Region::where('regCode', $admission->region_code)->first();
            $regionName = $region ? $region->regDesc : $admission->region_code;
        }

        if (Schema::hasTable('refprovince') && $admission->province_code) {
            $province = Province::where('provCode', $admission->province_code)->first();
            $provinceName = $province ? $province->provDesc : $admission->province_code;
        }

        if (Schema::hasTable('refcitymun') && $admission->city_code) {
            $city = City::where('citymunCode', $admission->city_code)->first();
            $cityName = $city ? $city->citymunDesc : $admission->city_code;
        }

        if (Schema::hasTable('refbrgy') && $admission->barangay_code) {
            $barangay = Barangay::where('brgyCode', $admission->barangay_code)->first();
            $barangayName = $barangay ? $barangay->brgyDesc : $admission->barangay_code;
        }

        return view('admin.admissions.show', compact('admission', 'regionName', 'provinceName', 'cityName', 'barangayName'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admission $admission)
    {
        // Refresh the admission model to ensure we have the latest data including updated files
        $admission->refresh();
        return view('admin.admissions.edit', compact('admission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'student_id'       => 'required',
            'student_type'     => 'nullable|string',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'last_name'        => 'required|string|max:100',
            'suffix'           => 'nullable|string|max:20',
            'date_of_birth'    => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $birthDate = Carbon::parse($value);
                        $age = $birthDate->age;
                        if ($age < 11) {
                            $fail('The student must be at least 11 years old to register.');
                        }
                    }
                },
            ],
            'place_of_birth'   => 'nullable|string',
            'gender'           => 'nullable|string',
            'nationality'      => 'nullable|string',
            'religion'         => 'nullable|string',
            'region_code'      => 'nullable|string',
            'province_code'    => 'nullable|string',
            'city_code'        => 'nullable|string',
            'barangay_code'    => 'nullable|string',
            'street'           => 'nullable|string',
            'contact_no'       => 'nullable|string',
            'email'            => 'required|email',
            'father_name'      => 'nullable|string',
            'father_occupation'=> 'nullable|string',
            'father_contact'   => 'nullable|string',
            'mother_name'      => 'nullable|string',
            'mother_occupation'=> 'nullable|string',
            'mother_contact'   => 'nullable|string',
            'guardian_name'    => 'nullable|string',
            'guardian_contact' => 'nullable|string',
            'year_level'       => 'nullable|string',
            'strand'           => 'nullable|string',
            'tvl_branch'       => 'nullable|in:CSS,EIM',
            'previous_school'  => 'nullable|string',
            'previous_school_address' => 'nullable|string',
            'lrn'              => 'nullable|string',
            'status'           => 'required|in:pending,approved,rejected',
            'rejection_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        // Handle rejection remarks
        if ($request->status === 'rejected') {
            $validated['rejection_remarks'] = $request->rejection_remarks;
        } else {
            // Clear remarks if status is not rejected
            $validated['rejection_remarks'] = null;
        }

        // Handle file uploads - ensure old files are deleted when new ones are uploaded
        $fileFields = ['report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if it exists
                if ($admission->$field && \Storage::disk('public')->exists($admission->$field)) {
                    \Storage::disk('public')->delete($admission->$field);
                }
                // Store new file
                $validated[$field] = $request->file($field)->store('uploads/admissions', 'public');
            } else {
                // If not uploading new, keep old path
                $validated[$field] = $admission->$field;
            }
        }

        // Ensure strand is empty for Grade 7-10
        $yearLevel = $validated['year_level'] ?? null;
        if ($yearLevel && in_array($yearLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        $admission->fill($validated);
        $admission->save();

        return redirect()->route('admin.admissions.index')->with('success', 'Admission updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Delete functionality removed to preserve all data
    public function destroy(Admission $admission)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.admissions.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }

    public function updateStatus(Request $request, Admission $admission)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejection_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        // Only save remarks if status is rejected
        if ($request->status === 'rejected') {
            $updateData['rejection_remarks'] = $request->rejection_remarks;
        } else {
            // Clear remarks if status is not rejected
            $updateData['rejection_remarks'] = null;
        }

        $admission->update($updateData);

        // Send notification with admission object for approved status
        if ($admission->user) {
            $admission->user->notify(new AdmissionStatusNotification($admission->status, $admission));
        } else {
            // If user doesn't exist yet, send email directly to admission email
            \Illuminate\Support\Facades\Notification::route('mail', $admission->email)
                ->notify(new AdmissionStatusNotification($admission->status, $admission));
        }

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission status updated successfully.');
    }


    public function updateAdmissionStatus(Request $request, Admission $admission)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejection_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        // Only save remarks if status is rejected
        if ($request->status === 'rejected') {
            $updateData['rejection_remarks'] = $request->rejection_remarks;
        } else {
            // Clear remarks if status is not rejected
            $updateData['rejection_remarks'] = null;
        }

        $admission->update($updateData);

        // Send notification with admission object for approved status
        if ($admission->user) {
            $admission->user->notify(new AdmissionStatusNotification($admission->status, $admission));
        } else {
            // If user doesn't exist yet, send email directly to admission email
            \Illuminate\Support\Facades\Notification::route('mail', $admission->email)
                ->notify(new AdmissionStatusNotification($admission->status, $admission));
        }

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission status updated successfully.');
    }
}
