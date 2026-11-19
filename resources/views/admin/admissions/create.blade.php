@extends('layout.app')

@section('content')
<style>
    .admission-card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: none;
    }
    .section-title {
        color: #2c5aa0;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .required-asterisk {
        color: #dc3545;
    }
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }
    .form-control-sm, .form-select-sm {
        font-size: 0.875rem;
        padding: 8px 12px;
    }
    .requirement-card {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .requirement-card:hover {
        border-color: #2c5aa0;
        background: #f0f4f8;
    }
    .requirement-card.uploaded {
        border-color: #28a745;
        border-style: solid;
        background: #e8f5e9;
    }
    .requirement-card.required {
        border-color: #ffc107;
    }
    .requirement-card .icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #6c757d;
    }
    .requirement-card.uploaded .icon {
        color: #28a745;
    }
    .requirement-card .title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .requirement-card .status {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .requirement-card.uploaded .status {
        color: #28a745;
    }
    .step-badge {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        border-radius: 50%;
        background: #2c5aa0;
        color: white;
        text-align: center;
        font-weight: bold;
        margin-right: 10px;
    }
    .step-badge.completed {
        background: #28a745;
    }
    .modal-lg-custom {
        max-width: 900px;
    }
    .file-preview-modal {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 8px;
    }
</style>

<div class="container my-4">
    <div class="card admission-card">
        <div class="card-body p-4">
            <h3 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Create New Admission</h3>
            
            {{-- Progress Tracker --}}
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center">
                        <span class="step-badge" id="badge-1">1</span>
                        <span>Student Selection</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="step-badge" id="badge-2">2</span>
                        <span>View Requirements</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="step-badge" id="badge-3">3</span>
                        <span>Complete Information</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="step-badge" id="badge-4">4</span>
                        <span>Review & Submit</span>
                    </div>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle me-1"></i> Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @php
                $admissionStoreRoute = (request()->routeIs('registrar.*') || !Route::has('admin.admissions.store'))
                    ? 'registrar.admissions.store'
                    : 'admin.admissions.store';
            @endphp
            <form action="{{ route($admissionStoreRoute) }}" method="POST" enctype="multipart/form-data" id="admissionForm">
                @csrf

                {{-- Step 1: Student Name Selection --}}
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Step 1: Student Name Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">First Name <span class="required-asterisk">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Last Name <span class="required-asterisk">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Suffix</label>
                                <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}" class="form-control form-control-sm" placeholder="Jr., III, etc.">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Student ID <span class="required-asterisk">*</span></label>
                                <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" class="form-control form-control-sm" required placeholder="Auto-generated or enter manually">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email <span class="required-asterisk">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Student Type <span class="required-asterisk">*</span></label>
                                <select name="student_type" id="student_type" class="form-select form-select-sm" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="new" {{ old('student_type') == 'new' ? 'selected' : '' }}>New Student</option>
                                    <option value="old" {{ old('student_type') == 'old' ? 'selected' : '' }}>Old Student (Returning)</option>
                                    <option value="transferee" {{ old('student_type') == 'transferee' ? 'selected' : '' }}>Transferee</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="completeStep1()">
                                <i class="fas fa-check me-1"></i> Next: View Requirements
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Requirements (Modal Triggers) --}}
                <div class="card mb-3" id="step2Card" style="display:none;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Step 2: Upload & View Requirements</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Click on each requirement card to upload and view documents in a modal</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="requirement-card required" data-bs-toggle="modal" data-bs-target="#birthCertModal">
                                    <i class="fas fa-id-card icon"></i>
                                    <div class="title">Birth Certificate <span class="required-asterisk">*</span></div>
                                    <div class="status" id="birth_cert_status">Click to upload</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="requirement-card required" data-bs-toggle="modal" data-bs-target="#idPictureModal">
                                    <i class="fas fa-user-circle icon"></i>
                                    <div class="title">ID Picture (2x2) <span class="required-asterisk">*</span></div>
                                    <div class="status" id="id_picture_status">Click to upload</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="requirement-card" data-bs-toggle="modal" data-bs-target="#reportCardModal">
                                    <i class="fas fa-file-alt icon"></i>
                                    <div class="title">Report Card (Form 138)</div>
                                    <div class="status" id="report_card_status">Optional - Click to upload</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="requirement-card" data-bs-toggle="modal" data-bs-target="#form137Modal">
                                    <i class="fas fa-file-alt icon"></i>
                                    <div class="title">Form 137</div>
                                    <div class="status" id="form137_status">Optional - Click to upload</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="requirement-card" data-bs-toggle="modal" data-bs-target="#goodMoralModal">
                                    <i class="fas fa-certificate icon"></i>
                                    <div class="title">Good Moral Certificate</div>
                                    <div class="status" id="good_moral_status">Optional - Click to upload</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="requirement-card" data-bs-toggle="modal" data-bs-target="#medicalCertModal">
                                    <i class="fas fa-notes-medical icon"></i>
                                    <div class="title">Medical Certificate</div>
                                    <div class="status" id="medical_cert_status">Optional - Click to upload</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary" onclick="completeStep2()">
                                <i class="fas fa-check me-1"></i> Next: Complete Information
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Complete All Information (Modal Trigger) --}}
                <div class="card mb-3" id="step3Card" style="display:none;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Step 3: Complete Admission Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-3">
                            <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#completeInfoModal">
                                <i class="fas fa-edit me-2"></i> Complete All Details
                            </button>
                            <p class="text-muted mt-2">Click to fill in personal, address, family, and academic information</p>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Review & Submit --}}
                <div class="card mb-3" id="step4Card" style="display:none;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Step 4: Review & Submit</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Please review all information. The admission will be set to <strong>Pending</strong> status for review.
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-check-circle me-2"></i> Submit Admission
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modals for Requirements --}}
{{-- Birth Certificate Modal --}}
<div class="modal fade" id="birthCertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Birth Certificate (PSA) <span class="text-danger">*</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File <span class="required-asterisk">*</span></label>
                    <input type="file" name="birth_certificate" id="birth_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(this, 'birthCertPreview', 'birth_cert_status')">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                </div>
                <div id="birthCertPreview" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ID Picture Modal --}}
<div class="modal fade" id="idPictureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>ID Picture (2x2) <span class="text-danger">*</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File <span class="required-asterisk">*</span></label>
                    <input type="file" name="id_picture" id="id_picture" class="form-control" accept=".jpg,.jpeg,.png" required onchange="previewFile(this, 'idPicturePreview', 'id_picture_status')">
                    <small class="text-muted">Accepted formats: JPG, PNG (Max: 5MB)</small>
                </div>
                <div id="idPicturePreview" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Report Card Modal --}}
<div class="modal fade" id="reportCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>Report Card (Form 138)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File</label>
                    <input type="file" name="report_card" id="report_card" class="form-control" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this, 'reportCardPreview', 'report_card_status')">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                </div>
                <div id="reportCardPreview" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Form 137 Modal --}}
<div class="modal fade" id="form137Modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>Form 137 (Transfer Credential)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File</label>
                    <input type="file" name="form_137" id="form_137" class="form-control" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this, 'form137Preview', 'form137_status')">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                </div>
                <div id="form137Preview" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Good Moral Modal --}}
<div class="modal fade" id="goodMoralModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-certificate me-2"></i>Good Moral Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File</label>
                    <input type="file" name="good_moral" id="good_moral" class="form-control" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this, 'goodMoralPreview', 'good_moral_status')">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                </div>
                <div id="goodMoralPreview" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Medical Certificate Modal --}}
<div class="modal fade" id="medicalCertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-notes-medical me-2"></i>Medical Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File</label>
                    <input type="file" name="medical_certificate" id="medical_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this, 'medicalCertPreview', 'medical_cert_status')">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                </div>
                <div id="medicalCertPreview" class="text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Complete Information Modal --}}
<div class="modal fade" id="completeInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clipboard-list me-2"></i>Complete Admission Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Personal Information --}}
                <h6 class="section-title"><i class="fas fa-user"></i> Personal Information</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth <span class="required-asterisk">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control form-control-sm" max="{{ date('Y-m-d', strtotime('-11 years')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Place of Birth <span class="required-asterisk">*</span></label>
                        <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender <span class="required-asterisk">*</span></label>
                        <select name="gender" id="gender" class="form-select form-select-sm" required>
                            <option value="">-- Select --</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nationality <span class="required-asterisk">*</span></label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', 'Filipino') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" id="religion" value="{{ old('religion') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact No. <span class="required-asterisk">*</span></label>
                        <input type="tel" name="contact_no" id="contact_no" value="{{ old('contact_no') }}" class="form-control form-control-sm" placeholder="09123456789" required>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Address Information --}}
                <h6 class="section-title"><i class="fas fa-map-marker-alt"></i> Address Information</h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Region <span class="required-asterisk">*</span></label>
                        <select name="region_code" id="region" class="form-select form-select-sm" required>
                            <option value="">-- Select Region --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region['code'] }}" {{ old('region_code') == $region['code'] ? 'selected' : '' }}>
                                    {{ $region['desc'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Province</label>
                        <select name="province_code" id="province" class="form-select form-select-sm">
                            <option value="">-- Select Province --</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City/Municipality</label>
                        <select name="city_code" id="city" class="form-select form-select-sm">
                            <option value="">-- Select City --</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Barangay</label>
                        <select name="barangay_code" id="barangay" class="form-select form-select-sm">
                            <option value="">-- Select Barangay --</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Street / House No.</label>
                        <input type="text" name="street" id="street" value="{{ old('street') }}" class="form-control form-control-sm" placeholder="Enter complete street address">
                    </div>
                </div>

                <hr class="my-4">

                {{-- Family Information --}}
                <h6 class="section-title"><i class="fas fa-users"></i> Family Information</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Father's Name <span class="required-asterisk">*</span></label>
                        <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Father's Occupation</label>
                        <input type="text" name="father_occupation" value="{{ old('father_occupation') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Father's Contact</label>
                        <input type="tel" name="father_contact" value="{{ old('father_contact') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mother's Name <span class="required-asterisk">*</span></label>
                        <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mother's Occupation</label>
                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mother's Contact</label>
                        <input type="tel" name="mother_contact" value="{{ old('mother_contact') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guardian's Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guardian's Contact</label>
                        <input type="tel" name="guardian_contact" value="{{ old('guardian_contact') }}" class="form-control form-control-sm">
                    </div>
                </div>

                <hr class="my-4">

                {{-- Academic Information --}}
                <h6 class="section-title"><i class="fas fa-graduation-cap"></i> Academic Information</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Year Level <span class="required-asterisk">*</span></label>
                        <select name="year_level" id="year_level" class="form-select form-select-sm" required>
                            <option value="">-- Select Year Level --</option>
                            <option value="Grade 7" {{ old('year_level') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                            <option value="Grade 8" {{ old('year_level') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                            <option value="Grade 9" {{ old('year_level') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                            <option value="Grade 10" {{ old('year_level') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                            <option value="Grade 11" {{ old('year_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                            <option value="Grade 12" {{ old('year_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3" id="strand_container" style="display: none;">
                        <label class="form-label">Strand</label>
                        <select name="strand" id="strand" class="form-select form-select-sm">
                            <option value="">-- Select Strand --</option>
                            <option value="GAS" {{ old('strand') == 'GAS' ? 'selected' : '' }}>GAS (General Academic)</option>
                            <option value="TVL" {{ old('strand') == 'TVL' ? 'selected' : '' }}>TVL (Tech-Voc-Livelihood)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3" id="tvl_branch_container" style="display: none;">
                        <label class="form-label">TVL Branch</label>
                        <select name="tvl_branch" id="tvl_branch" class="form-select form-select-sm">
                            <option value="">-- Select Branch --</option>
                            <option value="CSS" {{ old('tvl_branch') == 'CSS' ? 'selected' : '' }}>CSS</option>
                            <option value="EIM" {{ old('tvl_branch') == 'EIM' ? 'selected' : '' }}>EIM</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">LRN <span class="required-asterisk">*</span></label>
                        <input type="text" name="lrn" id="lrn" value="{{ old('lrn') }}" class="form-control form-control-sm" required maxlength="12" placeholder="12-digit LRN">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Previous School <span class="required-asterisk">*</span></label>
                        <input type="text" name="previous_school" id="previous_school" value="{{ old('previous_school') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Previous School Address</label>
                        <input type="text" name="previous_school_address" value="{{ old('previous_school_address') }}" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveCompleteInfo()">Save Information</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const yearLevel = document.getElementById('year_level');
    const strandContainer = document.getElementById('strand_container');
    const strand = document.getElementById('strand');
    const tvlBranchContainer = document.getElementById('tvl_branch_container');
    const tvlBranch = document.getElementById('tvl_branch');
    const regionSelect = document.getElementById('region');
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');

    // Toggle Strand/TVL visibility
    function toggleStrand() {
        if (!yearLevel || !strandContainer) return;
        const isSHS = yearLevel.value === 'Grade 11' || yearLevel.value === 'Grade 12';
        strandContainer.style.display = isSHS ? 'block' : 'none';
        if (!isSHS) {
            strand.value = '';
            tvlBranchContainer.style.display = 'none';
            tvlBranch.value = '';
        }
    }

    function toggleTvlBranch() {
        if (!strand || !tvlBranchContainer) return;
        tvlBranchContainer.style.display = strand.value === 'TVL' ? 'block' : 'none';
        if (strand.value !== 'TVL') tvlBranch.value = '';
    }

    // Address cascading
    window.loadProvinces = function(regCode) {
        provinceSelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.innerHTML = '<option value="">-- Select City --</option>';
        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
        if (!regCode) { provinceSelect.innerHTML = '<option value="">-- Select Province --</option>'; return; }
        fetch(`/api/provinces?region_code=${encodeURIComponent(regCode)}`)
            .then(r => r.json())
            .then(res => {
                provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                (res.data || []).forEach(p => {
                    provinceSelect.innerHTML += `<option value="${p.code}">${p.name}</option>`;
                });
            }).catch(() => { provinceSelect.innerHTML = '<option value="">-- Select Province --</option>'; });
    }

    window.loadCities = function(provCode) {
        citySelect.innerHTML = '<option value="">Loading...</option>';
        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
        if (!provCode) { citySelect.innerHTML = '<option value="">-- Select City --</option>'; return; }
        fetch(`/api/cities?province_code=${encodeURIComponent(provCode)}`)
            .then(r => r.json())
            .then(res => {
                citySelect.innerHTML = '<option value="">-- Select City --</option>';
                (res.data || []).forEach(c => {
                    citySelect.innerHTML += `<option value="${c.code}">${c.name}</option>`;
                });
            }).catch(() => { citySelect.innerHTML = '<option value="">-- Select City --</option>'; });
    }

    window.loadBarangays = function(cityCode) {
        barangaySelect.innerHTML = '<option value="">Loading...</option>';
        if (!cityCode) { barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>'; return; }
        fetch(`/api/barangays?city_code=${encodeURIComponent(cityCode)}`)
            .then(r => r.json())
            .then(res => {
                barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                (res.data || []).forEach(b => {
                    barangaySelect.innerHTML += `<option value="${b.code}">${b.name}</option>`;
                });
            }).catch(() => { barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>'; });
    }

    // Event Listeners
    if (yearLevel) { yearLevel.addEventListener('change', toggleStrand); toggleStrand(); }
    if (strand) { strand.addEventListener('change', toggleTvlBranch); toggleTvlBranch(); }
    if (regionSelect) regionSelect.addEventListener('change', () => loadProvinces(regionSelect.value));
    if (provinceSelect) provinceSelect.addEventListener('change', () => loadCities(provinceSelect.value));
    if (citySelect) citySelect.addEventListener('change', () => loadBarangays(citySelect.value));
});

// Step progression functions
function completeStep1() {
    const firstName = document.getElementById('first_name').value;
    const lastName = document.getElementById('last_name').value;
    const studentId = document.getElementById('student_id').value;
    const email = document.getElementById('email').value;
    const studentType = document.getElementById('student_type').value;

    if (!firstName || !lastName || !studentId || !email || !studentType) {
        alert('Please fill in all required fields in Step 1');
        return;
    }

    // Mark step 1 as completed
    document.getElementById('badge-1').classList.add('completed');
    
    // Show step 2
    document.getElementById('step2Card').style.display = 'block';
    document.getElementById('step2Card').scrollIntoView({ behavior: 'smooth' });
}

function completeStep2() {
    // Check if required files are uploaded
    const birthCert = document.getElementById('birth_certificate').files.length > 0;
    const idPicture = document.getElementById('id_picture').files.length > 0;

    if (!birthCert || !idPicture) {
        alert('Please upload Birth Certificate and ID Picture (required documents)');
        return;
    }

    // Mark step 2 as completed
    document.getElementById('badge-2').classList.add('completed');
    
    // Show step 3
    document.getElementById('step3Card').style.display = 'block';
    document.getElementById('step3Card').scrollIntoView({ behavior: 'smooth' });
}

function saveCompleteInfo() {
    // Validate required fields in the modal
    const requiredFields = [
        'date_of_birth', 'place_of_birth', 'gender', 'nationality',
        'contact_no', 'region', 'father_name', 'mother_name',
        'year_level', 'lrn', 'previous_school'
    ];

    let isValid = true;
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && !field.value) {
            field.classList.add('is-invalid');
            isValid = false;
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        alert('Please fill in all required fields');
        return;
    }

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('completeInfoModal'));
    modal.hide();

    // Mark step 3 as completed
    document.getElementById('badge-3').classList.add('completed');
    
    // Show step 4
    document.getElementById('step4Card').style.display = 'block';
    document.getElementById('step4Card').scrollIntoView({ behavior: 'smooth' });
}

// File preview function
function previewFile(input, previewId, statusId) {
    const preview = document.getElementById(previewId);
    const status = document.getElementById(statusId);
    const card = document.querySelector(`[data-bs-target="#${input.closest('.modal').id}"]`);

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        // Update status
        status.textContent = '✓ ' + file.name;
        if (card) {
            card.classList.add('uploaded');
            card.classList.remove('required');
        }

        reader.onload = function(e) {
            const fileType = file.type;
            if (fileType.startsWith('image/')) {
                preview.innerHTML = `<img src="${e.target.result}" class="file-preview-modal" alt="Preview">`;
            } else if (fileType === 'application/pdf') {
                preview.innerHTML = `<embed src="${e.target.result}" type="application/pdf" style="width:100%; height:400px;" />`;
            } else {
                preview.innerHTML = `<p class="text-success"><i class="fas fa-check-circle"></i> File uploaded successfully</p>`;
            }
        };

        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
        status.textContent = input.required ? 'Click to upload (Required)' : 'Click to upload (Optional)';
        if (card) {
            card.classList.remove('uploaded');
            if (input.required) card.classList.add('required');
        }
    }
}
</script>
@endsection

