@extends('layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background-color: #f5f7fa;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            padding: 30px;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            font-size: 32px;
            color: #2c5aa0;
            font-weight: 700;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            padding: 12px 18px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            width: 300px;
            background-color: #f9fbfd;
            color: #2c5aa0;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-box:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.15);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
        }

        /* Table Styles */
        .table-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .table thead tr {
            background-color: #f5f7fa;
            border-bottom: 2px solid #4a8fd8;
        }

        .table th {
            padding: 18px;
            text-align: left;
            font-weight: 700;
            color: #2c5aa0;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 18px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #555;
        }

        .table tbody tr:hover {
            background-color: #f9fbfd;
        }

        .table tbody tr td:first-child {
            font-weight: 600;
            color: #2c5aa0;
        }
        
.review-steps {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px;
}

.review-step {
    border: 1px solid #d4e5f3;
    border-radius: 8px;
    padding: 10px 6px;
    background: #f9fbfd;
    color: #2c5aa0;
    font-size: 12px;
    font-weight: 600;
}

.review-step.active {
    background: #2c5aa0;
    border-color: #2c5aa0;
    color: white;
}

.review-panel {
    min-height: 250px;
}

.review-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-top: 18px;
}

.review-info-grid > div {
    background: #f9fbfd;
    border: 1px solid #e0e8f0;
    border-radius: 8px;
    padding: 12px;
    min-width: 0;
    overflow-wrap: anywhere;
}

.review-info-grid strong {
    display: block;
    color: #2c5aa0;
    font-size: 12px;
    margin-bottom: 5px;
}

.review-info-grid p {
    margin: 0;
    font-size: 14px;
}

.review-document {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    border: 1px solid #e0e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.review-document-info {
    flex: 1;
    min-width: 0;
}

.review-document-status {
    width: 170px;
    flex-shrink: 0;
}

@media (max-width: 600px) {
    .review-steps {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .review-info-grid {
        grid-template-columns: 1fr;
    }

    .review-document {
        align-items: stretch;
        flex-direction: column;
    }

    .review-document-status {
        width: 100%;
    }
}


        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            min-width: 100px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Status Select */
        .form-select {
            padding: 10px 14px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            background-color: #f9fbfd;
            color: #2c5aa0;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-icon {
            padding: 10px 16px;
            border: 2px solid #d4e5f3;
            background-color: white;
            color: #2c5aa0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-icon:hover {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            border-color: #2c5aa0;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 40px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #d4e5f3;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 16px;
            color: #2c5aa0;
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .header-section h1 {
                font-size: 24px;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }

            .table th,
            .table td {
                padding: 12px;
                font-size: 13px;
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }
        

/* Fix all applicant review modals */
[id^="reviewModal"] .modal-dialog {
    margin: 1.75rem auto;
    max-width: 1000px;
    width: calc(100% - 2rem);
}

[id^="reviewModal"] .modal-content {
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 768px) {
    [id^="reviewModal"] .modal-dialog {
        margin: 1rem auto;
        width: calc(100% - 1rem);
    }
}
/* Keep the review modal above its backdrop */
.modal.show {
    z-index: 1055;
}

.modal-backdrop.show {
    z-index: 1050;
}

/* Keep the modal content fully visible */
[id^="reviewModal"] .modal-content {
    background-color: #fff;
    opacity: 1;
}

    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>📋 Admissions Management</h1>
                <p style="margin: 4px 0 0; color: #666;">Admissions are currently <strong style="color: {{ $admissionsEnabled ? '#27ae60' : '#e74c3c' }};">{{ $admissionsEnabled ? 'Enabled' : 'Disabled' }}</strong></p>
            </div>
            <div class="header-actions">
                <form method="POST" action="{{ route('registrar.admissions.toggle') }}">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $admissionsEnabled ? 0 : 1 }}">
                    <button type="submit" class="btn btn-primary" style="background: {{ $admissionsEnabled ? '#e74c3c' : '#2c5aa0' }}; border: none;">
                        <i class="fas {{ $admissionsEnabled ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                        {{ $admissionsEnabled ? 'Disable Admissions' : 'Enable Admissions' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
            <form method="GET" action="{{ route('registrar.admissions.index') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <input type="text" name="search" class="search-box" placeholder="Search by name, LRN, or email..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
                <select name="status" class="form-select" style="min-width: 180px;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('registrar.admissions.index') }}" class="btn" style="background: #f5f7fa; color: #666; border: 2px solid #d4e5f3;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div style="overflow-x: auto;">
                <table class="table" id="admissionsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Year Level</th>
                            <th>Applied At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admissionsTableBody">
                        @forelse($admissions as $admission)
                            <tr>
                                <td>
                                    {{ $admission->first_name ?? 'N/A' }} {{ $admission->last_name ?? '' }}
                                </td>
                                <td>{{ $admission->year_level ?? '-' }}</td>
                                <td>{{ $admission->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $admission->id }}" style="border: 2px solid #d4e5f3; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; background: white; color: #2c5aa0; cursor: pointer; transition: all 0.3s ease;">
                                            {{ ucfirst($admission->status) }}
                                        </button>
                                        @if($admission->status === 'rejected' && $admission->rejection_remarks)
                                            <small class="text-danger" style="font-size: 11px;" title="{{ $admission->rejection_remarks }}">
                                                <i class="fas fa-info-circle"></i> {{ Str::limit($admission->rejection_remarks, 40) }}
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Status Update Modal -->
                                    <td>
    <span class="status-badge status-{{ $admission->status }}">
        {{ ucfirst($admission->status) }}
    </span>

    @if($admission->status === 'rejected' && $admission->rejection_remarks)
        <br>
        <small class="text-danger">
            <i class="fas fa-info-circle"></i>
            {{ Str::limit($admission->rejection_remarks, 40) }}
        </small>
    @endif
</td>
                                </td>
                                <td>
                                    <div class="action-buttons">
<button type="button"
        class="btn-icon"
        data-bs-toggle="modal"
        data-bs-target="#reviewModal{{ $admission->id }}">
    <i class="fas fa-clipboard-check"></i> Review
</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p><strong>No admissions found</strong></p>
                                        <p style="font-size: 13px; color: #999; margin-top: 8px;">Check back later or adjust your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

@foreach($admissions as $admission)
    @php
        $documents = [
            'birth_certificate' => 'Birth Certificate',
            'id_picture' => '2x2 ID Picture',
            'report_card' => 'Form 138 / Report Card',
            'form_137' => 'Form 137 / School Record',
            'good_moral' => 'Good Moral Certificate',
            'medical_certificate' => 'Medical Certificate',
        ];

        $fileStatuses = $admission->file_review_status ?? [];
    @endphp

    <div class="modal fade"
         id="reviewModal{{ $admission->id }}"
         tabindex="-1"
         aria-labelledby="reviewTitle{{ $admission->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title"
                            id="reviewTitle{{ $admission->id }}">
                            Admission Review
                        </h5>
                        <small class="text-muted">
                            {{ $admission->first_name }}
                            {{ $admission->middle_name }}
                            {{ $admission->last_name }}
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    {{-- Step indicators --}}
                    <div class="review-steps mb-4">
                        <button type="button" class="review-step active"
                                data-step="1">1. Applicant</button>
                        <button type="button" class="review-step"
                                data-step="2">2. Requirements</button>
                        <button type="button" class="review-step"
                                data-step="3">3. Information</button>
                        <button type="button" class="review-step"
                                data-step="4">4. Decision</button>
                    </div>

                    {{-- STEP 1: Applicant --}}
                    <section class="review-panel" data-panel="1">
                        <h5>Applicant Details</h5>
                        <p class="text-muted">
                            Confirm that you are reviewing the correct applicant.
                        </p>

                        <div class="review-info-grid">
                            <div>
                                <strong>Full Name</strong>
                                <p>
                                    {{ $admission->first_name }}
                                    {{ $admission->middle_name }}
                                    {{ $admission->last_name }}
                                    {{ $admission->suffix }}
                                </p>
                            </div>

                            <div>
                                <strong>LRN</strong>
                                <p>{{ $admission->lrn ?: 'Not provided' }}</p>
                            </div>

                            <div>
                                <strong>Student Type</strong>
                                <p>{{ ucfirst($admission->student_type ?? 'N/A') }}</p>
                            </div>

                            <div>
                                <strong>Year Level</strong>
                                <p>{{ $admission->year_level ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Date Applied</strong>
                                <p>{{ $admission->created_at?->format('M d, Y') }}</p>
                            </div>

                            <div>
                                <strong>Current Status</strong>
                                <p>{{ ucfirst($admission->status) }}</p>
                            </div>
                        </div>
                    </section>

                    {{-- STEP 2: Requirements --}}
                    <section class="review-panel d-none" data-panel="2">
                        <h5>Document Requirements</h5>
                        <p class="text-muted">
                            Open each uploaded document and record your review.
                        </p>

                        @foreach($documents as $field => $label)
                            <div class="review-document">
                                <div class="review-document-info">
                                    <strong>{{ $label }}</strong>

                                    @if($admission->$field)
                                        <div class="mt-2">
                                            <a href="{{ route(
                                                'admissions.files.download',
                                                ['admission' => $admission->id, 'field' => $field]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                                View Document
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-danger mb-0">
                                            Not uploaded
                                        </p>
                                    @endif
                                </div>

                                <div class="review-document-status">
                                    <span class="text-muted small">Review Status</span>

                                    @if($admission->$field)
                                        <form method="POST"
                                              class="file-review-form"
                                              data-saved-status="{{ $fileStatuses[$field] ?? 'pending' }}"
                                              action="{{ route(
                                                'registrar.admissions.updateFileStatus',
                                                ['admission' => $admission->id, 'field' => $field]
                                              ) }}">
                                            @csrf
                                            @method('PUT')

                                            <select name="status"
                                                    class="form-select form-select-sm"
                                                    required>
                                                <option value="pending"
                                                    {{ ($fileStatuses[$field] ?? 'pending') === 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="accepted"
                                                    {{ ($fileStatuses[$field] ?? '') === 'accepted' ? 'selected' : '' }}>
                                                    Accepted
                                                </option>
                                                <option value="rejected"
                                                    {{ ($fileStatuses[$field] ?? '') === 'rejected' ? 'selected' : '' }}>
                                                    Rejected
                                                </option>
                                            </select>

                                            <button type="submit"
                                                    class="btn btn-sm btn-primary mt-2 file-review-save-btn">
                                                Save Review
                                            </button>
                                            <div class="file-review-feedback small mt-1 d-none" aria-live="polite"></div>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">
                                            Missing
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <small class="text-muted">
                            Each document review saves in the background without reloading the page
                            and is stored in the admission's file_review_status field.
                        </small>
                    </section>

                    {{-- STEP 3: Student Information --}}
                    <section class="review-panel d-none" data-panel="3">
                        <h5>Personal Information</h5>

                        <div class="review-info-grid">
                            <div>
                                <strong>Date of Birth</strong>
                                <p>{{ $admission->date_of_birth ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Place of Birth</strong>
                                <p>{{ $admission->place_of_birth ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Gender</strong>
                                <p>{{ $admission->gender ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Nationality</strong>
                                <p>{{ $admission->nationality ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Email</strong>
                                <p>{{ $admission->email ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Contact Number</strong>
                                <p>{{ $admission->contact_no ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Street</strong>
                                <p>{{ $admission->street ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Barangay</strong>
                                <p>{{ $admission->barangay?->brgyDesc ?? $admission->barangay_code ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>City / Municipality</strong>
                                <p>{{ $admission->city?->citymunDesc ?? $admission->city_code ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Province</strong>
                                <p>{{ $admission->province?->provDesc ?? $admission->province_code ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>
                        <h5>Family Information</h5>

                        <div class="review-info-grid">
                            <div>
                                <strong>Father's Name</strong>
                                <p>{{ $admission->father_name ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Father's Occupation</strong>
                                <p>{{ $admission->father_occupation ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Father's Contact</strong>
                                <p>{{ $admission->father_contact ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Mother's Name</strong>
                                <p>{{ $admission->mother_name ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Mother's Occupation</strong>
                                <p>{{ $admission->mother_occupation ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Mother's Contact</strong>
                                <p>{{ $admission->mother_contact ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Guardian's Name</strong>
                                <p>{{ $admission->guardian_name ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Guardian's Contact</strong>
                                <p>{{ $admission->guardian_contact ?: 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>
                        <h5>Academic Information</h5>

                        <div class="review-info-grid">
                            <div>
                                <strong>Previous School</strong>
                                <p>{{ $admission->previous_school ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Previous School Address</strong>
                                <p>{{ $admission->previous_school_address ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>Strand</strong>
                                <p>{{ $admission->strand ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>TVL Branch</strong>
                                <p>{{ $admission->tvl_branch ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <strong>GWA</strong>
                                <p>{{ $admission->gwa ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </section>

                    {{-- STEP 4: Final Decision --}}
                    <section class="review-panel d-none" data-panel="4">
                        <h5>Final Admission Decision</h5>

                        <div class="alert alert-info">
                            Review the uploaded requirements and student
                            information before making the final decision.
                        </div>

                        <form method="POST"
                              action="{{ route(
                                'registrar.admissions.updateAdmissionStatus',
                                $admission->id
                              ) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label"
                                       for="finalStatus{{ $admission->id }}">
                                    Admission Status
                                </label>

                                <select name="status"
                                        id="finalStatus{{ $admission->id }}"
                                        class="form-select"
                                        required
                                        onchange="toggleFinalRemarks({{ $admission->id }})">
                                    <option value="pending"
                                        {{ $admission->status === 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="approved"
                                        {{ $admission->status === 'approved' ? 'selected' : '' }}>
                                        Approve Admission
                                    </option>
                                    <option value="rejected"
                                        {{ $admission->status === 'rejected' ? 'selected' : '' }}>
                                        Reject Admission
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3"
                                 id="finalRemarks{{ $admission->id }}"
                                 style="{{ $admission->status === 'rejected' ? '' : 'display:none;' }}">
                                <label class="form-label"
                                       for="finalRemarksInput{{ $admission->id }}">
                                    Rejection Remarks <span class="text-danger">*</span>
                                </label>

                                <textarea name="rejection_remarks"
                                          id="finalRemarksInput{{ $admission->id }}"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Explain why the application is being rejected."
                                          {{ $admission->status === 'rejected' ? 'required' : '' }}>{{ $admission->rejection_remarks }}</textarea>

                                <small class="text-muted">
                                    Provide a clear reason that the student can understand.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle"></i>
                                Submit Final Decision
                            </button>
                        </form>
                    </section>

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button"
                            class="btn btn-outline-secondary review-prev"
                            disabled>
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>

                    <span class="text-muted small review-progress">
                        Step 1 of 4
                    </span>

                    <button type="button" class="btn btn-primary review-next">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
@endforeach

            <!-- Pagination -->
            @if($admissions->hasPages())
                <div class="pagination">
                    {{ $admissions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    
<script>
document.addEventListener('DOMContentLoaded', function () {
    
document.querySelectorAll('[id^="reviewModal"]').forEach(function (modal) {
    document.body.appendChild(modal);
});

    document.querySelectorAll('[id^="reviewModal"]').forEach(function (modal) {
        let currentStep = 1;

        const panels = modal.querySelectorAll('[data-panel]');
        const steps = modal.querySelectorAll('[data-step]');
        const prev = modal.querySelector('.review-prev');
        const next = modal.querySelector('.review-next');
        const progress = modal.querySelector('.review-progress');

        function showStep(step) {
            currentStep = step;

            panels.forEach(function (panel) {
                panel.classList.toggle(
                    'd-none',
                    Number(panel.dataset.panel) !== step
                );
            });

            steps.forEach(function (button) {
                button.classList.toggle(
                    'active',
                    Number(button.dataset.step) === step
                );
            });

            prev.disabled = step === 1;
            next.classList.toggle('d-none', step === 4);
            progress.textContent = 'Step ' + step + ' of 4';
        }

        prev.addEventListener('click', function () {
            if (currentStep > 1) showStep(currentStep - 1);
        });

        next.addEventListener('click', function () {
            if (currentStep < 4) showStep(currentStep + 1);
        });

        steps.forEach(function (button) {
            button.addEventListener('click', function () {
                showStep(Number(button.dataset.step));
            });
        });

        modal.addEventListener('show.bs.modal', function () {
            showStep(1);
            modal.querySelectorAll('.file-review-form').forEach(function (form) {
                const select = form.querySelector('select[name="status"]');
                if (select && form.dataset.savedStatus) {
                    select.value = form.dataset.savedStatus;
                }
                const feedback = form.querySelector('.file-review-feedback');
                if (feedback) {
                    feedback.className = 'file-review-feedback small mt-1 d-none';
                    feedback.textContent = '';
                }
            });
        });

        modal.querySelectorAll('.file-review-form').forEach(function (form) {
            const select = form.querySelector('select[name="status"]');
            const saveBtn = form.querySelector('.file-review-save-btn');
            const feedback = form.querySelector('.file-review-feedback');
            const modalBody = modal.querySelector('.modal-body');

            function setFeedback(text, isError) {
                if (feedback) {
                    feedback.textContent = text;
                    feedback.className = 'file-review-feedback small mt-1 ' + (isError ? 'text-danger' : 'text-success');
                }
                if (typeof toastr !== 'undefined') {
                    if (isError) toastr.error(text);
                    else toastr.success(text);
                }
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (form.dataset.saving === '1') {
                    return;
                }

                const previousStatus = form.dataset.savedStatus || (select ? select.value : 'pending');
                const scrollTop = modalBody ? modalBody.scrollTop : 0;
                const originalBtnText = saveBtn ? saveBtn.textContent.trim() : 'Save Review';

                form.dataset.saving = '1';
                if (saveBtn) {
                    saveBtn.disabled = true;
                    saveBtn.textContent = 'Saving...';
                }
                if (feedback) {
                    feedback.className = 'file-review-feedback small mt-1 d-none';
                    feedback.textContent = '';
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form),
                        credentials: 'same-origin'
                    });

                    let data = {};
                    try {
                        data = await response.json();
                    } catch (_) {
                        data = {};
                    }

                    if (!response.ok || !data.success) {
                        if (select) {
                            select.value = previousStatus;
                        }
                        let errMsg = data.message || 'Failed to save document review.';
                        if (data.errors && typeof data.errors === 'object') {
                            const firstField = Object.keys(data.errors)[0];
                            if (firstField && Array.isArray(data.errors[firstField]) && data.errors[firstField].length) {
                                errMsg = data.errors[firstField][0];
                            }
                        }
                        setFeedback(errMsg, true);
                        return;
                    }

                    const confirmedStatus = data.status || (select ? select.value : previousStatus);
                    form.dataset.savedStatus = confirmedStatus;
                    if (select) {
                        select.value = confirmedStatus;
                        Array.from(select.options).forEach(function (opt) {
                            opt.defaultSelected = opt.value === confirmedStatus;
                        });
                    }
                    setFeedback(data.message || 'Document review saved successfully.', false);
                } catch (err) {
                    if (select) {
                        select.value = previousStatus;
                    }
                    setFeedback('Network error while saving document review. Please try again.', true);
                } finally {
                    delete form.dataset.saving;
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.textContent = originalBtnText;
                    }
                    if (modalBody) {
                        modalBody.scrollTop = scrollTop;
                    }
                }
            });
        });

        showStep(1);
    });
});

function toggleFinalRemarks(id) {
    const status = document.getElementById('finalStatus' + id);
    const container = document.getElementById('finalRemarks' + id);
    const textarea = document.getElementById('finalRemarksInput' + id);

    const rejected = status.value === 'rejected';

    container.style.display = rejected ? 'block' : 'none';
    textarea.required = rejected;
}
</script>
@endsection
