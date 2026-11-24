@if(isset($isPublic) && $isPublic)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Success - San Roque High School</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
    </style>
</head>
<body>
@else
@extends('layout.app')

@section('content')
@endif

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="text-success mb-3">Admission Application Submitted Successfully!</h2>
                    <p class="text-muted mb-4">Your admission application has been received and is now under review.</p>
                    
                    <div class="alert alert-info mb-4">
                        <h5 class="mb-2">Admission Reference Number</h5>
                        <h3 class="text-primary mb-0">{{ $admission->reference_number ?? 'N/A' }}</h3>
                        <small class="text-muted">Please save this reference number for future reference.</small>
                    </div>

                    <div class="row mt-4">
    <div class="col-md-4 mb-3">
        <a href="{{ route('admission.download.pdf', $admission->id) }}"
           class="btn btn-success w-100">
            <i class="fas fa-download"></i> Download PDF
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <button onclick="window.print()" class="btn btn-primary w-100">
            <i class="fas fa-print"></i> Print Application
        </button>
    </div>
</div>
                        <div class="col-md-4 mb-3">
                            @if(Auth::check())
                                <a href="{{ route('student.admissions.show', $admission->id) }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> Check Admission Status
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-sign-in-alt"></i> Login to Check Status
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-link">Return to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($isPublic) && $isPublic)
</body>
</html>
@else
@endsection
@endif

