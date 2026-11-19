@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center; }
        .table-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .filters { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; margin-bottom: 15px; }
        label { font-weight: 600; color: #2c5aa0; font-size: 13px; margin-bottom: 6px; display:block; }
        input, select { padding: 10px; border: 2px solid #d4e5f3; border-radius: 8px; font-size: 14px; background: #f9fbfd; }
        .btn-primary { background: linear-gradient(135deg,#2c5aa0,#526c86); color:#fff; padding:10px 16px; border:none; border-radius:8px; font-weight:600; text-decoration:none; }
        .btn-secondary { background:#f5f7fa; border:2px solid #d4e5f3; color:#666; padding:10px 16px; border-radius:8px; text-decoration:none; }
        table { width:100%; border-collapse: collapse; }
        th { background:#2c5aa0; color:#fff; text-align:left; padding:12px; font-size:13px; }
        td { padding:12px; border-bottom:1px solid #e9ecef; font-size:14px; }
        .actions { display:flex; gap:8px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Grades Management</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-section">
            @php
                $selectedSchoolYear = request('school_year', $currentSchoolYear);
            @endphp
            <form method="GET" action="{{ route('admin.grades.index') }}" class="filters">
                <div>
                    <label>Search Student</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email">
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
                        @foreach($schoolYears as $schoolYear)
                            <option 
                                value="{{ $schoolYear->school_year }}" 
                                {{ $selectedSchoolYear == $schoolYear->school_year ? 'selected' : '' }}
                            >
                                {{ $schoolYear->school_year }} {{ $schoolYear->is_active ? '(Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="grid-column: 1 / -1; display:flex; gap:8px; justify-content:flex-end;">
                    <button class="btn-primary" type="submit">Filter</button>
                    <a class="btn-secondary" href="{{ route('admin.grades.index') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Grading Period</th>
                            <th>Grade</th>
                            <th>Teacher</th>
                            <th>School Year</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr>
                                <td>
                                    @php
                                        $routeName = request()->routeIs('registrar.*') ? 'registrar.grades.student-record' : 'admin.grades.student-record';
                                    @endphp
                                    <strong>
                                        <a href="{{ route($routeName, $grade->student->id) }}" 
                                           style="color: #2c5aa0; text-decoration: none; cursor: pointer;"
                                           onmouseover="this.style.textDecoration='underline'"
                                           onmouseout="this.style.textDecoration='none'"
                                           title="View complete academic record">
                                            {{ $grade->student->firstname }} {{ $grade->student->lastname }}
                                        </a>
                                    </strong><br>
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
                                <td>
                                    <div class="actions">
                                        <a class="btn-secondary" href="{{ route('admin.grades.edit', $grade->id) }}">Edit</a>
                                        {{-- Delete functionality removed to preserve all data --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" style="text-align:center; color:#999; padding:20px;">No grades found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 12px;">
                {{ $grades->links() }}
            </div>
        </div>
    </div>
@endsection

