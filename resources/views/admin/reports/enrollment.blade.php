@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; }
        .filters { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
        .table-responsive { overflow-x: auto; }
        label { font-weight: 600; color: #2c5aa0; font-size: 13px; margin-bottom: 6px; display:block; }
        input, select { padding: 10px; border: 2px solid #d4e5f3; border-radius: 8px; font-size: 14px; background: #f9fbfd; }
        .btn-primary { background: linear-gradient(135deg,#2c5aa0,#526c86); color:#fff; padding:10px 16px; border:none; border-radius:8px; font-weight:600; text-decoration:none; }
        .btn-secondary { background:#f5f7fa; border:2px solid #d4e5f3; color:#666; padding:10px 16px; border-radius:8px; text-decoration:none; }
        table { width:100%; border-collapse: collapse; }
        th { background:#2c5aa0; color:#fff; text-align:left; padding:12px; font-size:13px; }
        td { padding:12px; border-bottom:1px solid #e9ecef; font-size:14px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Enrollment Report</h1>
            <a href="{{ route('admin.reports.index') }}" class="btn-secondary" style="margin-top:10px; display:inline-block;">← Back to Reports</a>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1);">
            <form method="GET" action="{{ route('admin.reports.enrollment') }}" class="filters">
                <div style="grid-column: 1 / -1;">
                    <label>Search Student</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email...">
                </div>
                <div>
                    <label>Grade Level</label>
                    <select name="grade_level">
                        <option value="">All</option>
                        @foreach($gradeLevels as $gl)
                            <option value="{{ $gl }}" {{ request('grade_level')==$gl ? 'selected' : '' }}>{{ $gl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="">All</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ request('status')==$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>School Year</label>
                    <select name="school_year">
                        <option value="">All</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->school_year }}" {{ request('school_year', $currentSchoolYear) == $sy->school_year ? 'selected' : '' }}>{{ $sy->school_year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Strand</label>
                    <select name="strand">
                        <option value="">All</option>
                        @foreach($strands as $st)
                            <option value="{{ $st }}" {{ request('strand')==$st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="grid-column: 1 / -1; display:flex; gap:8px; justify-content:flex-end;">
                    <button class="btn-primary" type="submit">Generate Report</button>
                    <a class="btn-secondary" href="{{ route('admin.reports.enrollment') }}">Reset</a>
                </div>
            </form>

            <div style="margin: 20px 0; padding: 15px; background: #f5f7fa; border-radius: 8px;">
                <strong>Total Enrollments: {{ $enrollments->count() }}</strong>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Strand</th>
                            <th>Status</th>
                            <th>School Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->user->firstname }} {{ $enrollment->user->lastname }}</td>
                                <td>{{ $enrollment->grade_level }}</td>
                                <td>{{ $enrollment->section ?? '-' }}</td>
                                <td>{{ $enrollment->strand ?? '-' }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'badge-warning';
                                        if ($enrollment->status === 'enrolled') $badgeClass = 'badge-success';
                                        if ($enrollment->status === 'cancelled') $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($enrollment->status) }}</span>
                                </td>
                                <td>{{ $enrollment->school_year }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align:center; color:#999; padding:20px;">No enrollments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit search on typing (with debounce)
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const form = document.querySelector('form.filters');
            let searchTimeout;

            if (searchInput && form) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        form.submit();
                    }, 500); // Wait 500ms after user stops typing
                });
            }
        });
    </script>
@endsection

