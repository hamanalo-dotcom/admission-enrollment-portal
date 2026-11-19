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
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            font-size: 32px;
            color: #2c5aa0;
            font-weight: 700;
            margin: 0;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #2c5aa0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 10px;
        }

        .stat-card .label {
            font-size: 12px;
            color: #888;
        }

        /* Content Sections */
        .content-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c5aa0;
            margin-bottom: 20px;
            border-bottom: 2px solid #4a8fd8;
            padding-bottom: 10px;
        }


        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header-section {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>

    <div class="main-content">
        <header class="header-section">
            <h1>Teacher Dashboard</h1>
            <div>
                <span style="color: #2c5aa0; font-weight: 600;">Welcome, Teacher {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
            </div>
        </header>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Pending Enrollment</h3>
                <div class="number">{{ $totalPendingEnrollment }}</div>
                <div class="label">Requires attention</div>
            </div>

            <div class="stat-card">
                <h3>Total Enrolled</h3>
                <div class="number">{{ $totalEnrolled }}</div>
                <div class="label">✓ Active students</div>
            </div>

            <div class="stat-card">
                <h3>Pending Admission</h3>
                <div class="number">{{ $totalPendingAdmission }}</div>
                <div class="label">⚠️ Awaiting review</div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if(count($assignedGradeLevels) > 0)
        <div class="content-section">
            <h2 class="section-title">My Assigned Sections</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                @foreach($assignments as $gradeLevel => $gradeAssignments)
                    @php
                        $routeName = '';
                        if ($gradeLevel === 'Grade 7') {
                            $routeName = 'teacher.jhs.gradeSeven';
                        } elseif ($gradeLevel === 'Grade 8') {
                            $routeName = 'teacher.jhs.gradeEight';
                        } elseif ($gradeLevel === 'Grade 9') {
                            $routeName = 'teacher.jhs.gradeNine';
                        } elseif ($gradeLevel === 'Grade 10') {
                            $routeName = 'teacher.jhs.gradeTen';
                        } elseif ($gradeLevel === 'Grade 11') {
                            $routeName = 'teacher.shs.gradeEleven';
                        } elseif ($gradeLevel === 'Grade 12') {
                            $routeName = 'teacher.shs.gradeTwelve';
                        }
                    @endphp
                    @if(in_array($gradeLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10']))
                        <a href="{{ route($routeName) }}" class="btn btn-primary" style="text-decoration: none; padding: 12px 24px; border-radius: 8px; background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%); color: white; font-weight: 600; transition: all 0.3s ease;">
                            {{ $gradeLevel }} 
                            <small style="display: block; font-size: 11px; margin-top: 4px; opacity: 0.9;">
                                Sections: {{ $gradeAssignments->pluck('section')->unique()->implode(', ') }}
                            </small>
                        </a>
                    @elseif(in_array($gradeLevel, ['Grade 11', 'Grade 12']))
                        <a href="{{ route($routeName) }}" class="btn btn-primary" style="text-decoration: none; padding: 12px 24px; border-radius: 8px; background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%); color: white; font-weight: 600; transition: all 0.3s ease;">
                            {{ $gradeLevel }}
                            <small style="display: block; font-size: 11px; margin-top: 4px; opacity: 0.9;">
                                {{ $gradeAssignments->whereNotNull('strand')->pluck('strand')->unique()->count() > 0 ? 'Strands: ' . $gradeAssignments->whereNotNull('strand')->pluck('strand')->unique()->implode(', ') : 'All Strands' }}
                            </small>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        @else
        <div class="content-section">
            <h2 class="section-title">My Assigned Sections</h2>
            <p style="color: #999; text-align: center; padding: 20px;">No sections assigned yet. Please contact the administrator.</p>
        </div>
        @endif

    </div>
@endsection
