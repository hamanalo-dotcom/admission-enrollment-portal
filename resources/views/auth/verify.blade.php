@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="alert alert-info">
            <h5 class="mb-2">Verify your email address</h5>
            <p class="mb-3">We sent a verification link to your email. If you didn’t receive the email, you can request another below.</p>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>
            <hr class="my-3">
            <p class="mb-2">Students: you can finalize your account via Google without filling the form.</p>
            <a href="{{ route('login.google') }}" class="btn btn-danger" style="background:#db4437;border-color:#db4437;">
                <i class="fab fa-google me-2"></i> Continue with Google
            </a>
        </div>
    </div>
@endsection


