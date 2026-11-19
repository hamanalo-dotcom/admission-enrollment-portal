@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Enrollment Details</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Back Button -->
                <div class="position-absolute top-0 end-0 mt-4 me-4">
                    <a href="{{ route('admin.enrollment.index') }}" class="text-dark" style="font-size: 1.25rem;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>

                <!-- Student Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Student Information</h5>
                    <hr>
                    <p><strong>Name:</strong>
                        {{ $enrollment->admission->first_name }}
                        {{ $enrollment->admission->middle_name }}
                        {{ $enrollment->admission->last_name }}
                        {{ $enrollment->admission->suffix }}
                    </p>
                    <p><strong>Email:</strong> {{ $enrollment->admission->email }}</p>
                    <p><strong>Contact:</strong> {{ $enrollment->admission->contact_no }}</p>
                    @php
                        $ad = $enrollment->admission;
                        $regionName = $ad?->region_code ? \App\Models\Region::where('regCode', $ad->region_code)->value('regDesc') : null;
                        $provinceName = $ad?->province_code ? \App\Models\Province::where('provCode', $ad->province_code)->value('provDesc') : null;
                        $cityName = $ad?->city_code ? \App\Models\City::where('citymunCode', $ad->city_code)->value('citymunDesc') : null;
                        $barangayName = $ad?->barangay_code ? \App\Models\Barangay::where('brgyCode', $ad->barangay_code)->value('brgyDesc') : null;
                        $parts = array_filter([
                            $ad->street ?: null,
                            $barangayName,
                            $cityName,
                            $provinceName,
                            $regionName ? 'Region: '.$regionName : null,
                        ]);
                    @endphp
                    <p><strong>Address:</strong> {{ $parts ? implode(', ', $parts) : 'N/A' }}</p>
                </div>

                <!-- Enrollment Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Enrollment Information</h5>
                    <hr>
                    <p><strong>Grade Level:</strong> {{ $enrollment->grade_level }}</p>
                    @php
                        $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
                    @endphp
                    @if($isShs)
                        <p><strong>Strand/Track:</strong> {{ $enrollment->strand ?? 'N/A' }}</p>
                        @if(($enrollment->strand ?? null) === 'TVL')
                            <p><strong>TVL Branch:</strong> {{ $enrollment->tvl_branch ?? 'N/A' }}</p>
                        @endif
                    @endif
                    <p><strong>Section:</strong>
                        {{ $enrollment->section ?? 'Not Assigned' }}
                        <!-- Quick edit for admin: section -->
                        @php
                            $isShs = in_array($enrollment->grade_level, ['Grade 11','Grade 12']);
                            $options = [];
                            try {
                                $q = \App\Models\Section::where('grade_level', $enrollment->grade_level);
                                if ($isShs) {
                                    if (!empty($enrollment->strand)) {
                                        $q->where('strand', $enrollment->strand);
                                    } else {
                                        $q->whereNull('strand');
                                    }
                                } else {
                                    $q->whereNull('strand');
                                }
                                $options = $q->orderBy('name')->pluck('name')->unique()->values()->toArray();
                            } catch (\Throwable $e) {
                                $options = [];
                            }
                            if (empty($options)) {
                                $defaults = [
                                    'Grade 7' => ['HOPE', 'FAITH', 'LOVE', 'WISDOM'],
                                    'Grade 8' => ['CASILI', 'LANDONG', 'MORA', 'SAN JUAN'],
                                    'Grade 9' => ['ACACIA', 'LAUAN', 'NARRA'],
                                    'Grade 10' => ['BERYL', 'DIAMOND', 'JADE'],
                                ];
                                $shs = [
                                    'Grade 11' => [
                                        'GAS' => ['ZAMORA'],
                                        'TVL' => [
                                            'CSS' => ['PADOLINA'],
                                            'EIM' => ['ZARA','ALCARAZ'],
                                        ],
                                    ],
                                    'Grade 12' => [
                                        'GAS' => ['AMALTHEA'],
                                        'TVL' => [
                                            'CSS' => ['TITANIA'],
                                            'EIM' => ['PROTEUS'],
                                        ],
                                    ],
                                ];
                                if ($isShs) {
                                    if (($enrollment->strand ?? null) === 'TVL') {
                                        $options = $shs[$enrollment->grade_level]['TVL'][$enrollment->tvl_branch ?? ''] ?? [];
                                    } else {
                                        $options = $shs[$enrollment->grade_level][$enrollment->strand] ?? [];
                                    }
                                } else {
                                    $options = $defaults[$enrollment->grade_level] ?? [];
                                }
                            }
                        @endphp
                        <form action="{{ route('admin.enrollments.assignSection', $enrollment->id) }}" method="POST" class="d-inline ms-2">
                            @csrf
                            @method('PUT')
                            <select name="section" class="form-control form-control-sm d-inline-block w-auto">
                                <option value="">-- Set Section --</option>
                                @foreach($options as $sec)
                                    <option value="{{ $sec }}" {{ $enrollment->section === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </p>
                    <p><strong>School Year:</strong> {{ $enrollment->school_year }}</p>
                    @if($isShs)
                        <p><strong>Semester:</strong> {{ $enrollment->semester ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Status:</strong>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @php
                                $statusClass = match ($enrollment->status) {
                                    'pending' => 'warning',
                                    'enrolled' => 'success',
                                    'cancelled' => 'danger',
                                    'completed' => 'info',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($enrollment->status) }}</span>
                            @if($enrollment->status === 'cancelled' && $enrollment->cancellation_remarks)
                                <span class="text-danger" style="font-size: 14px;">
                                    <i class="fas fa-comment-alt"></i> <strong>Reason:</strong> {{ $enrollment->cancellation_remarks }}
                                </span>
                            @endif
                        </div>
                    </p>
                    @if($enrollment->admission && $enrollment->admission->student_type === 'transferee')
                        @php
                            $selectedSubjects = $enrollment->selected_subjects ?? [];
                            $droppedSubjects = $enrollment->dropped_subjects ?? [];
                        @endphp
                        @if(!empty($selectedSubjects) || !empty($droppedSubjects))
                            @if(!empty($selectedSubjects))
                                <p><strong>Subjects to Take:</strong>
                                    @php
                                        $subjects = \App\Models\Subject::whereIn('id', $selectedSubjects)->get();
                                    @endphp
                                    <ul style="margin: 5px 0 0 20px;">
                                        @foreach($subjects as $subject)
                                            <li><strong>{{ $subject->name }}</strong>@if($subject->code) ({{ $subject->code }})@endif</li>
                                        @endforeach
                                    </ul>
                                </p>
                            @endif
                            @if(!empty($droppedSubjects))
                                <p><strong>Subjects to Drop:</strong>
                                    @php
                                        $subjects = \App\Models\Subject::whereIn('id', $droppedSubjects)->get();
                                    @endphp
                                    <ul style="margin: 5px 0 0 20px;">
                                        @foreach($subjects as $subject)
                                            <li><strong>{{ $subject->name }}</strong>@if($subject->code) ({{ $subject->code }})@endif</li>
                                        @endforeach
                                    </ul>
                                </p>
                            @endif
                        @endif
                    @endif
                </div>

                <!-- Guardian Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Parent/Guardian Information</h5>
                    <hr>
                    <p><strong>Father:</strong> {{ $enrollment->admission->father_name ?? 'N/A' }} ({{ $enrollment->admission->father_occupation ?? 'N/A' }}) • {{ $enrollment->admission->father_contact ?? 'N/A' }}</p>
                    <p><strong>Mother:</strong> {{ $enrollment->admission->mother_name ?? 'N/A' }} ({{ $enrollment->admission->mother_occupation ?? 'N/A' }}) • {{ $enrollment->admission->mother_contact ?? 'N/A' }}</p>
                    @php
                        $admission = $enrollment->admission;
                        $guardianName = $enrollment->guardian_name ?? $admission->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? 'N/A';
                        $guardianContact = $enrollment->guardian_contact ?? $admission->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? 'N/A';
                    @endphp
                    <p><strong>Guardian:</strong> {{ $guardianName }} • {{ $guardianContact }}</p>
                </div>

                <!-- Admission Files quick access -->
                <div class="mb-4">
                    <h5 class="text-primary">Admission Files</h5>
                    <hr>
                    <div class="row">
                        @php 
                            $a = $enrollment->admission;
                            $fileLabels = [
                                'report_card' => 'Form 138 (Report Card)',
                                'form_137' => 'Form 137 (School Record)',
                                'birth_certificate' => 'Birth Certificate',
                                'good_moral' => 'Good Moral Certificate',
                                'medical_certificate' => 'Medical Certificate',
                                'id_picture' => 'ID Picture'
                            ];
                        @endphp
                        @foreach($fileLabels as $field => $label)
                            @if($a && $a->$field && Storage::disk('public')->exists($a->$field))
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('admissions.files.download', [$a->id, $field]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> {{ $label }}
                                    </a>
                                    @if($a->updated_at)
                                        <small class="text-muted ms-2">(Updated: {{ \Carbon\Carbon::parse($a->updated_at)->format('M d, Y') }})</small>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        @if(!$a || !collect($fileLabels)->keys()->contains(function($field) use ($a) { return $a && $a->$field && Storage::disk('public')->exists($a->$field); }))
                            <div class="col-12">
                                <p class="text-muted">No admission files available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Update Status Section (moved to bottom) -->
                <div class="mb-4 mt-4">
                    <h5 class="text-primary">Update Enrollment Status</h5>
                    <hr>
                    <form action="{{ route('admin.enrollments.updateEnrollmentStatus', $enrollment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label"><strong>Status:</strong></label>
                                <select name="status" id="status" class="form-control" onchange="toggleCancellationRemarks()">
                                    @foreach(['pending','enrolled','cancelled','completed'] as $s)
                                        <option value="{{ $s }}" {{ $enrollment->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3" id="cancellationRemarksField" style="display: {{ $enrollment->status === 'cancelled' ? 'block' : 'none' }};">
                                <label for="cancellation_remarks" class="form-label"><strong>Cancellation/Rejection Comments:</strong> <span class="text-danger">*</span></label>
                                <textarea name="cancellation_remarks" id="cancellation_remarks" class="form-control" rows="4" placeholder="Please provide a reason for cancellation/rejection...">{{ old('cancellation_remarks', $enrollment->cancellation_remarks ?? '') }}</textarea>
                                <small class="text-muted">This comment will be visible to the student.</small>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Status
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


                <script>
                    function toggleCancellationRemarks() {
                        const statusSelect = document.getElementById('status');
                        const remarksField = document.getElementById('cancellationRemarksField');
                        const remarksTextarea = document.getElementById('cancellation_remarks');
                        
                        if (statusSelect.value === 'cancelled') {
                            remarksField.style.display = 'block';
                            remarksTextarea.setAttribute('required', 'required');
                        } else {
                            remarksField.style.display = 'none';
                            remarksTextarea.removeAttribute('required');
                            remarksTextarea.value = '';
                        }
                    }
                    
                    // Initialize on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        toggleCancellationRemarks();
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
