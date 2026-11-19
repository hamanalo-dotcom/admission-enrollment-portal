@extends('layout.app')

@section('content')
    <style>
        .main-content { padding: 30px; max-width: 800px; margin: 0 auto; }
        .header-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 20px; }
        .form-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .form-group { margin-bottom: 20px; }
        label { font-weight: 600; color: #2c5aa0; font-size: 14px; margin-bottom: 6px; display:block; }
        input, select, textarea { padding: 10px; border: 2px solid #d4e5f3; border-radius: 8px; font-size: 14px; background: #f9fbfd; width: 100%; }
        .btn-primary { background: linear-gradient(135deg,#2c5aa0,#526c86); color:#fff; padding:10px 20px; border:none; border-radius:8px; font-weight:600; cursor:pointer; }
        .btn-secondary { background:#f5f7fa; border:2px solid #d4e5f3; color:#666; padding:10px 20px; border-radius:8px; text-decoration:none; display:inline-block; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1 style="margin:0; color:#2c5aa0;">Edit Grade</h1>
        </div>

        <div class="form-section">
            <form method="POST" action="{{ route('admin.grades.update', $grade->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Student</label>
                    <input type="text" value="{{ $grade->student->firstname }} {{ $grade->student->lastname }}" disabled>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" value="{{ $grade->subject->name }}" disabled>
                </div>

                <div class="form-group">
                    <label>Grade Level</label>
                    <input type="text" value="{{ $grade->grade_level }}" disabled>
                </div>

                <div class="form-group">
                    <label>Section</label>
                    <input type="text" value="{{ $grade->section }}" disabled>
                </div>

                <div class="form-group">
                    <label>Grading Period *</label>
                    <select name="grading_period" required>
                        @foreach($gradingPeriods as $gp)
                            <option value="{{ $gp }}" {{ $grade->grading_period == $gp ? 'selected' : '' }}>{{ $gp }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Grade Value *</label>
                    <input type="number" name="grade_value" value="{{ $grade->grade_value }}" min="0" max="100" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" rows="3">{{ $grade->remarks }}</textarea>
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit" class="btn-primary">Update Grade</button>
                    <a href="{{ route('admin.grades.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

