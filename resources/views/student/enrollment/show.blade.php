@extends('layout.app')

@section('content')
    <div class="shadow mb-4 w-full p-md-5">
        <div class="container">
            <div class="row">
                <div class="col mr-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="display-6">Enrollment Details</h1>
                        <div class="d-flex gap-2">
                            @if(in_array($enrollment->status, ['pending', 'cancelled']))
                                <a href="{{ route('student.enrollment.edit', $enrollment->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                {{-- Delete functionality removed to preserve all data --}}
                            @endif
                            <a href="{{ route('student.enrollment.index') }}" class="btn btn-primary btn-sm">← Back to Enrollments</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-hover table-sm" width="100%" cellspacing="0">
                            <tbody>
                                <tr>
                                    <th style="width: 25%">Student</th>
                                    <td>
                                        {{ $enrollment->admission->first_name ?? 'N/A' }}
                                        {{ $enrollment->admission->last_name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Grade Level</th>
                                    <td>{{ $enrollment->grade_level ?? '-' }}</td>
                                </tr>
                                @php
                                    $isShs = in_array($enrollment->grade_level, ['Grade 11', 'Grade 12']);
                                @endphp
                                @if($isShs)
                                    <tr>
                                        <th>Strand</th>
                                        <td>{{ $enrollment->strand ?? '-' }}</td>
                                    </tr>
                                    @if(($enrollment->strand ?? null) === 'TVL')
                                        <tr>
                                            <th>TVL Branch</th>
                                            <td>{{ $enrollment->tvl_branch ?? '-' }}</td>
                                        </tr>
                                    @endif
                                @endif
                                <tr>
                                    <th>Section</th>
                                    <td>{{ $enrollment->section ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>School Year</th>
                                    <td>{{ $enrollment->school_year ?? '-' }}</td>
                                </tr>
                                @if($isShs)
                                    <tr>
                                        <th>Semester</th>
                                        <td>{{ $enrollment->semester ?? '-' }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge 
                                            @if($enrollment->status === 'pending') bg-warning text-dark
                                            @elseif($enrollment->status === 'enrolled') bg-success
                                            @elseif($enrollment->status === 'cancelled') bg-danger
                                            @else bg-info @endif">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                            @if($enrollment->status === 'cancelled' && $enrollment->cancellation_remarks)
                                                <span class="text-danger" style="font-size: 14px;">
                                                    <i class="fas fa-comment-alt"></i> <strong>Reason:</strong> {{ $enrollment->cancellation_remarks }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @if($enrollment->admission && $enrollment->admission->student_type === 'transferee')
                                    @php
                                        $selectedSubjects = $enrollment->selected_subjects ?? [];
                                        $droppedSubjects = $enrollment->dropped_subjects ?? [];
                                    @endphp
                                    @if(!empty($selectedSubjects) || !empty($droppedSubjects))
                                        @if(!empty($selectedSubjects))
                                            <tr>
                                                <th>Subjects to Take</th>
                                                <td>
                                                    @php
                                                        $subjects = \App\Models\Subject::whereIn('id', $selectedSubjects)->get();
                                                    @endphp
                                                    <ul style="margin: 0; padding-left: 20px;">
                                                        @foreach($subjects as $subject)
                                                            <li><strong>{{ $subject->name }}</strong>@if($subject->code) ({{ $subject->code }})@endif</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif
                                        @if(!empty($droppedSubjects))
                                            <tr>
                                                <th>Subjects to Drop</th>
                                                <td>
                                                    @php
                                                        $subjects = \App\Models\Subject::whereIn('id', $droppedSubjects)->get();
                                                    @endphp
                                                    <ul style="margin: 0; padding-left: 20px;">
                                                        @foreach($subjects as $subject)
                                                            <li><strong>{{ $subject->name }}</strong>@if($subject->code) ({{ $subject->code }})@endif</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @endif
                                @php
                                    $admission = $enrollment->admission;
                                    $guardianName = $enrollment->guardian_name ?? $admission->guardian_name ?? $admission->father_name ?? $admission->mother_name ?? 'N/A';
                                    $guardianContact = $enrollment->guardian_contact ?? $admission->guardian_contact ?? $admission->father_contact ?? $admission->mother_contact ?? 'N/A';
                                @endphp
                                <tr>
                                    <th>Father Name</th>
                                    <td>{{ $admission->father_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Father Contact</th>
                                    <td>{{ $admission->father_contact ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Mother Name</th>
                                    <td>{{ $admission->mother_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Mother Contact</th>
                                    <td>{{ $admission->mother_contact ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Guardian Name</th>
                                    <td>{{ $guardianName }}</td>
                                </tr>
                                <tr>
                                    <th>Guardian Contact</th>
                                    <td>{{ $guardianContact }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $enrollment->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $enrollment->updated_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Assigned Subjects Section -->
                @if($enrollment->status === 'enrolled' && $enrollment->section)
                    <div class="mt-4">
                        <h5 class="text-primary mb-3">Assigned Subjects</h5>
                        <hr>
                        @if(isset($subjects) && $subjects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Teacher</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjects as $subject)
                                            @php
                                                // Get teacher assignment for this subject and section
                                                $assignment = $subject->teacherAssignments
                                                    ->where('grade_level', $enrollment->grade_level)
                                                    ->where('section', $enrollment->section)
                                                    ->first();
                                                $teacher = $assignment ? $assignment->teacher : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $subject->code ?? '-' }}</td>
                                                <td><strong>{{ $subject->name }}</strong></td>
                                                <td>
                                                    @if($teacher)
                                                        {{ $teacher->name ?? ($teacher->first_name . ' ' . $teacher->last_name) }}
                                                    @else
                                                        <span class="text-muted">Not Assigned</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No subjects have been assigned yet. Subjects will appear here once they are assigned to your section.
                            </div>
                        @endif
                    </div>
                @elseif($enrollment->status !== 'enrolled')
                    <div class="mt-4">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Subjects will be displayed once your enrollment status is "Enrolled" and you have been assigned to a section.
                        </div>
                    </div>
                @elseif(!$enrollment->section)
                    <div class="mt-4">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Subjects will be displayed once you have been assigned to a section.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


