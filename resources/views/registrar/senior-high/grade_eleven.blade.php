@extends('layout.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .shs-page { font-family: 'Poppins', sans-serif; background: #F8FAFC; padding: 24px; }

    /* ── Page Header ── */
    .shs-page-header {
        background: linear-gradient(135deg, #EAF2FF 0%, #CFE3FF 55%, #A7C7F7 100%);
        border-radius: 16px; padding: 26px 30px; margin-bottom: 22px;
        box-shadow: 0 2px 12px rgba(91,139,225,.15); color: #1E3A8A;
        display: flex; align-items: center; gap: 18px;
        position: relative; overflow: hidden;
    }
    .shs-page-header::after { content: ''; position: absolute; right: 120px; top: -30px; width: 200px; height: 160px; background: radial-gradient(ellipse, rgba(255,255,255,.55) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
    .shs-page-header::before { content: ''; position: absolute; right: -20px; bottom: -30px; width: 160px; height: 130px; background: radial-gradient(ellipse, rgba(167,199,247,.5) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
    .shs-page-header .header-icon { width: 56px; height: 56px; background: rgba(255,255,255,.75); border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #2563C8; flex-shrink: 0; box-shadow: 0 2px 8px rgba(91,139,225,.2); position: relative; z-index: 1; }
    .shs-page-header h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 3px; color: #1E3A8A; letter-spacing: -.2px; position: relative; z-index: 1; }
    .shs-page-header p { margin: 0; font-size: .84rem; color: #2563C8; position: relative; z-index: 1; }
    .header-total-badge { margin-left: auto; flex-shrink: 0; background: #5B8BE1; border-radius: 12px; padding: 10px 22px; text-align: center; color: #fff; position: relative; z-index: 1; }
    .header-total-badge .count { font-size: 1.8rem; font-weight: 700; line-height: 1; }
    .header-total-badge .label { font-size: .68rem; letter-spacing: .6px; text-transform: uppercase; opacity: .9; }

    /* ── Strand Card ── */
    .strand-card { border: none; border-radius: 14px; box-shadow: 0 2px 10px rgba(91,139,225,.12); margin-bottom: 22px; overflow: hidden; background: #fff; }
    .strand-card-header { background: linear-gradient(90deg, #F1F6FF 0%, #E3EDFF 55%, #D0E4FF 100%); padding: 14px 22px; display: flex; align-items: center; gap: 14px; position: relative; overflow: hidden; }
    .strand-card-header::after { content: ''; position: absolute; right: 200px; top: -20px; width: 120px; height: 90px; background: radial-gradient(ellipse, rgba(255,255,255,.7) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
    .strand-icon { width: 42px; height: 42px; background: rgba(91,139,225,.14); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #2563C8; flex-shrink: 0; position: relative; z-index: 1; }
    .strand-card-header h5 { margin: 0; font-size: 1.05rem; font-weight: 700; color: #1E3A8A; position: relative; z-index: 1; }
    .strand-card-header .strand-sub { font-size: .76rem; color: #2563C8; margin: 0; position: relative; z-index: 1; }
    .strand-count-pill { margin-left: auto; background: #fff; border: 1.5px solid #5B8BE1; color: #2563C8; border-radius: 20px; padding: 5px 14px; font-size: .8rem; font-weight: 600; white-space: nowrap; position: relative; z-index: 1; }
    .strand-download-btn { background: #5B8BE1; border: none; color: #fff; border-radius: 8px; padding: 7px 16px; font-size: .8rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: background .2s, transform .15s; white-space: nowrap; flex-shrink: 0; position: relative; z-index: 1; }
    .strand-download-btn:hover { background: #4a7fd4; color: #fff; transform: translateY(-1px); }

    /* ── Table ── */
    .shs-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    .shs-table thead tr { background: linear-gradient(90deg, #5B8BE1, #7DA7EA, #AFC8F6); color: #fff; }
    .shs-table thead th { padding: 11px 16px; font-weight: 600; font-size: .75rem; text-transform: uppercase; letter-spacing: .6px; border: none; }
    .shs-table tbody tr { border-bottom: 1px solid #EEF4FF; transition: background .15s; }
    .shs-table tbody tr:hover { background: #F0F5FF; }
    .shs-table tbody tr:last-child { border-bottom: none; }
    .shs-table td { padding: 12px 16px; vertical-align: middle; color: #374151; border: none; }
    .shs-table .student-name { font-weight: 600; color: #1E3A8A; }

    .status-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: .74rem; font-weight: 600; }
    .status-enrolled  { background:#dcfce7; color:#15803d; }
    .status-pending   { background:#FFF7E0; color:#92400e; }
    .status-cancelled { background:#FECACA; color:#b91c1c; }
    .status-completed { background:#dbeafe; color:#1e40af; }
    .status-default   { background:#f3f4f6; color:#6b7280; }

    .shs-empty { text-align: center; padding: 44px 20px; color: #9ca3af; }
    .shs-empty i { font-size: 2.4rem; margin-bottom: 12px; color: #CBD5E1; display: block; }
    .shs-empty p { margin: 0; font-size: .88rem; }
</style>

@php
    $gasCount   = isset($gradeElevenGas) ? $gradeElevenGas->count() : 0;
    $cssCount   = isset($gradeElevenCss) ? $gradeElevenCss->count() : 0;
    $eimCount   = isset($gradeElevenEim) ? $gradeElevenEim->count() : 0;
    $totalCount = $gasCount + $cssCount + $eimCount;
@endphp

<div class="container-fluid shs-page">

    <div class="shs-page-header">
        <div class="header-icon"><i class="fas fa-user-graduate"></i></div>
        <div>
            <h1>Grade 11 — Senior High School</h1>
            <p>Student enrollment records by strand</p>
        </div>
        <div class="header-total-badge">
            <div class="count">{{ $totalCount }}</div>
            <div class="label">Total Students</div>
        </div>
    </div>

    {{-- ══ GAS ══ --}}
    <div class="strand-card">
        <div class="strand-card-header">
            <div class="strand-icon"><i class="fas fa-globe"></i></div>
            <div><h5>GAS</h5><p class="strand-sub">General Academic Strand</p></div>
            <span class="strand-count-pill"><i class="fas fa-users me-1"></i>{{ $gasCount }} student{{ $gasCount != 1 ? 's' : '' }}</span>
            <a href="{{ route('registrar.shs.strand.pdf', ['grade' => '11', 'strand' => 'gas']) }}" class="strand-download-btn ms-2"><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
        <div class="table-responsive">
            <table class="shs-table">
                <thead>
                    <tr><th>#</th><th>Student</th><th>Grade</th><th class="text-center">Strand</th><th class="text-center">Section</th><th class="text-center">School Year</th><th class="text-center">Semester</th><th class="text-center">Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($gradeElevenGas ?? [] as $enrollment)
                        @php $statusClass = match($enrollment->status){'enrolled'=>'status-enrolled','pending'=>'status-pending','cancelled'=>'status-cancelled','completed'=>'status-completed',default=>'status-default'}; @endphp
                        <tr>
                            <td class="text-muted" style="width:42px">{{ $loop->iteration }}</td>
                            <td><span class="student-name">{{ $enrollment->admission->first_name }} {{ $enrollment->admission->last_name }}</span></td>
                            <td>{{ $enrollment->grade_level }}</td>
                            <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                            <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                            <td class="text-center">{{ $enrollment->school_year }}</td>
                            <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
                            <td class="text-center"><span class="status-badge {{ $statusClass }}">{{ ucfirst($enrollment->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="shs-empty"><i class="fas fa-inbox"></i><p>No students enrolled in GAS yet.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ CSS ══ --}}
    <div class="strand-card">
        <div class="strand-card-header">
            <div class="strand-icon"><i class="fas fa-laptop-code"></i></div>
            <div><h5>CSS</h5><p class="strand-sub">Computer Systems Servicing</p></div>
            <span class="strand-count-pill"><i class="fas fa-users me-1"></i>{{ $cssCount }} student{{ $cssCount != 1 ? 's' : '' }}</span>
            <a href="{{ route('registrar.shs.strand.pdf', ['grade' => '11', 'strand' => 'css']) }}" class="strand-download-btn ms-2"><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
        <div class="table-responsive">
            <table class="shs-table">
                <thead>
                    <tr><th>#</th><th>Student</th><th>Grade</th><th class="text-center">Strand</th><th class="text-center">Section</th><th class="text-center">School Year</th><th class="text-center">Semester</th><th class="text-center">Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($gradeElevenCss ?? [] as $enrollment)
                        @php $statusClass = match($enrollment->status){'enrolled'=>'status-enrolled','pending'=>'status-pending','cancelled'=>'status-cancelled','completed'=>'status-completed',default=>'status-default'}; @endphp
                        <tr>
                            <td class="text-muted" style="width:42px">{{ $loop->iteration }}</td>
                            <td><span class="student-name">{{ $enrollment->admission->first_name }} {{ $enrollment->admission->last_name }}</span></td>
                            <td>{{ $enrollment->grade_level }}</td>
                            <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                            <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                            <td class="text-center">{{ $enrollment->school_year }}</td>
                            <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
                            <td class="text-center"><span class="status-badge {{ $statusClass }}">{{ ucfirst($enrollment->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="shs-empty"><i class="fas fa-inbox"></i><p>No students enrolled in CSS yet.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ EIM ══ --}}
    <div class="strand-card">
        <div class="strand-card-header">
            <div class="strand-icon"><i class="fas fa-bolt"></i></div>
            <div><h5>EIM</h5><p class="strand-sub">Electrical Installation &amp; Maintenance</p></div>
            <span class="strand-count-pill"><i class="fas fa-users me-1"></i>{{ $eimCount }} student{{ $eimCount != 1 ? 's' : '' }}</span>
            <a href="{{ route('registrar.shs.strand.pdf', ['grade' => '11', 'strand' => 'eim']) }}" class="strand-download-btn ms-2"><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
        <div class="table-responsive">
            <table class="shs-table">
                <thead>
                    <tr><th>#</th><th>Student</th><th>Grade</th><th class="text-center">Strand</th><th class="text-center">Section</th><th class="text-center">School Year</th><th class="text-center">Semester</th><th class="text-center">Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($gradeElevenEim ?? [] as $enrollment)
                        @php $statusClass = match($enrollment->status){'enrolled'=>'status-enrolled','pending'=>'status-pending','cancelled'=>'status-cancelled','completed'=>'status-completed',default=>'status-default'}; @endphp
                        <tr>
                            <td class="text-muted" style="width:42px">{{ $loop->iteration }}</td>
                            <td><span class="student-name">{{ $enrollment->admission->first_name }} {{ $enrollment->admission->last_name }}</span></td>
                            <td>{{ $enrollment->grade_level }}</td>
                            <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                            <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                            <td class="text-center">{{ $enrollment->school_year }}</td>
                            <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
                            <td class="text-center"><span class="status-badge {{ $statusClass }}">{{ ucfirst($enrollment->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="shs-empty"><i class="fas fa-inbox"></i><p>No students enrolled in EIM yet.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
