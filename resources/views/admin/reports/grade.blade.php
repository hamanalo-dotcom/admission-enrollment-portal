@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; }
        .filters { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 15px; }
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
        .summary-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .table-responsive { overflow-x: auto; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Grade Report</h1>
            <a href="{{ route('registrar.reports.index') }}" class="btn-secondary" style="margin-top:10px; display:inline-block;">← Back to Reports</a>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1);">
            <form method="GET" action="{{ route('registrar.reports.grade') }}" class="filters">
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
                    <label>Section</label>
                    <input type="text" name="section" value="{{ request('section') }}" placeholder="Section name">
                </div>
                <div>
                    <label>Subject</label>
                    <select name="subject_id">
                        <option value="">All</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id')==$subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Grading Period</label>
                    <select name="grading_period">
                        <option value="">All</option>
                        @foreach($gradingPeriods as $gp)
                            <option value="{{ $gp }}" {{ request('grading_period')==$gp ? 'selected' : '' }}>{{ $gp }}</option>
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
                <div style="grid-column: 1 / -1; display:flex; gap:8px; justify-content:flex-end;">
                    <button class="btn-primary" type="submit">Generate Report</button>
                    <a class="btn-secondary" href="{{ route('registrar.reports.grade') }}">Reset</a>
                </div>
            </form>

            @if(isset($gradeSummary) && $gradeSummary->count() > 0)
                <div class="summary-section">
                    <h3 style="color: #2c5aa0; margin-bottom: 15px;">Grade Summary by Student</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Number of Grades</th>
                                    <th>Average Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeSummary as $summary)
                                    <tr>
                                        <td><strong>{{ $summary['student']->firstname }} {{ $summary['student']->lastname }}</strong></td>
                                        <td>{{ $summary['grades']->first()->grade_level ?? '-' }}</td>
                                        <td>{{ $summary['grades']->first()->section ?? '-' }}</td>
                                        <td>{{ $summary['count'] }}</td>
                                        <td>
                                            @php
                                                $avg = $summary['average'];
                                                $badgeClass = 'badge-success';
                                                if ($avg < 75) {
                                                    $badgeClass = 'badge-danger';
                                                } elseif ($avg < 85) {
                                                    $badgeClass = 'badge-warning';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ number_format($avg, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div style="margin: 20px 0; padding: 15px; background: #f5f7fa; border-radius: 8px;">
                <strong>Total Grades: {{ $grades->count() }}</strong>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Subject</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Grading Period</th>
                            <th>Grade</th>
                            <th>Teacher</th>
                            <th>School Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr>
                                <td>
                                    <strong>{{ $grade->student->firstname }} {{ $grade->student->lastname }}</strong><br>
                                    <small style="color:#999;">{{ $grade->student->email }}</small>
                                </td>
                                <td>{{ $grade->subject->name }}</td>
                                <td>{{ $grade->grade_level }}</td>
                                <td>{{ $grade->section }}</td>
                                <td>{{ $grade->grading_period }}</td>
                                <td>
                                    @php
                                        $gradeValue = $grade->grade_value;
                                        $badgeClass = 'badge-success';
                                        if ($gradeValue < 75) {
                                            $badgeClass = 'badge-danger';
                                        } elseif ($gradeValue < 85) {
                                            $badgeClass = 'badge-warning';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ number_format($gradeValue, 2) }}</span>
                                </td>
                                <td>{{ $grade->teacher->firstname }} {{ $grade->teacher->lastname }}</td>
                                <td>{{ $grade->school_year }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" style="text-align:center; color:#999; padding:20px;">No grades found.</td></tr>
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

