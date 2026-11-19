@extends('layout.app')

@section('content')
<style>
    .container-box {
        max-width: 900px;
        margin: 30px auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        padding: 30px;
    }
    .form-label {
        font-weight: 600;
        color: #2c5aa0;
    }
    .btn-primary {
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        border: none;
        color: #fff;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
    }
</style>

<div class="container-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Add New User</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
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

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">First Name *</label>
                <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middlename" class="form-control" value="{{ old('middlename') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Last Name *</label>
                <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Birthdate *</label>
                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sex *</label>
                <select name="sex" class="form-control" required>
                    <option value="">Select</option>
                    <option value="male" {{ old('sex')=='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ old('sex')=='female'?'selected':'' }}>Female</option>
                    <option value="other" {{ old('sex')=='other'?'selected':'' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Contact No.</label>
                <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Emergency Contact</label>
                <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Role *</label>
                <select name="role" class="form-control" required>
                    <option value="">Select role</option>
                    <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="registrar" {{ old('role')=='registrar'?'selected':'' }}>Registrar</option>
                    <option value="teacher" {{ old('role')=='teacher'?'selected':'' }}>Teacher</option>
                    <option value="student" {{ old('role')=='student'?'selected':'' }}>Student</option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
</div>
@endsection
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
            max-width: 1000px;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            color: #2c5aa0;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input, select {
            padding: 12px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background-color: #f9fbfd;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
            background-color: white;
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

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1>Add New User</h1>
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
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstname">First Name *</label>
                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" value="{{ old('middlename') }}">
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name *</label>
                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        <small style="color: #666; font-size: 12px; margin-top: 5px;">Note: @deped.gov.ph emails will automatically be assigned teacher role</small>
                    </div>

                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select id="role" name="role" required>
                            <option value="">-- Select Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="registrar" {{ old('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birth Date *</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex *</label>
                        <select id="sex" name="sex" required>
                            <option value="">-- Select --</option>
                            <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('sex') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contact_no">Contact Number</label>
                        <input type="text" id="contact_no" name="contact_no" value="{{ old('contact_no') }}">
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required minlength="8">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
                    </div>

                    <div class="form-group full-width" style="margin-top: 20px; display: flex; gap: 15px;">
                        <button type="submit" class="btn btn-primary">Create User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

