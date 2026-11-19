<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $gradeLevel }} - {{ $strand }} | Students</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        h2 { 
            margin: 0 0 10px; 
            text-align: center;
            color: #333;
        }
        .meta { 
            margin-bottom: 12px; 
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .school-name {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background: #f2f2f2; 
            font-weight: bold;
        }
        .text-center { 
            text-align: center; 
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 5px;">
        <img src="{{ public_path('img/logo.png') }}" alt="School Logo" style="height: 60px; margin-bottom: 4px;">
    </div>
    <div class="school-name">San Roque High School</div>
    <h2>{{ $gradeLevel }} - {{ $strand }}</h2>
    <div class="meta">
        Generated on: {{ now()->format('F d, Y h:i A') }}
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 30%">Student Name</th>
                <th style="width: 10%">Grade Level</th>
                <th style="width: 10%" class="text-center">Strand</th>
                <th style="width: 10%" class="text-center">Section</th>
                <th style="width: 15%" class="text-center">School Year</th>
                <th style="width: 10%" class="text-center">Semester</th>
                <th style="width: 10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($students as $index => $enrollment)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{ $enrollment->admission->last_name ?? '' }}, {{ $enrollment->admission->first_name ?? '' }}
                </td>
                <td>{{ $enrollment->grade_level }}</td>
                <td class="text-center">{{ $enrollment->strand ?? '-' }}</td>
                <td class="text-center">{{ $enrollment->section ?? '-' }}</td>
                <td class="text-center">{{ $enrollment->school_year ?? '-' }}</td>
                <td class="text-center">{{ $enrollment->semester ?? '-' }}</td>
                <td class="text-center">{{ ucfirst($enrollment->status ?? 'enrolled') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="no-data">No students found</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    @if($students->count() > 0)
    <div class="meta" style="margin-top: 15px;">
        Total Students: {{ $students->count() }}
    </div>
    @endif
</body>
</html>
