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

        .search-box, .select-filter {
            padding: 12px 18px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fbfd;
            color: #2c5aa0;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-box:focus, .select-filter:focus {
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

        .btn-icon {
            padding: 8px 14px;
            border: 2px solid #d4e5f3;
            background-color: white;
            color: #2c5aa0;
            border-radius: 6px;
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

        .btn-icon-danger {
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .btn-icon-danger:hover {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border-color: #e74c3c;
            color: white;
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #2c5aa0;
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #2c5aa0;
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

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-info {
            background-color: #e3f2fd;
            color: #2196f3;
        }

        .badge-warning {
            background-color: #fff3e0;
            color: #ff9800;
        }

        .badge-success {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .badge-danger {
            background-color: #ffebee;
            color: #f44336;
        }

        .badge-active {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .badge-inactive {
            background-color: #ffebee;
            color: #f44336;
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

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>📢 Announcements</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.announcements.create' : 'registrar.announcements.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Announcement
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <h3>Total Announcements</h3>
                <div class="number">{{ $totalAnnouncements }}</div>
            </div>
            <div class="stat-card">
                <h3>Active</h3>
                <div class="number">{{ $activeAnnouncements }}</div>
            </div>
            <div class="stat-card">
                <h3>Inactive</h3>
                <div class="number">{{ $inactiveAnnouncements }}</div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
            <form method="GET" action="{{ route(auth()->user()->role === 'admin' ? 'admin.announcements.index' : 'registrar.announcements.index') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <input type="text" name="search" class="search-box" placeholder="Search by title or message..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
                <select name="type" class="select-filter" style="min-width: 120px;">
                    <option value="">All Types</option>
                    <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                    <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="danger" {{ request('type') == 'danger' ? 'selected' : '' }}>Danger</option>
                </select>
                <select name="status" class="select-filter" style="min-width: 120px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search') || request('type') || request('status'))
                    <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.announcements.index' : 'registrar.announcements.index') }}" class="btn" style="background: #f5f7fa; color: #666; border: 2px solid #d4e5f3;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Published Date</th>
                            <th>Expiration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td style="font-weight: 600; color: #2c5aa0;">{{ $announcement->title }}</td>
                                <td style="max-width: 400px;">{{ Str::limit($announcement->message, 80) }}</td>
                                <td>
                                    <span class="badge badge-{{ $announcement->type }}">
                                        @if($announcement->type === 'info')
                                            <i class="fas fa-info-circle"></i>
                                        @elseif($announcement->type === 'warning')
                                            <i class="fas fa-exclamation-triangle"></i>
                                        @elseif($announcement->type === 'success')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($announcement->type === 'danger')
                                            <i class="fas fa-exclamation-circle"></i>
                                        @endif
                                        {{ ucfirst($announcement->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $announcement->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    @if($announcement->published_at)
                                        {{ $announcement->published_at->format('M d, Y') }}
                                    @else
                                        {{ $announcement->created_at->format('M d, Y') }}
                                    @endif
                                </td>
                                <td>
                                    @if($announcement->expiration_date)
                                        @php
                                            $isExpired = $announcement->expiration_date < now();
                                            $isExpiringSoon = $announcement->expiration_date <= now()->addDays(7) && $announcement->expiration_date > now();
                                        @endphp
                                        <span style="display: flex; align-items: center; gap: 5px;">
                                            {{ $announcement->expiration_date->format('M d, Y') }}
                                            @if($isExpired)
                                                <span class="badge badge-danger" style="font-size: 10px; padding: 2px 6px;">
                                                    <i class="fas fa-clock"></i> Expired
                                                </span>
                                            @elseif($isExpiringSoon)
                                                <span class="badge badge-warning" style="font-size: 10px; padding: 2px 6px;">
                                                    <i class="fas fa-exclamation-triangle"></i> Soon
                                                </span>
                                            @endif
                                        </span>
                                    @else
                                        <span style="color: #999;">No expiration</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.announcements.edit' : 'registrar.announcements.edit', $announcement->id) }}" class="btn-icon" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- Delete functionality removed to preserve all data --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-bullhorn"></i>
                                        <p><strong>No announcements found</strong></p>
                                        <p style="font-size: 13px; color: #999; margin-top: 8px;">Create an announcement to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($announcements->hasPages())
                <div style="margin-top: 30px; display: flex; justify-content: center;">
                    {{ $announcements->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

