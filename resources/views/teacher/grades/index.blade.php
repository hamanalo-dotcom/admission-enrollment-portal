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

    .table-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: #2c5aa0;
        color: white;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
    }

    .table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
        transform: translateY(-2px);
        color: white;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background: #2c5aa0;
        color: white;
    }
</style>

<div class="main-content">
    <header class="header-section">
        <h1>Submit Grades</h1>
        <div style="display:flex; gap:10px; align-items:center;">
            <a href="{{ route('teacher.subjects.create') }}" class="btn-primary">+ Add Subject</a>
            <span style="color: #2c5aa0; font-weight: 600;">Select a section and subject to submit grades</span>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-section">
        <table class="table">
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>Strand</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $group => $assignmentGroup)
                    @foreach($assignmentGroup as $assignment)
                        <tr>
                            <td>{{ $assignment->school_year }}</td>
                            <td>{{ $assignment->grade_level }}</td>
                            <td><span class="badge badge-primary">{{ $assignment->section }}</span></td>
                            <td>{{ $assignment->strand ?? 'N/A' }}</td>
                            <td>{{ $assignment->subject ? $assignment->subject->name : 'No Subject Assigned' }}</td>
                            <td>
                                @if($assignment->subject)
                                    <a href="{{ route('teacher.grades.create', [
                                        'grade_level' => $assignment->grade_level,
                                        'section' => $assignment->section,
                                        'subject_id' => $assignment->subject_id,
                                        'school_year' => $assignment->school_year
                                    ]) }}" class="btn btn-primary">Submit Grades</a>
                                @else
                                    <span style="color: #999; font-size: 12px;">No subject assigned</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                            No assignments found. Please contact the administrator to assign you to a section and subject.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

