@extends('layout.app')

@section('content')
@php
    $isRegistrar = request()->routeIs('registrar.*');
    $routePrefix = $isRegistrar ? 'registrar.school-years' : 'admin.school-years';
@endphp

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .sy-page-wrapper { font-family: 'Poppins', sans-serif; background: #F8FAFC; min-height: 100vh; padding: 2rem 1rem; }

    /* Page Header */
    .sy-header-banner {
        background: linear-gradient(135deg, #EAF2FF, #CFE3FF, #A7C7F7);
        border-radius: 16px;
        padding: 1.6rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
        box-shadow: 0 2px 12px rgba(91,139,225,.15);
    }
    .sy-header-banner h1 {
        font-size: 1.55rem;
        font-weight: 700;
        color: #1E3A8A;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .sy-header-banner h1 i { color: #5B8BE1; }
    .sy-header-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

    /* Card */
    .sy-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(91,139,225,.12);
        overflow: hidden;
    }
    .sy-card-header {
        background: linear-gradient(90deg, #F1F6FF, #E3EDFF, #D0E4FF);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem;
        border-bottom: 1px solid #D0E4FF;
    }
    .sy-card-header h5 {
        font-weight: 600;
        color: #1E3A8A;
        margin: 0;
        font-size: 1rem;
    }
    .sy-card-body { padding: 1.4rem 1.5rem; }

    /* Alerts */
    .sy-alert-success {
        background: #EFF9F2; border: 1px solid #86EFAC; color: #166534;
        border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .sy-alert-error {
        background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B;
        border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1rem;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* Search bar */
    .sy-search-row { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .sy-search-row .form-control {
        border-radius: 10px; border: 1.5px solid #C8DCFA;
        font-family: 'Poppins', sans-serif; font-size: .875rem;
        padding: .45rem .9rem;
    }
    .sy-search-row .form-control:focus { border-color: #5B8BE1; box-shadow: 0 0 0 3px rgba(91,139,225,.2); outline: none; }

    /* Buttons */
    .btn-sy-primary {
        background: #5B8BE1; color: #fff; border: none;
        border-radius: 10px; padding: .45rem 1.1rem; font-size: .85rem;
        font-family: 'Poppins', sans-serif; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem; cursor: pointer;
        transition: background .2s, transform .1s;
        text-decoration: none;
    }
    .btn-sy-primary:hover { background: #4A7AD0; color: #fff; transform: translateY(-1px); }

    .btn-sy-outline {
        background: transparent; color: #5B8BE1; border: 1.5px solid #5B8BE1;
        border-radius: 10px; padding: .42rem 1rem; font-size: .85rem;
        font-family: 'Poppins', sans-serif; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem; cursor: pointer;
        transition: all .2s; text-decoration: none;
    }
    .btn-sy-outline:hover { background: #5B8BE1; color: #fff; }

    .btn-sy-warning {
        background: #FEF3C7; color: #92400E; border: 1.5px solid #FCD34D;
        border-radius: 10px; padding: .42rem 1rem; font-size: .85rem;
        font-family: 'Poppins', sans-serif; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: all .2s; text-decoration: none;
    }
    .btn-sy-warning:hover { background: #FCD34D; color: #78350F; }

    .btn-sy-recycle {
        background: #FFF1F0; color: #C0392B; border: 1.5px solid #FBBFBC;
        border-radius: 10px; padding: .42rem 1rem; font-size: .85rem;
        font-family: 'Poppins', sans-serif; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: all .2s; text-decoration: none;
    }
    .btn-sy-recycle:hover { background: #FEE2E2; color: #991B1B; }

    /* Table */
    .sy-table-wrap { border-radius: 12px; overflow: hidden; border: 1px solid #D0E4FF; }
    .sy-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    .sy-table thead tr {
        background: linear-gradient(90deg, #5B8BE1, #7DA7EA, #AFC8F6);
    }
    .sy-table thead th {
        color: #fff; font-weight: 600; padding: .75rem 1rem;
        font-size: .82rem; text-transform: uppercase; letter-spacing: .5px;
        border: none;
    }
    .sy-table tbody tr { border-bottom: 1px solid #EAF2FF; transition: background .15s; }
    .sy-table tbody tr:last-child { border-bottom: none; }
    .sy-table tbody tr:hover { background: #F0F7FF; }
    .sy-table tbody tr.hidden-row { opacity: .7; background: #F8FAFC; }
    .sy-table td { padding: .7rem 1rem; vertical-align: middle; color: #334155; }

    /* Badges */
    .badge-active { background: #DCFCE7; color: #166534; border-radius: 20px; padding: .25rem .7rem; font-size: .75rem; font-weight: 600; }
    .badge-inactive { background: #F1F5F9; color: #64748B; border-radius: 20px; padding: .25rem .7rem; font-size: .75rem; font-weight: 600; }
    .badge-hidden { background: #F3F4F6; color: #374151; border-radius: 20px; padding: .2rem .6rem; font-size: .7rem; font-weight: 600; margin-left: .4rem; }

    /* Icon action buttons */
    .action-btns { display: flex; gap: .35rem; flex-wrap: wrap; align-items: center; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem; cursor: pointer; transition: all .15s;
        text-decoration: none;
    }
    .btn-icon.view   { background: #EFF6FF; color: #2563EB; }
    .btn-icon.view:hover   { background: #DBEAFE; color: #1D4ED8; }
    .btn-icon.dl     { background: #F0FDF4; color: #16A34A; }
    .btn-icon.dl:hover     { background: #DCFCE7; color: #15803D; }
    .btn-icon.edit   { background: #FFFBEB; color: #D97706; }
    .btn-icon.edit:hover   { background: #FEF3C7; color: #B45309; }
    .btn-icon.activate { background: #EFF6FF; color: #2563EB; }
    .btn-icon.activate:hover { background: #DBEAFE; }
    .btn-icon.deactivate { background: #F1F5F9; color: #64748B; }
    .btn-icon.deactivate:hover { background: #E2E8F0; }
    .btn-icon.hide    { background: #F3F4F6; color: #374151; }
    .btn-icon.hide:hover    { background: #E5E7EB; }
    .btn-icon.unhide  { background: #FEF3C7; color: #92400E; }
    .btn-icon.unhide:hover  { background: #FDE68A; }
    .btn-icon.trash   { background: #FFF1F0; color: #DC2626; }
    .btn-icon.trash:hover   { background: #FEE2E2; }
    .btn-icon:disabled, .btn-icon[disabled] { opacity: .4; cursor: not-allowed; pointer-events: none; }

    /* Empty state */
    .sy-empty { text-align: center; padding: 3rem 1rem; color: #94A3B8; }
    .sy-empty i { font-size: 2.5rem; margin-bottom: .75rem; display: block; }

    /* Modal */
    .sy-modal .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.15); font-family: 'Poppins', sans-serif; }
    .sy-modal .modal-header { background: linear-gradient(90deg, #F1F6FF, #E3EDFF); border-radius: 16px 16px 0 0; border-bottom: 1px solid #D0E4FF; padding: 1.1rem 1.5rem; }
    .sy-modal .modal-title { color: #1E3A8A; font-weight: 600; }
    .sy-modal .modal-footer { border-top: 1px solid #EAF2FF; }
    .sy-modal .form-label { color: #334155; font-weight: 500; font-size: .875rem; }
    .sy-modal .form-control { border-radius: 10px; border: 1.5px solid #C8DCFA; font-family: 'Poppins', sans-serif; }
    .sy-modal .form-control:focus { border-color: #5B8BE1; box-shadow: 0 0 0 3px rgba(91,139,225,.2); }
    .sy-modal .form-check-input:checked { background-color: #5B8BE1; border-color: #5B8BE1; }

    /* Soft Delete Confirmation Modal Styles */
    .soft-delete-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
    }
    .soft-delete-modal .modal-body {
        padding: 2.5rem 2rem 1.75rem;
        text-align: center;
    }
    .soft-delete-icon-wrapper {
        width: 76px;
        height: 76px;
        background-color: #FEE2E2;
        color: #DC2626;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.25rem;
    }
    .soft-delete-modal-title {
        color: #1E293B;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 0.6rem;
    }
    .soft-delete-modal-message {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 0.35rem;
    }
    .soft-delete-modal-hint {
        color: #94A3B8;
        font-size: 0.8rem;
        margin-bottom: 0;
    }
    .soft-delete-modal .modal-footer {
        border-top: 1px solid #F1F5F9;
        padding: 1.25rem 2rem 1.75rem;
        display: flex;
        justify-content: center;
        gap: 0.85rem;
    }
    .btn-modal-cancel {
        background-color: #FFFFFF;
        color: #475569;
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        padding: 0.55rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-modal-cancel:hover {
        background-color: #F8FAFC;
        border-color: #94A3B8;
        color: #1E293B;
    }
    .btn-modal-recycle {
        background-color: #DC2626;
        color: #FFFFFF;
        border: 1.5px solid #DC2626;
        border-radius: 10px;
        padding: 0.55rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }
    .btn-modal-recycle:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
        color: #FFFFFF;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35);
        transform: translateY(-1px);
    }

    /* Modal stacking and backdrop fixes */
    .modal {
        z-index: 1060 !important;
    }
    .modal-backdrop {
        z-index: 1055 !important;
    }
</style>

<div class="sy-page-wrapper">
    {{-- Page Header --}}
    <div class="sy-header-banner">
        <h1><i class="fas fa-calendar-alt"></i> Academic School Years</h1>
        <div class="sy-header-actions">
            <a href="{{ route($routePrefix . '.trashed') }}" class="btn-sy-recycle">
                <i class="fas fa-trash-restore"></i> Recycle Bin
            </a>
            <button type="button" class="btn-sy-primary" data-bs-toggle="modal" data-bs-target="#addSchoolYearModal">
                <i class="fas fa-plus"></i> Add School Year
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="sy-alert-success">
            <span><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="sy-alert-error">
            <span><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
        </div>
    @endif

    {{-- Search & Filter Card --}}
    <div class="sy-card mb-3">
        <div class="sy-card-header">
            <h5><i class="fas fa-search me-2"></i>Search & Filter</h5>
        </div>
        <div class="sy-card-body">
            <form method="GET" action="{{ route($routePrefix . '.index') }}">
                <div class="sy-search-row">
                    <input type="text" name="search" class="form-control flex-grow-1"
                           placeholder="Search by school year (e.g., 2024-2025)"
                           value="{{ request('search') }}">
                    @if(request('show_hidden'))
                        <input type="hidden" name="show_hidden" value="1">
                    @endif
                    <button type="submit" class="btn-sy-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @php
                        $params = request()->except('show_hidden');
                        $params['show_hidden'] = request('show_hidden') ? 0 : 1;
                    @endphp
                    <a href="{{ route($routePrefix . '.index', $params) }}"
                       class="{{ request('show_hidden') ? 'btn-sy-warning' : 'btn-sy-outline' }}">
                        <i class="fas fa-{{ request('show_hidden') ? 'eye-slash' : 'eye' }}"></i>
                        {{ request('show_hidden') ? 'Hide Hidden' : 'Show Hidden' }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="sy-card">
        <div class="sy-card-header">
            <h5><i class="fas fa-table me-2"></i>School Years List</h5>
            <span style="font-size:.8rem;color:#5B8BE1;font-weight:500;">{{ $schoolYears->count() }} record(s)</span>
        </div>
        <div class="sy-card-body" style="padding:0;">
            <div class="sy-table-wrap">
                <table class="sy-table">
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Status</th>
                            <th>Total Enrollments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolYears as $schoolYear)
                            @php
                                $enrollmentCount = \App\Models\Enrollment::where('school_year', $schoolYear->school_year)->count();

                                // Ensure school year has database record
                                if (!isset($schoolYear->id)) {
                                    $schoolYearRecord = \App\Models\SchoolYear::firstOrCreate(
                                        ['school_year' => $schoolYear->school_year],
                                        ['is_active' => false, 'is_hidden' => false]
                                    );
                                    $schoolYear = $schoolYearRecord;
                                }

                                $isActive = isset($schoolYear->is_active) ? $schoolYear->is_active : false;
                                $isHidden = isset($schoolYear->is_hidden) ? $schoolYear->is_hidden : false;
                            @endphp
                            <tr class="{{ $isHidden ? 'hidden-row' : '' }}">
                                <td>
                                    <strong style="color:#1E3A8A;">{{ $schoolYear->school_year }}</strong>
                                    @if($isHidden)
                                        <span class="badge-hidden"><i class="fas fa-eye-slash me-1"></i>Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isActive)
                                        <span class="badge-active"><i class="fas fa-circle me-1" style="font-size:.5rem;vertical-align:middle;"></i>Active</span>
                                    @else
                                        <span class="badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-weight:600;color:#334155;">{{ $enrollmentCount }}</span>
                                    <span style="font-size:.75rem;color:#94A3B8;"> students</span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        {{-- View --}}
                                        <a href="{{ route($routePrefix . '.show', $schoolYear->school_year) }}"
                                           class="btn-icon view" title="View Students">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- Download --}}
                                        <a href="{{ route($routePrefix . '.download', $schoolYear->school_year) }}"
                                           class="btn-icon dl" title="Download CSV">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route($routePrefix . '.edit', $schoolYear->school_year) }}"
                                           class="btn-icon edit" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        {{-- Toggle Active --}}
                                        <form action="{{ route($routePrefix . '.toggle-active', $schoolYear->school_year) }}" method="POST" style="display:contents;">
                                            @csrf
                                            <button type="submit"
                                                    class="btn-icon {{ $isActive ? 'deactivate' : 'activate' }}"
                                                    title="{{ $isActive ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $isActive ? 'toggle-on' : 'toggle-off' }}"></i>
                                            </button>
                                        </form>
                                        {{-- Toggle Hidden --}}
                                        @if($isActive && !$isHidden)
                                            <button class="btn-icon hide" disabled title="Deactivate first to hide">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route($routePrefix . '.toggle-hidden', $schoolYear->school_year) }}" method="POST" style="display:contents;">
                                                @csrf
                                                @if(request('show_hidden'))
                                                    <input type="hidden" name="show_hidden" value="1">
                                                @endif
                                                <button type="submit"
                                                        class="btn-icon {{ $isHidden ? 'unhide' : 'hide' }}"
                                                        title="{{ $isHidden ? 'Unhide' : 'Hide' }}">
                                                    <i class="fas fa-{{ $isHidden ? 'eye' : 'eye-slash' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                        {{-- Soft Delete (Recycle Bin) --}}
                                        @if($isActive)
                                            <button class="btn-icon trash" disabled title="Deactivate first to move to recycle bin">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn-icon trash" 
                                                    title="Move to Recycle Bin"
                                                    onclick="openSoftDeleteModal('{{ $schoolYear->school_year }}', '{{ route($routePrefix . '.destroy', $schoolYear->school_year) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="sy-empty">
                                        <i class="fas fa-calendar-times"></i>
                                        <p>No school years found.</p>
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

{{-- Add School Year Modal --}}
<div class="modal fade sy-modal" id="addSchoolYearModal" tabindex="-1" aria-labelledby="addSchoolYearModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSchoolYearModalLabel">
                    <i class="fas fa-calendar-plus me-2" style="color:#5B8BE1;"></i>Add New School Year
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route($routePrefix . '.store') }}">
                @csrf
                <div class="modal-body" style="padding:1.5rem;">
                    @if($errors->any())
                        <div class="sy-alert-error mb-3">
                            <span><i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first() }}</span>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="school_year" class="form-label">School Year <span style="color:#DC2626;">*</span></label>
                        <input
                            type="text"
                            class="form-control"
                            id="school_year"
                            name="school_year"
                            placeholder="e.g., 2025-2026"
                            value="{{ old('school_year') }}"
                            required
                            pattern="\d{4}-\d{4}"
                            inputmode="numeric"
                            minlength="9"
                            maxlength="9"
                            title="Enter a year range like 2025-2026"
                        >
                        <small class="text-muted" style="font-size:.78rem;">Format: YYYY-YYYY (end year must be start year + 1)</small>
                    </div>
                    <div class="mb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1">
                            <label class="form-check-label" for="is_active" style="font-size:.875rem;color:#334155;">
                                Set as Active School Year
                            </label>
                        </div>
                        <small class="text-muted" style="font-size:.78rem;padding-left:1.5rem;display:block;">Only one school year can be active at a time.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sy-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-sy-primary">
                        <i class="fas fa-plus"></i> Add School Year
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Confirm Soft Delete Modal --}}
<div class="modal fade soft-delete-modal" id="confirmSoftDeleteModal" tabindex="-1" aria-labelledby="confirmSoftDeleteModalLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="soft-delete-icon-wrapper">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h4 class="soft-delete-modal-title" id="confirmSoftDeleteModalLabel">Confirm Soft Delete</h4>
                <p class="soft-delete-modal-message" id="softDeleteModalMessage">
                    Are you sure you want to remove the school year <strong id="softDeleteTargetYear"></strong>?
                </p>
                <p class="soft-delete-modal-hint">
                    This school year will be moved to the Recycle Bin and can be restored later.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="softDeleteForm" method="POST" action="" style="display:inline-block; margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modal-recycle">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openSoftDeleteModal(schoolYear, actionUrl) {
        var yearElem = document.getElementById('softDeleteTargetYear');
        var formElem = document.getElementById('softDeleteForm');
        var modalElem = document.getElementById('confirmSoftDeleteModal');

        if (yearElem) yearElem.textContent = schoolYear;
        if (formElem) formElem.action = actionUrl;

        if (modalElem) {
            // Check if bootstrap JS is available
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var deleteModal = bootstrap.Modal.getInstance(modalElem) || new bootstrap.Modal(modalElem);
                deleteModal.show();
            } else if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal !== 'undefined') {
                $(modalElem).modal('show');
            } else {
                // Fallback manual display
                modalElem.classList.add('show');
                modalElem.style.display = 'block';
                modalElem.removeAttribute('aria-hidden');
                modalElem.setAttribute('aria-modal', 'true');
            }
        }
    }

    // Auto-move modals directly to document.body on load to avoid z-index/stacking context issues
    document.addEventListener('DOMContentLoaded', function() {
        ['addSchoolYearModal', 'confirmSoftDeleteModal'].forEach(function(modalId) {
            var m = document.getElementById(modalId);
            if (m && m.parentNode !== document.body) {
                document.body.appendChild(m);
            }
        });
    });
</script>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('addSchoolYearModal'));
        modal.show();
    });
</script>
@endif

@endsection
