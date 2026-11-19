@extends('layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background: linear-gradient(135deg, #4a8fd8 0%, #6ba3e5 50%, #7fb3ed 100%);
            color: #333;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            padding: 30px 0;
        }

        .form-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .form-card {
            background: linear-gradient(135deg, #ffffff 0%, #e8f1f9 50%, #d4e7f5 100%);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(30, 58, 95, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
        }

        .form-card h2 {
            font-size: 28px;
            color: #2c5aa0;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #2c5aa0;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control, .form-control-sm {
            border: 1px solid #d4e5f3;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            color: #2c5aa0;
            background-color: #f9fbfd;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus, .form-control-sm:focus {
            border-color: #2c5aa0;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.15);
            outline: none;
            background-color: white;
        }

        .form-control[readonly] {
            background-color: #f5f7fa;
            color: #666;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
        }

        .alert {
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .back-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #2c5aa0;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #1e4170;
        }

        .form-section-title {
            font-size: 18px;
            color: #2c5aa0;
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a8fd8;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 25px;
            }

            .form-card h2 {
                font-size: 22px;
            }

            .back-link {
                position: relative;
                top: 0;
                right: 0;
                display: inline-block;
                margin-bottom: 15px;
            }
        }
    </style>

    <div class="form-container">
        <div class="form-card">
            <a href="{{ route('student.enrollment.index') }}" class="back-link" title="Back to Enrollments">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2>{{ isset($editing) && $editing ? 'Edit' : 'Student' }} Enrollment Form</h2>
                        @if(isset($editing) && $editing && isset($enrollment) && $enrollment->status !== 'pending')
                            <div class="alert alert-warning">
                                <strong>Note:</strong> You can only edit enrollments with pending status. Current status: <strong>{{ ucfirst($enrollment->status) }}</strong>
                            </div>
                        @endif
                        <form action="{{ isset($editing) && $editing ? route('student.enrollment.update', $enrollment->id) : route('student.enrollment.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($editing) && $editing)
                                @method('PUT')
                            @endif

                            {{-- Validation Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            {{-- <label for="admission_id">Admission Record</label>
                                <select name="admission_id" id="admission_id" class="form-control form-control-sm" required>
                                    <option value="">-- Select Student --</option>
                                    @foreach ($admissions as $admission)
                                        <option value="{{ $admission->id }}">
                                            {{ $admission->first_name }} {{ $admission->last_name }}
                                            ({{ $admission->student_type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <!-- Hidden field for admission_id -->
                            <input type="hidden" name="admission_id" value="{{ $admission->id }}">


                            {{-- Admission Reference --}}
                            @php
                                $adYear = $admission->year_level ?? '';
                                $adStrand = $admission->strand ?? '';
                                
                                // Get active school year - use passed variable from controller
                                $activeSchoolYearValue = isset($activeSchoolYear) && $activeSchoolYear 
                                    ? $activeSchoolYear->school_year 
                                    : null;
                                
                                // Fallback: Query directly if not passed
                                if (!$activeSchoolYearValue) {
                                    $activeSchoolYearObj = \App\Models\SchoolYear::where('is_active', true)->first();
                                    $activeSchoolYearValue = $activeSchoolYearObj ? $activeSchoolYearObj->school_year : null;
                                }
                                
                                // Set default to active school year
                                $defaultSY = $activeSchoolYearValue ?? '';
                            @endphp
                            <div class="row">
                                <!-- Student Info -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Student Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }} {{ $admission->suffix }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Student Type</label>
                                        @php 
                                            $currentStudentType = old('student_type', isset($editing) && isset($enrollment) ? $admission->student_type : $admission->student_type);
                                        @endphp
                                        <select name="student_type" id="student_type" class="form-control form-control-sm" required>
                                            <option value="">-- Select Student Type --</option>
                                            <option value="new" {{ $currentStudentType == 'new' ? 'selected' : '' }}>New</option>
                                            <option value="old" {{ $currentStudentType == 'old' ? 'selected' : '' }}>Old</option>
                                            <option value="continuing" {{ $currentStudentType == 'continuing' ? 'selected' : '' }}>Continuing</option>
                                            <option value="transferee" {{ $currentStudentType == 'transferee' ? 'selected' : '' }}>Transferee</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Enrollment Information --}}
                            <h5 class="form-section-title">Enrollment Details</h5>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Grade Level</label>
                                    @php 
                                        $currentGrade = old('grade_level', isset($editing) && isset($enrollment) ? $enrollment->grade_level : $adYear);
                                    @endphp
                                    <select name="grade_level" id="grade_level" class="form-control form-control-sm" required>
                                        <option value="">-- Select Grade Level --</option>
                                        <option value="Grade 7" {{ $currentGrade == 'Grade 7' ? 'selected' : '' }}>
                                            Grade 7</option>
                                        <option value="Grade 8" {{ $currentGrade == 'Grade 8' ? 'selected' : '' }}>
                                            Grade 8</option>
                                        <option value="Grade 9" {{ $currentGrade == 'Grade 9' ? 'selected' : '' }}>
                                            Grade 9</option>
                                        <option value="Grade 10" {{ $currentGrade == 'Grade 10' ? 'selected' : '' }}>
                                            Grade 10</option>
                                        <option value="Grade 11" {{ $currentGrade == 'Grade 11' ? 'selected' : '' }}>
                                            Grade 11</option>
                                        <option value="Grade 12" {{ $currentGrade == 'Grade 12' ? 'selected' : '' }}>
                                            Grade 12</option>
                                    </select>
                                </div>

                                {{-- STRAND field (hidden by default) --}}
                                <div class="col-md-4 mb-3" id="strand_container" style="display:none;">
                                    <label class="form-label">Strand</label>
                                    @php 
                                        $currentStrand = old('strand', isset($editing) && isset($enrollment) ? $enrollment->strand : $adStrand);
                                    @endphp
                                    <select name="strand" id="strand" class="form-control form-control-sm">
                                        <option value="">-- Select Strand --</option>
                                        <option value="GAS" {{ $currentStrand == 'GAS' ? 'selected' : '' }}>GAS</option>
                                        <option value="TVL" {{ $currentStrand == 'TVL' ? 'selected' : '' }}>TVL</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="tvl_branch_container" style="display:none;">
                                    <label class="form-label">TVL Branch</label>
                                    @php $currentBranch = old('tvl_branch', isset($editing) && isset($enrollment) ? ($enrollment->tvl_branch ?? '') : ($adStrand==='TVL' ? ($admission->tvl_branch ?? '') : '')); @endphp
                                    <select name="tvl_branch" id="tvl_branch" class="form-control form-control-sm">
                                        <option value="">-- Select TVL Branch --</option>
                                        <option value="CSS" {{ $currentBranch == 'CSS' ? 'selected' : '' }}>CSS</option>
                                        <option value="EIM" {{ $currentBranch == 'EIM' ? 'selected' : '' }}>EIM</option>
                                    </select>
                                </div>



                                <div class="col-md-4 mb-3" id="section_container">
                                    <label class="form-label">Section</label>
                                    <input type="text" class="form-control form-control-sm" 
                                        value="{{ isset($editing) && isset($enrollment) && $enrollment->section ? $enrollment->section : 'Auto-assigned on submit' }}" 
                                        readonly>
                                    <small class="text-muted" id="section_note" style="display:none;">Section is assigned by registrar for SHS.</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Year</label>
                                    @if(isset($editing) && $editing)
                                        <input type="text" name="school_year" class="form-control form-control-sm" 
                                            value="{{ old('school_year', $enrollment->school_year) }}" 
                                            readonly>
                                        <small class="text-muted">School year cannot be changed. Contact admin if needed.</small>
                                    @else
                                        <select name="school_year" class="form-control form-control-sm">
                                            @if($activeSchoolYearValue)
                                                <option value="{{ $activeSchoolYearValue }}" selected>
                                                    {{ $activeSchoolYearValue }}
                                                </option>
                                            @else
                                                <option value="">-- No Active School Year --</option>
                                            @endif
                                        </select>
                                        <small class="text-muted">Active school year is automatically selected.</small>
                                    @endif
                                </div>

                                {{-- SEMESTER field (hidden by default) --}}
                                <div class="col-md-4 mb-3" id="semester_container" style="display:none;">
                                    <label class="form-label">Semester</label>
                                    @php 
                                        $currentSemester = old('semester', isset($editing) && isset($enrollment) ? $enrollment->semester : '');
                                    @endphp
                                    <select name="semester" class="form-control form-control-sm">
                                        <option value="">-- Select Semester --</option>
                                        <option value="1st" {{ $currentSemester == '1st' ? 'selected' : '' }}>1st
                                            Semester</option>
                                        <option value="2nd" {{ $currentSemester == '2nd' ? 'selected' : '' }}>2nd
                                            Semester</option>
                                    </select>
                                </div>

                            </div>

                            {{-- Subject Selection for Transferees --}}
                            @if(isset($isTransferee) && $isTransferee)
                                <div id="subject-selection-section">
                                <h5 class="form-section-title">Subject Selection (Transferee)</h5>
                                <div class="alert alert-info" style="background: #e3f2fd; border-left: 4px solid #2196f3; color: #1976d2; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>As a transferee student, please select:</strong>
                                    <ul style="margin: 10px 0 0 20px;">
                                        <li><strong>Subjects to Take:</strong> Select subjects you need to take that you haven't completed yet.</li>
                                        <li><strong>Subjects to Drop:</strong> Select subjects you've already taken at your previous school and want to drop.</li>
                                    </ul>
                                </div>

                                @if(isset($availableSubjects) && $availableSubjects->count() > 0)
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Subjects to Take <span class="text-danger">*</span></label>
                                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #d4e5f3; border-radius: 8px; padding: 10px; background: #f9fbfd;">
                                            @foreach($availableSubjects as $subject)
                                                @php
                                                    $isSelected = false;
                                                    if (isset($editing) && isset($enrollment) && $enrollment->selected_subjects) {
                                                        $isSelected = in_array($subject->id, $enrollment->selected_subjects);
                                                    } elseif (isset($currentSubjects) && isset($currentSubjects['selected'])) {
                                                        $isSelected = in_array($subject->id, $currentSubjects['selected']);
                                                    }
                                                    $isDropped = false;
                                                    if (isset($editing) && isset($enrollment) && $enrollment->dropped_subjects) {
                                                        $isDropped = in_array($subject->id, $enrollment->dropped_subjects);
                                                    } elseif (isset($currentSubjects) && isset($currentSubjects['dropped'])) {
                                                        $isDropped = in_array($subject->id, $currentSubjects['dropped']);
                                                    }
                                                @endphp
                                                <div class="form-check mb-2" style="padding: 8px; border-bottom: 1px solid #e0e0e0;">
                                                    <input class="form-check-input subject-take-checkbox" 
                                                        type="checkbox" 
                                                        name="selected_subjects[]" 
                                                        value="{{ $subject->id }}" 
                                                        id="subject_take_{{ $subject->id }}"
                                                        {{ $isSelected ? 'checked' : '' }}
                                                        onchange="handleSubjectSelection({{ $subject->id }})">
                                                    <label class="form-check-label" for="subject_take_{{ $subject->id }}" style="cursor: pointer; width: 100%;">
                                                        <strong>{{ $subject->name }}</strong>
                                                        @if($subject->code)
                                                            <small class="text-muted">({{ $subject->code }})</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">Select all subjects you need to take.</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Subjects to Drop</label>
                                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #d4e5f3; border-radius: 8px; padding: 10px; background: #f9fbfd;">
                                            @foreach($availableSubjects as $subject)
                                                @php
                                                    $isDropped = false;
                                                    if (isset($editing) && isset($enrollment) && $enrollment->dropped_subjects) {
                                                        $isDropped = in_array($subject->id, $enrollment->dropped_subjects);
                                                    } elseif (isset($currentSubjects) && isset($currentSubjects['dropped'])) {
                                                        $isDropped = in_array($subject->id, $currentSubjects['dropped']);
                                                    }
                                                @endphp
                                                <div class="form-check mb-2" style="padding: 8px; border-bottom: 1px solid #e0e0e0;">
                                                    <input class="form-check-input subject-drop-checkbox" 
                                                        type="checkbox" 
                                                        name="dropped_subjects[]" 
                                                        value="{{ $subject->id }}" 
                                                        id="subject_drop_{{ $subject->id }}"
                                                        {{ $isDropped ? 'checked' : '' }}
                                                        onchange="handleSubjectSelection({{ $subject->id }})">
                                                    <label class="form-check-label" for="subject_drop_{{ $subject->id }}" style="cursor: pointer; width: 100%;">
                                                        <strong>{{ $subject->name }}</strong>
                                                        @if($subject->code)
                                                            <small class="text-muted">({{ $subject->code }})</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">Select subjects you've already completed and want to drop.</small>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning" style="background: #fff3cd; border-left: 4px solid #ffc107; color: #856404; padding: 15px; border-radius: 8px;">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Note:</strong> Please select your grade level and strand (if applicable) first. Subjects will appear here once selected.
                                </div>
                                @endif
                                </div>
                            @endif

                            {{-- Parent/Guardian Update --}}
                            <h5 class="form-section-title">Parent/Guardian Information</h5>
                            {{-- Parents (auto-filled from Admission when available) --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Father's Name</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $admission->father_name ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Father's Occupation</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $admission->father_occupation ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Father's Contact</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $admission->father_contact ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $admission->mother_name ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mother's Occupation</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $admission->mother_occupation ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mother's Contact</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $admission->mother_contact ?? '' }}" readonly>
                                </div>
                            </div>

                            {{-- Guardian (auto-filled from Admission when available; editable by student) --}}
                            @php
                                // Use fallback logic: enrollment guardian -> admission guardian -> father -> mother
                                $defaultGuardianName = '';
                                $defaultGuardianContact = '';
                                
                                if (isset($editing) && isset($enrollment)) {
                                    // Editing: use enrollment guardian, fallback to admission, then parents
                                    $defaultGuardianName = $enrollment->guardian_name ?? $admission->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? '';
                                    $defaultGuardianContact = $enrollment->guardian_contact ?? $admission->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? '';
                                } else {
                                    // Creating: use admission guardian, fallback to parents
                                    $defaultGuardianName = $admission->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? '';
                                    $defaultGuardianContact = $admission->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? '';
                                }
                            @endphp
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Guardian's Name</label>
                                    <input type="text" name="guardian_name" class="form-control form-control-sm"
                                        placeholder="Guardian Name" 
                                        value="{{ old('guardian_name', $defaultGuardianName) }}">
                                    <small class="text-muted">If not provided, will use available parent information.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Guardian's Contact</label>
                                    <input type="text" name="guardian_contact" class="form-control form-control-sm"
                                        placeholder="Guardian Contact No."
                                        value="{{ old('guardian_contact', $defaultGuardianContact) }}">
                                    <small class="text-muted">If not provided, will use available parent contact.</small>
                                </div>
                            </div>

                            {{-- Requirements are collected during Admission. No upload fields needed here. --}}

                            <div style="text-align: right; margin-top: 30px;">
                                <button type="submit" class="btn-primary">
                                    {{ isset($editing) && $editing ? 'Update Enrollment' : 'Submit Enrollment' }}
                                </button>
                            </div>
                        </form>
        </div>
    </div>
@endsection
{{-- JS script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const gradeLevel = document.getElementById('grade_level');
        const strandContainer = document.getElementById('strand_container');
        const semesterContainer = document.getElementById('semester_container');
        const sectionContainer = document.getElementById('section_container');
        const sectionNote = document.getElementById('section_note');

        function toggleFields() {
            if (gradeLevel.value === 'Grade 11' || gradeLevel.value === 'Grade 12') {
                strandContainer.style.display = 'block';
                semesterContainer.style.display = 'block';
                const strandSelect = document.getElementById('strand');
                const tvlBranchContainer = document.getElementById('tvl_branch_container');
                const tvlBranchSelect = document.getElementById('tvl_branch');
                if (strandSelect && strandSelect.value === 'TVL') {
                    tvlBranchContainer.style.display = 'block';
                    tvlBranchSelect && tvlBranchSelect.setAttribute('required','required');
                } else {
                    tvlBranchContainer.style.display = 'none';
                    tvlBranchSelect && (tvlBranchSelect.removeAttribute('required'), tvlBranchSelect.value='');
                }
                // Hide section input for SHS; registrar assigns
                sectionContainer.style.display = 'none';
                sectionNote.style.display = 'inline-block';
            } else {
                strandContainer.style.display = 'none';
                semesterContainer.style.display = 'none';
                // Clear values when hidden (for Grade 7-10)
                const strandSelect = strandContainer.querySelector('select');
                const semesterSelect = semesterContainer.querySelector('select');
                if (strandSelect) strandSelect.value = '';
                if (semesterSelect) semesterSelect.value = '';
                const tvlBranchContainer = document.getElementById('tvl_branch_container');
                const tvlBranchSelect = document.getElementById('tvl_branch');
                tvlBranchContainer.style.display = 'none';
                tvlBranchSelect && (tvlBranchSelect.removeAttribute('required'), tvlBranchSelect.value='');
                // Show section input for JHS
                sectionContainer.style.display = 'block';
                sectionNote.style.display = 'none';
            }
            
            // Show/hide subject selection for transferees
            const studentTypeSelect = document.getElementById('student_type');
            const subjectSection = document.getElementById('subject-selection-section');
            if (subjectSection && studentTypeSelect) {
                if (studentTypeSelect.value === 'transferee' && gradeLevel.value) {
                    subjectSection.style.display = 'block';
                } else {
                    subjectSection.style.display = 'none';
                }
            }
        }

        // Handle student type change
        function handleStudentTypeChange() {
            const studentType = document.getElementById('student_type').value;
            const subjectSection = document.getElementById('subject-selection-section');
            
            // Show/hide subject selection based on student type
            if (subjectSection) {
                if (studentType === 'transferee') {
                    const gradeLevel = document.getElementById('grade_level').value;
                    if (gradeLevel) {
                        subjectSection.style.display = 'block';
                    } else {
                        subjectSection.style.display = 'none';
                    }
                } else {
                    subjectSection.style.display = 'none';
                    // Clear subject selections
                    document.querySelectorAll('input[name="selected_subjects[]"]').forEach(cb => cb.checked = false);
                    document.querySelectorAll('input[name="dropped_subjects[]"]').forEach(cb => cb.checked = false);
                }
            }
        }

        // Load subjects dynamically for transferees based on grade level and strand
        function loadSubjectsForTransferee() {
            const gradeLevel = document.getElementById('grade_level').value;
            const strand = document.getElementById('strand') ? document.getElementById('strand').value : '';
            const studentType = document.getElementById('student_type').value;
            
            if (studentType !== 'transferee' || !gradeLevel) {
                return;
            }
            
            // The subjects are already loaded on page load, so we just need to show/hide the section
            const subjectSection = document.getElementById('subject-selection-section');
            if (subjectSection) {
                subjectSection.style.display = 'block';
            }
        }

        // run on page load (for edit form)
        toggleFields();
        // run on change
        if (gradeLevel) {
            gradeLevel.addEventListener('change', function() {
                toggleFields();
                handleStudentTypeChange();
            });
        }
        const strandSelect = document.getElementById('strand');
        if (strandSelect) {
            strandSelect.addEventListener('change', function() {
                toggleFields();
                handleStudentTypeChange();
            });
        }
        
        // Handle student type change
        const studentTypeSelect = document.getElementById('student_type');
        if (studentTypeSelect) {
            studentTypeSelect.addEventListener('change', handleStudentTypeChange);
        }

        // Handle subject selection for transferees
        function handleSubjectSelection(subjectId) {
            const takeCheckbox = document.getElementById('subject_take_' + subjectId);
            const dropCheckbox = document.getElementById('subject_drop_' + subjectId);
            
            // If a subject is selected to take, uncheck it from drop
            if (takeCheckbox && takeCheckbox.checked && dropCheckbox) {
                dropCheckbox.checked = false;
            }
            
            // If a subject is selected to drop, uncheck it from take
            if (dropCheckbox && dropCheckbox.checked && takeCheckbox) {
                takeCheckbox.checked = false;
            }
        }

        // Validate that at least one subject is selected to take for transferees
        const enrollmentForm = document.querySelector('form');
        if (enrollmentForm) {
            enrollmentForm.addEventListener('submit', function(e) {
                const studentType = document.getElementById('student_type').value;
                if (studentType === 'transferee') {
                    const selectedSubjects = document.querySelectorAll('input[name="selected_subjects[]"]:checked');
                    if (selectedSubjects.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Subjects Selected',
                            text: 'Please select at least one subject to take.',
                            confirmButtonColor: '#2c5aa0'
                        });
                        return false;
                    }
                }
            });
        }
    });
</script>
