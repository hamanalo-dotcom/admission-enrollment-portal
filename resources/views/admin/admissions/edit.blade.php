@extends('layout.app')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Edit Admission</h3>
            <form action="{{ route('admin.admissions.update', $admission->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Personal Info -->
                <h5 class="mb-3 text-primary">Student Information</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><strong>Student Type</strong></label>
                        <input type="text" name="student_type" value="{{ old('student_type', $admission->student_type) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Status</strong></label>
                        <select name="status" id="statusSelect" class="form-control form-control-sm" onchange="toggleRemarksField()">
                            <option value="pending" {{ $admission->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $admission->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $admission->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-12" id="remarksField" style="display: {{ $admission->status == 'rejected' ? 'block' : 'none' }};">
                        <label><strong>Rejection Remarks/Comments:</strong> <span class="text-danger">*</span></label>
                        <textarea name="rejection_remarks" class="form-control form-control-sm" rows="3" placeholder="Please provide a reason for rejection...">{{ old('rejection_remarks', $admission->rejection_remarks ?? '') }}</textarea>
                        <small class="text-muted">This comment will be visible to the student.</small>
                    </div>
                </div>
                <script>
                    function toggleRemarksField() {
                        const statusSelect = document.getElementById('statusSelect');
                        const remarksField = document.getElementById('remarksField');
                        const remarksTextarea = remarksField.querySelector('textarea');
                        
                        if (statusSelect.value === 'rejected') {
                            remarksField.style.display = 'block';
                            remarksTextarea.setAttribute('required', 'required');
                        } else {
                            remarksField.style.display = 'none';
                            remarksTextarea.removeAttribute('required');
                        }
                    }
                </script>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>First Name</strong></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $admission->first_name) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Middle Name</strong></label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $admission->middle_name) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Last Name</strong></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $admission->last_name) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Suffix</label>
                        <input type="text" name="suffix" value="{{ old('suffix', $admission->suffix) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $admission->date_of_birth) }}" class="form-control form-control-sm" max="{{ date('Y-m-d', strtotime('-11 years')) }}">
                        <small class="text-muted">Student must be at least 11 years old.</small>
                    </div>
                    <div class="col-md-4">
                        <label>Place of Birth</label>
                        <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $admission->place_of_birth) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <!-- Address Part -->
                <h5 class="mt-4 mb-3 text-primary">Address</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Region</label>
                        <select name="region_code" id="region" class="form-control form-control-sm" required>
                            <option value="">-- Select Region --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Province</label>
                        <select name="province_code" id="province" class="form-control form-control-sm">
                            <option value="">-- Select Province --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>City/Municipality</label>
                        <select name="city_code" id="city" class="form-control form-control-sm">
                            <option value="">-- Select City/Municipality --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Barangay</label>
                        <select name="barangay_code" id="barangay" class="form-control form-control-sm">
                            <option value="">-- Select Barangay --</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Street</label>
                        <input type="text" name="street" value="{{ old('street', $admission->street) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label>Contact No</label>
                        <input type="text" name="contact_no" value="{{ old('contact_no', $admission->contact_no) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <!-- Academic & Other Info -->
                <h5 class="mt-4 mb-3 text-primary">Academic and Family Information</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Year Level</label>
                        @php 
                            $yl = old('year_level', $admission->year_level ?? '');
                            $isShs = in_array($yl, ['Grade 11', 'Grade 12']);
                        @endphp
                        <select name="year_level" id="year_level" class="form-control form-control-sm">
                            <option value="">-- Select Year Level --</option>
                            <option value="Grade 7" {{ $yl=='Grade 7' ? 'selected' : '' }}>Grade 7</option>
                            <option value="Grade 8" {{ $yl=='Grade 8' ? 'selected' : '' }}>Grade 8</option>
                            <option value="Grade 9" {{ $yl=='Grade 9' ? 'selected' : '' }}>Grade 9</option>
                            <option value="Grade 10" {{ $yl=='Grade 10' ? 'selected' : '' }}>Grade 10</option>
                            <option value="Grade 11" {{ $yl=='Grade 11' ? 'selected' : '' }}>Grade 11</option>
                            <option value="Grade 12" {{ $yl=='Grade 12' ? 'selected' : '' }}>Grade 12</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3" id="strand_container" style="display: {{ $isShs ? 'block' : 'none' }};">
                        <label>Strand <span class="text-muted">(Required for SHS)</span></label>
                        @php $st = old('strand', $admission->strand ?? ''); @endphp
                        <select name="strand" id="strand" class="form-control form-control-sm" {{ $isShs ? 'required' : '' }}>
                            <option value="">-- Select Strand --</option>
                            <option value="GAS" {{ $st=='GAS' ? 'selected' : '' }}>GAS</option>
                            <option value="TVL" {{ $st=='TVL' ? 'selected' : '' }}>TVL</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3" id="tvl_branch_container" style="display: {{ ($isShs && ($st=='TVL')) ? 'block' : 'none' }};">
                        <label>TVL Branch</label>
                        @php $tb = old('tvl_branch', $admission->tvl_branch ?? ''); @endphp
                        <select name="tvl_branch" id="tvl_branch" class="form-control form-control-sm" {{ ($isShs && ($st=='TVL')) ? 'required' : '' }}>
                            <option value="">-- Select TVL Branch --</option>
                            <option value="CSS" {{ $tb=='CSS' ? 'selected' : '' }}>CSS</option>
                            <option value="EIM" {{ $tb=='EIM' ? 'selected' : '' }}>EIM</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>LRN</label>
                        <input type="text" name="lrn" value="{{ old('lrn', $admission->lrn) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Previous School</label>
                        <input type="text" name="previous_school" value="{{ old('previous_school', $admission->previous_school) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label>Previous School Address</label>
                        <input type="text" name="previous_school_address" value="{{ old('previous_school_address', $admission->previous_school_address) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Father's Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $admission->father_name) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>Occupation</label>
                        <input type="text" name="father_occupation" value="{{ old('father_occupation', $admission->father_occupation) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>Contact</label>
                        <input type="text" name="father_contact" value="{{ old('father_contact', $admission->father_contact) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Mother's Name</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $admission->mother_name) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>Occupation</label>
                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $admission->mother_occupation) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>Contact</label>
                        <input type="text" name="mother_contact" value="{{ old('mother_contact', $admission->mother_contact) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Guardian's Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $admission->guardian_name) }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label>Guardian's Contact</label>
                        <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $admission->guardian_contact) }}" class="form-control form-control-sm">
                    </div>
                </div>
                <!-- File Uploads & Downloads -->
                <h5 class="mt-4 text-primary">Uploaded Files</h5>
                <div class="row mb-3">
                    @php
                        $fileLabels = [
                            'report_card' => 'Form 138 (Report Card)',
                            'form_137' => 'Form 137 (School Record)',
                            'birth_certificate' => 'Birth Certificate',
                            'good_moral' => 'Good Moral Certificate',
                            'medical_certificate' => 'Medical Certificate',
                            'id_picture' => 'ID Picture'
                        ];
                    @endphp
                    @foreach($fileLabels as $field => $label)
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>{{ $label }}</strong></label>
                            @if($admission->$field && Storage::disk('public')->exists($admission->$field))
                                <div class="mb-2">
                                    <small class="text-muted">Current file:</small>
                                    <a href="{{ route('admissions.files.download', [$admission->id, $field]) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-download"></i> View Current
                                    </a>
                                    <small class="text-muted ms-2">(Updated: {{ \Carbon\Carbon::parse($admission->updated_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A') }} Philippine Time)</small>
                                </div>
                            @else
                                <div class="mb-2">
                                    <small class="text-muted">No file uploaded</small>
                                </div>
                            @endif
                            <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Upload a new file to replace the existing one</small>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dependent dropdowns for address
        const regionSelect = document.getElementById('region');
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');

        // Preload full offline datasets and filter client-side
        let PROVINCES_ALL = [];
        let CITIES_ALL = [];
        let BARANGAYS_ALL = [];

        // Old/preselected values from admission
        const oldRegion   = @json(old('region_code', $admission->region_code ?? null));
        const oldProvince = @json(old('province_code', $admission->province_code ?? null));
        const oldCity     = @json(old('city_code', $admission->city_code ?? null));
        const oldBarangay = @json(old('barangay_code', $admission->barangay_code ?? null));

        // Disable dependent selects until parent chosen
        provinceSelect.disabled = true;
        citySelect.disabled = true;
        barangaySelect.disabled = true;

        // Helper function to manually trigger cascade with old values
        function triggerCascade() {
            if (!oldRegion) return;
            
            // Trigger province loading
            const regCode = oldRegion;
            provinceSelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
            barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
            provinceSelect.disabled = true;
            citySelect.disabled = true;
            barangaySelect.disabled = true;
            
            // Load provinces
            const fromCacheFull = Array.isArray(PROVINCES_ALL) && PROVINCES_ALL.length > 0;
            const fromCache = fromCacheFull ? PROVINCES_ALL.filter(p => String(p.region_code) === String(regCode)) : [];
            
            function loadProvinces(list) {
                provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                list.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.code || p.provCode;
                    opt.textContent = p.name || p.provDesc;
                    provinceSelect.appendChild(opt);
                });
                provinceSelect.disabled = false;
                
                // Set province value and continue cascade
                setTimeout(() => {
                    if (oldProvince && Array.from(provinceSelect.options).some(opt => opt.value === String(oldProvince))) {
                        provinceSelect.value = oldProvince;
                        // Trigger city loading
                        const provCode = oldProvince;
                        citySelect.innerHTML = '<option value="">Loading...</option>';
                        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                        citySelect.disabled = true;
                        barangaySelect.disabled = true;
                        
                        const citiesFull = Array.isArray(CITIES_ALL) && CITIES_ALL.length > 0;
                        const cachedCities = citiesFull ? CITIES_ALL.filter(c => String(c.province_code) === String(provCode)) : [];
                        
                        function loadCities(cityList) {
                            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
                            cityList.forEach(c => {
                                const opt = document.createElement('option');
                                opt.value = c.code || c.citymunCode || c.psgcCode;
                                opt.textContent = c.name || c.citymunDesc;
                                citySelect.appendChild(opt);
                            });
                            citySelect.disabled = false;
                            
                            // Set city value and continue cascade
                            setTimeout(() => {
                                if (oldCity && Array.from(citySelect.options).some(opt => opt.value === String(oldCity))) {
                                    citySelect.value = oldCity;
                                    // Trigger barangay loading
                                    const citymunCode = oldCity;
                                    barangaySelect.innerHTML = '<option value="">Loading...</option>';
                                    barangaySelect.disabled = true;
                                    
                                    const brgysFull = Array.isArray(BARANGAYS_ALL) && BARANGAYS_ALL.length > 0;
                                    const cachedBrgys = brgysFull ? BARANGAYS_ALL.filter(b => String(b.city_code) === String(citymunCode)) : [];
                                    
                                    function loadBarangays(brgyList) {
                                        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                                        brgyList.forEach(b => {
                                            const opt = document.createElement('option');
                                            opt.value = b.code || b.brgyCode;
                                            opt.textContent = b.name || b.brgyDesc;
                                            barangaySelect.appendChild(opt);
                                        });
                                        barangaySelect.disabled = false;
                                        
                                        // Set barangay value
                                        setTimeout(() => {
                                            if (oldBarangay && Array.from(barangaySelect.options).some(opt => opt.value === String(oldBarangay))) {
                                                barangaySelect.value = oldBarangay;
                                            }
                                        }, 50);
                                    }
                                    
                                    if (brgysFull && cachedBrgys.length > 0) {
                                        loadBarangays(cachedBrgys);
                                    } else {
                                        fetch(window.location.origin + `/api/barangays?city_code=${encodeURIComponent(citymunCode)}`)
                                            .then(r => r.json())
                                            .then(resp => (resp.data || resp))
                                            .then(loadBarangays)
                                            .catch(() => {
                                                barangaySelect.innerHTML = '<option value="">Failed to load barangays</option>';
                                                barangaySelect.disabled = false;
                                            });
                                    }
                                }
                            }, 50);
                        }
                        
                        if (citiesFull && cachedCities.length > 0) {
                            loadCities(cachedCities);
                        } else {
                            fetch(window.location.origin + `/api/cities?province_code=${encodeURIComponent(provCode)}`)
                                .then(r => r.json())
                                .then(resp => (resp.data || resp))
                                .then(loadCities)
                                .catch(() => {
                                    citySelect.innerHTML = '<option value="">Failed to load cities</option>';
                                    citySelect.disabled = false;
                                });
                        }
                    }
                }, 100);
            }
            
            if (fromCacheFull && fromCache.length > 0) {
                loadProvinces(fromCache);
            } else {
                fetch(window.location.origin + `/api/provinces?region_code=${encodeURIComponent(regCode)}`)
                    .then(r => r.json())
                    .then(resp => (resp.data || resp))
                    .then(loadProvinces)
                    .catch(() => {
                        provinceSelect.innerHTML = '<option value="">Failed to load provinces</option>';
                        provinceSelect.disabled = false;
                    });
            }
        }

        // Populate regions from our offline API (always fetch to ensure fill)
        if (regionSelect) {
            const base = window.location.origin;
            regionSelect.innerHTML = '<option value="">-- Select Region --</option>';
            
            // Load regions and preload all datasets, then trigger cascade if needed
            Promise.all([
                fetch(base + '/api/regions/all').then(r => r.json()).then(resp => (resp.data || resp)),
                fetch(base + '/api/provinces/all').then(r => r.json()).then(resp => { PROVINCES_ALL = resp.data || []; return resp.data || []; }),
                fetch(base + '/api/cities/all').then(r => r.json()).then(resp => { CITIES_ALL = resp.data || []; return resp.data || []; }),
                fetch(base + '/api/barangays/all').then(r => r.json()).then(resp => { BARANGAYS_ALL = resp.data || []; return resp.data || []; })
            ])
            .then(([regionsList]) => {
                // Populate regions dropdown
                regionsList.forEach(rg => {
                    const opt = document.createElement('option');
                    opt.value = rg.code;
                    opt.textContent = rg.name;
                    regionSelect.appendChild(opt);
                });
                
                // Apply old selection if any and trigger cascade manually
                if (oldRegion) {
                    regionSelect.value = oldRegion;
                    // Wait a bit for datasets to be fully loaded, then trigger cascade
                    setTimeout(() => {
                        triggerCascade();
                    }, 200);
                }
            })
            .catch((e) => {
                console.error('Failed loading regions or datasets', e);
            });
        }

        regionSelect.addEventListener('change', function () {
            const regCode = this.value;
            provinceSelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
            barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
            if (!regCode) return;
            provinceSelect.disabled = true;
            citySelect.disabled = true;
            barangaySelect.disabled = true;
            // Prefer offline dataset if loaded
            const fromCacheFull = Array.isArray(PROVINCES_ALL) && PROVINCES_ALL.length > 0;
            const fromCache = fromCacheFull ? PROVINCES_ALL.filter(p => String(p.region_code) === String(regCode)) : [];
            if (fromCacheFull && fromCache.length > 0) {
                provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                fromCache.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.code;
                    opt.textContent = p.name;
                    provinceSelect.appendChild(opt);
                });
                provinceSelect.disabled = false;
                // Set province value after options are rendered
                setTimeout(() => {
                    if (oldProvince && Array.from(provinceSelect.options).some(opt => opt.value === String(oldProvince))) {
                        provinceSelect.value = oldProvince;
                        provinceSelect.dispatchEvent(new Event('change'));
                    }
                }, 50);
            } else {
                fetch(window.location.origin + `/api/provinces?region_code=${encodeURIComponent(regCode)}`)
                    .then(r => r.json())
                    .then(resp => (resp.data || resp))
                    .then(list => {
                        provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                        list.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.code || p.provCode;
                            opt.textContent = p.name || p.provDesc;
                            provinceSelect.appendChild(opt);
                        });
                        provinceSelect.disabled = false;
                        // Set province value after options are rendered
                        setTimeout(() => {
                            const provValue = oldProvince ? (Array.from(provinceSelect.options).find(opt => opt.value === String(oldProvince)) ? oldProvince : null) : null;
                            if (provValue) {
                                provinceSelect.value = provValue;
                                provinceSelect.dispatchEvent(new Event('change'));
                            }
                        }, 50);
                    })
                    .catch(() => {
                        provinceSelect.innerHTML = '<option value="">Failed to load provinces</option>';
                        provinceSelect.disabled = false;
                    });
            }
        });

        provinceSelect.addEventListener('change', function () {
            const provCode = this.value;
            citySelect.innerHTML = '<option value="">Loading...</option>';
            barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
            if (!provCode) return;
            citySelect.disabled = true;
            barangaySelect.disabled = true;
            const citiesFull = Array.isArray(CITIES_ALL) && CITIES_ALL.length > 0;
            const cachedCities = citiesFull ? CITIES_ALL.filter(c => String(c.province_code) === String(provCode)) : [];
            if (citiesFull && cachedCities.length > 0) {
                citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
                cachedCities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.code;
                    opt.textContent = c.name;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
                // Set city value after options are rendered
                setTimeout(() => {
                    if (oldCity && Array.from(citySelect.options).some(opt => opt.value === String(oldCity))) {
                        citySelect.value = oldCity;
                        citySelect.dispatchEvent(new Event('change'));
                    }
                }, 50);
            } else {
                fetch(window.location.origin + `/api/cities?province_code=${encodeURIComponent(provCode)}`)
                    .then(r => r.json())
                    .then(resp => (resp.data || resp))
                    .then(list => {
                        citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
                        list.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.code || c.citymunCode || c.psgcCode;
                            opt.textContent = c.name || c.citymunDesc;
                            citySelect.appendChild(opt);
                        });
                        citySelect.disabled = false;
                        // Set city value after options are rendered
                        setTimeout(() => {
                            const cityValue = oldCity ? (Array.from(citySelect.options).find(opt => opt.value === String(oldCity)) ? oldCity : null) : null;
                            if (cityValue) {
                                citySelect.value = cityValue;
                                citySelect.dispatchEvent(new Event('change'));
                            }
                        }, 50);
                    })
                    .catch(() => {
                        citySelect.innerHTML = '<option value="">Failed to load cities</option>';
                        citySelect.disabled = false;
                    });
            }
        });

        citySelect.addEventListener('change', function () {
            const citymunCode = this.value;
            barangaySelect.innerHTML = '<option value="">Loading...</option>';
            if (!citymunCode) return;
            barangaySelect.disabled = true;
            const brgysFull = Array.isArray(BARANGAYS_ALL) && BARANGAYS_ALL.length > 0;
            const cachedBrgys = brgysFull ? BARANGAYS_ALL.filter(b => String(b.city_code) === String(citymunCode)) : [];
            if (brgysFull && cachedBrgys.length > 0) {
                barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                cachedBrgys.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.code;
                    opt.textContent = b.name;
                    barangaySelect.appendChild(opt);
                });
                barangaySelect.disabled = false;
                // Set barangay value after options are rendered
                setTimeout(() => {
                    if (oldBarangay && Array.from(barangaySelect.options).some(opt => opt.value === String(oldBarangay))) {
                        barangaySelect.value = oldBarangay;
                    }
                }, 50);
            } else {
                fetch(window.location.origin + `/api/barangays?city_code=${encodeURIComponent(citymunCode)}`)
                    .then(r => r.json())
                    .then(resp => (resp.data || resp))
                    .then(list => {
                        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                        list.forEach(b => {
                            const opt = document.createElement('option');
                            opt.value = b.code || b.brgyCode;
                            opt.textContent = b.name || b.brgyDesc;
                            barangaySelect.appendChild(opt);
                        });
                        barangaySelect.disabled = false;
                        // Set barangay value after options are rendered
                        setTimeout(() => {
                            if (oldBarangay && Array.from(barangaySelect.options).some(opt => opt.value === String(oldBarangay))) {
                                barangaySelect.value = oldBarangay;
                            }
                        }, 50);
                    })
                    .catch(() => {
                        barangaySelect.innerHTML = '<option value="">Failed to load barangays</option>';
                        barangaySelect.disabled = false;
                    });
            }
        });
        
        // Toggle strand field based on year level
        const yearLevel = document.getElementById('year_level');
        const strandContainer = document.getElementById('strand_container');
        
        function toggleStrand() {
            if (!yearLevel || !strandContainer) return;
            
            const strandSelect = strandContainer.querySelector('select[name="strand"]');
            if (yearLevel.value === 'Grade 11' || yearLevel.value === 'Grade 12') {
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
        if (yearLevel) {
            yearLevel.addEventListener('change', function() {
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
