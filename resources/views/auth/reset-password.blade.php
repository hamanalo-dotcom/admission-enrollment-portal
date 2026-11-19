@extends('layout.app')

@section('content')
    <div class="container mt-4" style="max-width: 480px;">
        <h4 class="mb-3">Reset Password</h4>
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Password</button>
        </form>
    </div>
@endsection


