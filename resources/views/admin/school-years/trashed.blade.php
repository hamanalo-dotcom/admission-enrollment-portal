@extends('layout.app')

@section('content')
@php
    $isRegistrar = request()->routeIs('registrar.*');
    $routePrefix = $isRegistrar ? 'registrar.school-years' : 'admin.school-years';
@endphp

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .sy-page-wrapper { font-family: 'Poppins', sans-serif; background: #F8FAFC; min-height: 100vh; padding: 2rem 1rem; }

    .sy-header-banner {
        background: linear-gradient(135deg, #FFF1F0, #FEE2E2, #FBBFBC);
        border-radius: 16px;
        padding: 1.6rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
        box-shadow: 0 2px 12px rgba(220,38,38,.12);
    }
    .sy-header-banner h1 {
        font-size: 1.55rem;
        font-weight: 700;
        color: #7F1D1D;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .sy-header-banner h1 i { color: #DC2626; }
    .sy-header-desc { font-size: .82rem; color: #991B1B; margin-top: .25rem; }

    .sy-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(91,139,225,.1);
        overflow: hidden;
    }
    .sy-card-header {
        background: linear-gradient(90deg, #FFF1F0, #FEE2E2, #FECACA);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #FECACA;
    }
    .sy-card-header h5 { font-weight: 600; color: #7F1D1D; margin: 0; font-size: 1rem; }

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

    .btn-sy-back {
        background: #EAF2FF; color: #1E3A8A; border: 1.5px solid #A7C7F7;
        border-radius: 10px; padding: .45rem 1.1rem; font-size: .85rem;
        font-family: 'Poppins', sans-serif; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem; cursor: pointer;
        transition: all .2s; text-decoration: none;
    }
    .btn-sy-back:hover { background: #CFE3FF; color: #1E3A8A; }

    .sy-table-wrap { border-radius: 0; overflow: hidden; }
    .sy-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    .sy-table thead tr { background: linear-gradient(90deg, #DC2626, #EF4444, #F87171); }
    .sy-table thead th { color: #fff; font-weight: 600; padding: .75rem 1rem; font-size: .82rem; text-transform: uppercase; letter-spacing: .5px; border: none; }
    .sy-table tbody tr { border-bottom: 1px solid #FEE2E2; transition: background .15s; }
    .sy-table tbody tr:last-child { border-bottom: none; }
    .sy-table tbody tr:hover { background: #FFF5F5; }
    .sy-table td { padding: .7rem 1rem; vertical-align: middle; color: #334155; }

    .badge-trashed { background: #FEE2E2; color: #991B1B; border-radius: 20px; padding: .25rem .7rem; font-size: .75rem; font-weight: 600; }

    .action-btns { display: flex; gap: .35rem; flex-wrap: wrap; align-items: center; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem; cursor: pointer; transition: all .15s; text-decoration: none;
    }
    .btn-icon.restore { background: #DCFCE7; color: #16A34A; border: 1.5px solid #86EFAC; }
    .btn-icon.restore:hover { background: #BBF7D0; color: #15803D; }

    .sy-empty { text-align: center; padding: 3.5rem 1rem; color: #94A3B8; }
    .sy-empty i { font-size: 2.8rem; margin-bottom: .75rem; display: block; color: #FBBFBC; }
    .sy-empty p { font-weight: 500; color: #64748B; }
    .sy-empty small { color: #94A3B8; font-size: .8rem; }

    .sy-info-banner {
        background: #FEF9EC; border: 1px solid #FDE68A; border-radius: 10px;
        padding: .75rem 1rem; margin-bottom: 1rem; font-size: .82rem; color: #78350F;
        display: flex; align-items: center; gap: .5rem;
    }
</style>

<div class="sy-page-wrapper">
    {{-- Page Header --}}
    <div class="sy-header-banner">
        <div>
            <h1><i class="fas fa-trash-restore"></i> Recycle Bin</h1>
            <div class="sy-header-desc">School years moved to recycle bin. Restore them to make them visible again.</div>
        </div>
        <a href="{{ route($routePrefix . '.index') }}" class="btn-sy-back">
            <i class="fas fa-arrow-left"></i> Back to School Years
        </a>
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

    @if($schoolYears->isNotEmpty())
    <div class="sy-info-banner">
        <i class="fas fa-info-circle"></i>
        <span>Items in the recycle bin are hidden from all lists. You can restore them to make them visible again.</span>
    </div>
    @endif

    {{-- Table Card --}}
    <div class="sy-card">
        <div class="sy-card-header">
            <h5><i class="fas fa-trash me-2"></i>Trashed School Years</h5>
            <span style="font-size:.8rem;color:#DC2626;font-weight:500;">{{ $schoolYears->count() }} item(s)</span>
        </div>
        <div style="padding:0;">
            <div class="sy-table-wrap">
                <table class="sy-table">
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolYears as $schoolYear)
                            <tr>
                                <td>
                                    <strong style="color:#7F1D1D;">{{ $schoolYear->school_year }}</strong>
                                    <span class="badge-trashed ms-2"><i class="fas fa-trash-alt me-1"></i>Trashed</span>
                                </td>
                                <td style="color:#64748B;font-size:.82rem;">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $schoolYear->deleted_at ? $schoolYear->deleted_at->format('M d, Y  h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    <div class="action-btns">
                                        {{-- Restore --}}
                                        <form action="{{ route($routePrefix . '.restore', $schoolYear->school_year) }}" method="POST" style="display:contents;">
                                            @csrf
                                            <button type="submit" class="btn-icon restore" title="Restore to Active List">
                                                <i class="fas fa-trash-restore"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="sy-empty">
                                        <i class="fas fa-check-circle"></i>
                                        <p>Recycle bin is empty</p>
                                        <small>The recycle bin is empty. All school years have been restored.</small>
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

@endsection
