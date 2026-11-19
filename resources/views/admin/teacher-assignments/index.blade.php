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

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box, .select-filter {
            padding: 12px 18px;
            border: 2px solid #d4e5f3;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fbfd;
            color: #2c5aa0;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-box:focus, .select-filter:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.15);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.3);
        }

        .btn-icon {
            padding: 8px 14px;
            border: 2px solid #d4e5f3;
            background-color: white;
            color: #2c5aa0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-icon:hover {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            border-color: #2c5aa0;
            transform: translateY(-2px);
        }

        .btn-icon-danger {
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .btn-icon-danger:hover {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border-color: #e74c3c;
            color: white;
        }

        .table-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .table thead tr {
            background-color: #f5f7fa;
            border-bottom: 2px solid #4a8fd8;
        }

        .table th {
            padding: 18px;
            text-align: left;
            font-weight: 700;
            color: #2c5aa0;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 18px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #555;
        }

        .table tbody tr:hover {
            background-color: #f9fbfd;
        }

        .empty-state {
            text-align: center;
            padding: 60px 40px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #d4e5f3;
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>👨‍🏫 Teacher Assignments</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.teacher-assignments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Assign Teacher
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
            <form method="GET" action="{{ route('admin.teacher-assignments.index') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <input type="text" name="search" class="search-box" placeholder="Search by teacher name or email..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
                <select name="grade_level" class="select-filter" style="min-width: 150px;">
                    <option value="">All Grades</option>
                    @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $grade)
                        <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                    @endforeach
                </select>
                <select name="strand" id="strandFilter" class="select-filter" style="min-width: 150px; display: none;">
                    <option value="">All Strands</option>
                    @foreach(($strands ?? ['GAS','TVL']) as $strand)
                        <option value="{{ $strand }}" {{ request('strand') == $strand ? 'selected' : '' }}>{{ $strand }}</option>
                    @endforeach
                </select>
                <select name="school_year" class="select-filter" style="min-width: 150px;">
                    <option value="">All School Years</option>
                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        @php
                            $schoolYear = $year . '-' . ($year + 1);
                        @endphp
                        <option value="{{ $schoolYear }}" {{ request('school_year') == $schoolYear ? 'selected' : '' }}>{{ $schoolYear }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search') || request('grade_level') || request('school_year') || request('strand'))
                    <a href="{{ route('admin.teacher-assignments.index') }}" class="btn" style="background: #f5f7fa; color: #666; border: 2px solid #d4e5f3;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
            <script>
                (function() {
                    const gradeSelect = document.querySelector('select[name="grade_level"]');
                    const strandFilter = document.getElementById('strandFilter');
                    function toggleStrand() {
                        const val = gradeSelect.value;
                        if (val === 'Grade 11' || val === 'Grade 12') {
                            strandFilter.style.display = '';
                        } else {
                            strandFilter.style.display = 'none';
                            if (strandFilter) strandFilter.value = '';
                        }
                    }
                    gradeSelect.addEventListener('change', toggleStrand);
                    // initialize on load
                    toggleStrand();
                })();
            </script>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Strand</th>
                            <th>School Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    @if($assignment->teacher)
                                        Teacher {{ $assignment->teacher->firstname }} {{ $assignment->teacher->lastname }}
                                        <br>
                                        <small style="color: #999;">{{ $assignment->teacher->email }}</small>
                                    @else
                                        <span style="color: #999;">Teacher not found</span>
                                    @endif
                                </td>
                                <td>{{ $assignment->grade_level }}</td>
                                <td>
                                    @if(in_array($assignment->grade_level, ['Grade 11','Grade 12']))
                                        @if($assignment->strand === 'GAS')
                                            GAS - {{ $assignment->section }}
                                        @elseif($assignment->strand === 'TVL')
                                            TVL-{{ $assignment->tvl_branch ?? '—' }} - {{ $assignment->section }}
                                        @else
                                            {{ $assignment->section }}
                                        @endif
                                    @else
                                        {{ $assignment->section }}
                                    @endif
                                </td>
                                <td>{{ $assignment->strand ?? '-' }}</td>
                                <td>{{ $assignment->school_year }}</td>
                                <td>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="{{ route('admin.teacher-assignments.edit', $assignment->id) }}" class="btn-icon" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- Delete functionality removed to preserve all data --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <p><strong>No teacher assignments found</strong></p>
                                        <p style="font-size: 13px; color: #999; margin-top: 8px;">Assign teachers to sections to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($assignments->hasPages())
                <div style="margin-top: 30px; display: flex; justify-content: center;">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

