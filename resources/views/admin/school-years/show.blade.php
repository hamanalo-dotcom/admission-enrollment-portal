@extends('layout.app')

@section('content')
@php
    $isRegistrar = request()->routeIs('registrar.*');
    $routePrefix = $isRegistrar ? 'registrar.school-years' : 'admin.school-years';
@endphp
<div class="container my-5">
    <div class="card shadow">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Enrolled Students - {{ $schoolYear }}</h2>
                <div class="btn-group">
                    <a href="{{ route($routePrefix . '.download', $schoolYear) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Download CSV
                    </a>
                    <a href="{{ route($routePrefix . '.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <p class="text-muted">Total Enrolled Students: <strong>{{ $enrollments->count() }}</strong></p>

            <!-- Grouped by Grade Level and Section -->
            @foreach($grouped as $groupKey => $groupEnrollments)
                @php
                    $firstEnrollment = $groupEnrollments->first();
                    $isShs = $firstEnrollment && in_array($firstEnrollment->grade_level, ['Grade 11', 'Grade 12']);
                @endphp
                <div class="mb-4">
                    <h4 class="text-primary">{{ $groupKey }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    @if($isShs)
                                        <th>Strand</th>
                                    @endif
                                    <th>Status</th>
                                    <th>Guardian Name</th>
                                    <th>Guardian Contact</th>
                                    <th>Email</th>
                                    <th>Contact No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupEnrollments as $enrollment)
                                    @php
                                        $user = $enrollment->user;
                                        $admission = $enrollment->admission;
                                        $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
                                        
                                        // Use parent details if guardian details are missing
                                        $guardianName = $enrollment->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? 'N/A';
                                        $guardianContact = $enrollment->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? 'N/A';
                                    @endphp
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            {{ $user->firstname ?? '' }} {{ $user->lastname ?? '' }}
                                        </td>
                                        @if($isShs)
                                            <td>{{ $enrollment->strand ?? 'N/A' }}</td>
                                        @endif
                                        <td>
                                            <span class="badge bg-{{ $enrollment->status === 'enrolled' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $guardianName }}</td>
                                        <td>{{ $guardianContact }}</td>
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                        <td>{{ $admission->contact_no ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            @if($enrollments->isEmpty())
                <div class="alert alert-info">
                    <p class="mb-0">No students enrolled for this school year.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

