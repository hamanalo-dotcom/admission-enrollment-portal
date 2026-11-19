<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Login Code</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .email-display {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 25px;
            word-break: break-all;
        }
        .email-display strong {
            color: #667eea;
            font-size: 16px;
        }
        input[type="text"] {
            width: 100%;
            padding: 18px;
            font-size: 32px;
            text-align: center;
            letter-spacing: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
            transition: all 0.3s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        button {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 12px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .icon {
            text-align: center;
            font-size: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔐</div>
        <h2>Enter Verification Code</h2>
        <p class="subtitle">Check your email for the 6-digit code</p>

        @if(session('message'))
            <div class="message success">{{ session('message') }}</div>
        @endif

        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @error('error')
            <div class="message error">{{ $message }}</div>
        @enderror

        <div class="email-display">
            📧 Code sent to: <br><strong>{{ session('email') }}</strong>
        </div>

        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf
            
            <input 
                type="text" 
                name="code" 
                placeholder="000000"
                maxlength="6" 
                pattern="[0-9]{6}"
                inputmode="numeric"
                required 
                autofocus
                autocomplete="off"
            >
            
            @error('code')
                <div class="message error">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-primary">✓ Verify Code</button>
        </form>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-secondary">↻ Resend Code</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
    </div>

    <script>
        // Auto-format input (numbers only)
        document.querySelector('input[name="code"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Auto-submit when 6 digits entered
        document.querySelector('input[name="code"]').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                this.form.submit();
            }
        });
    </script>
</body>
</html>