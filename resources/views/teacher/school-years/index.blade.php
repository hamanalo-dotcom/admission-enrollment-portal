@extends('layout.app')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">School Years</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Search Form -->
            <form method="GET" action="{{ route('teacher.school-years.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by school year (e.g., 2024-2025)" 
                               value="{{ request('search') }}">
                        @if(request('show_hidden'))
                            <input type="hidden" name="show_hidden" value="1">
                        @endif
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                    <div class="col-md-2">
                        @php
                            $params = request()->except('show_hidden');
                            $params['show_hidden'] = request('show_hidden') ? 0 : 1;
                        @endphp
                        <a href="{{ route('teacher.school-years.index', $params) }}" 
                           class="btn {{ request('show_hidden') ? 'btn-warning' : 'btn-outline-secondary' }} w-100">
                            <i class="fas fa-{{ request('show_hidden') ? 'eye-slash' : 'eye' }}"></i> 
                            {{ request('show_hidden') ? 'Hide Hidden' : 'Show Hidden' }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- School Years Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>School Year</th>
                            <th>My Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolYears as $schoolYear)
                            @php
                                $teacherId = Auth::id();
                                
                                // Ensure school year has database record
                                if (!isset($schoolYear->id)) {
                                    $schoolYearRecord = \App\Models\SchoolYear::firstOrCreate(
                                        ['school_year' => $schoolYear->school_year],
                                        [
                                            'is_active' => false,
                                            'is_hidden' => false,
                                        ]
                                    );
                                    $schoolYear = $schoolYearRecord;
                                }
                                
                                $assignments = \App\Models\TeacherSectionAssignment::where('teacher_id', $teacherId)
                                    ->where('school_year', $schoolYear->school_year)
                                    ->get();
                                
                                // Calculate student count for teacher's assigned sections
                                if ($assignments->isEmpty()) {
                                    $studentCount = 0;
                                } else {
                                    $studentCount = \App\Models\Enrollment::where('school_year', $schoolYear->school_year)
                                        ->where('status', 'enrolled')
                                        ->where(function($q) use ($assignments) {
                                            foreach ($assignments as $assignment) {
                                                $q->orWhere(function($query) use ($assignment) {
                                                    $query->where('grade_level', $assignment->grade_level)
                                                        ->where('section', $assignment->section);
                                                    if ($assignment->strand) {
                                                        $query->where('strand', $assignment->strand);
                                                    }
                                                });
                                            }
                                        })
                                        ->count();
                                }
                                
                                $isActive = isset($schoolYear->is_active) ? $schoolYear->is_active : false;
                                $isHidden = isset($schoolYear->is_hidden) ? $schoolYear->is_hidden : false;
                            @endphp
                            <tr class="{{ (!$isActive && $isHidden) ? 'table-secondary opacity-75' : '' }}">
                                <td>
                                    <strong>{{ $schoolYear->school_year }}</strong>
                                    @if(!$isActive && $isHidden)
                                        <span class="badge bg-dark ms-2">Hidden</span>
                                    @endif
                                </td>
                                <td>{{ $studentCount }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teacher.school-years.show', $schoolYear->school_year) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View Students
                                        </a>
                                        <a href="{{ route('teacher.school-years.download', $schoolYear->school_year) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <p class="text-muted">No school years found for your assigned sections.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

