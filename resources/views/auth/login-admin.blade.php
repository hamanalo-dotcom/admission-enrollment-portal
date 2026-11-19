<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body,
        html {
            height: 100%;
            background: linear-gradient(135deg, #4a8fd8 0%, #6ba3e5 50%, #7fb3ed 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #495057;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            width: 100%;
            padding-right: 80px;
            padding-left: 60px;
        }

        .login-box {
            background: linear-gradient(135deg, #ffffff 0%, #e8f1f9 50%, #d4e7f5 100%);
            padding: 40px;
            border-radius: 12px;
            width: 375px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(30, 58, 95, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: fadeInBox 1s ease-in-out;
        }

        @keyframes fadeInBox {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo img {
            max-width: 100px;
            margin-bottom: 20px;
        }

        h2 {
            margin: 16px 0;
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }

        .role-badge {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        form {
            width: 100%;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #2c5aa0;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #d4e5f3;
            border-radius: 6px;
            font-size: 16px;
            color: #2c5aa0;
            background-color: #f9fbfd;
            box-shadow: 0 2px 6px rgba(44, 90, 160, 0.05);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-group input:focus {
            border-color: #2c5aa0;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.15);
            outline: none;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        .submit-button:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
        }

        .submit-button.loading::after {
            content: "";
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-top-color: transparent;
            border-radius: 50%;
            position: absolute;
            right: 15px;
            animation: spinner 0.6s linear infinite;
        }

        .submit-button.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .background-content {
            position: relative;
            left: 0;
            width: 45%;
            color: white;
            z-index: 1;
            animation: fadeInLeft 1s ease-in-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .background-content h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .background-content p {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.6;
            opacity: 0.95;
        }

        .background-content ul {
            list-style: none;
            font-size: 15px;
            line-height: 1.8;
        }

        .background-content ul li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .background-content ul li:before {
            content: "✓";
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 18px;
        }

        .alert-error {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 14px;
        }

        .login-links {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
        }

        .login-links a {
            color: #2c5aa0;
            text-decoration: none;
            margin: 0 5px;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        .google-signin-btn {
            width: 100%;
            padding: 12px;
            background: #fff;
            border: 2px solid #4285f4;
            border-radius: 6px;
            color: #4285f4;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(66, 133, 244, 0.2);
            margin-bottom: 15px;
        }

        .google-signin-btn:hover {
            background: #f8f9fa;
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
            transform: translateY(-1px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #666;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider span {
            padding: 0 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding-right: 20px;
                padding-left: 20px;
                justify-content: center;
            }

            .login-box {
                width: 100%;
                max-width: 375px;
            }

            .background-content {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="background-content">
            <h1>Admin Portal</h1>
            <p>System administration and management</p>
            <ul>
                <li>Manage school years and academic settings</li>
                <li>View comprehensive reports and statistics</li>
                <li>Manage subjects and sections</li>
                <li>Oversee user accounts and permissions</li>
                <li>Monitor system usage and activity</li>
            </ul>
        </div>
        <div class="login-box">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="San Roque High School Logo">
            </div>
            <h2>Admin Login</h2>
            <div class="role-badge">ADMIN PORTAL</div>
            
            @if($errors->any())
                <div class="alert-error">
                    <strong style="display: block; margin-bottom: 5px;">⚠️ Login Failed</strong>
                    <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" onsubmit="showLoading(event)">
                @csrf
                <input type="hidden" name="role" value="admin">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required style="@error('email') border-color: #c33; @enderror">
                    @error('email')
                        <span style="color: #c33; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required style="@error('password') border-color: #c33; @enderror">
                    @error('password')
                        <span style="color: #c33; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="input-group">
                    <a href="{{ route('login.google', ['role' => 'admin']) }}" class="google-signin-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Sign in with Google
                    </a>
                    
                    <div class="divider">
                        <span>OR</span>
                    </div>
                </div>
                
                <div class="input-group">
                    <button class="submit-button" type="submit">Login</button>
                </div>
            </form>
            
            <div class="login-links">
                Login as: 
                <a href="{{ route('login.student') }}">Student</a> | 
                <a href="{{ route('login.teacher') }}">Teacher</a> | 
                <a href="{{ route('login.registrar') }}">Registrar</a>
            </div>
        </div>
    </div>

    <script>
        function showLoading(event) {
            const button = document.querySelector('.submit-button');
            button.classList.add('loading');
            button.disabled = true;
        }
    </script>
</body>

</html>

