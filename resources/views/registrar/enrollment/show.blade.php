@extends('layout.app')

@section('content')
    <style>
        .enrollment-details-page .enrollment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            flex-wrap: wrap;
        }

        .enrollment-details-page .detail-card {
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 14px;
        }

        .enrollment-details-page .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1f4f8f;
            margin-bottom: 12px;
        }

        .enrollment-details-page .section-subtitle {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin: 16px 0 8px;
        }

        .enrollment-details-page .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 14px;
        }

        .enrollment-details-page .detail-item {
            background: #f8fafc;
            border: 1px solid #e9eef5;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .enrollment-details-page .detail-item .label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6c757d;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .enrollment-details-page .detail-item .value {
            display: block;
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }

        .enrollment-details-page .status-chip {
            font-size: 13px;
            padding: 7px 11px;
        }

        .enrollment-details-page .facts-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .enrollment-details-page .facts-list li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #eef1f5;
            font-size: 14px;
        }

        .enrollment-details-page .facts-list li:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .enrollment-details-page .facts-list span {
            color: #6c757d;
            font-weight: 500;
        }

        .enrollment-details-page .facts-list strong {
            color: #1f2937;
            text-align: right;
        }

        .enrollment-details-page .sticky-panel {
            position: sticky;
            top: 90px;
        }

        @media (max-width: 991.98px) {
            .enrollment-details-page .sticky-panel {
                position: static;
            }
        }

        @media (max-width: 767.98px) {
            .enrollment-details-page .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        $admission = $enrollment->admission;
        $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);

        $regionName = $admission?->region_code ? \App\Models\Region::where('regCode', $admission->region_code)->value('regDesc') : null;
        $provinceName = $admission?->province_code ? \App\Models\Province::where('provCode', $admission->province_code)->value('provDesc') : null;
        $cityName = $admission?->city_code ? \App\Models\City::where('citymunCode', $admission->city_code)->value('citymunDesc') : null;
        $barangayName = $admission?->barangay_code ? \App\Models\Barangay::where('brgyCode', $admission->barangay_code)->value('brgyDesc') : null;
        $parts = array_filter([
            $admission->street ?? null,
            $barangayName,
            $cityName,
            $provinceName,
            $regionName ? 'Region: ' . $regionName : null,
        ]);

        $fullName = trim(implode(' ', array_filter([
            $admission->first_name ?? null,
            $admission->middle_name ?? null,
            $admission->last_name ?? null,
            $admission->suffix ?? null,
        ])));

        $statusClass = match ($enrollment->status) {
            'pending' => 'warning',
            'enrolled' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };

        $guardianName = $enrollment->guardian_name
            ?? $admission->guardian_name
            ?? $admission->father_name
            ?? $admission->mother_name
            ?? 'N/A';

        $guardianContact = $enrollment->guardian_contact
            ?? $admission->guardian_contact
            ?? $admission->father_contact
            ?? $admission->mother_contact
            ?? 'N/A';

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
            $shsDefaults = [
                'Grade 11' => ['GAS' => ['ZAMORA'], 'TVL' => ['ZARA', 'ALCARAZ']],
                'Grade 12' => ['GAS' => ['AMALTHEA'], 'TVL' => ['PROTEUS']],
            ];
            $options = $isShs
                ? ($shsDefaults[$enrollment->grade_level][$enrollment->strand] ?? [])
                : ($defaults[$enrollment->grade_level] ?? []);
        }
    @endphp

    <div class="container my-4 my-md-5 enrollment-details-page">
        <div class="enrollment-header mb-3">
            <div>
                <h3 class="mb-1">Enrollment Details</h3>
                <p class="text-muted mb-0">Review student profile, section assignment, and enrollment status.</p>
            </div>
            <a href="{{ route('registrar.enrollment.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card shadow-sm border-0 detail-card mb-3">
                    <div class="card-body">
                        <div class="section-title">Student Information</div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Name</span>
                                <span class="value">{{ $fullName ?: 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Email</span>
                                <span class="value">{{ $admission->email ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Contact</span>
                                <span class="value">{{ $admission->contact_no ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Student Type</span>
                                <span class="value">{{ ucfirst($admission->student_type ?? 'n/a') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">LRN</span>
                                <span class="value">{{ $admission->lrn ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Date of Birth</span>
                                <span class="value">{{ $admission->date_of_birth ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="label">Address</span>
                                <span class="value">{{ $parts ? implode(', ', $parts) : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 detail-card mb-3">
                    <div class="card-body">
                        <div class="section-title">Enrollment Information</div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Grade Level</span>
                                <span class="value">{{ $enrollment->grade_level }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">School Year</span>
                                <span class="value">{{ $enrollment->school_year }}</span>
                            </div>
                            @if($isShs)
                                <div class="detail-item">
                                    <span class="label">Strand</span>
                                    <span class="value">{{ $enrollment->strand ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Semester</span>
                                    <span class="value">{{ $enrollment->semester ?? 'N/A' }}</span>
                                </div>
                                @if(($enrollment->strand ?? null) === 'TVL')
                                    <div class="detail-item">
                                        <span class="label">TVL Branch</span>
                                        <span class="value">{{ $enrollment->tvl_branch ?? 'N/A' }}</span>
                                    </div>
                                @endif
                            @endif
                            <div class="detail-item">
                                <span class="label">Current Section</span>
                                <span class="value">{{ $enrollment->section ?? 'Not Assigned' }}</span>
                            </div>
                        </div>

                        <div class="section-subtitle">Section Assignment</div>
                        <form action="{{ route('registrar.enrollments.assignSection', $enrollment->id) }}" method="POST" class="row g-2 align-items-center">
                            @csrf
                            @method('PUT')
                            <div class="col-sm-8">
                                <select name="section" class="form-select form-select-sm">
                                    <option value="">-- Set Section --</option>
                                    @foreach($options as $sec)
                                        <option value="{{ $sec }}" {{ $enrollment->section === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 d-grid">
                                <button type="submit" class="btn btn-primary btn-sm">Save Section</button>
                            </div>
                        </form>

                        @if($admission && $admission->student_type === 'transferee')
                            @php
                                $selectedSubjects = $enrollment->selected_subjects ?? [];
                                $droppedSubjects = $enrollment->dropped_subjects ?? [];
                            @endphp
                            @if(!empty($selectedSubjects) || !empty($droppedSubjects))
                                <div class="section-subtitle">Transferee Subject Mapping</div>
                                <div class="detail-grid">
                                    @if(!empty($selectedSubjects))
                                        @php $subjects = \App\Models\Subject::whereIn('id', $selectedSubjects)->get(); @endphp
                                        <div class="detail-item">
                                            <span class="label">Subjects to Take</span>
                                            <span class="value">
                                                @foreach($subjects as $subject)
                                                    <div>{{ $subject->name }}@if($subject->code) ({{ $subject->code }})@endif</div>
                                                @endforeach
                                            </span>
                                        </div>
                                    @endif
                                    @if(!empty($droppedSubjects))
                                        @php $subjects = \App\Models\Subject::whereIn('id', $droppedSubjects)->get(); @endphp
                                        <div class="detail-item">
                                            <span class="label">Subjects to Drop</span>
                                            <span class="value">
                                                @foreach($subjects as $subject)
                                                    <div>{{ $subject->name }}@if($subject->code) ({{ $subject->code }})@endif</div>
                                                @endforeach
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 detail-card">
                    <div class="card-body">
                        <div class="section-title">Parent / Guardian Information</div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Father</span>
                                <span class="value">{{ $admission->father_name ?? 'N/A' }} ({{ $admission->father_occupation ?? 'N/A' }}) • {{ $admission->father_contact ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Mother</span>
                                <span class="value">{{ $admission->mother_name ?? 'N/A' }} ({{ $admission->mother_occupation ?? 'N/A' }}) • {{ $admission->mother_contact ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="label">Guardian</span>
                                <span class="value">{{ $guardianName }} • {{ $guardianContact }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card shadow-sm border-0 detail-card sticky-panel mb-3">
                    <div class="card-body">
                        <div class="section-title">Enrollment Status</div>
                        <div class="mb-3">
                            <span class="badge status-chip bg-{{ $statusClass }}">{{ ucfirst($enrollment->status) }}</span>
                        </div>

                        @if($enrollment->status === 'cancelled' && $enrollment->cancellation_remarks)
                            <div class="alert alert-danger py-2 px-3 mb-3">
                                <strong>Reason:</strong> {{ $enrollment->cancellation_remarks }}
                            </div>
                        @endif

                        <form action="{{ route('registrar.enrollments.updateEnrollmentStatus', $enrollment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label for="status" class="form-label">Update Status</label>
                            <select name="status" id="status" class="form-select form-select-sm mb-2">
                                <option value="pending" {{ $enrollment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="enrolled" {{ $enrollment->status === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="cancelled" {{ $enrollment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $enrollment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-sm">Save Status</button>
                            </div>
                        </form>
                        <small class="text-muted d-block mt-2">Saving status redirects to the enrollment list.</small>
                    </div>
                </div>

                <div class="card shadow-sm border-0 detail-card">
                    <div class="card-body">
                        <div class="section-title">Quick Facts</div>
                        <ul class="facts-list">
                            <li><span>Grade</span><strong>{{ $enrollment->grade_level }}</strong></li>
                            <li><span>Section</span><strong>{{ $enrollment->section ?? 'Not Assigned' }}</strong></li>
                            <li><span>School Year</span><strong>{{ $enrollment->school_year }}</strong></li>
                            @if($isShs)
                                <li><span>Strand</span><strong>{{ $enrollment->strand ?? 'N/A' }}</strong></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
