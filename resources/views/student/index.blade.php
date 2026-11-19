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

        .welcome-message {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
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

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            min-width: 100px;
        }

        .bg-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .bg-success {
            background-color: #d4edda;
            color: #155724;
        }

        .bg-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Action Buttons */
        .btn-icon {
            padding: 8px 14px;
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
            font-weight: 600;
        }

        .section-title {
            font-size: 24px;
            color: #2c5aa0;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #4a8fd8;
            padding-bottom: 10px;
        }


        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-section h1 {
                font-size: 24px;
            }

            .table th,
            .table td {
                padding: 12px;
                font-size: 13px;
            }
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>Welcome, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h1>
                <div class="welcome-message">
                    This is your student dashboard. Here, you can easily view and track the status of your admission and enrollment.
                </div>
            </div>
        </div>

        <!-- Current Enrollment Summary -->
        @if(isset($currentEnrollment))
            <div class="table-section" style="margin-bottom: 30px;">
                <h2 class="section-title">My Current Enrollment</h2>
                <p style="margin-bottom: 10px;">
                    <strong>School Year:</strong> {{ $currentEnrollment->school_year }}
                    @if(isset($activeSchoolYear) && $activeSchoolYear && $activeSchoolYear->school_year === $currentEnrollment->school_year)
                        <span class="badge" style="margin-left: 8px; background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 12px;">Active School Year</span>
                    @endif
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>Grade Level:</strong> {{ $currentEnrollment->grade_level }}
                    @if($currentEnrollment->strand)
                        | <strong>Strand:</strong> {{ $currentEnrollment->strand }}
                    @endif
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>Official Section:</strong> 
                    @if($currentEnrollment->section)
                        {{ $currentEnrollment->section }}
                    @else
                        <span class="text-muted">Section to be assigned</span>
                    @endif
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>Status:</strong>
                    @if($currentEnrollment->status === 'enrolled')
                        <span class="badge" style="background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 12px;">Enrolled</span>
                    @elseif($currentEnrollment->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($currentEnrollment->status === 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($currentEnrollment->status) }}</span>
                    @endif
                </p>

                <h3 style="margin-top: 20px; margin-bottom: 10px; color:#2c5aa0; font-size:18px;">Enrolled Subjects</h3>
                @if(isset($enrolledSubjects) && $enrolledSubjects->count() > 0)
                    <ul style="padding-left: 18px; margin-bottom: 10px;">
                        @foreach($enrolledSubjects as $subject)
                            <li>{{ $subject->name }} @if($subject->code) ({{ $subject->code }}) @endif</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No subjects found for this enrollment.</p>
                @endif

                <a href="{{ route('student.enrollment.index') }}" class="btn-icon" style="margin-top: 10px;">
                    <i class="fas fa-clipboard-list"></i> View Enrollment Details
                </a>
            </div>
        @endif

    </div>
@endsection
