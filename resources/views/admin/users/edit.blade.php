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
            <h1>Edit User</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstname">First Name *</label>
                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" value="{{ old('middlename', $user->middlename) }}">
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name *</label>
                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        <small style="color: #666; font-size: 12px; margin-top: 5px;">Note: @deped.gov.ph emails will automatically be assigned teacher role</small>
                    </div>

                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select id="role" name="role" required>
                            <option value="">-- Select Role --</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="registrar" {{ old('role', $user->role) == 'registrar' ? 'selected' : '' }}>Registrar</option>
                            <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birth Date *</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex *</label>
                        <select id="sex" name="sex" required>
                            <option value="">-- Select --</option>
                            <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('sex', $user->sex) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contact_no">Contact Number</label>
                        <input type="text" id="contact_no" name="contact_no" value="{{ old('contact_no', $user->contact_no) }}">
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Password</label>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <button type="button" class="btn btn-primary" style="padding: 12px 30px;" data-bs-toggle="modal" data-bs-target="#passwordModal">Generate New Password</button>
                            <small style="color: #666; font-size: 12px;">Click the button to generate a random password for this user.</small>
                        </div>
                    </div>

                    <div class="form-group full-width" style="margin-top: 20px; display: flex; gap: 15px;">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Generation Modal (outside main form to prevent nesting) -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Generate New Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Click the button below to generate a random password. Review it before confirming.</p>
                    <div id="passwordDisplay" style="display: none; margin: 20px 0;">
                        <label style="font-weight: 600; color: #2c5aa0;">Generated Password:</label>
                        <div style="background: #f5f7fa; padding: 15px; border-radius: 8px; border: 2px solid #d4e5f3; margin-top: 10px;">
                            <code id="generatedPassword" style="font-size: 18px; font-weight: bold; color: #2c5aa0; letter-spacing: 2px;"></code>
                        </div>
                        <p style="color: #721c24; font-size: 12px; margin-top: 10px;"><strong>Warning:</strong> Make sure to copy this password before confirming. You won't be able to see it again!</p>
                    </div>
                    <button type="button" class="btn btn-primary" id="generatePasswordBtn" onclick="generatePassword()">Generate Password</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.users.changePassword', $user->id) }}" method="POST" id="confirmPasswordForm" style="display: none;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="password" id="passwordInput" value="" autocomplete="off">
                        <button type="submit" class="btn btn-primary" id="savePasswordBtn">Confirm & Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let generatedPasswordValue = '';
                        
                        function generatePassword() {
                            const url = '{{ route("admin.users.generatePassword") }}';
                            console.log('Fetching password from:', url);
                            
                            fetch(url, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                },
                                credentials: 'same-origin'
                            })
                                .then(response => {
                                    console.log('Response status:', response.status);
                                    if (!response.ok) {
                                        return response.text().then(text => {
                                            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Password generated:', data);
                                    if (data && data.password) {
                                        generatedPasswordValue = data.password;
                                        
                                        // Get elements with null checks
                                        const generatedPasswordEl = document.getElementById('generatedPassword');
                                        const passwordDisplayEl = document.getElementById('passwordDisplay');
                                        const passwordInputEl = document.getElementById('passwordInput');
                                        const confirmPasswordFormEl = document.getElementById('confirmPasswordForm');
                                        const generatePasswordBtnEl = document.getElementById('generatePasswordBtn');
                                        
                                        if (generatedPasswordEl) {
                                            generatedPasswordEl.textContent = data.password;
                                        }
                                        if (passwordDisplayEl) {
                                            passwordDisplayEl.style.display = 'block';
                                        }
                                        if (passwordInputEl) {
                                            // Set the password value - this is critical!
                                            passwordInputEl.value = data.password;
                                            generatedPasswordValue = data.password; // Store in global variable
                                            console.log('Password set in hidden input:', passwordInputEl.value ? 'Yes' : 'No');
                                            console.log('Password length:', passwordInputEl.value.length);
                                            console.log('Password value (first 3 chars):', passwordInputEl.value.substring(0, 3));
                                            console.log('Generated password value stored:', generatedPasswordValue ? 'Yes' : 'No');
                                        }
                                        if (confirmPasswordFormEl) {
                                            confirmPasswordFormEl.style.display = 'inline-block';
                                        }
                                        if (generatePasswordBtnEl) {
                                            generatePasswordBtnEl.textContent = 'Regenerate Password';
                                        }
                                        
                                        console.log('Password generation complete. Ready to save.');
                                    } else {
                                        throw new Error('Invalid response format');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error details:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Password Generation Error',
                                        text: 'Error generating password: ' + error.message + '. Please try again.',
                                        confirmButtonColor: '#2c5aa0'
                                    });
                                });
                        }

                        // Handle form submission
                        document.addEventListener('DOMContentLoaded', function() {
                            const confirmPasswordForm = document.getElementById('confirmPasswordForm');
                            if (confirmPasswordForm) {
                                confirmPasswordForm.addEventListener('submit', function(e) {
                                    const passwordInput = document.getElementById('passwordInput');
                                    console.log('Form submitting...', {
                                        hasPasswordInput: !!passwordInput,
                                        passwordValue: passwordInput ? passwordInput.value : 'N/A',
                                        passwordLength: passwordInput ? passwordInput.value.length : 0,
                                        generatedPasswordValue: generatedPasswordValue ? generatedPasswordValue.length : 0
                                    });
                                    
                                    // CRITICAL: Ensure the password value matches what was generated
                                    if (generatedPasswordValue && passwordInput) {
                                        passwordInput.value = generatedPasswordValue;
                                        console.log('Setting password from generatedPasswordValue:', passwordInput.value.length);
                                    } else if (!passwordInput.value && generatedPasswordValue) {
                                        // Fallback: if input is empty but we have the value, set it
                                        passwordInput.value = generatedPasswordValue;
                                        console.log('Fallback: Setting password from generatedPasswordValue');
                                    }
                                    
                                    // Final check before submission
                                    if (!passwordInput || !passwordInput.value || passwordInput.value.trim() === '') {
                                        e.preventDefault();
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Password Required',
                                            text: 'Please generate a password first.',
                                            confirmButtonColor: '#2c5aa0'
                                        });
                                        console.error('Password is empty, preventing submission');
                                        return false;
                                    }
                                    
                                    // Log final password value before submission
                                    console.log('FINAL CHECK - Password being submitted:', {
                                        length: passwordInput.value.length,
                                        preview: passwordInput.value.substring(0, 5) + '...',
                                        hasValue: !!passwordInput.value
                                    });
                                    
                                    // Show loading state
                                    const saveBtn = document.getElementById('savePasswordBtn');
                                    if (saveBtn) {
                                        saveBtn.disabled = true;
                                        saveBtn.textContent = 'Saving...';
                                    }
                                    
                                    // Verify the form action is correct
                                    console.log('Form action:', confirmPasswordForm.action);
                                    console.log('Form method:', confirmPasswordForm.method);
                                    console.log('Password being submitted:', passwordInput.value.substring(0, 5) + '...');
                                    
                                    // Let the form submit normally - don't prevent default
                                    // The form will POST to the server and the page will reload with success message
                                    return true;
                                });
                            }
                            
                            // Reset modal when closed
                            const passwordModal = document.getElementById('passwordModal');
                            if (passwordModal) {
                                passwordModal.addEventListener('hidden.bs.modal', function () {
                                    const passwordDisplayEl = document.getElementById('passwordDisplay');
                                    const confirmPasswordFormEl = document.getElementById('confirmPasswordForm');
                                    const generatePasswordBtnEl = document.getElementById('generatePasswordBtn');
                                    const saveBtn = document.getElementById('savePasswordBtn');
                                    
                                    if (passwordDisplayEl) {
                                        passwordDisplayEl.style.display = 'none';
                                    }
                                    if (confirmPasswordFormEl) {
                                        confirmPasswordFormEl.style.display = 'none';
                                    }
                                    if (generatePasswordBtnEl) {
                                        generatePasswordBtnEl.textContent = 'Generate Password';
                                    }
                                    if (saveBtn) {
                                        saveBtn.disabled = false;
                                        saveBtn.textContent = 'Confirm & Save';
                                    }
                                    generatedPasswordValue = '';
                                });
                            }
                        });
    </script>
@endsection

