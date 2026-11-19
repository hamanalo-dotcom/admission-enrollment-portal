@extends('layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background-color: #f5f7fa;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .card-header h1 {
            font-size: 28px;
            color: #2c5aa0;
            font-weight: 700;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c5aa0;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #f9fbfd;
            color: #2c5aa0;
        }

        .form-control:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
            background-color: white;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #f9fbfd;
            color: #2c5aa0;
        }

        .form-select:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
        }

        .btn-secondary {
            background: #f5f7fa;
            color: #666;
            border: 2px solid #d4e5f3;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>

    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h1>Create New Event</h1>
                <a href="{{ route('registrar.events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('registrar.events.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title" class="form-label">Event Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="event_date" class="form-label">Event Date <span style="color: red;">*</span></label>
                    <input type="date" name="event_date" id="event_date" class="form-control" value="{{ old('event_date') }}" required>
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">Event Type <span style="color: red;">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        <option value="admission" {{ old('type') == 'admission' ? 'selected' : '' }}>Admission</option>
                        <option value="enrollment" {{ old('type') == 'enrollment' ? 'selected' : '' }}>Enrollment</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="form-label" style="margin: 0;">Active Event</label>
                    </div>
                    <small style="color: #666; font-size: 12px;">Active events will appear on the dashboard calendar.</small>
                </div>

                <div class="btn-group">
                    <a href="{{ route('registrar.events.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

