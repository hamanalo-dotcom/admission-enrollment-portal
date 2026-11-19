<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Status Lookup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .status-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 600px;
            width: 100%;
        }
        .status-card h3 {
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 15px;
        }
        .status-card p.lead {
            color: #6c757d;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 14px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            border: none;
            padding: 12px 18px;
            font-weight: 600;
            border-radius: 10px;
        }
        .result {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #e2e8f0;
        }
        .badge-status {
            font-size: 0.95rem;
            padding: 6px 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="status-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <h3 class="mb-0"><i class="fas fa-search"></i> Check Admission Status</h3>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
        <p class="lead">Enter your Admission Reference Number to view your status.</p>

        <form method="GET" action="{{ route('admission.status') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="ref" class="form-control" placeholder="e.g. ADM-2025-00001" value="{{ $ref ?? '' }}" required>
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>

        @if(!empty($ref) && !$admission)
            <div class="alert alert-warning mb-0">
                No admission found for reference number: <strong>{{ $ref }}</strong>.
                Please check the number and try again.
            </div>
        @endif

        @if($admission)
            <div class="result">
                <h5 class="mb-2"><i class="fas fa-file-alt"></i> Admission Details</h5>
                <p class="mb-1"><strong>Reference #:</strong> {{ $admission->reference_number }}</p>
                <p class="mb-1"><strong>Name:</strong> {{ $admission->first_name }} {{ $admission->last_name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $admission->email }}</p>
                <p class="mb-1"><strong>Grade Level:</strong> {{ $admission->year_level ?? 'N/A' }}</p>
                <p class="mb-2">
                    <strong>Status:</strong>
                    <span class="badge-status bg-{{ $admission->status === 'approved' ? 'success' : ($admission->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($admission->status) }}
                    </span>
                </p>
                @if(!empty($admission->rejection_remarks))
                    <p class="mb-1 text-danger"><strong>Remarks:</strong> {{ $admission->rejection_remarks }}</p>
                @endif
            </div>
        @endif
    </div>
</body>
</html>

