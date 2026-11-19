@extends('layout.app')

@section('content')
    <div class="shadow mb-4 w-full p-md-5">
        <div class="container">
            <div class="row">
                <div class="col mr-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="display-6">Grade 10 students</h1>

                    </div>
                    {{-- <a href="{{ route('student.enrollment.create') }}" class="btn btn-primary btn-sm float-end">
                        Enroll Now
                    </a> --}}
                    {{-- <div class="d-flex align-items-center justify-content-between mt-2"> --}}

                    {{-- <div class="d-flex align-items-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <strong>Total:</strong>
                            </div>
                            <div class="h5 ms-1 mb-1.5 font-weight-bold text-gray-800" id="admissionsCount">
                                {{ $admissions->count() }}
                            </div>
                        </div> --}}
                    {{-- Search bar --}}
                    {{-- <x-searchBar placeholder="Search admissions..." /> --}}
                    {{-- </div> --}}
                </div>

                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-hover" id="admissionsTable" width="100%" cellspacing="0">
                            <table class="table table-hover table-sm">
                                <colgroup>
                                    <col style="width: 20%;"> {{-- Student --}}
                                    <col style="width: 10%;"> {{-- Grade Level --}}
                                    {{-- <col style="width: 10%;"> Strand/Track --}}
                                    <col style="width: 10%;"> {{-- Section --}}
                                    <col style="width: 15%;"> {{-- School Year --}}
                                    {{-- <col style="width: 10%;"> Semester --}}
                                    <col style="width: 10%;"> {{-- Status --}}
                                    {{-- <col style="width: 10%;"> Actions --}}
                                </colgroup>
                                <thead class="table-light">
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th>Student</th>
                                        <th>Grade</th>
                                        {{-- <th class="text-center">Strand</th> --}}
                                        <th class="text-center">Section</th>
                                        <th class="text-center">School Year</th>
                                        {{-- <th class="text-center">Semester</th> --}}
                                        <th class="text-center">Status</th>
                                        {{-- <th class="text-center">Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jhsGradeTen as $jhsGradeTen)
                                        <tr>
                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                            <td>
                                                {{ $jhsGradeTen->admission->first_name }}
                                                {{ $jhsGradeTen->admission->last_name }}
                                            </td>
                                            <td>{{ $jhsGradeTen->grade_level }}</td>
                                            {{-- <td class="text-center">{{ $jhsGradeTen->strand ?? '-' }}</td> --}}
                                            <td class="text-center">{{ $jhsGradeTen->section ?? '-' }}</td>
                                            <td class="text-center">{{ $jhsGradeTen->school_year }}</td>
                                            {{-- <td class="text-center">{{ $jhsGradeTen->semester ?? '-' }}</td> --}}
                                            <td class="text-center">
                                                @php
                                                    $statusClass = match ($jhsGradeTen->status) {
                                                        'pending' => 'warning',
                                                        'enrolled' => 'success',
                                                        'cancelled' => 'danger',
                                                        'completed' => 'info',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst($jhsGradeTen->status) }}
                                                </span>
                                            </td>
                                            {{-- <td class="text-center">
                                                <a href="" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a> 
                                            </td> --}}
                                            {{-- Update Status (Registrar only) --}}
                                            {{-- <form action="{{ route('enrollments.update', $enrollment->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()"
                                                    class="form-select form-select-sm d-inline w-auto">
                                                    <option value="pending"
                                                        {{ $enrollment->status == 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="enrolled"
                                                        {{ $enrollment->status == 'enrolled' ? 'selected' : '' }}>Enrolled
                                                    </option>
                                                    <option value="completed"
                                                        {{ $enrollment->status == 'completed' ? 'selected' : '' }}>
                                                        Completed</option>
                                                    <option value="cancelled"
                                                        {{ $enrollment->status == 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled</option>
                                                </select>
                                            </form> --}}

                                            {{-- Delete --}}
                                            {{-- <form action="{{ route('enrollments.destroy', $enrollment->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirmSubmit(event, 'Are you sure to delete this record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form> --}}



                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No admissions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{-- {{ $jhsGradeTen->links() }} Pagination --}}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
















{{-- @extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col mr-0">
                <div class="card shadow">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="display-6">Admissions</h1>
                        <a href="{{ route('registrar.admissions.create') }}" class="btn btn-primary btn-sm">
                            Add Data
                        </a>
                    </div>

                    <div class="card-body">

                        {{-- Success Message --}}
{{-- @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="mb-3">
                            <a href="{{ route('student.enrollment.create') }}" class="btn btn-primary btn-sm">
                                + New Enrollment
                            </a>
                        </div>
                        <div class="module">
                            <div class="card-body">
                                <div class="table table-responsive">
                                    <table class="table table-hover" id="admissionsTable" width="100%" cellspacing="0">
                                        <table class="table table-hover table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student</th>
                                                    <th>Grade Level</th>
                                                    <th>Strand/Track</th>
                                                    <th>Section</th>
                                                    <th>School Year</th>
                                                    <th>Semester</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($enrollments as $enrollment)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ $enrollment->admission->first_name }}
                                                            {{ $enrollment->admission->last_name }}
                                                        </td>
                                                        <td>{{ $enrollment->grade_level }}</td>
                                                        <td>{{ $enrollment->strand ?? '-' }}</td>
                                                        <td>{{ $enrollment->section ?? '-' }}</td>
                                                        <td>{{ $enrollment->school_year }}</td>
                                                        <td>{{ $enrollment->semester ?? '-' }}</td>
                                                        <td>
                                                            @php
                                                                $statusClass = match ($enrollment->status) {
                                                                    'pending' => 'warning',
                                                                    'enrolled' => 'success',
                                                                    'cancelled' => 'danger',
                                                                    'completed' => 'info',
                                                                    default => 'secondary',
                                                                };
                                                            @endphp
                                                            <span class="badge bg-{{ $statusClass }}">
                                                                {{ ucfirst($enrollment->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('student.enrollment.show', $enrollment->id) }}"
                                                                class="btn btn-info btn-sm">View</a> --}}

{{-- Update Status (Registrar only) --}}
{{-- <form action="{{ route('enrollments.update', $enrollment->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()"
                                                    class="form-select form-select-sm d-inline w-auto">
                                                    <option value="pending"
                                                        {{ $enrollment->status == 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="enrolled"
                                                        {{ $enrollment->status == 'enrolled' ? 'selected' : '' }}>Enrolled
                                                    </option>
                                                    <option value="completed"
                                                        {{ $enrollment->status == 'completed' ? 'selected' : '' }}>
                                                        Completed</option>
                                                    <option value="cancelled"
                                                        {{ $enrollment->status == 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled</option>
                                                </select>
                                            </form> --}}

{{-- Delete --}}
{{-- <form action="{{ route('enrollments.destroy', $enrollment->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirmSubmit(event, 'Are you sure to delete this record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form> --}}
{{-- </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center text-muted">No enrollments
                                                            found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <div class="mt-3">
                                            {{ $enrollments->links() }} {{-- Pagination --}}
{{-- </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection   --}}
