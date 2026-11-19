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

        .download-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
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
            <h1>📚 Grade 11 Students</h1>
        </div>

        <!-- GAS - ZAMORA -->
        <div class="section-container">
            <div class="section-header">
                <h2>GAS - ZAMORA</h2>
                <a href="{{ route('admin.sections.download-strand-section-pdf', ['grade' => '11', 'strand' => 'gas', 'section' => 'zamora']) }}" class="download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Strand</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">School Year</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradeElevenGasZamora as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? '' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td class="text-center">{{ $enrollment->grade_level }}</td>
                                <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->school_year }}</td>
                                <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
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
                                <td colspan="7">
                                    <div class="empty-state">
                                        <p>No students found in GAS - ZAMORA</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TVL - ZARA -->
        <div class="section-container">
            <div class="section-header">
                <h2>TVL - ZARA</h2>
                <a href="{{ route('admin.sections.download-strand-section-pdf', ['grade' => '11', 'strand' => 'tvl', 'section' => 'zara']) }}" class="download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Strand</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">School Year</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradeElevenTvlZara as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? '' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td class="text-center">{{ $enrollment->grade_level }}</td>
                                <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->school_year }}</td>
                                <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
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
                                <td colspan="7">
                                    <div class="empty-state">
                                        <p>No students found in TVL - ZARA</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- TVL - ALCARAZ -->
        <div class="section-container">
            <div class="section-header">
                <h2>TVL - ALCARAZ</h2>
                <a href="{{ route('admin.sections.download-strand-section-pdf', ['grade' => '11', 'strand' => 'tvl', 'section' => 'alcaraz']) }}" class="download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Strand</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">School Year</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradeElevenTvlAlcaraz as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? '' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td class="text-center">{{ $enrollment->grade_level }}</td>
                                <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                                <td class="text-center">{{ $enrollment->school_year }}</td>
                                <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
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
                                <td colspan="7">
                                    <div class="empty-state">
                                        <p>No students found in TVL - ALCARAZ</p>
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
