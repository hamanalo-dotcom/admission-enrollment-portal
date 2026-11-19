
@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; }
        .cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 18px; }
        .card { background:#fff; padding:16px; border-radius:8px; box-shadow: 0 1px 6px rgba(0,0,0,.05); }
        .card h3 { margin:0; color:#2c5aa0; font-size:18px; }
        .muted { color:#666; font-size:13px; margin-top:6px; }
        table { width:100%; border-collapse: collapse; margin-top:8px; }
        th { background:#2c5aa0; color:#fff; text-align:left; padding:10px; font-size:13px; }
        td { padding:10px; border-bottom:1px solid #e9ecef; font-size:14px; }
        .small-grid { display:grid; grid-template-columns: 1fr 1fr; gap:14px; }
        .form-inline { display:flex; gap:10px; align-items:center; margin-bottom:14px; }
        .form-select { padding:10px 12px; border:2px solid #d4e5f3; border-radius:8px; background:#f9fbfd; color:#2c5aa0; font-weight:600; }
        .btn { padding:10px 14px; border-radius:8px; border:none; background:linear-gradient(135deg,#2c5aa0,#526c86); color:#fff; font-weight:700; cursor:pointer; }
        .btn-clear { background:#f5f7fa; color:#666; border:2px solid #d4e5f3; }
        .link-reset { color:inherit; text-decoration:none; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Statistics</h1>
            <p class="muted">Overview of students, enrollments and admissions. Select a school year to filter enrollment totals.</p>
        </div>

        <form method="GET" action="{{ route('admin.reports.statistics') }}" class="form-inline">
            <label for="school_year" style="font-weight:600;color:#2c5aa0;">School Year</label>
            <select name="school_year" id="school_year" class="form-select">
                <option value="">All years</option>
                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->school_year }}" {{ (isset($selectedSchoolYear) && $selectedSchoolYear == $sy->school_year) ? 'selected' : '' }}>
                        {{ $sy->school_year }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn">Apply</button>
            @if(request('school_year'))
                <a href="{{ route('admin.reports.statistics') }}" class="btn btn-clear" style="margin-left:8px;">Clear</a>
            @endif
        </form>

        <div class="cards">
            <div class="card">
                <h3>{{ $totalStudents ?? 0 }}</h3>
                <p class="muted">Total Students</p>
            </div>

            <div class="card">
                <h3>{{ $totalTeachers ?? 0 }}</h3>
                <p class="muted">Total Teachers</p>
            </div>

            @if(!empty($selectedSchoolYear))
                <div class="card">
                    <h3>
                        <a href="{{ route('admin.reports.enrollment', ['school_year' => $selectedSchoolYear]) }}" class="link-reset">
                            {{ $totalEnrolled ?? 0 }}
                        </a>
                    </h3>
                    <p class="muted">Currently Enrolled — {{ $selectedSchoolYear }}</p>
                </div>
            @endif

            <div class="card">
                <h3>{{ $totalPendingAdmissions ?? 0 }}</h3>
                <p class="muted">Pending Admissions</p>
            </div>
        </div>

        <div class="small-grid">
            <div class="card">
                <h3 style="margin-bottom:8px;">Enrollment by Grade</h3>
                @if(!empty($enrollmentByGrade) && count($enrollmentByGrade))
                    <table>
                        <thead><tr><th>Grade Level</th><th>Count</th></tr></thead>
                        <tbody>
                            @foreach($enrollmentByGrade as $grade => $count)
                                <tr><td>{{ $grade }}</td><td>{{ $count }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">No data available.</p>
                @endif
            </div>

            <div class="card">
                <h3 style="margin-bottom:8px;">Enrollment by Strand</h3>
                @if(!empty($enrollmentByStrand) && count($enrollmentByStrand))
                    <table>
                        <thead><tr><th>Strand</th><th>Count</th></tr></thead>
                        <tbody>
                            @foreach($enrollmentByStrand as $strand => $count)
                                <tr><td>{{ $strand }}</td><td>{{ $count }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">No data available.</p>
                @endif
            </div>
        </div>

        <div style="margin-top:14px;">
            <div class="card">
                <h3 style="margin-bottom:8px;">Admissions Status Breakdown</h3>
                @if(!empty($admissionStatus) && count($admissionStatus))
                    <table>
                        <thead><tr><th>Status</th><th>Count</th></tr></thead>
                        <tbody>
                            @foreach($admissionStatus as $status => $count)
                                <tr><td>{{ ucfirst($status) }}</td><td>{{ $count }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">No data available.</p>
                @endif
            </div>
        </div>
    </div>
@endsection