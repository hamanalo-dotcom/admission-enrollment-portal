@extends('layout.app')

@section('content')
    <div class="shadow mb-4 w-full p-md-5">
        <div class="container">
            <div class="row">
                <div class="col mr-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="display-6">Admissions</h1>
                        <a href="{{ route('student.admissions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Admission
                        </a>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <strong>Total:</strong>
                            </div>
                            <div class="h5 ms-1 mb-1.5 font-weight-bold text-gray-800" id="admissionsCount">
                                {{ $admissions->count() }}
                            </div>
                        </div>
                        {{-- Search bar --}}
                        <x-searchBar placeholder="Search admissions..." />
                    </div>
                </div>

                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-hover" id="admissionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-light">
                                    <th>Name</th>
                                    <th>Year Level</th>
                                    <th>Status</th>
                                    <th>Applied At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="admissionsTableBody">
                                @forelse($admissions as $admission)
                                    <tr>
                                        <td>{{ $admission->first_name ?? 'N/A' }} {{ $admission->middle_name ?? '' }} {{ $admission->last_name ?? '' }}</td>
                                        <td>{{ $admission->year_level ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                @if ($admission->status === 'draft' || $admission->is_draft)
                                                    <span class="badge bg-secondary">Draft</span>
                                                @elseif ($admission->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($admission->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($admission->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $admission->status ?? '-' }}</span>
                                                @endif
                                                @if($admission->status === 'rejected' && $admission->rejection_remarks)
                                                    <small class="text-danger" style="font-size: 12px;" title="{{ $admission->rejection_remarks }}">
                                                        <i class="fas fa-info-circle"></i> {{ Str::limit($admission->rejection_remarks, 50) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $admission->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('student.admissions.show', $admission->id) }}" 
                                                   class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('student.admissions.edit', $admission->id) }}" 
                                                   class="btn btn-sm btn-secondary" title="Edit Admission">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                {{-- Delete functionality removed to preserve all data --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="py-4">
                                                <p class="text-muted">No admissions found.</p>
                                                <a href="{{ route('student.admissions.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Create Your First Admission
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
