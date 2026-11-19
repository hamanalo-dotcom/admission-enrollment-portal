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

        .header-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .header-section h1 {
            font-size: 32px;
            color: #2c5aa0;
            font-weight: 700;
            margin: 0;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #2c5aa0;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input, select, textarea {
            padding: 12px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background-color: #f9fbfd;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
            background-color: white;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
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
            background: #e9ecef;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        small {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1>Edit Announcement</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <form action="{{ route(auth()->user()->role === 'admin' ? 'admin.announcements.update' : 'registrar.announcements.update', $announcement->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" required placeholder="Enter announcement title">
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" required placeholder="Enter announcement message">{{ old('message', $announcement->message) }}</textarea>
                    <small>This message will be displayed on the homepage for all users.</small>
                </div>

                <div class="form-group">
                    <label for="type">Type *</label>
                    <select id="type" name="type" required>
                        <option value="info" {{ old('type', $announcement->type) == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="warning" {{ old('type', $announcement->type) == 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="success" {{ old('type', $announcement->type) == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="danger" {{ old('type', $announcement->type) == 'danger' ? 'selected' : '' }}>Danger</option>
                    </select>
                    <small>Select the type of announcement (determines color on homepage).</small>
                </div>

                <div class="form-group">
                    <label for="published_at">Published Date</label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}">
                    <small>Leave empty to publish immediately. Announcements are only shown if published date has passed.</small>
                </div>

                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="datetime-local" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $announcement->expiration_date ? $announcement->expiration_date->format('Y-m-d\TH:i') : '') }}">
                    <small>Leave empty for no expiration. Announcements will automatically stop displaying after this date.</small>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                        <label for="is_active" style="margin: 0; font-weight: normal;">Active (Visible on homepage)</label>
                    </div>
                    <small>Uncheck to hide this announcement from the homepage without deleting it.</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Announcement
                    </button>
                    <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.announcements.index' : 'registrar.announcements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

