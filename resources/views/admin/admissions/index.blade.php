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

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #2c5aa0;
            text-align: center;
        }

        .stat-card h3 {
            color: #2c5aa0;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
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

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>📋 Admissions Management</h1>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <h3 id="admissionsCount">{{ $admissions->total() }}</h3>
                <p>Total Admissions</p>
            </div>
            <div class="stat-card" style="border-left-color: #27ae60;">
                <h3 style="color: #27ae60;">{{ \App\Models\Admission::where('is_draft', false)->where('status', 'approved')->count() }}</h3>
                <p>Approved</p>
            </div>
            <div class="stat-card" style="border-left-color: #f39c12;">
                <h3 style="color: #f39c12;">{{ \App\Models\Admission::where('is_draft', false)->where('status', 'pending')->count() }}</h3>
                <p>Pending</p>
            </div>
            <div class="stat-card" style="border-left-color: #e74c3c;">
                <h3 style="color: #e74c3c;">{{ \App\Models\Admission::where('is_draft', false)->where('status', 'rejected')->count() }}</h3>
                <p>Rejected</p>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
            <form method="GET" action="{{ route('admin.admissions.index') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
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
                    <a href="{{ route('admin.admissions.index') }}" class="btn" style="background: #f5f7fa; color: #666; border: 2px solid #d4e5f3;">
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
                            <th>LRN</th>
                            <th>Student Type</th>
                            <th>Grade Level</th>
                            <th>Date Applied</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admissionsTableBody">
                        @forelse($admissions as $admission)
                            @php
                                $isRecentlyUpdated = $admission->updated_at->diffInHours(now()) < 24;
                                $wasUpdatedByStudent = $admission->updated_at->greaterThan($admission->created_at);
                                $needsReview = $admission->status === 'pending' && $wasUpdatedByStudent && $isRecentlyUpdated;
                            @endphp
                            <tr class="{{ $needsReview ? 'table-warning' : '' }}" style="{{ $needsReview ? 'background-color: #fff3cd !important;' : '' }}">
                                <td>
                                    {{ $admission->first_name ?? 'N/A' }} {{ $admission->last_name ?? '' }}
                                    @if($needsReview)
                                        <span class="badge bg-warning text-dark ms-2" title="Recently updated by student - Needs Review">
                                            <i class="fas fa-exclamation-circle"></i> Updated
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $admission->lrn ?? '-' }}</td>
                                <td>{{ $admission->student_type ?? '-' }}</td>
                                <td>{{ $admission->year_level ?? '-' }}</td>
                                <td>{{ $admission->created_at->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                                <td>
                                    @if($wasUpdatedByStudent)
                                        <span class="{{ $isRecentlyUpdated ? 'text-danger fw-bold' : 'text-muted' }}" title="Last updated: {{ $admission->updated_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }} (Philippine Time)">
                                            {{ $admission->updated_at->setTimezone('Asia/Manila')->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        @php
                                            $badge = match ($admission->status) {
                                                'pending' => 'status-badge status-pending',
                                                'approved' => 'status-badge status-approved',
                                                'rejected' => 'status-badge status-rejected',
                                                default => 'status-badge',
                                            };
                                        @endphp
                                        <span class="{{ $badge }}">{{ ucfirst($admission->status) }}</span>
                                        @if($admission->status === 'rejected' && $admission->rejection_remarks)
                                            <small class="text-danger" style="font-size: 11px;" title="{{ $admission->rejection_remarks }}">
                                                <i class="fas fa-info-circle"></i> {{ Str::limit($admission->rejection_remarks, 40) }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.admissions.show', $admission->id) }}" class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-inbox"></i><p><strong>No admissions found</strong></p><p style="font-size: 13px; color: #999; margin-top: 8px;">Check back later or adjust your search.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($admissions->hasPages())
                <div style="margin-top: 30px; display: flex; justify-content: center;">
                    {{ $admissions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection