<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            background: linear-gradient(135deg, #4a8fd8 0%, #6ba3e5 50%, #7fb3ed 100%);
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:20px;
        }

        .register-box{
            background: linear-gradient(135deg, #ffffff 0%, #e8f1f9 50%, #d4e7f5 100%);
            padding:40px 30px;
            border-radius:12px;
            width:90%;
            max-width:520px;
            box-shadow:0 8px 32px rgba(30,58,95,0.25);
            border:1px solid rgba(255,255,255,0.5);
            text-align:center;
        }

        .logo img{
            max-width:80px;
            margin:0 auto 20px;
            display:block;
        }

        h2{
            color:#2c5aa0;
            font-weight:700;
            margin-bottom:10px;
        }

        p{
            color:#4a6b8a;
        }

        .btn-google{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            padding:12px 16px;
            background:#db4437;
            color:white;
            border:none;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
            box-shadow:0 4px 12px rgba(219,68,55,0.2);
            transition:0.3s;
        }

        .btn-google:hover{
            transform:translateY(-2px);
            opacity:0.95;
        }

        .login-link{
            margin-top:18px;
        }

        .login-link a{
            color:#2c5aa0;
            font-weight:600;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="register-box">

    <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
    </div>

    <h2>Create an Account</h2>

    <p style="margin-bottom:18px; font-weight:600;">
        Students: use Google to create your account.
    </p>

    <a href="{{ route('register.google') }}" class="btn-google">
        <i class="fab fa-google"></i>
        Continue with Google
    </a>

    <p style="margin-top:16px;">
        Make sure your admission is approved and use the same email you applied with.
    </p>

    <div class="login-link">
        Already have an account?
        <a href="{{ route('home') }}">Sign in</a>
    </div>

</div>

</body>
</html>