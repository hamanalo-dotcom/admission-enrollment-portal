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

        /* Section Container */
        .section-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4a8fd8;
        }

        .section-header h2 {
            font-size: 24px;
            color: #2c5aa0;
            font-weight: 700;
            margin: 0;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
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

        .table th.text-center {
            text-align: center;
        }

        .table td {
            padding: 18px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #555;
        }

        .table td.text-center {
            text-align: center;
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

        .bg-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .bg-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .empty-state {
            text-align: center;
            padding: 60px 40px;
            color: #999;
        }

        .empty-state p {
            font-size: 16px;
            color: #2c5aa0;
            font-weight: 600;
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

            .section-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <h1>📚 Grade 9 Students</h1>
        </div>

        <!-- Section ACACIA -->
        <div class="section-container">
            <div class="section-header">
                <h2>ACACIA</h2>
                <a href="{{ route('admin.sections.download-pdf', ['grade' => '9', 'section' => 'acacia']) }}" class="download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">School Year</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradeNineSectionA as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? '' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td class="text-center">{{ $enrollment->grade_level }}</td>
                                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->school_year }}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match ($enrollment->status) {
                                            'pending' => 'warning',
                                            'enrolled' => 'success',
                                            'cancelled' => 'danger',
                                            'completed' => 'info',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <p>No students found in ACACIA</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section LAUAN -->
        <div class="section-container">
            <div class="section-header">
                <h2>LAUAN</h2>
                <a href="{{ route('admin.sections.download-pdf', ['grade' => '9', 'section' => 'lauan']) }}" class="download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">School Year</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradeNineSectionB as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? '' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td class="text-center">{{ $enrollment->grade_level }}</td>
                                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->school_year }}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match ($enrollment->status) {
                                            'pending' => 'warning',
                                            'enrolled' => 'success',
                                            'cancelled' => 'danger',
                                            'completed' => 'info',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <p>No students found in LAUAN</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section NARRA -->
        <div class="section-container">
            <div class="section-header">
                <h2>NARRA</h2>
                <a href="{{ route('admin.sections.download-pdf', ['grade' => '9', 'section' => 'narra']) }}" class="download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">School Year</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradeNineSectionC as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? '' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td class="text-center">{{ $enrollment->grade_level }}</td>
                                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->school_year }}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match ($enrollment->status) {
                                            'pending' => 'warning',
                                            'enrolled' => 'success',
                                            'cancelled' => 'danger',
                                            'completed' => 'info',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <p>No students found in NARRA</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
