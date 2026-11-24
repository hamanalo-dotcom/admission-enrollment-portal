@extends('layout.app')

@section('content')
@php
    $isRegistrar = request()->routeIs('registrar.*');
    $indexRoute = $isRegistrar ? route('registrar.teacher-assignments.index') : route('admin.teacher-assignments.index');
    $storeRoute = $isRegistrar ? route('registrar.teacher-assignments.store') : route('admin.teacher-assignments.store');
    $sectionsUrl = $isRegistrar ? route('registrar.teacher-assignments.sections') : route('admin.teacher-assignments.sections');
@endphp
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
            <h1>Assign Teacher to Section</h1>
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
            <form action="{{ $storeRoute }}" method="POST" id="assignmentForm">
                @csrf

                <div class="form-group">
                    <label for="teacher_id">Teacher *</label>
                    <select id="teacher_id" name="teacher_id" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                            <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="strand-group" style="display: none;">
                    <label for="strand">Strand (For Senior High School)</label>
                    <select id="strand" name="strand">
                        <option value="">-- Select Strand --</option>
                        @foreach($strands as $strand)
                            <option value="{{ $strand }}" {{ old('strand') == $strand ? 'selected' : '' }}>{{ $strand }}</option>
                        @endforeach
                    </select>
                </div>

        <div class="form-group" id="tvl-branch-group" style="display: none;">
            <label for="tvl_branch">TVL Branch</label>
            <select id="tvl_branch" name="tvl_branch">
                <option value="">-- Select TVL Branch --</option>
                <option value="CSS" {{ old('tvl_branch') == 'CSS' ? 'selected' : '' }}>CSS</option>
                <option value="EIM" {{ old('tvl_branch') == 'EIM' ? 'selected' : '' }}>EIM</option>
            </select>
        </div>

                <div class="form-group">
                    <label for="subject_id">Subject *</label>
                    <select id="subject_id" name="subject_id" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option 
                                value="{{ $subject->id }}" 
                                data-grade="{{ $subject->grade_level ?? '' }}"
                                data-strand="{{ $subject->strand ?? '' }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                            >
                                {{ $subject->name }} @if($subject->code) ({{ $subject->code }}) @endif
                                @if($subject->grade_level) - {{ $subject->grade_level }} @endif
                                @if($subject->strand) - {{ $subject->strand }} @endif
                            </option>
                        @endforeach
                    </select>
                    <small style="color:#666;font-size:12px;">Subjects are filtered by Grade Level and Strand.</small>
                </div>

                <div class="form-group">
                    <label for="sections">Sections *</label>
                    <div id="sections-container" style="border: 2px solid #d4e5f3; border-radius: 8px; padding: 15px; min-height: 100px; max-height: 200px; overflow-y: auto; background-color: #f9fbfd;">
                        <p style="color: #999; font-size: 13px; margin: 0;">Select grade level first to see available sections</p>
                    </div>
                    <input type="hidden" name="sections" id="sections-input" value="@if(old('sections')){{ is_array(old('sections')) ? json_encode(old('sections')) : old('sections') }}@else[]@endif" required>
                    <small style="color: #666; font-size: 12px; margin-top: 5px;">You can select multiple sections for this teacher</small>
                </div>

                <div class="form-group">
                    <label for="school_year">School Year *</label>
                    <input type="text" id="school_year" name="school_year" value="{{ old('school_year', $currentSchoolYear) }}" required placeholder="e.g., 2024-2025">
                    <small style="color: #666; font-size: 12px; margin-top: 5px;">Format: YYYY-YYYY (e.g., 2024-2025)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Assign Teacher</button>
                    <a href="{{ $indexRoute }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pre-load sections data for fallback
        @php
            $sectionsByGradeAndStrand = $sections->groupBy('grade_level')->map(function($group, $grade) {
                if (in_array($grade, ['Grade 11', 'Grade 12'])) {
                    return $group->groupBy('strand')->map(function($byStrand) {
                        return $byStrand->pluck('name')->unique()->values();
                    });
                }
                return $group->whereNull('strand')->pluck('name')->unique()->values();
            });
        @endphp
        const sectionsData = @json($sectionsByGradeAndStrand);
        const defaultSections = {
            'Grade 7': ['HOPE','FAITH','LOVE','WISDOM'],
            'Grade 8': ['CASILI','LANDONG','MORA','SAN JUAN'],
            'Grade 9': ['ACACIA','LAUAN','NARRA'],
            'Grade 10': ['BERYL','DIAMOND','JADE'],
            'Grade 11': { 'GAS': ['ZAMORA'], 'TVL': { 'CSS': ['PADOLINA'], 'EIM': ['ZARA','ALCARAZ'] } },
            'Grade 12': { 'GAS': ['AMALTHEA'], 'TVL': { 'CSS': ['TITANIA'], 'EIM': ['PROTEUS'] } }
        };

        const gradeLevelEl = document.getElementById('grade_level');
        const strandEl = document.getElementById('strand');
        const subjectEl = document.getElementById('subject_id');

        function filterSubjects() {
            const gl = gradeLevelEl.value;
            const st = strandEl.value;
            Array.from(subjectEl.options).forEach((opt, idx) => {
                if (idx === 0) { opt.hidden = false; return; }
                const sGrade = opt.getAttribute('data-grade') || '';
                const sStrand = opt.getAttribute('data-strand') || '';
                let show = true;
                if (gl === 'Grade 11' || gl === 'Grade 12') {
                    // SHS: match grade and strand (subject may be generic with empty)
                    if (sGrade && sGrade !== gl) show = false;
                    if (st) {
                        if (sStrand && sStrand !== st) show = false;
                    } else {
                        // if strand not chosen yet, allow generic or any; do not hide aggressively
                        show = sGrade ? (sGrade === gl) : true;
                    }
                } else {
                    // JHS: grade must match if set; strand must be empty
                    if (sStrand) show = false;
                    if (sGrade && sGrade !== gl) show = false;
                }
                opt.hidden = !show;
            });
            // Reset selection if hidden
            if (subjectEl.selectedOptions.length && subjectEl.selectedOptions[0].hidden) {
                subjectEl.value = '';
            }
        }

        gradeLevelEl.addEventListener('change', function() {
            const gradeLevel = this.value;
            const strandGroup = document.getElementById('strand-group');
            const tvlBranchGroup = document.getElementById('tvl-branch-group');

            // Show/hide strand for SHS
            if (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12') {
                strandGroup.style.display = 'block';
            } else {
                strandGroup.style.display = 'none';
                strandEl.value = '';
                tvlBranchGroup.style.display = 'none';
                document.getElementById('tvl_branch').value = '';
            }

            // Update sections based on grade level
            const sectionsContainer = document.getElementById('sections-container');
            
            if (gradeLevel) {
                const url = `{{ $sectionsUrl }}?grade_level=${encodeURIComponent(gradeLevel)}&strand=${encodeURIComponent(strandEl.value || '')}&tvl_branch=${encodeURIComponent(document.getElementById('tvl_branch').value || '')}`;
                console.log('Fetching sections from:', url);
                
                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received sections data:', data);
                        if (data && Array.isArray(data) && data.length > 0) {
                            renderSectionCheckboxes(data, sectionsContainer);
                            console.log(`Loaded ${data.length} sections`);
                        } else {
                            console.log('No sections from API, using fallback');
                            loadFallbackSections(gradeLevel, strandEl.value || '', sectionsContainer);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching sections:', error);
                        // Fallback: show sections from pre-loaded data
                        loadFallbackSections(gradeLevel, strandEl.value || '', sectionsContainer);
                    });
            } else {
                sectionsContainer.innerHTML = '<p style="color: #999; font-size: 13px; margin: 0;">Select grade level first to see available sections</p>';
                updateSelectedSections();
            }

            filterSubjects();
        });

        function formatSectionLabel(sectionName) {
            const gl = gradeLevelEl.value;
            if (!(gl === 'Grade 11' || gl === 'Grade 12')) return sectionName;
            const st = strandEl.value || '';
            if (st === 'GAS') {
                return `GAS - ${sectionName}`;
            }
            if (st === 'TVL') {
                const tb = document.getElementById('tvl_branch').value || '';
                return tb ? `TVL-${tb} - ${sectionName}` : `TVL - ${sectionName}`;
            }
            return sectionName;
        }

        function loadFallbackSections(gradeLevel, strand, sectionsContainer) {
            let sections = [];
            const data = sectionsData[gradeLevel];
            if (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12') {
                if (data && strand && data[strand] && Array.isArray(data[strand])) {
                    sections = data[strand];
                } else {
                    const def = defaultSections[gradeLevel];
                    if (strand === 'TVL') {
                        const tb = document.getElementById('tvl_branch').value || '';
                        sections = (def && def['TVL'] && def['TVL'][tb]) ? def['TVL'][tb] : [];
                    } else {
                        sections = (def && def[strand]) ? def[strand] : [];
                    }
                }
            } else {
                if (Array.isArray(data) && data.length) {
                    sections = data;
                } else {
                    sections = defaultSections[gradeLevel] || [];
                }
            }
            renderSectionCheckboxes(sections, sectionsContainer);
        }

        function renderSectionCheckboxes(sectionsData, container) {
            container.innerHTML = '';
            
            if (!sectionsData || sectionsData.length === 0) {
                container.innerHTML = '<p style="color: #999; font-size: 13px; margin: 0;">No sections available</p>';
                updateSectionsInput([]);
                return;
            }

            const oldSections = JSON.parse(document.getElementById('sections-input').value || '[]');
            
            sectionsData.forEach(sectionName => {
                const label = document.createElement('label');
                label.style.cssText = 'display: flex; align-items: center; padding: 10px; margin-bottom: 8px; background: white; border-radius: 6px; cursor: pointer; transition: background-color 0.2s;';
                label.onmouseover = function() { this.style.backgroundColor = '#f0f7ff'; };
                label.onmouseout = function() { this.style.backgroundColor = 'white'; };
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = sectionName; // keep raw section name as value
                checkbox.name = 'section_checkbox[]';
                checkbox.style.cssText = 'width: 18px; height: 18px; margin-right: 12px; cursor: pointer;';
                
                // Check if this section was previously selected
                if (oldSections.includes(sectionName)) {
                    checkbox.checked = true;
                }
                
                checkbox.addEventListener('change', updateSelectedSections);
                
                const span = document.createElement('span');
                span.textContent = formatSectionLabel(sectionName);
                span.style.cssText = 'font-size: 14px; color: #2c5aa0; font-weight: 500;';
                
                label.appendChild(checkbox);
                label.appendChild(span);
                container.appendChild(label);
            });
            
            updateSelectedSections();
        }

        function updateSelectedSections() {
            const checkboxes = document.querySelectorAll('input[name="section_checkbox[]"]:checked');
            const selectedSections = Array.from(checkboxes).map(cb => cb.value);
            document.getElementById('sections-input').value = JSON.stringify(selectedSections);
            
            // Update required validation
            const sectionsInput = document.getElementById('sections-input');
            if (selectedSections.length === 0) {
                sectionsInput.setCustomValidity('Please select at least one section');
            } else {
                sectionsInput.setCustomValidity('');
            }
        }

        strandEl.addEventListener('change', function() {
            const gradeLevel = gradeLevelEl.value;
            const strand = this.value;
            const tvlBranchGroup = document.getElementById('tvl-branch-group');
            if (strand === 'TVL' && (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12')) {
                tvlBranchGroup.style.display = 'block';
            } else {
                tvlBranchGroup.style.display = 'none';
                document.getElementById('tvl_branch').value = '';
            }
            const sectionsContainer = document.getElementById('sections-container');

            if (gradeLevel && (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12')) {
                const url = `{{ url('/admin/teacher-assignments/sections') }}?grade_level=${encodeURIComponent(gradeLevel)}&strand=${encodeURIComponent(strand || '')}&tvl_branch=${encodeURIComponent(document.getElementById('tvl_branch').value || '')}`;
                console.log('Fetching sections (strand change) from:', url);
                
                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received sections data (strand):', data);
                        if (data && Array.isArray(data) && data.length > 0) {
                            renderSectionCheckboxes(data, sectionsContainer);
                            console.log(`Loaded ${data.length} sections (strand)`);
                        } else {
                            console.log('No sections from API (strand), using fallback');
                            loadFallbackSections(gradeLevel, strand, sectionsContainer);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching sections (strand):', error);
                        loadFallbackSections(gradeLevel, strand, sectionsContainer);
                    });
            }
            filterSubjects();
        });

        const tvlBranchEl = document.getElementById('tvl_branch');
        if (tvlBranchEl) {
            tvlBranchEl.addEventListener('change', function() {
                const gradeLevel = gradeLevelEl.value;
                const strand = strandEl.value;
                if (gradeLevel && (gradeLevel === 'Grade 11' || gradeLevel === 'Grade 12') && strand === 'TVL') {
                    const sectionsContainer = document.getElementById('sections-container');
                    const url = `{{ url('/admin/teacher-assignments/sections') }}?grade_level=${encodeURIComponent(gradeLevel)}&strand=${encodeURIComponent(strand || '')}&tvl_branch=${encodeURIComponent(this.value || '')}`;
                    console.log('Fetching sections (tvl_branch change) from:', url);
                    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }})
                        .then(r => r.json())
                        .then(data => {
                            if (data && Array.isArray(data) && data.length > 0) {
                                renderSectionCheckboxes(data, sectionsContainer);
                            } else {
                                loadFallbackSections(gradeLevel, strand, sectionsContainer);
                            }
                        })
                        .catch(() => loadFallbackSections(gradeLevel, strand, sectionsContainer));
                }
            });
        }

        // Initialize subject options on first load
        filterSubjects();
    </script>
@endsection

