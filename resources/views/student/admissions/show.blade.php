@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Admission Details</h3>

                <!-- Back Button and Actions -->
                <div class="position-absolute top-0 end-0 mt-4 me-4 d-flex gap-2">
                    <a href="{{ route('admission.download.pdf', $admission->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <a href="{{ route('student.admissions.edit', $admission->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    {{-- Delete functionality removed to preserve all data --}}
                    <a href="{{ route('student.admissions.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <!-- Student Information Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="text-primary">Student Information</h5>
                    </div>
                    <hr>
                    {{-- <p><strong>Student ID:</strong> {{ $admission->student_id }}</p> --}}
                    <p><strong>Name:</strong>
                        {{ $admission->first_name }}
                        {{ $admission->middle_name }}
                        {{ $admission->last_name }}
                        {{ $admission->suffix }}
                    </p>
                    <p><strong>Email:</strong> {{ $admission->email }}</p>
                    <p><strong>Contact:</strong> {{ $admission->contact_no }}</p>
                    @php
                        $regionName = $admission?->region_code ? \App\Models\Region::where('regCode', $admission->region_code)->value('regDesc') : null;
                        $provinceName = $admission?->province_code ? \App\Models\Province::where('provCode', $admission->province_code)->value('provDesc') : null;
                        $cityName = $admission?->city_code ? \App\Models\City::where('citymunCode', $admission->city_code)->value('citymunDesc') : null;
                        $barangayName = $admission?->barangay_code ? \App\Models\Barangay::where('brgyCode', $admission->barangay_code)->value('brgyDesc') : null;
                        $parts = array_filter([
                            $admission->street ?: null,
                            $barangayName,
                            $cityName,
                            $provinceName,
                            $regionName ? 'Region: '.$regionName : null,
                        ]);
                    @endphp
                    <p><strong>Address:</strong> {{ $parts ? implode(', ', $parts) : 'N/A' }}</p>
                    <p><strong>Previous School:</strong> {{ $admission->previous_school ?? 'N/A' }}</p>
                    <p><strong>Previous School Address:</strong> {{ $admission->previous_school_address ?? 'N/A' }}</p>
                </div>

                <!-- Guardian Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Guardian Information</h5>
                    <hr>
                    @php
                        // Use parent details if guardian details are missing
                        $guardianName = $admission->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? 'N/A';
                        $guardianContact = $admission->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? 'N/A';
                    @endphp
                    <p><strong>Guardian Name:</strong> {{ $guardianName }}</p>
                    <p><strong>Guardian Contact:</strong> {{ $guardianContact }}</p>
                </div>

                <!-- Academic Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Academic Information</h5>
                    <hr>
                    <p><strong>Year Level:</strong> {{ $admission->year_level }}</p>
                    @php
                        $isShs = in_array($admission->year_level, ['Grade 11', 'Grade 12']);
                    @endphp
                    @if($isShs)
                        <p><strong>Strand:</strong> {{ $admission->strand ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Status:</strong>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span
                                class="badge 
                                @if ($admission->status == 'draft' || $admission->is_draft) bg-secondary 
                                @elseif ($admission->status == 'approved') bg-success 
                                @elseif($admission->status == 'pending') bg-warning 
                                @else bg-danger @endif">
                                {{ ucfirst($admission->status ?? 'draft') }}
                            </span>
                            @if($admission->status === 'rejected' && $admission->rejection_remarks)
                                <span class="text-danger" style="font-size: 14px;">
                                    <i class="fas fa-comment-alt"></i> <strong>Reason:</strong> {{ $admission->rejection_remarks }}
                                </span>
                            @endif
                        </div>
                    </p>
                </div>

                <!-- Uploaded Files -->
                <div class="mb-4">
                    <h5 class="text-primary">Uploaded Files</h5>
                    <hr>
                    <ul>
                        @if($admission->report_card)
                            <li><a href="{{ route('admissions.files.download', [$admission->id, 'report_card']) }}">Form 138 (Report Card)</a></li>
                        @endif
                        @if($admission->form_137)
                            <li><a href="{{ route('admissions.files.download', [$admission->id, 'form_137']) }}">Form 137 (School Record)</a></li>
                        @endif
                        @if($admission->birth_certificate)
                            <li><a href="{{ route('admissions.files.download', [$admission->id, 'birth_certificate']) }}">Birth Certificate</a></li>
                        @endif
                        @if($admission->good_moral)
                            <li><a href="{{ route('admissions.files.download', [$admission->id, 'good_moral']) }}">Good Moral Certificate</a></li>
                        @endif
                        @if($admission->medical_certificate)
                            <li><a href="{{ route('admissions.files.download', [$admission->id, 'medical_certificate']) }}">Medical Certificate</a></li>
                        @endif
                        @if($admission->id_picture)
                            <li><a href="{{ route('admissions.files.download', [$admission->id, 'id_picture']) }}">ID Picture</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Status History -->
                <div class="mb-4">
                    <h5 class="text-primary">Status History</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admission->statusHistories()->latest()->get() as $h)
                                    <tr>
                                        <td>{{ $h->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $h->from_status ?? '-' }}</td>
                                        <td>{{ $h->to_status }}</td>
                                        <td>{{ $h->comment }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">No history yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
