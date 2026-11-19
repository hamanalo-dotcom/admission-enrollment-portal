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

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
            color: white;
        }

        /* Table Styles */
        .table-section {
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
            font-weight: 600;
            margin: 0;
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

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                gap: 15px;
                text-align: center;
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
        <header class="header-section">
            <h1>Grade 7 Students</h1>
        </header>

        @forelse ($sectionsByName as $sectionName => $students)
            <div class="table-section">
                <div class="section-header">
                    <h2>{{ $sectionName }}</h2>
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('teacher.grades.create', ['grade_level' => 'Grade 7', 'section' => $sectionName]) }}" class="btn-primary">➕ Add Grades</a>
                        <a href="{{ route('teacher.jhs.section.pdf', ['grade' => '7', 'section' => $sectionName]) }}" class="btn-primary">📥 Download PDF</a>
                    </div>
                </div>
                <div class="table-responsive">
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
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->admission->first_name }} {{ $student->admission->last_name }}</td>
                                    <td class="text-center">{{ $student->grade_level }}</td>
                                    <td class="text-center">{{ $student->section ?? '-' }}</td>
                                    <td class="text-center">{{ $student->school_year }}</td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = match ($student->status) {
                                                'pending' => 'warning',
                                                'enrolled' => 'success',
                                                'cancelled' => 'danger',
                                                'completed' => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ ucfirst($student->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="table-section">
                <div class="section-header">
                    <h2>No Sections Assigned</h2>
                </div>
                <div class="table-responsive" style="color:#888; padding: 16px;">You have no assigned Grade 7 sections.</div>
            </div>
        @endforelse
    </div>
@endsection
