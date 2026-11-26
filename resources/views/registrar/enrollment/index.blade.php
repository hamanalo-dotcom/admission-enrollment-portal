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

        .status-enrolled {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background-color: #cfe2ff;
            color: #084298;
        }

        /* Status Select */
        .form-select {
            padding: 8px 12px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            background-color: #f9fbfd;
            color: #2c5aa0;
            font-size: 13px;
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

        .btn-icon.danger {
            color: #dc3545;
            border-color: #f5c2c7;
        }

        .btn-icon.danger:hover {
            background: #f8d7da;
            color: #a71d2a;
            border-color: #f1aeb5;
            transform: translateY(-2px);
        }

        .btn-icon.restore {
            color: #28a745;
            border-color: #28a745;
        }

        .btn-icon.restore:hover {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            transform: translateY(-2px);
        }

        /* Deleted row styling */
        .table tbody tr.deleted {
            background-color: #fff3cd;
            opacity: 0.8;
        }

        .table tbody tr.deleted:hover {
            background-color: #ffe69c;
            opacity: 1;
        }

        .deleted-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            background-color: #ffc107;
            color: #856404;
            margin-left: 8px;
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


        .section-select {
            min-width: 120px;
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
    </style>

    <div class="main-content">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>📚 Enrollment Management</h1>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
            <!-- Tabs for Active/Deleted -->
            <div style="display: flex; gap: 10px; margin-bottom: 15px; border-bottom: 2px solid #e0e0e0;">
                <a href="{{ route('registrar.enrollment.index') }}" 
                   class="btn" 
                   style="background: {{ !isset($showDeleted) || !$showDeleted ? 'linear-gradient(135deg, #2c5aa0 0%, #526c86 100%)' : '#f5f7fa' }}; 
                          color: {{ !isset($showDeleted) || !$showDeleted ? 'white' : '#666' }}; 
                          border: 2px solid {{ !isset($showDeleted) || !$showDeleted ? '#2c5aa0' : '#d4e5f3' }};
                          border-bottom: none;
                          border-radius: 8px 8px 0 0;
                          padding: 10px 20px;">
                    <i class="fas fa-list"></i> Active Enrollments
                </a>
                <a href="{{ route('registrar.enrollment.index', ['show_deleted' => 1]) }}" 
                   class="btn" 
                   style="background: {{ isset($showDeleted) && $showDeleted ? 'linear-gradient(135deg, #2c5aa0 0%, #526c86 100%)' : '#f5f7fa' }}; 
                          color: {{ isset($showDeleted) && $showDeleted ? 'white' : '#666' }}; 
                          border: 2px solid {{ isset($showDeleted) && $showDeleted ? '#2c5aa0' : '#d4e5f3' }};
                          border-bottom: none;
                          border-radius: 8px 8px 0 0;
                          padding: 10px 20px;">
                    <i class="fas fa-trash-restore"></i> Deleted Enrollments
                </a>
            </div>
            
            <form method="GET" action="{{ route('registrar.enrollment.index') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                @if(isset($showDeleted) && $showDeleted)
                    <input type="hidden" name="show_deleted" value="1">
                @endif
                <input type="text" name="search" class="search-box" placeholder="Search by student name, LRN, grade level, or section..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
                @if(!isset($showDeleted) || !$showDeleted)
                    <select name="status" class="form-select" style="min-width: 180px;">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                @endif
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search') || request('status') || (isset($showDeleted) && $showDeleted))
                    <a href="{{ route('registrar.enrollment.index') }}" class="btn" style="background: #f5f7fa; color: #666; border: 2px solid #d4e5f3;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div style="overflow-x: auto;">
                <table class="table" id="enrollmentTable">
                    @php
                        $hasShs = $enrollments->contains(function($e) {
                            return in_array($e->grade_level, ['Grade 11', 'Grade 12']);
                        });
                    @endphp
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Grade Level</th>
                            @if($hasShs)
                                <th>Strand</th>
                            @endif
                            <th>Section</th>
                            <th>School Year</th>
                            @if($hasShs)
                                <th>Semester</th>
                            @endif
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="enrollmentTableBody">
                        @forelse($enrollments as $enrollment)
                            @php
                                $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
                            @endphp
                            <tr class="{{ isset($showDeleted) && $showDeleted ? 'deleted' : '' }}">
                                <td>
                                    {{ $enrollment->admission->first_name ?? 'N/A' }} 
                                    {{ $enrollment->admission->last_name ?? '' }}
                                    @if(isset($showDeleted) && $showDeleted)
                                        <span class="deleted-badge">DELETED</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->grade_level ?? '-' }}</td>
                                @if($hasShs)
                                    <td>{{ $isShs ? ($enrollment->strand ?? '-') : '-' }}</td>
                                @endif
                                <td>{{ $enrollment->section ?: '-' }}</td>
                                <td>{{ $enrollment->school_year ?? '-' }}</td>
                                @if($hasShs)
                                    <td>{{ $isShs ? ($enrollment->semester ?? '-') : '-' }}</td>
                                @endif
                                <td>
                                    @php
                                        $statusKey = strtolower($enrollment->status ?? 'pending');
                                        $statusBadgeClass = in_array($statusKey, ['pending', 'enrolled', 'cancelled', 'completed'])
                                            ? 'status-' . $statusKey
                                            : 'status-pending';
                                    @endphp
                                    <span class="status-badge {{ $statusBadgeClass }}">
                                        {{ ucfirst($enrollment->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('registrar.enrollment.show', $enrollment->id) }}" class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if(isset($showDeleted) && $showDeleted)
                                            <!-- Restore button for deleted enrollments -->
                                            <form action="{{ route('registrar.enrollment.restore', $enrollment->id) }}" method="POST" onsubmit="return confirmSubmit(event, 'Restore this enrollment?');" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-icon" style="color: #28a745; border-color: #28a745;" title="Restore Enrollment">
                                                    <i class="fas fa-undo"></i> Restore
                                                </button>
                                            </form>
                                        @else
                                            <!-- Delete button for active enrollments -->
                                            <form action="{{ route('registrar.enrollment.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirmSubmit(event, 'Delete this enrollment? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon danger" title="Delete Enrollment">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $hasShs ? 8 : 6 }}">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p><strong>No enrollments found</strong></p>
                                        <p style="font-size: 13px; color: #999; margin-top: 8px;">Check back later or adjust your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($enrollments->hasPages())
                <div class="pagination">
                    {{ $enrollments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
