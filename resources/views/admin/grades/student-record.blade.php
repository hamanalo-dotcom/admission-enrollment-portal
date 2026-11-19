@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { 
            background: #fff; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 8px rgba(0,0,0,.1); 
            margin-bottom: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .student-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .student-info h1 {
            margin: 0;
            color: #2c5aa0;
            font-size: 28px;
        }
        .student-info .email {
            color: #666;
            font-size: 14px;
        }
        .back-btn {
            background: #f5f7fa;
            border: 2px solid #d4e5f3;
            color: #2c5aa0;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover {
            background: #e9ecef;
        }
        .school-year-section {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            margin-bottom: 25px;
        }
        .school-year-header {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 700;
        }
        .subject-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }
        .subject-name {
            font-size: 16px;
            font-weight: 700;
            color: #2c5aa0;
        }
        .subject-average {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        .grading-periods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .grading-period-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        .grading-period-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .grade-value {
            font-size: 24px;
            font-weight: 700;
            color: #2c5aa0;
        }
        .grade-value.low {
            color: #dc3545;
        }
        .grade-value.medium {
            color: #ffc107;
        }
        .grade-value.high {
            color: #28a745;
        }
        .teacher-info {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .summary-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px solid #2196f3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .summary-card h3 {
            margin: 0 0 15px 0;
            color: #1976d2;
            font-size: 18px;
        }
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #2c5aa0;
        }
    </style>

    <div class="main-content">
        <div class="header-section">
            <div class="student-info">
                <div>
                    <h1>{{ $student->firstname }} {{ $student->lastname }}</h1>
                    <div class="email">{{ $student->email }}</div>
                </div>
            </div>
            @php
                $backRoute = request()->routeIs('registrar.*') ? 'registrar.grades.index' : 'admin.grades.index';
            @endphp
            <a href="{{ route($backRoute) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Grades
            </a>
        </div>

        @if($gradesBySchoolYear->isEmpty())
            <div class="empty-state">
                <i class="fas fa-graduation-cap" style="font-size: 64px; color: #d4e5f3; margin-bottom: 20px;"></i>
                <p><strong>No academic records found</strong></p>
                <p style="font-size: 14px; margin-top: 8px;">This student has no grades recorded yet.</p>
            </div>
        @else
            <!-- Overall Summary -->
            @php
                $totalGrades = $gradesBySchoolYear->flatten();
                $overallAverage = $totalGrades->avg('grade_value');
                $totalSubjects = $totalGrades->groupBy('subject_id')->count();
                $totalSchoolYears = $gradesBySchoolYear->count();
            @endphp
            <div class="summary-card">
                <h3><i class="fas fa-chart-line"></i> Academic Summary</h3>
                <div class="summary-stats">
                    <div class="stat-item">
                        <div class="stat-label">Overall Average</div>
                        <div class="stat-value">{{ number_format($overallAverage, 2) }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Total Subjects</div>
                        <div class="stat-value">{{ $totalSubjects }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">School Years</div>
                        <div class="stat-value">{{ $totalSchoolYears }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Total Grades</div>
                        <div class="stat-value">{{ $totalGrades->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Academic Records by School Year -->
            @foreach($gradesBySchoolYear as $schoolYear => $grades)
                <div class="school-year-section">
                    <div class="school-year-header">
                        <i class="fas fa-calendar-alt"></i> School Year: {{ $schoolYear }}
                    </div>

                    @php
                        $gradesBySubject = $grades->groupBy('subject_id');
                        $schoolYearAverage = $grades->avg('grade_value');
                    @endphp

                    <!-- School Year Summary -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: #2c5aa0;">School Year Average:</strong>
                            <span style="font-size: 20px; font-weight: 700; color: #2c5aa0; margin-left: 10px;">
                                {{ number_format($schoolYearAverage, 2) }}
                            </span>
                        </div>
                        <div style="color: #666;">
                            {{ $gradesBySubject->count() }} Subject(s) • {{ $grades->count() }} Grade(s)
                        </div>
                    </div>

                    <!-- Subjects -->
                    @foreach($gradesBySubject as $subjectId => $subjectGrades)
                        @php
                            $subject = $subjectGrades->first()->subject;
                            $subjectAverage = isset($averages[$schoolYear][$subjectId]) ? $averages[$schoolYear][$subjectId] : $subjectGrades->avg('grade_value');
                            $firstGrade = $subjectGrades->first();
                        @endphp
                        <div class="subject-card">
                            <div class="subject-header">
                                <div class="subject-name">
                                    {{ $subject->name }}
                                    @if($firstGrade->strand)
                                        <span style="font-size: 12px; color: #666; font-weight: normal;">({{ $firstGrade->strand }})</span>
                                    @endif
                                    <div style="font-size: 12px; color: #666; font-weight: normal; margin-top: 5px;">
                                        {{ $firstGrade->grade_level }} • Section: {{ $firstGrade->section }}
                                    </div>
                                </div>
                                <div class="subject-average">
                                    Average: {{ number_format($subjectAverage, 2) }}
                                </div>
                            </div>

                            <div class="grading-periods">
                                @foreach($subjectGrades as $grade)
                                    @php
                                        $gradeClass = 'high';
                                        if ($grade->grade_value < 75) {
                                            $gradeClass = 'low';
                                        } elseif ($grade->grade_value < 85) {
                                            $gradeClass = 'medium';
                                        }
                                    @endphp
                                    <div class="grading-period-item">
                                        <div class="grading-period-label">{{ $grade->grading_period }}</div>
                                        <div class="grade-value {{ $gradeClass }}">
                                            {{ number_format($grade->grade_value, 2) }}
                                        </div>
                                        <div class="teacher-info">
                                            <i class="fas fa-chalkboard-teacher"></i> 
                                            {{ $grade->teacher->firstname }} {{ $grade->teacher->lastname }}
                                        </div>
                                        @if($grade->remarks)
                                            <div style="font-size: 11px; color: #666; margin-top: 5px; font-style: italic;">
                                                {{ $grade->remarks }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
@endsection

