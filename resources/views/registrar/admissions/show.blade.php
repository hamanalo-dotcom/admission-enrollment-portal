@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Admission Details</h3>

                <!-- Back Button -->
                <div class="position-absolute top-0 end-0 mt-4 me-4 d-flex gap-2">
                    <a href="{{ route('admission.download.pdf', $admission->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <a href="{{ route('registrar.admissions.index') }}" class="btn btn-light btn-sm">
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

                <!-- Uploaded Files (Preview + Per-file Review) -->
                <div class="mb-4">
                    <h5 class="text-primary">Uploaded Files</h5>
                    <hr>
                    @php
                        $fileLabels = [
                            'report_card' => 'Form 138 (Report Card)',
                            'form_137' => 'Form 137 (School Record)',
                            'birth_certificate' => 'Birth Certificate',
                            'good_moral' => 'Good Moral Certificate',
                            'medical_certificate' => 'Medical Certificate',
                            'id_picture' => 'ID Picture',
                        ];
                        $fileStatus = $admission->file_review_status ?? [];
                    @endphp
                    <div class="row">
                        @foreach($fileLabels as $field => $label)
                            <div class="col-md-6 mb-4">
                                <h6>{{ $label }}</h6>
                                @if($admission->$field && \Storage::disk('public')->exists($admission->$field))
                                    @php
                                        $url = \Storage::url($admission->$field);
                                        $extension = strtolower(pathinfo($admission->$field, PATHINFO_EXTENSION));
                                        $status = $fileStatus[$field] ?? 'pending';
                                    @endphp
                                    <div class="border rounded mb-2 p-2" style="background:#f8f9fa;">
                                        @if(in_array($extension, ['jpg','jpeg','png','gif','webp']))
                                            <img src="{{ $url }}" alt="{{ $label }}" style="max-width: 100%; max-height: 300px; object-fit: contain;">
                                        @elseif($extension === 'pdf')
                                            <embed src="{{ $url }}" type="application/pdf" style="width:100%; height:300px;" />
                                        @else
                                            <p class="text-muted">Unsupported preview type. You can still download this file from the student view.</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('registrar.admissions.updateFileStatus', [$admission->id, $field]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex align-items-center gap-2">
                                            <span><strong>Status:</strong></span>
                                            <select name="status" class="form-control form-control-sm w-auto">
                                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                        @if($status === 'accepted')
                                            <small class="text-success d-block mt-1"><i class="fas fa-check-circle"></i> This file has been accepted.</small>
                                        @elseif($status === 'rejected')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-times-circle"></i> This file has been rejected.</small>
                                        @else
                                            <small class="text-muted d-block mt-1">Waiting for review.</small>
                                        @endif
                                    </form>
                                @else
                                    <p class="text-muted">No file uploaded.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
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
                                    <th>By</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admission->statusHistories()->latest()->get() as $h)
                                    <tr>
                                        <td>{{ $h->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $h->from_status ?? '-' }}</td>
                                        <td>{{ $h->to_status }}</td>
                                        <td>{{ optional($h->user)->firstname }} {{ optional($h->user)->lastname }}</td>
                                        <td>{{ $h->comment }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-muted">No history yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                    <p><strong>Strand:</strong> {{ $admission->strand ?? 'N/A' }}</p>
                    <p><strong>Status:</strong>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span
                                class="badge 
                                @if ($admission->status == 'approved') bg-success 
                                @elseif($admission->status == 'pending') bg-warning 
                                @else bg-danger @endif">
                                {{ ucfirst($admission->status) }}
                            </span>
                            @if($admission->status === 'rejected' && $admission->rejection_remarks)
                                <span class="text-danger" style="font-size: 14px;">
                                    <i class="fas fa-comment-alt"></i> <strong>Reason:</strong> {{ $admission->rejection_remarks }}
                                </span>
                            @endif
                        </div>
                    </p>
                </div>

                <!-- Status Update Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Update Status</h5>
                    <hr>
                    <form action="{{ route('registrar.admissions.updateAdmissionStatus', $admission->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <label><strong>Change Status:</strong></label>
                            <select name="status" id="statusSelect" class="form-control form-control-sm d-inline-block w-auto mb-2" onchange="toggleRemarksField()">
                                <option value="pending" {{ $admission->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $admission->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $admission->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div id="remarksField" style="display: {{ $admission->status == 'rejected' ? 'block' : 'none' }};" class="mb-2">
                            <label><strong>Rejection Remarks/Comments:</strong> <span class="text-danger">*</span></label>
                            <textarea name="rejection_remarks" class="form-control form-control-sm" rows="3" placeholder="Please provide a reason for rejection...">{{ old('rejection_remarks', $admission->rejection_remarks ?? '') }}</textarea>
                            <small class="text-muted">This comment will be visible to the student.</small>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary mb-2">Update</button>
                    </form>

                    <script>
                        function toggleRemarksField() {
                            const statusSelect = document.getElementById('statusSelect');
                            const remarksField = document.getElementById('remarksField');
                            const remarksTextarea = remarksField.querySelector('textarea');
                            
                            if (statusSelect.value === 'rejected') {
                                remarksField.style.display = 'block';
                                remarksTextarea.setAttribute('required', 'required');
                            } else {
                                remarksField.style.display = 'none';
                                remarksTextarea.removeAttribute('required');
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
