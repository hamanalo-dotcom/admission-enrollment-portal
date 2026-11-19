@extends('layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { background-color: #f5f7fa; color: #333; font-family: 'Poppins', sans-serif; }
        .main-content { padding: 30px; }
        .header-section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .header-section h1 { font-size: 28px; color: #2c5aa0; font-weight: 700; margin: 0; }
        
        /* Tabs */
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e9ecef; }
        .tab-btn { padding: 12px 24px; background: transparent; border: none; border-bottom: 3px solid transparent; color: #666; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .tab-btn:hover { color: #2c5aa0; }
        .tab-btn.active { color: #2c5aa0; border-bottom-color: #2c5aa0; }
        
        /* Tab Content */
        .tab-content { display: none; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .tab-content.active { display: block; }
        
        /* Search and Filters */
        .search-filter { display: flex; gap: 10px; margin-bottom: 20px; align-items: end; }
        .search-filter input, .search-filter select { padding: 10px; border: 2px solid #d4e5f3; border-radius: 8px; font-size: 14px; background: #f9fbfd; }
        .search-filter input { flex: 1; }
        .search-filter select { width: 200px; }
        .btn-filter { background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        
        /* Tables */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2c5aa0; color: white; text-align: left; padding: 12px; font-size: 13px; font-weight: 600; }
        td { padding: 12px; border-bottom: 1px solid #e9ecef; font-size: 14px; }
        tr:hover { background: #f8f9fa; }
        
        /* Report Cards */
        .reports-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .report-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .report-card:hover { transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .report-card h3 { color: #2c5aa0; font-size: 18px; font-weight: 600; margin: 0 0 10px 0; }
        .report-card p { color: #666; font-size: 14px; margin: 0 0 15px 0; }
        .report-card .icon { font-size: 32px; color: #2c5aa0; margin-bottom: 15px; }
        .btn-report { background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.3s ease; }
        .btn-report:hover { background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%); transform: translateY(-2px); }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-muted { background: #eef2f7; color: #4f6279; }
    </style>

    <div class="main-content">
        <div class="header-section">
            <h1>Reports Dashboard</h1>
            <p style="color: #666; margin: 10px 0 0 0;">View comprehensive lists and generate various reports</p>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('students')">Students</button>
            <button class="tab-btn" onclick="showTab('sections')">Sections</button>
            <button class="tab-btn" onclick="showTab('school-years')">School Years</button>
            <button class="tab-btn" onclick="showTab('instructors')">Instructors</button>
            <button class="tab-btn" onclick="showTab('subjects')">Subjects</button>
            <button class="tab-btn" onclick="showTab('reports')">Generate Reports</button>
        </div>

        <!-- Students Tab -->
        <div id="students" class="tab-content active">
            <h2 style="color: #2c5aa0; margin-bottom: 20px;">List of Students</h2>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="search-filter">
                <input type="hidden" name="tab" value="students">
                <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="Search by name or email...">
                <button type="submit" class="btn-filter">Search</button>
                <a href="{{ route('admin.reports.index', ['tab' => 'students']) }}" class="btn-filter" style="text-decoration: none; background: #6c757d;">Reset</a>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td><strong>{{ $student->firstname }} {{ $student->lastname }}</strong></td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->contact_no ?? '-' }}</td>
                                <td>{{ $student->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; color: #999; padding: 20px;">No students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 15px;">{{ $students->appends(request()->except('students_page'))->links() }}</div>
        </div>

        <!-- Sections Tab -->
        <div id="sections" class="tab-content">
            <h2 style="color: #2c5aa0; margin-bottom: 20px;">List of Sections</h2>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="search-filter">
                <input type="hidden" name="tab" value="sections">
                <select name="section_grade_level">
                    <option value="">All Grade Levels</option>
                    @foreach($gradeLevels as $gl)
                        <option value="{{ $gl }}" {{ request('section_grade_level') == $gl ? 'selected' : '' }}>{{ $gl }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-filter">Filter</button>
                <a href="{{ route('admin.reports.index', ['tab' => 'sections']) }}" class="btn-filter" style="text-decoration: none; background: #6c757d;">Reset</a>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Grade Level</th>
                            <th>Section Name</th>
                            <th>Strand</th>
                            <th>Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td><strong>{{ $section->grade_level }}</strong></td>
                                <td>{{ $section->name }}</td>
                                <td>
                                    @if($section->strand)
                                        <span class="badge badge-muted">{{ $section->strand }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $section->capacity }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; color: #999; padding: 20px;">No sections found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- School Years Tab -->
        <div id="school-years" class="tab-content">
            <h2 style="color: #2c5aa0; margin-bottom: 20px;">School Years</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolYears as $schoolYear)
                            <tr>
                                <td><strong>{{ $schoolYear->school_year }}</strong></td>
                                <td>
                                    @if(isset($schoolYear->is_active) && $schoolYear->is_active)
                                        <span class="badge" style="background: #d4edda; color: #155724;">Active</span>
                                    @else
                                        <span class="badge badge-muted">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; color: #999; padding: 20px;">No school years found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Instructors Tab -->
        <div id="instructors" class="tab-content">
            <h2 style="color: #2c5aa0; margin-bottom: 20px;">List of Instructors (Teachers)</h2>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="search-filter">
                <input type="hidden" name="tab" value="instructors">
                <input type="text" name="instructor_search" value="{{ request('instructor_search') }}" placeholder="Search by name or email...">
                <button type="submit" class="btn-filter">Search</button>
                <a href="{{ route('admin.reports.index', ['tab' => 'instructors']) }}" class="btn-filter" style="text-decoration: none; background: #6c757d;">Reset</a>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $instructor)
                            <tr>
                                <td><strong>{{ $instructor->firstname }} {{ $instructor->lastname }}</strong></td>
                                <td>{{ $instructor->email }}</td>
                                <td>{{ $instructor->contact_no ?? '-' }}</td>
                                <td>{{ $instructor->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; color: #999; padding: 20px;">No instructors found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 15px;">{{ $instructors->appends(request()->except('instructors_page'))->links() }}</div>
        </div>

        <!-- Subjects Tab -->
        <div id="subjects" class="tab-content">
            <h2 style="color: #2c5aa0; margin-bottom: 20px;">List of Subjects</h2>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="search-filter">
                <input type="hidden" name="tab" value="subjects">
                <select name="subject_grade_level">
                    <option value="">All Grade Levels</option>
                    @foreach($gradeLevels as $gl)
                        <option value="{{ $gl }}" {{ request('subject_grade_level') == $gl ? 'selected' : '' }}>{{ $gl }}</option>
                    @endforeach
                </select>
                <select name="subject_strand">
                    <option value="">All Strands</option>
                    @foreach($strands as $strand)
                        <option value="{{ $strand }}" {{ request('subject_strand') == $strand ? 'selected' : '' }}>{{ $strand }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-filter">Filter</button>
                <a href="{{ route('admin.reports.index', ['tab' => 'subjects']) }}" class="btn-filter" style="text-decoration: none; background: #6c757d;">Reset</a>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Code</th>
                            <th>Grade Level</th>
                            <th>Strand</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td><strong>{{ $subject->name }}</strong></td>
                                <td>{{ $subject->code ?? '-' }}</td>
                                <td>{{ $subject->grade_level ?? 'All' }}</td>
                                <td>
                                    @if($subject->strand)
                                        <span class="badge badge-muted">{{ $subject->strand }}</span>
                                    @else
                                        {{ in_array($subject->grade_level, ['Grade 11', 'Grade 12']) ? 'All' : '-' }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align: center; color: #999; padding: 20px;">No subjects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Generate Reports Tab -->
        <div id="reports" class="tab-content">
            <h2 style="color: #2c5aa0; margin-bottom: 20px;">Generate Reports</h2>
            <div class="reports-grid">
                <div class="report-card">
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Enrollment Report</h3>
                    <p>View detailed enrollment information by grade level, section, status, and school year.</p>
                    <a href="{{ route('admin.reports.enrollment') }}" class="btn-report">View Report</a>
                </div>

                <div class="report-card">
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Admission Report</h3>
                    <p>Generate reports on student admissions with status, date range, and year level filters.</p>
                    <a href="{{ route('admin.reports.admission') }}" class="btn-report">View Report</a>
                </div>

                <div class="report-card">
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Grade Report</h3>
                    <p>View student grades by subject, grading period, section, and school year with summary statistics.</p>
                    <a href="{{ route('admin.reports.grade') }}" class="btn-report">View Report</a>
                </div>

                <div class="report-card">
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>Teacher Assignment Report</h3>
                    <p>View all teacher assignments by grade level, section, subject, and school year.</p>
                    <a href="{{ route('admin.reports.teacherAssignment') }}" class="btn-report">View Report</a>
                </div>

                <div class="report-card">
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Statistics Report</h3>
                    <p>View overall statistics including student counts, enrollment breakdowns, and admission status.</p>
                    <a href="{{ route('admin.reports.statistics') }}" class="btn-report">View Report</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set active tab from URL parameter or default to first tab
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'students';
        
        // Show the active tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            showTab(activeTab, false);
        });
        
        function showTab(tabName, updateUrl = true) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            const tabContent = document.getElementById(tabName);
            if (tabContent) {
                tabContent.classList.add('active');
            }
            
            // Add active class to corresponding button
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabNames = ['students', 'sections', 'school-years', 'instructors', 'subjects', 'reports'];
            const tabIndex = tabNames.indexOf(tabName);
            if (tabIndex !== -1 && tabButtons[tabIndex]) {
                tabButtons[tabIndex].classList.add('active');
            }
            
            // Update URL without reloading
            if (updateUrl) {
                const url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.pushState({}, '', url);
            }
        }
        
        // Make tab buttons clickable
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tab-btn').forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const tabNames = ['students', 'sections', 'school-years', 'instructors', 'subjects', 'reports'];
                    showTab(tabNames[index], true);
                });
            });
        });
    </script>
@endsection
