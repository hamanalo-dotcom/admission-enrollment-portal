@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Admission Details</h3>

                <!-- Back Button -->
                <div class="position-absolute top-0 end-0 mt-4 me-4">
                    <a href="{{ route('registrar.admissions.index') }}" class="text-dark" style="font-size: 1.25rem;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>

                <!-- Student Information Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="text-primary">Student Information</h5>
                    </div>
                    <hr>
                    {{-- <p><strong>Student ID:</strong> {{ $admission->student_id }}</p> --}}
                    <p><strong>Name:</strong>
                        {{ $admission->first_name }}
                        {{ $admission->middle_name }}
                        {{ $admission->last_name }}
                        {{ $admission->suffix }}
                    </p>
                    <p><strong>Email:</strong> {{ $admission->email }}</p>
                    <p><strong>Contact:</strong> {{ $admission->contact_no }}</p>
                    @php
                        $regionName = $admission?->region_code ? \App\Models\Region::where('regCode', $admission->region_code)->value('regDesc') : null;
                        $provinceName = $admission?->province_code ? \App\Models\Province::where('provCode', $admission->province_code)->value('provDesc') : null;
                        $cityName = $admission?->city_code ? \App\Models\City::where('citymunCode', $admission->city_code)->value('citymunDesc') : null;
                        $barangayName = $admission?->barangay_code ? \App\Models\Barangay::where('brgyCode', $admission->barangay_code)->value('brgyDesc') : null;
                        $parts = array_filter([
                            $admission->street ?: null,
                            $barangayName,
                            $cityName,
                            $provinceName,
                            $regionName ? 'Region: '.$regionName : null,
                        ]);
                    @endphp
                    <p><strong>Address:</strong> {{ $parts ? implode(', ', $parts) : 'N/A' }}</p>
                    <p><strong>Previous School:</strong> {{ $admission->previous_school ?? 'N/A' }}</p>
                    <p><strong>Previous School Address:</strong> {{ $admission->previous_school_address ?? 'N/A' }}</p>
                </div>

                <!-- Guardian Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Guardian Information</h5>
                    <hr>
                    <p><strong>Guardian Name:</strong> {{ $admission->guardian_name ?? 'N/A' }}</p>
                    <p><strong>Guardian Contact:</strong> {{ $admission->guardian_contact ?? 'N/A' }}</p>
                </div>

                <!-- Academic Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Academic Information</h5>
                    <hr>
                    <p><strong>Year Level:</strong> {{ $admission->year_level }}</p>
                    <p><strong>Strand:</strong> {{ $admission->strand ?? 'N/A' }}</p>
                    <p><strong>Status:</strong>
                        <span
                            class="badge 
                            @if ($admission->status == 'approved') bg-success 
                            @elseif($admission->status == 'pending') bg-warning 
                            @else bg-danger @endif">
                            {{ ucfirst($admission->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
