@extends('layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body { background-color: #f5f7fa; color: #333; font-family: 'Poppins', sans-serif; }
        .main-content { padding: 30px; }
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header-section h1 { font-size: 28px; color: #2c5aa0; font-weight: 700; margin: 0; }
        .container { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .form-group { display: flex; flex-direction: column; margin-bottom: 15px; }
        label { font-weight: 600; color: #2c5aa0; font-size: 14px; margin-bottom: 6px; }
        input, select { padding: 10px; border: 2px solid #d4e5f3; border-radius: 8px; font-size: 14px; background: #f9fbfd; }
        input:focus, select:focus { outline: none; border-color: #2c5aa0; background: #fff; box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1); }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%); transform: translateY(-1px); }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px; border-bottom: 1px solid #eaeef3; font-size: 14px; }
        .table thead tr { background: #f5f7fa; border-bottom: 2px solid #4a8fd8; }
        .badge { padding: 6px 10px; border-radius: 16px; font-size: 12px; font-weight: 600; }
        .badge-muted { background: #eef2f7; color: #4f6279; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        @media (max-width: 960px) { .container { grid-template-columns: 1fr; } }
    </style>

    @php
        $isRegistrar = auth()->user()->role === 'registrar';
        $storeRoute = $isRegistrar ? route('registrar.sections.store') : route('admin.sections.store');
        $addAllDefaultsRoute = $isRegistrar ? route('registrar.sections.add-all-defaults') : route('admin.sections.add-all-defaults');
        $dashboardRoute = $isRegistrar ? route('registrar.index') : route('admin.index');
        $downloadPdfRoute = $isRegistrar ? 'registrar.sections.download-pdf' : 'admin.sections.download-pdf';
        $downloadStrandPdfRoute = $isRegistrar ? 'registrar.sections.download-strand-section-pdf' : 'admin.sections.download-strand-section-pdf';
    @endphp

    <div class="main-content">
        <div class="header-section">
            <h1>Manage Sections</h1>
            <div style="display: flex; gap: 10px; align-items: center;">
                <form action="{{ $addAllDefaultsRoute }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <i class="fas fa-plus-circle"></i> Add All Default Sections
                    </button>
                </form>
                <a href="{{ $dashboardRoute }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $sectionsByGrade = $sections->groupBy('grade_level');
        @endphp

        <div class="container" style="grid-template-columns: 1fr;">
            @foreach ($gradeLevels as $grade)
                <div class="card">
                    <div style="display:flex; justify-content: space-between; align-items:center; gap: 16px; flex-wrap: wrap;">
                        <h2 style="margin:0; color:#2c5aa0; font-size:18px;">{{ $grade }}</h2>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1.5fr; gap: 20px; margin-top: 16px;">
                        <div>
                            <h3 style="margin:0 0 10px 0; color:#526c86; font-size:14px;">Add Section</h3>
                            <form action="{{ $storeRoute }}" method="POST">
                                @csrf
                                <input type="hidden" name="grade_level" value="{{ $grade }}">

                                @if (in_array($grade, ['Grade 11', 'Grade 12']))
                                    <div class="form-group">
                                        <label for="strand_{{ str_replace(' ', '_', $grade) }}">Strand (optional)</label>
                                        <select name="strand" id="strand_{{ str_replace(' ', '_', $grade) }}">
                                            <option value="">-- Optional --</option>
                                            @foreach ($strands as $strand)
                                                <option value="{{ $strand }}">{{ $strand }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="name_{{ str_replace(' ', '_', $grade) }}">Section Name *</label>
                                    <input type="text" name="name" id="name_{{ str_replace(' ', '_', $grade) }}" placeholder="e.g., HOPE / ZAMORA" required>
                                </div>

                                @if (!$isRegistrar)
                                <div class="form-group">
                                    <label for="capacity_{{ str_replace(' ', '_', $grade) }}">Capacity</label>
                                    <input type="number" name="capacity" id="capacity_{{ str_replace(' ', '_', $grade) }}" value="45" min="1" max="100">
                                </div>
                                @endif

                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>

                        <div>
                            <h3 style="margin:0 0 10px 0; color:#526c86; font-size:14px;">Existing Sections</h3>
                            <div style="overflow-x:auto;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            @if (in_array($grade, ['Grade 11', 'Grade 12']))
                                                <th>Strand</th>
                                            @endif
                                            <th>Section</th>
                                            @if (!$isRegistrar)
                                            <th class="text-center">Capacity</th>
                                            @endif
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rows = $sectionsByGrade->get($grade, collect());
                                            $colspan = in_array($grade, ['Grade 11', 'Grade 12']) ? ($isRegistrar ? 4 : 5) : ($isRegistrar ? 3 : 4);
                                        @endphp
                                        @forelse ($rows as $section)
                                            <tr>
                                                @if (in_array($grade, ['Grade 11', 'Grade 12']))
                                                    <td>
                                                        @if($section->strand)
                                                            <span class="badge badge-muted">{{ $section->strand }}</span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endif
                                                <td style="font-weight:600; color:#2c5aa0;">{{ $section->name }}</td>
                                                @if (!$isRegistrar)
                                                <td class="text-center">{{ $section->capacity }}</td>
                                                @endif
                                                <td>{{ $section->created_at ? $section->created_at->format('Y-m-d') : '-' }}</td>
                                                <td style="white-space: nowrap;">
                                                    @php
                                                        $gradeNumber = str_replace('Grade ', '', $grade);
                                                        $sectionName = strtolower(str_replace(' ', '-', $section->name));
                                                    @endphp
                                                    @if (in_array($grade, ['Grade 11', 'Grade 12']) && $section->strand)
                                                        <a href="{{ route($downloadStrandPdfRoute, [
                                                            'grade' => $gradeNumber,
                                                            'strand' => strtolower($section->strand),
                                                            'section' => $sectionName
                                                        ]) }}" 
                                                        class="btn btn-primary" 
                                                        style="padding: 6px 12px; font-size: 12px; text-decoration: none; display: inline-block; white-space: nowrap;">
                                                            <i class="fas fa-download"></i> Download PDF
                                                        </a>
                                                    @else
                                                        <a href="{{ route($downloadPdfRoute, [
                                                            'grade' => $gradeNumber,
                                                            'section' => $sectionName
                                                        ]) }}" 
                                                        class="btn btn-primary" 
                                                        style="padding: 6px 12px; font-size: 12px; text-decoration: none; display: inline-block; white-space: nowrap;">
                                                            <i class="fas fa-download"></i> Download PDF
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $colspan }}" style="text-align:center; color:#999; padding: 16px;">No sections yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // No page-level JS needed; forms are per-grade
    </script>
@endsection


