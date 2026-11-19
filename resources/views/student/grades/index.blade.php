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

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 600;
        color: #2c5aa0;
        font-size: 14px;
        margin-bottom: 8px;
    }

    select {
        padding: 12px;
        border: 2px solid #d4e5f3;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
        background-color: #f9fbfd;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
        transform: translateY(-2px);
    }

    .grades-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .subject-card {
        background: #f8f9fa;
        border: 2px solid #d4e5f3;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .subject-card:hover {
        border-color: #2c5aa0;
        box-shadow: 0 4px 12px rgba(44, 90, 160, 0.1);
    }

    .subject-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .subject-header h3 {
        color: #2c5aa0;
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .grades-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .grade-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        text-align: center;
    }

    .grade-item .period {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }

    .grade-item .value {
        font-size: 24px;
        font-weight: 700;
        color: #2c5aa0;
    }

    .grade-item .average {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    .no-grades {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    @media (max-width: 768px) {
        .filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    <header class="header-section">
        <div>
            <h1>My Grades</h1>
            @if($currentEnrollment)
                <p style="margin: 5px 0 0; color:#526c86;">
                    {{ $isShs ? 'Senior High grading periods: 1st/2nd Semester Midterm & Final.' : 'Junior High grading periods: 1st to 4th Quarter.' }}
                </p>
            @endif
        </div>
    </header>

    @if($currentEnrollment)
        <div class="filter-section">
            <form method="GET" action="{{ route('student.grades.index') }}">
                <div class="filter-row">
                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                    {{ $subj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>School Year</label>
                        <input type="text" name="school_year" value="{{ $schoolYear }}" style="padding: 12px; border: 2px solid #d4e5f3; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        @if($subjects->count() > 0)
            <div class="grades-section">
                @foreach($subjects as $subjectObj)
                    @php
                        $subjectGrades = $gradesBySubject->get($subjectObj->id) ?? collect();
                        $subjectAverage = $subjectGrades->avg('grade_value');
                    @endphp
                    <div class="subject-card">
                        <div class="subject-header">
                            <h3>{{ $subjectObj->name }} @if($subjectObj->code)({{ $subjectObj->code }})@endif</h3>
                            <span style="color: #2c5aa0; font-weight: 600;">
                                Average: {{ $subjectGrades->isNotEmpty() ? number_format($subjectAverage, 2) : 'N/A' }}
                            </span>
                        </div>
                        <div class="grades-grid">
                            @foreach($gradingPeriods as $period)
                                @php
                                    $grade = $subjectGrades->firstWhere('grading_period', $period['key']);
                                @endphp
                                <div class="grade-item">
                                    <div class="period">{{ $period['label'] }}</div>
                                    <div class="value">
                                        @if($grade)
                                            {{ number_format($grade->grade_value, 2) }}
                                        @else
                                            <span style="color: #ccc;">-</span>
                                        @endif
                                    </div>
                                    @if($grade && $grade->remarks)
                                        <div class="average" style="font-size: 10px;">{{ $grade->remarks }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="{{ route('student.grades.show', $subjectObj->id) }}?school_year={{ $schoolYear }}" 
                               style="color: #2c5aa0; text-decoration: none; font-size: 14px; font-weight: 600;">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="grades-section">
                <div class="no-grades">
                    <p>No grades available yet.</p>
                    <p style="font-size: 12px; color: #999;">Grades will appear here once your teachers submit them.</p>
                </div>
            </div>
        @endif
    @else
        <div class="grades-section">
            <div class="no-grades">
                <p>You are not currently enrolled.</p>
                <p style="font-size: 12px; color: #999;">Please complete your enrollment to view grades.</p>
            </div>
        </div>
    @endif
</div>
@endsection

