@extends('layout.app')

@section('content')
    <div class="shadow mb-4 w-full p-md-5">
        <div class="container">
            <div class="row">
                <div class="col mr-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="display-6">Admissions</h1>
                    </div>
                    {{-- <a href="{{ route('registrar.admissions.create') }}" class="btn btn-primary btn-sm float-end">
                        Add Data
                    </a> --}}

                    {{-- <div class="d-flex align-items-center justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <strong>Total:</strong>
                            </div>
                            <div class="h5 ms-1 mb-1.5 font-weight-bold text-gray-800" id="admissionsCount">
                                {{ $admissions->count() }}
                            </div>
                        </div> --}}
                    {{-- Search bar --}}
                    {{-- <x-searchBar placeholder="Search admissions..." /> --}}
                </div>
            </div>

            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-hover" id="admissionsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-light">
                                <th>Name</th>
                                <th>Year Level</th>
                                <th>Applied At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="admissionsTableBody">
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>{{ $admission->first_name ?? 'N/A' }}
                                        {{ $admission->last_name ?? '' }}</td>
                                    <td>{{ $admission->year_level ?? '-' }}</td>
                                    <td>{{ $admission->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <form
                                            action="{{ route('registrar.admissions.updateAdmissionStatus', $admission->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm w-auto border-0"
                                                style="background-color: #f0f4fa;" onchange="this.form.submit()">
                                                <option value="pending"
                                                    {{ $admission->status == 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="approved"
                                                    {{ $admission->status == 'approved' ? 'selected' : '' }}>
                                                    Approved
                                                </option>
                                                <option value="rejected"
                                                    {{ $admission->status == 'rejected' ? 'selected' : '' }}>
                                                    Rejected
                                                </option>
                                            </select>

                                        </form>

                                    </td>

                                    <td>
                                        <a href="{{ route('registrar.admissions.show', $admission->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- <a href="{{ route('registrar.admissions.edit', $admission->id) }}"
                                                class="btn btn-sm btn-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}
                                        {{-- <form action="{{ route('registrar.admissions.destroy', $admission->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="if(!confirm('Are you sure?')) return false;" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No admissions found.</td>
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
