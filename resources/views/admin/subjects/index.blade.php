@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center; }
        .table-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .filters { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
        label { font-weight: 600; color: #2c5aa0; font-size: 13px; margin-bottom: 6px; display:block; }
        input, select { padding: 10px; border: 2px solid #d4e5f3; border-radius: 8px; font-size: 14px; background: #f9fbfd; }
        .btn-primary { background: linear-gradient(135deg,#2c5aa0,#526c86); color:#fff; padding:10px 16px; border:none; border-radius:8px; font-weight:600; text-decoration:none; }
        .btn-secondary { background:#f5f7fa; border:2px solid #d4e5f3; color:#666; padding:10px 16px; border-radius:8px; text-decoration:none; }
        table { width:100%; border-collapse: collapse; }
        th { background:#2c5aa0; color:#fff; text-align:left; padding:12px; font-size:13px; }
        td { padding:12px; border-bottom:1px solid #e9ecef; font-size:14px; }
        .actions { display:flex; gap:8px; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Subjects</h1>
            <a href="{{ request()->routeIs('registrar.*') ? route('registrar.subjects.create') : route('admin.subjects.create') }}" class="btn-primary">+ Add Subject</a>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#2c5aa0',
                        timer: 3000,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif

        <div class="table-section">
            <form method="GET" action="{{ request()->routeIs('registrar.*') ? route('registrar.subjects.index') : route('admin.subjects.index') }}" class="filters">
                <div>
                    <label>Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or code">
                </div>
                <div>
                    <label>Grade Level</label>
                    <select name="grade_level">
                        <option value="">All</option>
                        @foreach($gradeLevels as $gl)
                            <option value="{{ $gl }}" {{ request('grade_level')==$gl ? 'selected' : '' }}>{{ $gl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Strand</label>
                    <select name="strand">
                        <option value="">All</option>
                        @foreach($strands as $st)
                            <option value="{{ $st }}" {{ request('strand')==$st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="align-self:end; display:flex; gap:8px;">
                    <button class="btn-primary" type="submit">Filter</button>
                    <a class="btn-secondary" href="{{ request()->routeIs('registrar.*') ? route('registrar.subjects.index') : route('admin.subjects.index') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Grade Level</th>
                            <th>Strand</th>
                            <th>Semester</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->code ?? '-' }}</td>
                                <td>{{ $subject->grade_level ?? 'All' }}</td>
                                <td>{{ $subject->strand ?? (in_array($subject->grade_level,['Grade 11','Grade 12']) ? 'All' : '-') }}</td>
                                <td>{{ $subject->semester ?? (in_array($subject->grade_level,['Grade 11','Grade 12']) ? '-' : '-') }}</td>
                                <td>
                                    <div class="actions">
                                        <a class="btn-secondary" href="{{ request()->routeIs('registrar.*') ? route('registrar.subjects.edit',$subject->id) : route('admin.subjects.edit',$subject->id) }}">Edit</a>
                                        {{-- Delete functionality removed to preserve all data --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="text-align:center; color:#999; padding:20px;">No subjects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 12px;">
                {{ $subjects->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection


