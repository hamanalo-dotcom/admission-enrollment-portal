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

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background-color: #cfe2ff;
            color: #084298;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
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

            .header-actions {
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
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>📚 My Enrollments</h1>
            </div>
            <div class="header-actions">
                @php
                    // Get active school year value - try from passed variable first, then query directly
                    $activeSchoolYearValue = null;
                    if (isset($activeSchoolYear) && $activeSchoolYear) {
                        $activeSchoolYearValue = $activeSchoolYear->school_year ?? null;
                    }
                    
                    // Fallback: Query directly if not passed or null
                    if (!$activeSchoolYearValue) {
                        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();
                        $activeSchoolYearValue = $activeSchoolYear ? $activeSchoolYear->school_year : null;
                    }
                    
                    // Check if student has enrollment for active school year
                    $hasActiveYearEnrollment = false;
                    if ($activeSchoolYearValue) {
                        // Query database directly to check for enrollment
                        $hasActiveYearEnrollment = \App\Models\Enrollment::where('user_id', auth()->id())
                            ->where('school_year', $activeSchoolYearValue)
                            ->where('status', '!=', 'cancelled')
                            ->exists();
                    }
                @endphp
                @if($activeSchoolYearValue && !$hasActiveYearEnrollment)
                    <a href="{{ route('student.enrollment.create') }}" class="btn btn-primary">
                        + Enroll Now
                    </a>
                @elseif($activeSchoolYearValue && $hasActiveYearEnrollment)
                    @php
                        // Get the enrollment from database to ensure we have the full record
                        $currentEnrollment = \App\Models\Enrollment::where('user_id', auth()->id())
                            ->where('school_year', $activeSchoolYearValue)
                            ->where('status', '!=', 'cancelled')
                            ->first();
                    @endphp
                    @if($currentEnrollment && in_array($currentEnrollment->status, ['pending', 'cancelled']))
                        <a href="{{ route('student.enrollment.edit', $currentEnrollment->id) }}" class="btn btn-primary">
                            ✏️ Edit Enrollment
                        </a>
                    @else
                        <span class="text-muted" style="font-size: 14px;">You already have an enrollment for {{ $activeSchoolYearValue }}</span>
                    @endif
                @else
                    <span class="text-muted" style="font-size: 14px;">No active school year available for enrollment</span>
                @endif
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Grade Level</th>
                            <th>Strand</th>
                            <th>Section</th>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->admission->first_name ?? 'N/A' }}
                                    {{ $enrollment->admission->last_name ?? '' }}
                                </td>
                                <td>{{ $enrollment->grade_level ?? '-' }}</td>
                                <td>{{ $enrollment->strand ?? '-' }}</td>
                                <td>{{ $enrollment->section ?? '-' }}</td>
                                <td>{{ $enrollment->school_year ?? '-' }}</td>
                                <td>{{ $enrollment->semester ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        @php
                                            $badgeClass = match($enrollment->status) {
                                                'pending' => 'badge-warning',
                                                'enrolled' => 'badge-success',
                                                'cancelled' => 'badge-danger',
                                                'completed' => 'badge-info',
                                                default => 'badge-info',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($enrollment->status) }}</span>
                                        @if($enrollment->status === 'cancelled' && $enrollment->cancellation_remarks)
                                            <small class="text-danger" style="font-size: 12px;" title="{{ $enrollment->cancellation_remarks }}">
                                                <i class="fas fa-info-circle"></i> {{ Str::limit($enrollment->cancellation_remarks, 50) }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('student.enrollment.show', $enrollment->id) }}"
                                            class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if(in_array($enrollment->status, ['pending', 'cancelled']))
                                            <a href="{{ route('student.enrollment.edit', $enrollment->id) }}"
                                                class="btn-icon" title="Edit Enrollment">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            {{-- Delete functionality removed to preserve all data --}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p><strong>No enrollments found</strong></p>
                                        <p style="font-size: 13px; color: #999; margin-top: 8px; font-weight: 400;">Start by clicking "Enroll Now" to create your first enrollment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
@endsection