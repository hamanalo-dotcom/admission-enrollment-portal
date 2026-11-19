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
            max-width: 800px;
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

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #2c5aa0;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input, select {
            padding: 12px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background-color: #f9fbfd;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
            background-color: white;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
        }

        .btn-secondary {
            background: #f5f7fa;
            color: #666;
            border: 2px solid #d4e5f3;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1>Edit Teacher Assignment</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <form action="{{ route('admin.teacher-assignments.update', $teacherAssignment->id) }}" method="POST" id="assignmentForm">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="teacher_id">Teacher *</label>
                    <select id="teacher_id" name="teacher_id" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $teacherAssignment->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                Teacher {{ $teacher->firstname }} {{ $teacher->lastname }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="grade_level">Grade Level *</label>
                    <select id="grade_level" name="grade_level" required>
                        <option value="">-- Select Grade Level --</option>
                        @foreach($gradeLevels as $grade)
                            <option value="{{ $grade }}" {{ old('grade_level', $teacherAssignment->grade_level) == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="strand-group" style="{{ in_array($teacherAssignment->grade_level, ['Grade 11', 'Grade 12']) ? '' : 'display: none;' }}">
                    <label for="strand">Strand (For Senior High School)</label>
                    <select id="strand" name="strand">
                        <option value="">-- Select Strand --</option>
                        @foreach($strands as $strand)
                            <option value="{{ $strand }}" {{ old('strand', $teacherAssignment->strand) == $strand ? 'selected' : '' }}>{{ $strand }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="tvl-branch-group" style="{{ (in_array($teacherAssignment->grade_level, ['Grade 11', 'Grade 12']) && $teacherAssignment->strand === 'TVL') ? '' : 'display: none;' }}">
                    <label for="tvl_branch">TVL Branch</label>
                    @php $tb = old('tvl_branch', $teacherAssignment->tvl_branch ?? ''); @endphp
                    <select id="tvl_branch" name="tvl_branch">
                        <option value="">-- Select TVL Branch --</option>
                        <option value="CSS" {{ $tb=='CSS' ? 'selected' : '' }}>CSS</option>
                        <option value="EIM" {{ $tb=='EIM' ? 'selected' : '' }}>EIM</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="section">Section *</label>
                    <select id="section" name="section" required>
                        <option value="">-- Select Section --</option>
                        @php
                            $currentGrade = old('grade_level', $teacherAssignment->grade_level);
                            $currentStrand = old('strand', $teacherAssignment->strand);
                            $currentBranch = old('tvl_branch', $teacherAssignment->tvl_branch ?? '');
                            $sectionsForGrade = $sections->where('grade_level', $currentGrade);
                            if (in_array($currentGrade, ['Grade 11', 'Grade 12'])) {
                                $sectionsForGrade = $sectionsForGrade->where('strand', $currentStrand);
                            } else {
                                $sectionsForGrade = $sectionsForGrade->whereNull('strand');
                            }
                            $formatLabel = function($name) use ($currentGrade, $currentStrand, $currentBranch) {
                                if (in_array($currentGrade, ['Grade 11','Grade 12'])) {
                                    if ($currentStrand === 'GAS') return 'GAS - '.$name;
                                    if ($currentStrand === 'TVL') return 'TVL-'.($currentBranch ?: '—').' - '.$name;
                                }
                                return $name;
                            };
                        @endphp
                        @foreach($sectionsForGrade as $section)
                            <option value="{{ $section->name }}" {{ old('section', $teacherAssignment->section) == $section->name ? 'selected' : '' }}>
                                {{ $formatLabel($section->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject_id">Subject *</label>
                    <select id="subject_id" name="subject_id" required>
                        <option value="">-- Select Subject --</option>
                        @php
                            $currentGrade = old('grade_level', $teacherAssignment->grade_level);
                            $currentStrand = old('strand', $teacherAssignment->strand);
                            $subjectsForGrade = collect($subjects)->where('grade_level', $currentGrade);
                            if (in_array($currentGrade, ['Grade 11', 'Grade 12'])) {
                                $subjectsForGrade = $subjectsForGrade->where('strand', $currentStrand);
                            } else {
                                $subjectsForGrade = $subjectsForGrade->whereNull('strand');
                            }
                        @endphp
                        @foreach($subjectsForGrade as $subj)
                            <option value="{{ $subj->id }}" {{ (int) old('subject_id', $teacherAssignment->subject_id) === (int) $subj->id ? 'selected' : '' }}>
                                {{ $subj->name }} @if($subj->code) ({{ $subj->code }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="school_year">School Year *</label>
                    <input type="text" id="school_year" name="school_year" value="{{ old('school_year', $teacherAssignment->school_year) }}" required placeholder="e.g., 2024-2025">
                    <small style="color: #666; font-size: 12px; margin-top: 5px;">Format: YYYY-YYYY (e.g., 2024-2025)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
                    <a href="{{ route('admin.teacher-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('grade_level').addEventListener('change', function() {
            const gradeLevel = this.value;
            const strandGroup = document.getElementById('strand-group');
            const strandSelect = document.getElementById('strand');
            const tvlBranchGroup = document.getElementById('tvl-branch-group');

            if (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12') {
                strandGroup.style.display = 'block';
            } else {
                strandGroup.style.display = 'none';
                strandSelect.value = '';
                tvlBranchGroup.style.display = 'none';
                const tb = document.getElementById('tvl_branch');
                if (tb) tb.value = '';
            }

            updateSections();
        });

        document.getElementById('strand').addEventListener('change', function() {
            updateSections();
            updateSubjects();
            const gradeLevel = document.getElementById('grade_level').value;
            const tvlBranchGroup = document.getElementById('tvl-branch-group');
            if (this.value === 'TVL' && (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12')) {
                tvlBranchGroup.style.display = 'block';
            } else {
                tvlBranchGroup.style.display = 'none';
                document.getElementById('tvl_branch').value = '';
            }
        });

        const tvlBranchEl = document.getElementById('tvl_branch');
        if (tvlBranchEl) {
            tvlBranchEl.addEventListener('change', function() {
                const gradeLevel = document.getElementById('grade_level').value;
                const strand = document.getElementById('strand').value;
                if ((gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12') && strand === 'TVL') {
                    updateSections();
                }
            });
        }

        function sectionLabel(name) {
            const gl = document.getElementById('grade_level').value;
            if (!(gl === 'Grade 11' || gl === 'Grade 12')) return name;
            const st = document.getElementById('strand').value || '';
            if (st === 'GAS') return `GAS - ${name}`;
            if (st === 'TVL') {
                const tb = document.getElementById('tvl_branch') ? document.getElementById('tvl_branch').value : '';
                return tb ? `TVL-${tb} - ${name}` : `TVL - ${name}`;
            }
            return name;
        }

        function updateSections() {
            const gradeLevel = document.getElementById('grade_level').value;
            const strand = document.getElementById('strand').value;
            const sectionSelect = document.getElementById('section');

            sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';

            if (gradeLevel) {
                fetch(`{{ url('/admin/teacher-assignments/sections') }}?grade_level=${gradeLevel}&strand=${strand}&tvl_branch=${encodeURIComponent(document.getElementById('tvl_branch') ? document.getElementById('tvl_branch').value : '')}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section;
                            option.textContent = sectionLabel(section);
                            if (section === '{{ old('section', $teacherAssignment->section) }}') {
                                option.selected = true;
                            }
                            sectionSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching sections:', error);
                        @php
                            // Prepare fallback map by grade and strand
                            $fallback = $sections->groupBy('grade_level')->map(function($group, $grade) {
                                if (in_array($grade, ['Grade 11', 'Grade 12'])) {
                                    return $group->groupBy('strand')->map(function($byStrand) {
                                        return $byStrand->pluck('name')->unique()->values();
                                    });
                                }
                                return $group->whereNull('strand')->pluck('name')->unique()->values();
                            });
                        @endphp
                        const fallback = @json($fallback);
                        const g = document.getElementById('grade_level').value;
                        const s = document.getElementById('strand').value;
                        let names = [];
                        if (g === 'Grade 11' || g === 'Grade 12') {
                            if (fallback[g] && fallback[g][s]) names = fallback[g][s];
                            else {
                                const defaults = {
                                    'Grade 11': { 'GAS': ['ZAMORA'], 'TVL': { 'CSS': ['PADOLINA'], 'EIM': ['ZARA','ALCARAZ'] } },
                                    'Grade 12': { 'GAS': ['AMALTHEA'], 'TVL': { 'CSS': ['TITANIA'], 'EIM': ['PROTEUS'] } }
                                };
                                if (s === 'TVL') {
                                    const tb = document.getElementById('tvl_branch') ? document.getElementById('tvl_branch').value : '';
                                    names = (defaults[g] && defaults[g]['TVL'] && defaults[g]['TVL'][tb]) ? defaults[g]['TVL'][tb] : [];
                                } else {
                                    names = (defaults[g] && defaults[g][s]) ? defaults[g][s] : [];
                                }
                            }
                        } else {
                            if (Array.isArray(fallback[g])) names = fallback[g];
                        }
                        names.forEach(n => {
                            const option = document.createElement('option');
                            option.value = n;
                            option.textContent = sectionLabel(n);
                            if (n === '{{ old('section', $teacherAssignment->section) }}') option.selected = true;
                            sectionSelect.appendChild(option);
                        });
                    });
            }
        }

        function updateSubjects() {
            const gradeLevel = document.getElementById('grade_level').value;
            const strand = document.getElementById('strand').value;
            const subjectSelect = document.getElementById('subject_id');

            subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';

            @php
                $subjectMap = collect($subjects)->groupBy('grade_level')->map(function($group, $grade) {
                    if (in_array($grade, ['Grade 11', 'Grade 12'])) {
                        return $group->groupBy('strand')->map(function($byStrand) {
                            return $byStrand->values()->map(function($s){ return ['id'=>$s->id,'name'=>$s->name,'code'=>$s->code]; });
                        });
                    }
                    return $group->whereNull('strand')->values()->map(function($s){ return ['id'=>$s->id,'name'=>$s->name,'code'=>$s->code]; });
                });
            @endphp
            const subjectMap = @json($subjectMap);
            let list = [];
            if (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12') {
                if (subjectMap[gradeLevel] && subjectMap[gradeLevel][strand]) list = subjectMap[gradeLevel][strand];
            } else {
                if (Array.isArray(subjectMap[gradeLevel])) list = subjectMap[gradeLevel];
            }

            const current = '{{ (int) old('subject_id', $teacherAssignment->subject_id) }}';
            list.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name + (s.code ? ` (${s.code})` : '');
                if (String(s.id) === current) opt.selected = true;
                subjectSelect.appendChild(opt);
            });
        }

        // Initialize subjects on load
        updateSubjects();
    </script>
@endsection

