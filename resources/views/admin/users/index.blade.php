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

        .select-filter {
            padding: 12px 18px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fbfd;
            color: #2c5aa0;
            cursor: pointer;
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

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            transform: translateY(-2px);
        }

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

        .role-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .role-admin {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .role-registrar {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .role-teacher {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .role-student {
            background-color: #fff3e0;
            color: #e65100;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
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

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>👥 Users Management</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <h3>{{ $totalUsers ?? 0 }}</h3>
                <p>Total Users</p>
            </div>
            <div class="stat-card" style="border-left-color: #e65100;">
                <h3 style="color: #e65100;">{{ $totalStudents ?? 0 }}</h3>
                <p>Students</p>
            </div>
            <div class="stat-card" style="border-left-color: #2e7d32;">
                <h3 style="color: #2e7d32;">{{ $totalTeachers ?? 0 }}</h3>
                <p>Teachers</p>
            </div>
            <div class="stat-card" style="border-left-color: #1565c0;">
                <h3 style="color: #1565c0;">{{ $totalAdmins ?? 0 }}</h3>
                <p>Admins/Registrars</p>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 15px; align-items: center;">
                <input type="text" name="search" class="search-box" placeholder="Search by name or email..." value="{{ request('search') }}">
                <select name="role" class="select-filter">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="registrar" {{ request('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                    <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn" style="background: #f5f7fa; color: #666;">
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Contact No</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    @if($user->role === 'teacher')
                                        Teacher {{ $user->firstname }} {{ $user->lastname }}
                                    @else
                                        {{ $user->firstname }} {{ $user->lastname }}
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="role-badge role-{{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->contact_no ?: '-' }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- Delete functionality removed to preserve all data --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <p><strong>No users found</strong></p>
                                        <p style="font-size: 13px; color: #999; margin-top: 8px;">Check back later or adjust your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div style="margin-top: 30px; display: flex; justify-content: center;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

