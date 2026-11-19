@extends('layout.app')

@section('content')
    <div class="container mt-4" style="max-width: 480px;">
        <h4 class="mb-3">Forgot Password</h4>
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
    </div>
@endsection


