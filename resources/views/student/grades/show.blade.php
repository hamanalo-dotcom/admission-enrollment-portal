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
        max-width: 900px;
        margin: 0 auto;
    }

    .header-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section h1 {
        font-size: 32px;
        color: #2c5aa0;
        font-weight: 700;
        margin: 0;
    }

    .grades-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .subject-info {
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .subject-info h2 {
        margin: 0 0 10px 0;
        font-size: 24px;
    }

    .subject-info p {
        margin: 5px 0;
        opacity: 0.9;
    }

    .grades-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .grades-table thead th {
        background: #2c5aa0;
        color: white;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
    }

    .grades-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .grades-table tbody tr:hover {
        background: #f8f9fa;
    }

    .grade-value {
        font-size: 20px;
        font-weight: 700;
        color: #2c5aa0;
    }

    .average-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        text-align: center;
    }

    .average-value {
        font-size: 36px;
        font-weight: 700;
        color: #2c5aa0;
        margin: 10px 0;
    }

    .btn-secondary {
        background: #f5f7fa;
        color: #666;
        padding: 10px 20px;
        border: 2px solid #d4e5f3;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #e9ecef;
    }
</style>

<div class="main-content">
    <header class="header-section">
        <h1>Grade Details</h1>
        <a href="{{ route('student.grades.index') }}" class="btn-secondary">← Back to All Grades</a>
    </header>

    <div class="grades-section">
        <div class="subject-info">
            <h2>{{ $subject->name }}</h2>
            @if($subject->code)
                <p>Code: {{ $subject->code }}</p>
            @endif
            <p>School Year: {{ $schoolYear }}</p>
            <p>Grade Level: {{ $currentEnrollment->grade_level }} - Section: {{ $currentEnrollment->section }}</p>
        </div>

        @if($grades->count() > 0)
            <table class="grades-table">
                <thead>
                    <tr>
                        <th>Grading Period</th>
                        <th>Grade</th>
                        <th>Teacher</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                        <tr>
                            <td>{{ $grade->grading_period }}</td>
                            <td><span class="grade-value">{{ number_format($grade->grade_value, 2) }}</span></td>
                            <td>{{ $grade->teacher->firstname }} {{ $grade->teacher->lastname }}</td>
                            <td>{{ $grade->remarks ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($average)
                <div class="average-section">
                    <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Overall Average</div>
                    <div class="average-value">{{ number_format($average, 2) }}</div>
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 40px; color: #999;">
                <p>No grades available for this subject yet.</p>
                <p style="font-size: 12px;">Your teacher will submit grades here once they're available.</p>
            </div>
        @endif
    </div>
</div>
@endsection

