<?php


namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Admission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class StudentAdmissionController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // List all admissions of the logged in student
    public function index()
    {
        $admissions = Admission::where('user_id', Auth::id())->get();
        return view('student.admissions.index', compact('admissions'));
    }

    // Show admission form (public - for guests)
    public function createPublic()
    {
        $admissionsEnabled = filter_var(Setting::getValue('admissions_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        if (!$admissionsEnabled) {
            return redirect()->route('home')->with('error', 'Admissions are currently disabled. Please check back later.');
        }

        $regions = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('refregion')) {
            $regions = \App\Models\Region::all()->map(fn($r) => [
                'code' => $r->regCode,
                'desc' => $r->regDesc
            ]);
        }
        $user = null;
        $profile = null;
        $isPublic = true; // Flag to indicate this is a public form
        return view('student.admissions.create', compact('user', 'regions', 'profile', 'isPublic'));
    }

    // Show admission form
    public function create()
    {
        $admissionsEnabled = filter_var(Setting::getValue('admissions_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        if (!$admissionsEnabled) {
            return redirect()->route('student.dashboard')->with('error', 'Admissions are currently disabled. Please check back later.');
        }

        // Get student's current grade level from latest enrollment or admission
        $currentGrade = $this->getCurrentGradeLevel();
        
        // If student is Grade 8-10 or Grade 12, redirect to enrollment page (unless they're a transferee)
        // Note: Transferees can access admission for any grade level
        if ($currentGrade && in_array($currentGrade, ['Grade 8', 'Grade 9', 'Grade 10', 'Grade 12'])) {
            // Check if there's a pending admission with transferee type
            $pendingTransferee = Admission::where('user_id', Auth::id())
                ->where('student_type', 'transferee')
                ->where(function($q) {
                    $q->where('is_draft', true)->orWhere('status', 'pending');
                })
                ->latest('id')->first();
            
            // If no pending transferee admission, redirect to enrollment
            if (!$pendingTransferee) {
                return redirect()->route('student.enrollment.index')
                    ->with('info', 'Students in Grade ' . str_replace('Grade ', '', $currentGrade) . ' can directly access the enrollment page. Please use enrollment to proceed.');
            }
        }

        // Check if student has draft or pending admission
        $admission = Admission::where('user_id', Auth::id())
            ->where(function($q) {
                $q->where('is_draft', true)->orWhere('status', 'pending');
            })
            ->latest('id')->first();
        if ($admission) {
            // Redirect to edit form with prefilling
            return redirect()->route('student.admissions.edit', $admission->id);
        }
        $regions = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('refregion')) {
            $regions = \App\Models\Region::all()->map(fn($r) => [
                'code' => $r->regCode,
                'desc' => $r->regDesc
            ]);
        }
        $user = Auth::user();
        $profile = \App\Models\StudentProfile::firstWhere('user_id', Auth::id());
        return view('student.admissions.create', compact('user', 'regions', 'profile'));
    }

    /**
     * Get student's current grade level from latest enrollment or admission
     */
    private function getCurrentGradeLevel()
    {
        // Check latest enrollment first
        $latestEnrollment = \App\Models\Enrollment::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->latest('id')
            ->first();
        
        if ($latestEnrollment) {
            return $latestEnrollment->grade_level;
        }

        // If no enrollment, check latest admission
        $latestAdmission = Admission::where('user_id', Auth::id())
            ->where('status', '!=', 'rejected')
            ->latest('id')
            ->first();
        
        if ($latestAdmission) {
            return $latestAdmission->year_level;
        }

        return null;
    }

    // Store admission form (public - for guests)
    public function storePublic(Request $request)
    {
        $admissionsEnabled = filter_var(Setting::getValue('admissions_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        if (!$admissionsEnabled) {
            return redirect()->route('home')->with('error', 'Admissions are currently disabled. Please check back later.');
        }

        // Check if saving as draft FIRST, before validation
        $isDraft = $request->has('save_as_draft') || $request->boolean('save_as_draft');
        
        // Build validation rules - make most fields optional if saving as draft
        $rules = [
            // Student Identifier
            'student_type' => 'required|in:new,transferee',

            // Personal Information
            'first_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => $isDraft ? 'nullable|date' : [
                'required',
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
            'place_of_birth' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'gender' => $isDraft ? 'nullable|string|max:50' : 'required|string|max:50',
            'nationality' => $isDraft ? 'nullable|string|max:100' : 'required|string|max:100',
            'religion' => 'nullable|string|max:100',

            // Contact Information
            'region_code' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'province_code' => 'nullable|string|max:255',
            'city_code' => 'nullable|string|max:255',
            'barangay_code' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'contact_no' => $isDraft ? 'nullable|string|max:50' : 'required|string|max:50',
            'email' => $isDraft ? 'nullable|email:rfc,filter|max:255' : 'required|email:rfc,filter|max:255',

            // Family Information
            'father_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:50',
            'mother_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',

            // Academic Background
            'year_level' => $isDraft ? 'nullable|string' : [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $studentType = strtolower($request->input('student_type', ''));
                    $allowedGrades = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
                    $restrictedGrades = ['Grade 7', 'Grade 11'];
                    
                    if ($studentType === 'transferee') {
                        if (!in_array($value, $allowedGrades)) {
                            $fail('Invalid year level for transferee. Allowed: Grade 7-12.');
                        }
                    } else {
                        if (!in_array($value, $restrictedGrades)) {
                            $fail('Year level must be Grade 7 or Grade 11 for non-transferee students.');
                        }
                    }
                },
            ],
            'gwa' => $isDraft ? 'nullable|numeric|min:60|max:100' : 'nullable|numeric|min:60|max:100',
            'strand' => 'nullable|in:GAS,TVL',
            'tvl_branch' => 'nullable|in:CSS,EIM',
            'previous_school' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'previous_school_address' => 'nullable|string|max:255',
            'lrn' => $isDraft ? 'nullable|string' : 'required|string|unique:admissions,lrn',

            // File uploads
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'form_137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'id_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // Validate inputs
        $validated = $request->validate($rules);

        // Ensure strand is empty for Grade 7-10 (only if year_level is provided)
        $yearLevel = $validated['year_level'] ?? null;
        if ($yearLevel && in_array($yearLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        // If TVL selected, require TVL branch (only for Grade 11-12 and if not draft)
        if (!$isDraft && ($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            throw ValidationException::withMessages([
                'tvl_branch' => 'Please select a TVL branch.',
            ]);
        }

        // Conditional file requirements based on student type - only if NOT saving as draft
        if (!$isDraft) {
            $stype = strtolower($request->input('student_type', ''));
            if (in_array($stype, ['new','transferee'])) {
                $request->validate([
                    'report_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Form 138
                    'form_137' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
            }
        }

        // Check if user exists with this email
        $user = \App\Models\User::where('email', $validated['email'])->first();
        
        if (!$user) {
            // Create a minimal user account for the admission
            // The user will need to set their password when they register later
            $user = \App\Models\User::create([
                'firstname' => $validated['first_name'],
                'middlename' => $validated['middle_name'] ?? '',
                'lastname' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)), // Temporary random password
                'role' => 'student',
                'birthdate' => $validated['date_of_birth'] ?? now(),
                'sex' => strtolower($validated['gender'] ?? 'other'),
                'contact_no' => $validated['contact_no'] ?? '',
                'emergency_contact' => '',
                'address' => '',
            ]);
        }

        // Auto-generate student_id (format e.g. 2025-0001)
        $lastAdmission = Admission::latest('id')->first();
        $nextNumber = $lastAdmission ? $lastAdmission->id + 1 : 1;
        $studentId = now()->format('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        // Generate unique reference number (format: ADM-YYYY-XXXXX) - only when not a draft
        $referenceNumber = null;
        if (!$isDraft) {
            $referenceNumber = 'ADM-' . now()->format('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        }

        // Handle file uploads
        $uploads = [];
        foreach (['report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $uploads[$fileField] = $request->file($fileField)->store('uploads/admissions', 'public');
            }
        }

        // Create admission record
        $admissionData = array_merge($validated, [
            'user_id' => $user->id,
            'student_id' => $studentId,
            'reference_number' => $referenceNumber, // null for drafts, generated reference number for submissions
            'is_draft' => $isDraft,
            // Set status to 'draft' when saving as draft, 'pending' when submitting
            'status' => $isDraft ? 'draft' : 'pending',
        ], $uploads);
        
        $admission = Admission::create($admissionData);

        // Sync profile snapshot
        \App\Models\StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated)
        );

        if ($admission->is_draft) {
            return redirect()->route('admission.create.public')
                ->with('success', 'Draft saved. You can apply it later after logging in.');
        }

       // Store temporary authorization for the guest applicant's confirmation and PDF
session([
    'submitted_admission_id' => $admission->id,
    'downloadable_admission_id' => $admission->id,
]);
        // Redirect to success page with reference number
        return redirect()->route('admission.success', $admission->id)
            ->with('success', 'Admission applied successfully!');
    }

    // Store admission form
    public function store(Request $request)
    {
        $admissionsEnabled = filter_var(Setting::getValue('admissions_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        if (!$admissionsEnabled) {
            return redirect()->route('student.dashboard')->with('error', 'Admissions are currently disabled. Please check back later.');
        }

        // Check if saving as draft FIRST, before validation
        $isDraft = $request->has('save_as_draft') || $request->boolean('save_as_draft');
        
        // Build validation rules - make most fields optional if saving as draft
        $rules = [
            // Student Identifier
            'student_type' => 'required|in:new,transferee',

            // Personal Information
            'first_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => $isDraft ? 'nullable|date' : [
                'required',
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
            'place_of_birth' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'gender' => $isDraft ? 'nullable|string|max:50' : 'required|string|max:50',
            'nationality' => $isDraft ? 'nullable|string|max:100' : 'required|string|max:100',
            'religion' => 'nullable|string|max:100',

            // Contact Information
            'region_code' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'province_code' => 'nullable|string|max:255',
            'city_code' => 'nullable|string|max:255',
            'barangay_code' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'contact_no' => $isDraft ? 'nullable|string|max:50' : 'required|string|max:50',
            'email' => $isDraft ? 'nullable|email:rfc,filter|max:255' : [
                'required',
                'email:rfc,filter',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && $value !== Auth::user()->email) {
                        $fail('The email must match your account email address.');
                    }
                },
            ],

            // Family Information
            'father_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:50',
            'mother_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',

            // Academic Background
            'year_level' => $isDraft ? 'nullable|string' : [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $studentType = strtolower($request->input('student_type', ''));
                    $allowedGrades = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
                    $restrictedGrades = ['Grade 7', 'Grade 11'];
                    
                    if ($studentType === 'transferee') {
                        if (!in_array($value, $allowedGrades)) {
                            $fail('Invalid year level for transferee. Allowed: Grade 7-12.');
                        }
                    } else {
                        if (!in_array($value, $restrictedGrades)) {
                            $fail('Year level must be Grade 7 or Grade 11 for non-transferee students.');
                        }
                    }
                },
            ],
            'gwa' => $isDraft ? 'nullable|numeric|min:60|max:100' : 'nullable|numeric|min:60|max:100',
            'strand' => 'nullable|in:GAS,TVL',
            'tvl_branch' => 'nullable|in:CSS,EIM',
            'previous_school' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'previous_school_address' => 'nullable|string|max:255',
            'lrn' => $isDraft ? 'nullable|string' : 'required|string|unique:admissions,lrn',

            // File uploads
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'form_137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'id_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // Validate inputs
        $validated = $request->validate($rules);

        // Ensure strand is empty for Grade 7-10 (only if year_level is provided)
        $yearLevel = $validated['year_level'] ?? null;
        if ($yearLevel && in_array($yearLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        // If TVL selected, require TVL branch (only for Grade 11-12 and if not draft)
        if (!$isDraft && ($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            throw ValidationException::withMessages([
                'tvl_branch' => 'Please select a TVL branch.',
            ]);
        }

        // Conditional file requirements based on student type - only if NOT saving as draft
        if (!$isDraft) {
            $stype = strtolower($request->input('student_type', ''));
            if (in_array($stype, ['new','transferee'])) {
                $request->validate([
                    'report_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Form 138
                    'form_137' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
            }
        }

        // Auto-generate student_id (format e.g. 2025-0001)
        $lastAdmission = Admission::latest('id')->first();
        $nextNumber = $lastAdmission ? $lastAdmission->id + 1 : 1;
        $studentId = now()->format('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        // Generate unique reference number (format: ADM-YYYY-XXXXX)
        $referenceNumber = 'ADM-' . now()->format('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Handle file uploads
        $uploads = [];
        foreach (['report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $uploads[$fileField] = $request->file($fileField)->store('uploads/admissions', 'public');
            }
        }

        // Create admission record
        $admissionData = array_merge($validated, [
            'user_id' => Auth::id(),
            'student_id' => $studentId,
            'reference_number' => $isDraft ? null : $referenceNumber, // Only generate reference number when submitting, not for drafts
            'is_draft' => $isDraft,
            // Set status to 'draft' when saving as draft, 'pending' when submitting
            'status' => $isDraft ? 'draft' : 'pending',
        ], $uploads);
        
        $admission = Admission::create($admissionData);

        // Sync profile snapshot
        \App\Models\StudentProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge($validated)
        );

        if ($admission->is_draft) {
            return redirect()->route('student.admissions.index')
                ->with('success', 'Draft saved. You can apply it later.');
        }

       // Store temporary authorization for guest confirmation and PDF download
session([
    'submitted_admission_id' => $admission->id,
    'downloadable_admission_id' => $admission->id,
]);

        // Redirect to success page with reference number
        return redirect()->route('admission.success', $admission->id)
            ->with('success', 'Admission applied successfully!');
    }

    
// Show success page after admission submission
public function success(Request $request, Admission $admission)
{
    $isAuthorized = false;
    $isPublic = !Auth::check();

    // Allow Registrar or the student who owns this admission
    if (Auth::check()) {
        $user = Auth::user();

        if (
            $user->role === \App\Models\User::ROLE_REGISTRAR ||
            (
                $user->role === \App\Models\User::ROLE_STUDENT &&
                (int) $user->id === (int) $admission->user_id
            )
        ) {
            $isAuthorized = true;
        }
    }

    // Allow a guest applicant who just submitted this admission.
    // Keep the session authorization available for the applicant's PDF download.
    if (!$isAuthorized && !Auth::check()) {
        $submittedAdmissionId = $request->session()->get('submitted_admission_id');

        if (
            $submittedAdmissionId !== null &&
            (int) $submittedAdmissionId === (int) $admission->id
        ) {
            $isAuthorized = true;
           
        }
    }

    // Allow access through a valid signed URL
    if (!$isAuthorized && $request->hasValidSignature()) {
        $isAuthorized = true;
    }

    if (!$isAuthorized) {
        abort(403, 'Unauthorized action. You do not have permission to view this admission confirmation.');
    }

    return view('student.admissions.success', compact('admission', 'isPublic'));
}

    /**
     * Download admission application as PDF
     */
    /**
 * Download admission application as PDF
 */
public function downloadPdf(Request $request, Admission $admission)
{
    $isAuthorized = false;

    // Registrar or owner student
    if (Auth::check()) {
        $user = Auth::user();

        if (
            $user->role === \App\Models\User::ROLE_REGISTRAR ||
            (
                $user->role === \App\Models\User::ROLE_STUDENT &&
                (int) $user->id === (int) $admission->user_id
            )
        ) {
            $isAuthorized = true;
        }
    }

    // Guest applicant: allow only the admission submitted in this session
    if (!$isAuthorized && !Auth::check()) {
        $downloadableAdmissionId = $request->session()->get('downloadable_admission_id');

        if (
            $downloadableAdmissionId !== null &&
            (int) $downloadableAdmissionId === (int) $admission->id
        ) {
            $isAuthorized = true;
        }
    }

    if (!$isAuthorized) {
        abort(403, 'You are not authorized to download this admission PDF.');
    }

    $admission->load(['region', 'province', 'city', 'barangay']);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'student.admissions.admission-pdf',
        compact('admission')
    );

    $pdf->setPaper('A4', 'portrait');

    $filename = 'admission-' .
        ($admission->reference_number ?: $admission->id) . '.pdf';

    return $pdf->download($filename);
}

    /**
     * Show admission status lookup form (by reference number).
     */
    public function statusForm(Request $request)
    {
        $admission = null;
        $ref = $request->query('ref');

        if ($ref) {
            $admission = Admission::where('reference_number', $ref)->first();
        }

        return view('student.admissions.status', compact('admission', 'ref'));
    }

    // Show single admission
    public function show(Admission $admission)
    {
        $this->authorize('view', $admission);
        return view('student.admissions.show', compact('admission'));
    }

    public function edit(Admission $admission)
    {
        $this->authorize('update', $admission);
        $regions = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('refregion')) {
            $regions = \App\Models\Region::all()->map(fn($r) => [
                'code' => $r->regCode,
                'desc' => $r->regDesc
            ]);
        }
        $user = Auth::user();
        $profile = \App\Models\StudentProfile::firstWhere('user_id', Auth::id());
        return view('student.admissions.create', [ // Use same blade
            'admission' => $admission,
            'user' => $user,
            'regions' => $regions,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request, Admission $admission)
    {
        $this->authorize('update', $admission);
        
        // Check if saving as draft FIRST, before validation
        $isDraft = $request->has('save_as_draft') || $request->boolean('save_as_draft');
        
        // Build validation rules - make most fields optional if saving as draft
        $rules = [
            'student_type' => 'required|in:new,transferee',
            'first_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => $isDraft ? 'nullable|date' : [
                'required',
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
            'place_of_birth' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'gender' => $isDraft ? 'nullable|string|max:50' : 'required|string|max:50',
            'nationality' => $isDraft ? 'nullable|string|max:100' : 'required|string|max:100',
            'religion' => 'nullable|string|max:100',
            'region_code' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'province_code' => 'nullable|string|max:255',
            'city_code' => 'nullable|string|max:255',
            'barangay_code' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'contact_no' => $isDraft ? 'nullable|string|max:50' : 'required|string|max:50',
            'email' => $isDraft ? 'nullable|email:rfc,filter|max:255' : [
                'required',
                'email:rfc,filter',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && $value !== Auth::user()->email) {
                        $fail('The email must match your account email address.');
                    }
                },
            ],
            'father_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:50',
            'mother_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
            'year_level' => $isDraft ? 'nullable|string' : [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $studentType = strtolower($request->input('student_type', ''));
                    $allowedGrades = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
                    $restrictedGrades = ['Grade 7', 'Grade 11'];
                    
                    if ($studentType === 'transferee') {
                        if (!in_array($value, $allowedGrades)) {
                            $fail('Invalid year level for transferee. Allowed: Grade 7-12.');
                        }
                    } else {
                        if (!in_array($value, $restrictedGrades)) {
                            $fail('Year level must be Grade 7 or Grade 11 for non-transferee students.');
                        }
                    }
                },
            ],
            'gwa' => $isDraft ? 'nullable|numeric|min:60|max:100' : 'nullable|numeric|min:60|max:100',
            'strand' => 'nullable|in:GAS,TVL',
            'tvl_branch' => 'nullable|in:CSS,EIM',
            'previous_school' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'previous_school_address' => 'nullable|string|max:255',
            'lrn' => $isDraft ? 'nullable|string' : 'required|string|unique:admissions,lrn,' . $admission->id,
        ];
        
        $validated = $request->validate($rules);

        // Ensure strand is empty for Grade 7-10 (only if year_level is provided)
        $yearLevel = $validated['year_level'] ?? null;
        if ($yearLevel && in_array($yearLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $validated['strand'] = null;
            $validated['tvl_branch'] = null;
        }

        // If TVL selected, require TVL branch (only for Grade 11-12 and if not draft)
        if (!$isDraft && ($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            return back()->withErrors(['tvl_branch' => 'Please select a TVL branch.'])->withInput();
        }
        
        // Validate required files for non-draft applications
        // Medical Certificate and ID Picture are always optional
        $optionalFiles = ['medical_certificate', 'id_picture'];
        $requiredFiles = ['report_card', 'form_137', 'birth_certificate', 'good_moral'];
        
        if (!$isDraft) {
            $stype = strtolower($request->input('student_type', ''));
            
            // Check which files are required based on student type
            $missingFiles = [];
            
            foreach ($requiredFiles as $fileField) {
                // Skip if file is in optional list
                if (in_array($fileField, $optionalFiles)) {
                    continue;
                }
                
                // Check if file exists in admission
                $fileExists = $admission->$fileField && \Storage::disk('public')->exists($admission->$fileField);
                
                // Check if new file is being uploaded
                $newFileUploaded = $request->hasFile($fileField);
                
                // If file doesn't exist and no new file is uploaded, it's missing
                if (!$fileExists && !$newFileUploaded) {
                    // Determine requirement based on student type
                    $isRequired = false;
                    
                    if (in_array($stype, ['new', 'transferee'])) {
                        // For new/transferee: form_137, report_card, birth_certificate are required
                        if (in_array($fileField, ['form_137', 'report_card', 'birth_certificate'])) {
                            $isRequired = true;
                        }
                    }
                    
                    if ($isRequired) {
                        $missingFiles[] = $fileField;
                    }
                }
            }
            
            // Validate missing files
            if (!empty($missingFiles)) {
                $fileLabels = [
                    'form_137' => 'Form 137',
                    'report_card' => 'Report Card',
                    'birth_certificate' => 'Birth Certificate',
                    'good_moral' => 'Good Moral Certificate',
                ];
                
                $errors = [];
                foreach ($missingFiles as $field) {
                    $errors[$field] = ucfirst($fileLabels[$field] ?? $field) . ' is required. Please upload this file.';
                }
                
                return back()->withErrors($errors)->withInput();
            }
        }
        
        // Handle file replacement logic
        foreach ([ 'report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'] as $fileField) {
            if ($request->hasFile($fileField)) {
                if ($admission->$fileField) {
                    \Storage::disk('public')->delete($admission->$fileField);
                }
                $validated[$fileField] = $request->file($fileField)->store('uploads/admissions', 'public');
            } else {
                $validated[$fileField] = $admission->$fileField;
            }
        }
        
        // Check if saving as draft (already checked above, but need to set in validated)
        $validated['is_draft'] = $isDraft;
        
        // Store original status before updating
        $originalStatus = $admission->status;
        $wasRejected = $originalStatus === 'rejected';
        $wasApproved = $originalStatus === 'approved';
        $wasDraft = $admission->is_draft;
        
        // Handle status changes
        if ($isDraft) {
            // If saving as draft, set status to 'draft' (don't submit to admin)
            $validated['status'] = 'draft';
        } else {
            // If applying (not draft)
            if ($wasDraft) {
                // If converting from draft to application, set status to pending
                $validated['status'] = 'pending';
            } elseif ($wasRejected || $wasApproved) {
                // If admission was rejected or approved, change status back to pending when edited
                // This ensures changes are reviewed by admin/registrar
                $validated['status'] = 'pending';
            }
            // Otherwise, keep existing status
        }
        
        $admission->fill($validated);
        $admission->save();

        // Sync profile snapshot
        \App\Models\StudentProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge($validated)
        );
        
        // Generate appropriate success message
        if ($admission->is_draft) {
            $message = 'Draft updated.';
        } elseif ($wasRejected) {
            $message = 'Admission updated and resubmitted for review!';
        } elseif ($wasApproved) {
            $message = 'Admission updated. Status changed to pending for re-review.';
        } else {
            $message = 'Admission updated!';
        }
        
        return redirect()->route('student.admissions.index')->with('success', $message);
    }

    // Delete functionality removed to preserve all data
    public function destroy(Admission $admission)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('student.admissions.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }

    // Province API
    public function getProvinces($regCode)
    {
        if (\Schema::hasTable('refprovince')) {
            $provinces = \App\Models\Province::where('regCode', $regCode)->orderBy('provDesc')->get(['provCode','provDesc','regCode']);
            $data = $provinces->map(fn($p) => [
                'code' => $p->provCode,
                'name' => $p->provDesc,
                'region_code' => $p->regCode,
            ])->values();
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => []]);
    }
    // City API
    public function getCities($provCode)
    {
        if (\Schema::hasTable('refcitymun')) {
            $cities = \App\Models\City::where('provCode', $provCode)->orderBy('citymunDesc')->get(['citymunCode','citymunDesc','provCode']);
            $data = $cities->map(fn($c) => [
                'code' => $c->citymunCode,
                'name' => $c->citymunDesc,
                'province_code' => $c->provCode,
            ])->values();
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => []]);
    }
    // Barangay API
    public function getBarangays($citymunCode)
    {
        if (\Schema::hasTable('refbrgy')) {
            $brgys = \App\Models\Barangay::where('citymunCode', $citymunCode)->orderBy('brgyDesc')->get(['brgyCode','brgyDesc','citymunCode']);
            $data = $brgys->map(fn($b) => [
                'code' => $b->brgyCode,
                'name' => $b->brgyDesc,
                'city_code' => $b->citymunCode,
            ])->values();
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => []]);
    }
}


// namespace App\Http\Controllers\Student;

// use App\Http\Controllers\Controller;
// use App\Models\Admission;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\Region;
// use App\Models\Province;
// use App\Models\City;
// use App\Models\Barangay;

// class StudentAdmissionController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         $admissions = Admission::latest()->paginate(10);
//         return view('student.admissions.index', compact('admissions'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         $regions = Region::all();
//         return view('student.admissions.create', compact('regions'));
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'student_id'       => 'required|unique:admissions,student_id',
//             'first_name'       => 'required|string|max:100',
//             'middle_name'      => 'nullable|string|max:100',
//             'last_name'        => 'required|string|max:100',
//             'suffix'           => 'nullable|string|max:20',
//             'address'          => 'required|string|max:255',
//             'region_code' => 'required',
//             'province_code' => 'required',
//             'citymun_code' => 'required',
//             'barangay_code' => 'required',
//             'email'            => 'required|email|unique:admissions,email',
//             'contact'          => 'required|string|max:20',
//             'guardian_name'    => 'required|string|max:150',
//             'guardian_contact' => 'required|string|max:20',
//             'year'             => 'required|string',
//             'strand'           => 'nullable|string',
//             'last_school_attended' => 'nullable|string|max:255',
//         ]);
//         $user = Auth::user();


//         // auto-generate student_id kung wala pa
//         // $studentId = 'STU-' . str_pad(Admission::count() + 1, 5, '0', STR_PAD_LEFT);

//         Admission::create(array_merge($validated, [
//             'user_id'    => $user->id,   // logged-in student
//             // 'student_id' => $studentId,  // auto-generated
//         ]));

//         return redirect()->route('student.index')->with('success', 'Admission created successfully!');
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(Admission $admission)
//     {
//         return view('student.admissions.show', compact('admission'));
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(Admission $admission)
//     {
//         return view('student.admissions.edit', compact('admission'));
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, Admission $admission)
//     {
//         $validated = $request->validate([
//             'user_id'          => 'required|exists:users,id',
//             'student_id'       => 'required|unique:admissions,student_id,' . $admission->id,
//             'first_name'       => 'required|string|max:100',
//             'middle_name'      => 'nullable|string|max:100',
//             'last_name'        => 'required|string|max:100',
//             'suffix'           => 'nullable|string|max:20',
//             'address'          => 'required|string|max:255',
//             'email'            => 'required|email|unique:admissions,email,' . $admission->id,
//             'contact'          => 'required|string|max:20',
//             'guardian_name'    => 'required|string|max:150',
//             'guardian_contact' => 'required|string|max:20',
//             'year'             => 'required|string',
//             'strand'           => 'nullable|string',
//             'last_school_attended' => 'nullable|string|max:255',
//         ]);

//         $admission->update($validated);

//         return redirect()->route('student.index')->with('success', 'Admission updated successfully!');
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(Admission $admission)
//     {
//         $admission->delete();
//         return redirect()->route('student.admissions.index')->with('success', 'Admission deleted successfully!');
//     }
//     // --- AJAX Functions ---
//     public function getProvinces($regCode)
//     {
//         $region = Region::with('provinces')->where('regCode', $regCode)->first();

//         if (!$region) {
//             return response()->json([]); // para hindi mag-error kung walang region
//         }

//         return response()->json($region->provinces);
//     }


//     public function getCities($provCode)
//     {
//         $cities = City::where('provCode', $provCode)->get();
//         return response()->json($cities);
//     }

//     public function getBarangays($citymunCode)
//     {
//         $barangays = Barangay::where('citymunCode', $citymunCode)->get();
//         return response()->json($barangays);
//     }
// }


// //   public function create()
// //     {
// //         $regions = Region::with('provinces')->get(); // preload regions with provinces
// //         return view('student.admissions.create', compact('regions'));
// //     }

// //     public function getProvinces($regCode)
// //     {
// //         $region = Region::where('regCode', $regCode)->first();
// //         return response()->json($region ? $region->provinces : []);
// //     }

// //     public function getCities($provCode)
// //     {
// //         $province = Province::where('provCode', $provCode)->first();
// //         return response()->json($province ? $province->cities : []);
// //     }

// //     public function getBarangays($citymunCode)
// //     {
// //         $city = City::where('citymunCode', $citymunCode)->first();
// //         return response()->json($city ? $city->barangays : []);
// //     }