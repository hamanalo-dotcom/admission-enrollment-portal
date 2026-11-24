@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; max-width: 800px; margin: 0 auto; }
        .header-section { background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.1); margin-bottom:20px; }
        .form-container { background:#fff; padding:30px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .form-group { display:flex; flex-direction:column; margin-bottom:16px; }
        label { font-weight:600; color:#2c5aa0; font-size:13px; margin-bottom:6px; }
        input, select, textarea { padding:12px; border:2px solid #d4e5f3; border-radius:8px; background:#f9fbfd; }
        .actions { display:flex; gap:10px; margin-top:20px; }
        .btn-primary { background: linear-gradient(135deg,#2c5aa0,#526c86); color:#fff; padding:12px 24px; border:none; border-radius:8px; font-weight:600; }
        .btn-secondary { background:#f5f7fa; border:2px solid #d4e5f3; color:#666; padding:12px 24px; border-radius:8px; text-decoration:none; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Edit Subject</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <form method="POST" action="{{ request()->routeIs('registrar.*') ? route('registrar.subjects.update', $subject->id) : route('admin.subjects.update', $subject->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="{{ old('name', $subject->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" name="code" value="{{ old('code', $subject->code) }}" placeholder="e.g., MATH7">
                </div>
                <div class="form-group">
                    <label>Grade Level</label>
                    <select name="grade_level" id="grade_level">
                        <option value="">All</option>
                        @foreach($gradeLevels as $gl)
                            <option value="{{ $gl }}" {{ old('grade_level', $subject->grade_level)==$gl ? 'selected' : '' }}>{{ $gl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="strand-group" style="display:none;">
                    <label>Strand (SHS only)</label>
                    <select name="strand" id="strand">
                        <option value="">All</option>
                        @foreach($strands as $st)
                            <option value="{{ $st }}" {{ old('strand', $subject->strand)==$st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="semester-group" style="display:none;">
                    <label>Semester (SHS only) *</label>
                    <select name="semester" id="semester">
                        <option value="">Select Semester</option>
                        <option value="First Semester" {{ old('semester', $subject->semester)=='First Semester' ? 'selected' : '' }}>First Semester</option>
                        <option value="Second Semester" {{ old('semester', $subject->semester)=='Second Semester' ? 'selected' : '' }}>Second Semester</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4">{{ old('description', $subject->description) }}</textarea>
                </div>
                <div class="actions">
                    <button class="btn-primary" type="submit">Update</button>
                    <a class="btn-secondary" href="{{ request()->routeIs('registrar.*') ? route('registrar.subjects.index') : route('admin.subjects.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const gl = document.getElementById('grade_level');
        const sg = document.getElementById('strand-group');
        const semg = document.getElementById('semester-group');
        function toggleStrandAndSemester() {
            const isShs = (gl.value === 'Grade 11' || gl.value === 'Grade 12');
            sg.style.display = isShs ? 'block' : 'none';
            semg.style.display = isShs ? 'block' : 'none';
            if (!isShs) {
                document.getElementById('strand').value = '';
                document.getElementById('semester').value = '';
            }
        }
        gl.addEventListener('change', toggleStrandAndSemester);
        toggleStrandAndSemester();
    </script>
@endsection


