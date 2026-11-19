@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Admission Details</h3>

                @php
                    $wasUpdatedByStudent = $admission->updated_at->greaterThan($admission->created_at);
                    $isRecentlyUpdated = $admission->updated_at->diffInHours(now()) < 24;
                    $needsReview = $admission->status === 'pending' && $wasUpdatedByStudent && $isRecentlyUpdated;
                @endphp

                @if($needsReview)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-triangle"></i> Attention Required!</strong>
                        This admission was recently updated by the student on {{ $admission->updated_at->setTimezone('Asia/Manila')->format('F d, Y \a\t h:i A') }}. 
                        Please review the changes, especially the uploaded files.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif($wasUpdatedByStudent)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-info-circle"></i> Last Updated:</strong>
                        This admission was last updated by the student on {{ $admission->updated_at->setTimezone('Asia/Manila')->format('F d, Y \a\t h:i A') }} ({{ $admission->updated_at->setTimezone('Asia/Manila')->diffForHumans() }}).
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Student Information Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="text-primary">Student Information</h5>
                        <div>
                            {{-- <form action="{{ route('admissions.destroy', $admission->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <!-- Delete Button Modal -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $admission->id }}">
                                    Delete
                                </button>

                                <div class="modal fade" id="deleteModal{{ $admission->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $admission->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $admission->id }}">
                                                    Confirm Deletion
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this admission record?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form> --}}
                        </div>
                    </div>

                    <div class="position-absolute top-0 end-0 mt-4 me-4 d-flex gap-2">
                        <a href="{{ route('admission.download.pdf', $admission->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="{{ route('admin.admissions.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Student ID:</strong> {{ $admission->student_id }}</p>
                            <p><strong>Student Type:</strong> {{ ucfirst($admission->student_type ?? 'N/A') }}</p>
                            <p><strong>Name:</strong> {{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }} {{ $admission->suffix }}</p>
                            <p><strong>Email:</strong> {{ $admission->email }}</p>
                            <p><strong>Contact No:</strong> {{ $admission->contact_no }}</p>
                            <p><strong>Date of Birth:</strong> {{ $admission->date_of_birth ? \Carbon\Carbon::parse($admission->date_of_birth)->format('F d, Y') : 'N/A' }}</p>
                            <p><strong>Place of Birth:</strong> {{ $admission->place_of_birth ?? 'N/A' }}</p>
                            <p><strong>Gender:</strong> {{ ucfirst($admission->gender ?? 'N/A') }}</p>
                            <p><strong>Nationality:</strong> {{ $admission->nationality ?? 'N/A' }}</p>
                            <p><strong>Religion:</strong> {{ $admission->religion ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong></p>
                            <p class="ms-3">
                                @if($admission->street)
                                    {{ $admission->street }},<br>
                                @endif
                                @if($barangayName && $barangayName !== 'N/A')
                                    {{ $barangayName }},<br>
                                @endif
                                @if($cityName && $cityName !== 'N/A')
                                    {{ $cityName }},
                                @endif
                                @if($provinceName && $provinceName !== 'N/A')
                                    {{ $provinceName }}<br>
                                @endif
                                @if($regionName && $regionName !== 'N/A')
                                    Region: {{ $regionName }}
                                @endif
                            </p>
                            <p><strong>LRN:</strong> {{ $admission->lrn ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <h5 class="mt-4 text-primary">Uploaded Files</h5>
                    <div class="row">
                        @php
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
                            <div class="col-md-6 mb-2">
                                <strong>{{ $label }}:</strong>
                                @if($admission->$field && \Storage::disk('public')->exists($admission->$field))
                                    <a href="{{ route('admissions.files.download', [$admission->id, $field]) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <small class="text-muted ms-2">(Last updated: {{ \Carbon\Carbon::parse($admission->updated_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A') }})</small>
                                @else
                                    <span class="text-muted ms-2">Not uploaded</span>
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
                                        <td>{{ $h->created_at->setTimezone('Asia/Manila')->format('Y-m-d H:i') }}</td>
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

                <!-- Family Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Family Information</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-secondary">Father</h6>
                            <p><strong>Name:</strong> {{ $admission->father_name ?? 'N/A' }}</p>
                            <p><strong>Occupation:</strong> {{ $admission->father_occupation ?? 'N/A' }}</p>
                            <p><strong>Contact:</strong> {{ $admission->father_contact ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-secondary">Mother</h6>
                            <p><strong>Name:</strong> {{ $admission->mother_name ?? 'N/A' }}</p>
                            <p><strong>Occupation:</strong> {{ $admission->mother_occupation ?? 'N/A' }}</p>
                            <p><strong>Contact:</strong> {{ $admission->mother_contact ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-secondary">Guardian</h6>
                            @php
                                // Use parent details if guardian details are missing
                                $guardianName = $admission->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? 'N/A';
                                $guardianContact = $admission->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? 'N/A';
                            @endphp
                            <p><strong>Name:</strong> {{ $guardianName }}</p>
                            <p><strong>Contact:</strong> {{ $guardianContact }}</p>
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Academic Information</h5>
                    <hr>
                    <p><strong>Year Level:</strong> {{ $admission->year_level ?? 'N/A' }}</p>
                    @php
                        $isShs = in_array($admission->year_level, ['Grade 11', 'Grade 12']);
                    @endphp
                    @if($isShs)
                        <p><strong>Strand:</strong> {{ $admission->strand ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Previous School:</strong> {{ $admission->previous_school ?? 'N/A' }}</p>
                    <p><strong>Previous School Address:</strong> {{ $admission->previous_school_address ?? 'N/A' }}</p>
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

                <form action="{{ route('admin.admissions.updateAdmissionStatus', $admission->id) }}" method="POST" class="mb-3">
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
@endsection
