<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Application - {{ $admission->reference_number }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            margin: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 10px;
        }
        .school-logo {
            height: 70px;
            margin-bottom: 5px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            margin: 5px 0;
        }
        .school-subtitle {
            font-size: 10px;
            color: #666;
        }
        .doc-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px;
            text-align: center;
        }
        .ref-number {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
        }
        .section-title {
            background: #f0f0f0;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #2c5aa0;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
            padding-right: 10px;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333;
        }
        .two-column {
            width: 100%;
        }
        .column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin-right: 2%;
        }
        .column:last-child {
            margin-right: 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-approved { background: #28a745; color: #fff; }
        .status-rejected { background: #dc3545; color: #fff; }
        .documents-list {
            margin-top: 5px;
            padding-left: 15px;
        }
        .documents-list li {
            margin-bottom: 3px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('img/logo.png') }}" alt="School Logo" class="school-logo">
        <div class="school-name">San Roque High School</div>
        <div class="school-subtitle">San Roque, Bato, Camarines Sur</div>
    </div>

    <div class="doc-title">ADMISSION APPLICATION</div>
    <div class="ref-number">Reference No: {{ $admission->reference_number }}</div>

    <!-- Student Information -->
    <div class="section-title">STUDENT INFORMATION</div>
    <div class="info-row">
        <div class="info-label">Student ID:</div>
        <div class="info-value">{{ $admission->student_id ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Full Name:</div>
        <div class="info-value">{{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }} {{ $admission->suffix }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Email:</div>
        <div class="info-value">{{ $admission->email }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Date of Birth:</div>
        <div class="info-value">{{ $admission->date_of_birth ? \Carbon\Carbon::parse($admission->date_of_birth)->format('F d, Y') : 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Place of Birth:</div>
        <div class="info-value">{{ $admission->place_of_birth ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Gender:</div>
        <div class="info-value">{{ ucfirst($admission->gender ?? 'N/A') }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Nationality:</div>
        <div class="info-value">{{ $admission->nationality ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Religion:</div>
        <div class="info-value">{{ $admission->religion ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Contact No:</div>
        <div class="info-value">{{ $admission->contact_no ?? 'N/A' }}</div>
    </div>

    <!-- Address Information -->
    <div class="section-title">ADDRESS INFORMATION</div>
    <div class="info-row">
        <div class="info-label">Region:</div>
        <div class="info-value">{{ $admission->region->regDesc ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Province:</div>
        <div class="info-value">{{ $admission->province->provDesc ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">City/Municipality:</div>
        <div class="info-value">{{ $admission->city->citymunDesc ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Barangay:</div>
        <div class="info-value">{{ $admission->barangay->brgyDesc ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Street/House No:</div>
        <div class="info-value">{{ $admission->street ?? 'N/A' }}</div>
    </div>

    <!-- Family Information -->
    <div class="section-title">FAMILY INFORMATION</div>
    <div class="info-row">
        <div class="info-label">Father's Name:</div>
        <div class="info-value">{{ $admission->father_name ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Father's Occupation:</div>
        <div class="info-value">{{ $admission->father_occupation ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Father's Contact:</div>
        <div class="info-value">{{ $admission->father_contact ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Mother's Name:</div>
        <div class="info-value">{{ $admission->mother_name ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Mother's Occupation:</div>
        <div class="info-value">{{ $admission->mother_occupation ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Mother's Contact:</div>
        <div class="info-value">{{ $admission->mother_contact ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Guardian's Name:</div>
        <div class="info-value">{{ $admission->guardian_name ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Guardian's Contact:</div>
        <div class="info-value">{{ $admission->guardian_contact ?? 'N/A' }}</div>
    </div>

    <!-- Academic Information -->
    <div class="section-title">ACADEMIC INFORMATION</div>
    <div class="info-row">
        <div class="info-label">Student Type:</div>
        <div class="info-value">{{ ucfirst($admission->student_type ?? 'N/A') }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Grade Level:</div>
        <div class="info-value">{{ $admission->year_level ?? 'N/A' }}</div>
    </div>
    @if($admission->strand)
    <div class="info-row">
        <div class="info-label">Strand:</div>
        <div class="info-value">{{ $admission->strand }}{{ $admission->tvl_branch ? ' - ' . $admission->tvl_branch : '' }}</div>
    </div>
    @endif
    <div class="info-row">
        <div class="info-label">LRN:</div>
        <div class="info-value">{{ $admission->lrn ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Previous School:</div>
        <div class="info-value">{{ $admission->previous_school ?? 'N/A' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Previous School Address:</div>
        <div class="info-value">{{ $admission->previous_school_address ?? 'N/A' }}</div>
    </div>

    <!-- Documents Submitted -->
    <div class="section-title">DOCUMENTS SUBMITTED</div>
    <ul class="documents-list">
        @if($admission->birth_certificate)
            <li>Birth Certificate (PSA)</li>
        @endif
        @if($admission->report_card)
            <li>Report Card (Form 138)</li>
        @endif
        @if($admission->form_137)
            <li>Form 137 (Transfer Credential)</li>
        @endif
        @if($admission->good_moral)
            <li>Good Moral Certificate</li>
        @endif
        @if($admission->id_picture)
            <li>ID Picture</li>
        @endif
        @if($admission->medical_certificate)
            <li>Medical Certificate</li>
        @endif
        @if(!$admission->birth_certificate && !$admission->report_card && !$admission->form_137 && !$admission->good_moral && !$admission->id_picture && !$admission->medical_certificate)
            <li>No documents submitted yet</li>
        @endif
    </ul>

    <!-- Application Status -->
    <div class="section-title">APPLICATION STATUS</div>
    <div class="info-row">
        <div class="info-label">Status:</div>
        <div class="info-value">
            <span class="status-badge status-{{ strtolower($admission->status) }}">
                {{ strtoupper($admission->status) }}
            </span>
        </div>
    </div>
    <div class="info-row">
        <div class="info-label">Application Date:</div>
        <div class="info-value">{{ $admission->created_at ? $admission->created_at->format('F d, Y h:i A') : 'N/A' }}</div>
    </div>
    @if($admission->status_remarks)
    <div class="info-row">
        <div class="info-label">Remarks:</div>
        <div class="info-value">{{ $admission->status_remarks }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Generated on {{ now()->format('F d, Y h:i A') }}<br>
        This is a computer-generated document and does not require a signature.
    </div>
</body>
</html>
