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

    .form-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #2c5aa0;
        font-size: 14px;
        margin-bottom: 8px;
        display: block;
    }

    input, select {
        padding: 12px;
        border: 2px solid #d4e5f3;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
        background-color: #f9fbfd;
        width: 100%;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #2c5aa0;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        background-color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #f5f7fa;
        color: #666;
        padding: 12px 30px;
        border: 2px solid #d4e5f3;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #e9ecef;
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

    .grade-input {
        width: 100px;
        padding: 8px;
        border: 2px solid #d4e5f3;
        border-radius: 6px;
        text-align: center;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    <header class="header-section">
        <h1>Submit Grades</h1>
        <a href="{{ route('teacher.grades.index') }}" class="btn-secondary">← Back to Grades</a>
    </header>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-section">
        <form action="{{ route('teacher.grades.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="grade_level" value="{{ $gradeLevel }}">
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="school_year" value="{{ $schoolYear }}">

            <div class="form-row">
                <div class="form-group">
                    <label>Subject *</label>
                    <select name="subject_id" id="subject_id" required onchange="updateForm()">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}" {{ $subject && $subject->id == $subj->id ? 'selected' : '' }}>
                                {{ $subj->name }} @if($subj->code)({{ $subj->code }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Grading Period *</label>
                    <select name="grading_period" id="grading_period" required onchange="updateForm()">
                        @foreach($gradingPeriods as $period)
                            <option value="{{ $period }}" {{ $gradingPeriod == $period ? 'selected' : '' }}>
                                {{ $period }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>School Year</label>
                    <input type="text" value="{{ $schoolYear }}" readonly style="background: #e9ecef;">
                </div>

                <div class="form-group">
                    <label>Grade Level & Section</label>
                    <input type="text" value="{{ $gradeLevel }} - {{ $section }}" readonly style="background: #e9ecef;">
                </div>

                @if(isset($isShs) && $isShs && isset($semester))
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="text" value="{{ $semester }}" readonly style="background: #e9ecef;">
                    </div>
                @endif
            </div>

            @if($subject && $enrollments->count() > 0)
                <div style="margin-top: 30px;">
                    <h3 style="color: #2c5aa0; margin-bottom: 20px;">Student Grades</h3>
                    <table class="grades-table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>LRN</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                @php
                                    $student = $enrollment->user;
                                    $existingGrade = $existingGrades[$student->id] ?? null;
                                @endphp
                                <tr id="row-{{ $student->id }}">
                                    <td>
                                        {{ $enrollment->admission->first_name }} 
                                        {{ $enrollment->admission->middle_name }} 
                                        {{ $enrollment->admission->last_name }}
                                        {{ $enrollment->admission->suffix }}
                                    </td>
                                    <td>{{ $enrollment->admission->lrn ?? 'N/A' }}</td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="grades[{{ $student->id }}][grade_value]" 
                                            class="grade-input"
                                            value="{{ $existingGrade ? $existingGrade->grade_value : '' }}"
                                            min="0" 
                                            max="100" 
                                            step="0.01"
                                            placeholder="0.00"
                                            
                                        >
                                        <input type="hidden" name="grades[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                        <input type="hidden" name="grades[{{ $student->id }}][delete]" id="delete-{{ $student->id }}" value="0">
                                    </td>
                                    <td>
                                        <input 
                                            type="text" 
                                            name="grades[{{ $student->id }}][remarks]" 
                                            value="{{ $existingGrade ? $existingGrade->remarks : '' }}"
                                            placeholder="Optional remarks"
                                            style="width: 100%; padding: 8px; border: 2px solid #d4e5f3; border-radius: 6px;"
                                        >
                                    </td>
                                    <td>
                                        <button type="button" class="btn-secondary" style="border-color:#e0a0a0;color:#a33;" onclick="toggleRemove({{ $student->id }})">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="margin-top: 30px; display: flex; gap: 15px;">
                        <button type="submit" class="btn-primary">Save Grades</button>
                        <a href="{{ route('teacher.grades.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </div>
            @elseif($subject && $enrollments->count() == 0)
                <div style="text-align: center; padding: 40px; color: #999;">
                    No enrolled students found in this section.
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #999;">
                    Please select a subject and grading period to continue.
                </div>
            @endif
        </form>
    </div>
</div>

<script>
    function updateForm() {
        const subjectId = document.getElementById('subject_id').value;
        const gradingPeriod = document.getElementById('grading_period').value;
        
        if (subjectId && gradingPeriod) {
            // Create a temporary form to submit GET request
            const tempForm = document.createElement('form');
            tempForm.method = 'GET';
            tempForm.action = '{{ route("teacher.grades.create") }}';
            
            const params = {
                grade_level: '{{ $gradeLevel }}',
                section: '{{ $section }}',
                subject_id: subjectId,
                grading_period: gradingPeriod,
                school_year: '{{ $schoolYear }}'
            };
            
            for (const [key, value] of Object.entries(params)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                tempForm.appendChild(input);
            }
            
            document.body.appendChild(tempForm);
            tempForm.submit();
        }
    }

    function toggleRemove(studentId) {
        const deleteInput = document.getElementById('delete-' + studentId);
        const row = document.getElementById('row-' + studentId);
        const gradeInput = row.querySelector('input[name="grades[' + studentId + '][grade_value]"]');
        const remarksInput = row.querySelector('input[name="grades[' + studentId + '][remarks]"]');

        const isDeleting = deleteInput.value === '1';
        if (isDeleting) {
            // Unmark deletion
            deleteInput.value = '0';
            row.style.opacity = '1';
            gradeInput.disabled = false;
            remarksInput.disabled = false;
        } else {
            // Mark for deletion
            deleteInput.value = '1';
            row.style.opacity = '0.5';
            gradeInput.disabled = true;
            remarksInput.disabled = true;
        }
    }
</script>
@endsection

