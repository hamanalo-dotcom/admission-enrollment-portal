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
        max-width: 1200px;
        margin: 0 auto;
    }

    .header-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .header-section h1 {
        font-size: 32px;
        color: #2c5aa0;
        font-weight: 700;
        margin: 0;
    }

    .info-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .info-section h5 {
        color: #2c5aa0;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .subjects-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .subjects-section h5 {
        color: #2c5aa0;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        color: white;
    }

    .table thead th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
    }

    .table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f9fbfd;
    }

    .table tbody td {
        padding: 15px;
        font-size: 14px;
    }

    .alert {
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-info {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
        color: #1976d2;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .badge-warning {
        background-color: #fff3e0;
        color: #e65100;
    }
</style>

<div class="main-content">
    <div class="header-section">
        <h1><i class="fas fa-book"></i> My Subjects</h1>
    </div>

    @if($message)
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> {{ $message }}
        </div>
    @endif

    @if(isset($admission) && $admission)
        <!-- Admission Information -->
        <div class="info-section">
            <h5>Admission Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Year Level:</strong> {{ $admission->year_level }}</p>
                    @php
                        $isShs = in_array($admission->year_level, ['Grade 11', 'Grade 12']);
                    @endphp
                    @if($isShs)
                        <p><strong>Strand:</strong> {{ $admission->strand ?? 'N/A' }}</p>
                        @if(($admission->strand ?? null) === 'TVL')
                            <p><strong>TVL Branch:</strong> {{ $admission->tvl_branch ?? 'N/A' }}</p>
                        @endif
                    @endif
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $admission->status === 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($admission->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Subjects List -->
        <div class="subjects-section">
            <h5>Available Subjects</h5>
            @if($subjects && $subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td><strong>{{ $subject->code ?? '-' }}</strong></td>
                                    <td>{{ $subject->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No subjects available for your year level and strand.
                </div>
            @endif
        </div>
    @elseif($enrollment)
        <!-- Enrollment Information -->
        <div class="info-section">
            <h5>Enrollment Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Grade Level:</strong> {{ $enrollment->grade_level }}</p>
                    @php
                        $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
                    @endphp
                    @if($isShs)
                        <p><strong>Strand:</strong> {{ $enrollment->strand ?? 'N/A' }}</p>
                        @if(($enrollment->strand ?? null) === 'TVL')
                            <p><strong>TVL Branch:</strong> {{ $enrollment->tvl_branch ?? 'N/A' }}</p>
                        @endif
                    @endif
                </div>
                <div class="col-md-6">
                    <p><strong>Section:</strong> {{ $enrollment->section ?? 'Not Assigned' }}</p>
                    <p><strong>School Year:</strong> {{ $enrollment->school_year }}</p>
                    @if($isShs)
                        <p><strong>Semester:</strong> {{ $enrollment->semester ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $enrollment->status === 'enrolled' ? 'success' : 'warning' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Subjects List -->
        <div class="subjects-section">
            <h5>Assigned Subjects</h5>
            @if($subjects && $subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                @php
                                    // Get teacher assignment for this subject and section
                                    $assignment = $subject->teacherAssignments
                                        ->where('grade_level', $enrollment->grade_level)
                                        ->where('section', $enrollment->section)
                                        ->when($enrollment->strand, function($collection) use ($enrollment) {
                                            return $collection->filter(function($item) use ($enrollment) {
                                                return ($item->strand === $enrollment->strand || $item->strand === null);
                                            });
                                        })
                                        ->when($enrollment->tvl_branch, function($collection) use ($enrollment) {
                                            return $collection->filter(function($item) use ($enrollment) {
                                                return ($item->tvl_branch === $enrollment->tvl_branch || $item->tvl_branch === null);
                                            });
                                        })
                                        ->first();
                                    $teacher = $assignment && $assignment->teacher ? $assignment->teacher : null;
                                @endphp
                                <tr>
                                    <td><strong>{{ $subject->code ?? '-' }}</strong></td>
                                    <td>{{ $subject->name }}</td>
                                    <td>
                                        @if($teacher)
                                            <strong>{{ $teacher->name ?? ($teacher->firstname . ' ' . $teacher->lastname) }}</strong>
                                            @if($teacher->email)
                                                <br><small class="text-muted">{{ $teacher->email }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted"><i class="fas fa-user-times"></i> Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No subjects have been assigned yet. Subjects will appear here once they are assigned to your section.
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

