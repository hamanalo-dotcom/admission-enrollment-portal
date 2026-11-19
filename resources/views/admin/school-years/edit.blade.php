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
                <h2 class="mb-0">Edit School Year</h2>
                <a href="{{ route($routePrefix . '.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route($routePrefix . '.update', $schoolYearRecord->school_year) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="school_year" class="form-label">School Year *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="school_year" 
                        name="school_year" 
                        value="{{ old('school_year', $schoolYearRecord->school_year) }}" 
                        required
                        pattern="\d{4}-\d{4}"
                        inputmode="numeric"
                        minlength="9"
                        maxlength="9"
                        title="Enter a year range like 2025-2026"
                    >
                    <small class="form-text text-muted">Format: YYYY-YYYY (end year must be start year + 1)</small>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $schoolYearRecord->is_active ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Set as Active School Year
                        </label>
                        <small class="form-text text-muted d-block">Only one school year can be active at a time. Activating this will deactivate others.</small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update School Year
                    </button>
                    <a href="{{ route($routePrefix . '.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

