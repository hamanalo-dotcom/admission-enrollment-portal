@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2>Student Admission Form</h2>
                        <div class="position-absolute top-0 end-0 mt-4 me-4">
                            <a href="{{ route('registrar.admissions.index') }}" class="text-dark" style="font-size: 1.25rem;">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                        <br>

                        @php
                            $editing = isset($admission);
                        @endphp
                        <form action="{{ $editing ? route('registrar.admissions.update', $admission->id) : route('registrar.admissions.store') }}" method="POST">
                            @csrf
                            @if($editing)
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

                            <!-- Student Information -->
                            <h5 class="mb-3 text-primary">Student Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="student_id"><strong>Student ID</strong></label>
                                    <input type="text" name="student_id" id="student_id"
                                        class="form-control form-control-sm" value="{{ old('student_id', isset($admission) ? ($admission->student_id ?? '') : '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email"><strong>Email</strong></label>
                                    <input type="email" name="email" id="email" class="form-control form-control-sm"
                                        value="{{ old('email', isset($admission) ? ($admission->email ?? '') : '') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="first_name"><strong>First Name</strong></label>
                                    <input type="text" name="first_name" id="first_name"
                                        class="form-control form-control-sm" value="{{ old('first_name', isset($admission) ? ($admission->first_name ?? '') : '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="middle_name"><strong>Middle Name</strong></label>
                                    <input type="text" name="middle_name" id="middle_name"
                                        class="form-control form-control-sm" value="{{ old('middle_name', isset($admission) ? ($admission->middle_name ?? '') : '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name"><strong>Last Name</strong></label>
                                    <input type="text" name="last_name" id="last_name"
                                        class="form-control form-control-sm" value="{{ old('last_name', isset($admission) ? ($admission->last_name ?? '') : '') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="suffix"><strong>Suffix (Optional)</strong></label>
                                    <input type="text" name="suffix" id="suffix" class="form-control form-control-sm"
                                        placeholder="e.g. Jr., Sr., III" value="{{ old('suffix', isset($admission) ? ($admission->suffix ?? '') : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="contact"><strong>Contact Number</strong></label>
                                    <input type="text" name="contact" id="contact" class="form-control form-control-sm"
                                        value="{{ old('contact', isset($admission) ? ($admission->contact ?? $admission->contact_no ?? '') : '') }}" required>
                                </div>
                            </div>

                            <h5 class="mb-3 text-primary">Address & Contact Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label><strong>Region</strong></label>
                                    <select name="region_code" id="region" class="form-control form-control-sm" required>
                                        <option value="">-- Select Region --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>Province</strong></label>
                                    <select name="province_code" id="province" class="form-control form-control-sm" required>
                                        <option value="">-- Select Province --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>City/Municipality</strong></label>
                                    <select name="city_code" id="city" class="form-control form-control-sm" required>
                                        <option value="">-- Select City/Municipality --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>Barangay</strong></label>
                                    <select name="barangay_code" id="barangay" class="form-control form-control-sm" required>
                                        <option value="">-- Select Barangay --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Guardian Information -->
                            <h5 class="mb-3 text-primary">uardian Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="guardian_name"><strong>Guardian Full Name</strong></label>
                                    <input type="text" name="guardian_name" id="guardian_name"
                                        class="form-control form-control-sm" value="{{ old('guardian_name', isset($admission) ? ($admission->guardian_name ?? '') : '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian_contact"><strong>Guardian Contact Number</strong></label>
                                    <input type="text" name="guardian_contact" id="guardian_contact"
                                        class="form-control form-control-sm" value="{{ old('guardian_contact', isset($admission) ? ($admission->guardian_contact ?? '') : '') }}" required>
                                </div>
                            </div>

                            <!-- Academic Info -->
                            <h5 class="mb-3 text-primary">Academic Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="last_school_attended"><strong>Previous School</strong></label>
                                    <input type="text" name="last_school_attended" id="last_school_attended"
                                        class="form-control form-control-sm" value="{{ old('last_school_attended', isset($admission) ? ($admission->last_school_attended ?? $admission->previous_school ?? '') : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="year"><strong>Year</strong></label>
                                    @php 
                                        $year = old('year', isset($admission) ? ($admission->year ?? $admission->year_level ?? '') : '');
                                        $isShs = in_array($year, ['Grade 11', 'Grade 12']);
                                    @endphp
                                    <select name="year" id="year" class="form-control form-control-sm" required>
                                        <option value="">-- Select Year --</option>
                                        <option value="Grade 7" {{ $year=='Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                        <option value="Grade 8" {{ $year=='Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                        <option value="Grade 9" {{ $year=='Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                        <option value="Grade 10" {{ $year=='Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                        <option value="Grade 11" {{ $year=='Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                        <option value="Grade 12" {{ $year=='Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3" id="strand_container" style="display: {{ $isShs ? 'block' : 'none' }};">
                                    <label for="strand"><strong>Strand <span class="text-muted">(Required for SHS)</span></strong></label>
                                    @php $st = old('strand', isset($admission) ? ($admission->strand ?? '') : ''); @endphp
                                    <select name="strand" id="strand" class="form-control form-control-sm" {{ $isShs ? 'required' : '' }}>
                                        <option value="">-- Select Strand --</option>
                                        <option value="GAS" {{ $st=='GAS' ? 'selected' : '' }}>GAS</option>
                                        <option value="TVL" {{ $st=='TVL' ? 'selected' : '' }}>TVL</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3" id="tvl_branch_container" style="display:none;">
                                    <label for="tvl_branch"><strong>TVL Branch</strong></label>
                                    @php $tb = old('tvl_branch', isset($admission) ? ($admission->tvl_branch ?? '') : ''); @endphp
                                    <select name="tvl_branch" id="tvl_branch" class="form-control form-control-sm">
                                        <option value="">-- Select TVL Branch --</option>
                                        <option value="CSS" {{ $tb=='CSS' ? 'selected' : '' }}>CSS</option>
                                        <option value="EIM" {{ $tb=='EIM' ? 'selected' : '' }}>EIM</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex float-end">
                                <button type="submit" class="btn btn-sm btn-primary">{{ $editing ? 'Update' : 'Save' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const base = window.location.origin;
    const regionSelect = document.getElementById('region');
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');

    function resetSelect(sel, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
    }

    // Load regions
    resetSelect(regionSelect, '-- Select Region --');
    fetch(base + '/api/regions/all')
        .then(r => r.json())
        .then(resp => (resp.data || resp))
        .then(list => {
            list.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.code; opt.textContent = r.name; regionSelect.appendChild(opt);
            });
        });

    regionSelect.addEventListener('change', function () {
        const code = this.value;
        resetSelect(provinceSelect, 'Loading...'); resetSelect(citySelect, '-- Select City/Municipality --'); resetSelect(barangaySelect, '-- Select Barangay --');
        provinceSelect.disabled = citySelect.disabled = barangaySelect.disabled = true;
        fetch(base + `/api/provinces?region_code=${encodeURIComponent(code)}`)
            .then(r => r.json()).then(resp => (resp.data || resp)).then(list => {
                resetSelect(provinceSelect, '-- Select Province --');
                list.forEach(p => { const o = document.createElement('option'); o.value = p.code; o.textContent = p.name; provinceSelect.appendChild(o); });
                provinceSelect.disabled = false;
            });
    });

    provinceSelect.addEventListener('change', function () {
        const code = this.value;
        resetSelect(citySelect, 'Loading...'); resetSelect(barangaySelect, '-- Select Barangay --');
        citySelect.disabled = barangaySelect.disabled = true;
        fetch(base + `/api/cities?province_code=${encodeURIComponent(code)}`)
            .then(r => r.json()).then(resp => (resp.data || resp)).then(list => {
                resetSelect(citySelect, '-- Select City/Municipality --');
                list.forEach(c => { const o = document.createElement('option'); o.value = c.code; o.textContent = c.name; citySelect.appendChild(o); });
                citySelect.disabled = false;
            });
    });

    citySelect.addEventListener('change', function () {
        const code = this.value;
        resetSelect(barangaySelect, 'Loading...');
        barangaySelect.disabled = true;
        fetch(base + `/api/barangays?city_code=${encodeURIComponent(code)}`)
            .then(r => r.json()).then(resp => (resp.data || resp)).then(list => {
                resetSelect(barangaySelect, '-- Select Barangay --');
                list.forEach(b => { const o = document.createElement('option'); o.value = b.code; o.textContent = b.name; barangaySelect.appendChild(o); });
                barangaySelect.disabled = false;
            });
    });
    
    // Toggle strand field based on year level
    const yearSelect = document.getElementById('year');
    const strandContainer = document.getElementById('strand_container');
    
    function toggleStrand() {
        if (!yearSelect || !strandContainer) return;
        
        const strandSelect = strandContainer.querySelector('select[name="strand"]');
        if (yearSelect.value === 'Grade 11' || yearSelect.value === 'Grade 12') {
            strandContainer.style.display = 'block';
            if (strandSelect) {
                strandSelect.setAttribute('required', 'required');
            }
        } else {
            strandContainer.style.display = 'none';
            if (strandSelect) {
                strandSelect.removeAttribute('required');
                strandSelect.value = '';
            }
        }
    }
    
    // Run on load
    toggleStrand();
    
    // Run on change
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            toggleStrand();
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const strand = document.getElementById('strand');
    const tvlBranchContainer = document.getElementById('tvl_branch_container');
    const tvlBranch = document.getElementById('tvl_branch');
    function toggleTvlBranch() {
        if (!strand) return;
        if (strand.value === 'TVL') {
            tvlBranchContainer.style.display = 'block';
            tvlBranch && tvlBranch.setAttribute('required','required');
        } else {
            tvlBranchContainer.style.display = 'none';
            if (tvlBranch) { tvlBranch.removeAttribute('required'); tvlBranch.value=''; }
        }
    }
    toggleTvlBranch();
    if (strand) strand.addEventListener('change', toggleTvlBranch);
});
</script>
