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
            max-width: 900px;
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

        .profile-form {
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

        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 15px;
            }
        }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1>Edit Profile</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-form">
            <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Picture Section -->
                <div class="form-group full-width" style="margin-bottom: 30px;">
                    <label style="margin-bottom: 15px;">Profile Picture</label>
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 15px;">
                        @if($user->profile_picture)
                            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid #2c5aa0; flex-shrink: 0;">
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: 700; flex-shrink: 0;">
                                {{ strtoupper(substr($user->firstname ?? '', 0, 1) . substr($user->lastname ?? '', 0, 1)) }}
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg,image/jpg,image/png" style="padding: 8px; cursor: pointer;" onchange="previewImage(this)">
                            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                Accepted formats: JPEG, JPG, PNG (Max: 4MB)
                            </div>
                            @error('profile_picture')
                                <span style="color: #e74c3c; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstname">First Name *</label>
                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
                        @error('firstname')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" value="{{ old('middlename', $user->middlename) }}">
                        @error('middlename')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name *</label>
                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                        @error('lastname')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birth Date *</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}" required>
                        @error('birthdate')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex *</label>
                        <select id="sex" name="sex" required>
                            <option value="male" {{ old('sex', $user->sex) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $user->sex) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('sex', $user->sex) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('sex')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact_no">Contact Number *</label>
                        <input type="text" id="contact_no" name="contact_no" value="{{ old('contact_no', $user->contact_no) }}" required>
                        @error('contact_no')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact *</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" required>
                        @error('emergency_contact')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address *</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required>
                        @error('address')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width" style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                        <label style="color: #666; font-size: 16px; margin-bottom: 15px;">Change Password (Leave blank to keep current password)</label>
                    </div>

                    <div class="form-group password-toggle">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" autocomplete="new-password">
                        <i class="fas fa-eye toggle-icon" onclick="togglePassword('password', this)"></i>
                        @error('password')
                            <span style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group password-toggle">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        <i class="fas fa-eye toggle-icon" onclick="togglePassword('password_confirmation', this)"></i>
                    </div>

                    <div class="form-group full-width" style="margin-top: 20px;">
                        <button type="submit" class="btn-primary">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = input.closest('.form-group').querySelector('div[style*="display: flex"]');
                    const existingImg = container.querySelector('img');
                    const existingDiv = container.querySelector('div[style*="border-radius: 50%"]');
                    
                    if (existingImg) {
                        existingImg.src = e.target.result;
                    } else if (existingDiv) {
                        existingDiv.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection

