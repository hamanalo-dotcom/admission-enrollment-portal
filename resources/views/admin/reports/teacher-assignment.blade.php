@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; }
        .btn-secondary { background:#f5f7fa; border:2px solid #d4e5f3; color:#666; padding:10px 16px; border-radius:8px; text-decoration:none; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th { background:#2c5aa0; color:#fff; text-align:left; padding:12px; font-size:13px; }
        td { padding:12px; border-bottom:1px solid #e9ecef; font-size:14px; }
        .muted { color:#666; font-size:13px; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Teacher Assignment Report</h1>
            <a href="{{ route('admin.reports.index') }}" class="btn-secondary" style="margin-top:10px; display:inline-block;">← Back to Reports</a>
            <p class="muted" style="margin-top:8px;">Showing assignments for: {{ $currentSchoolYear ?? 'All years' }}</p>
        </div>

        @if($assignments->isEmpty())
            <p>No teacher assignments found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>School Year</th>
                        <th>Grade Level</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Strand</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->school_year ?? '—' }}</td>
                            <td>{{ $assignment->grade_level ?? '—' }}</td>
                            <td>{{ $assignment->section ?? '—' }}</td>
                            <td>{{ optional($assignment->subject)->name ?? ($assignment->subject_name ?? '—') }}</td>
                            <td>
                                @php
                                    $t = optional($assignment->teacher);
                                @endphp
                                {{ $t->lastname ? ($t->lastname . ', ' . ($t->firstname ?? '')) : ($assignment->teacher_name ?? '—') }}
                            </td>
                            <td>{{ $assignment->strand ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection