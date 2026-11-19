<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    .sidenav {
        width: 250px;
        background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
        color: white;
        padding: 30px 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        left: 0;
        top: 0;
    }

    .sidenav::-webkit-scrollbar {
        width: 6px;
    }

    .sidenav::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidenav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    .close-btn {
        display: none !important;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        float: right;
        margin-right: 15px;
        margin-top: 10px;
    }

    /* Sidebar overlay - completely disabled on desktop */
    .sidebar-overlay {
        display: none !important;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
        pointer-events: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
    }
    
    /* Only enable overlay on mobile when active */
    @media (max-width: 768px) {
        .sidebar-overlay {
            width: 100% !important;
            height: 100% !important;
            visibility: visible;
            opacity: 1;
        }
        
        .sidebar-overlay.active {
            display: block !important;
            pointer-events: auto !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    }

    /* Logo Section (Admin/Registrar) */
    .logo-section {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        margin-bottom: 0;
    }

    .logo-section img {
        width: 80px;
        height: 80px;
        margin-bottom: 10px;
        border-radius: 50%;
        object-fit: cover;
    }

    .logo-section h1 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .logo-section p {
        font-size: 12px;
        margin-top: 5px;
        opacity: 0.8;
        color: rgba(255, 255, 255, 0.8);
    }

    /* User Profile Section (Student/Teacher) */
    .user-profile-section {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        margin-bottom: 0;
    }

    .user-profile-section .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        margin: 0 auto 15px auto;
        background: linear-gradient(135deg, #4a8fd8 0%, #7fb3ed 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        font-weight: 700;
    }

    .user-profile-section .user-name {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: white;
    }

    .user-profile-section .user-mail {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
    }


    /* Navigation Menu */
    .nav-container {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
    }

    .dropdownSmsprofile {
        margin: 0;
    }

    .dropdown-a {
        display: block;
        padding: 14px 20px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        font-size: 14px;
        font-weight: 500;
    }

    .dropdown-a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        border-left-color: #4a8fd8;
        padding-left: 16px;
    }

    .dropdown-a.active {
        background-color: rgba(74, 143, 216, 0.2);
        color: #4a8fd8;
        border-left-color: #4a8fd8;
    }

    .dropdown-a i {
        margin-right: 12px;
        width: 18px;
        text-align: center;
    }

    /* Dropdown Button */
    .dropdown-btn {
        width: 100%;
        padding: 14px 20px;
        background-color: transparent;
        border: none;
        border-left: 4px solid transparent;
        color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        text-align: left;
        transition: all 0.3s ease;
        font-size: 15px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-sizing: border-box;
    }
    
    .dropdown-btn span {
        flex: 1;
    }

    .dropdown-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        border-left-color: #4a8fd8;
    }

    .dropdown-btn:focus {
        outline: none;
    }

    .dropdown-btn.active {
        background-color: rgba(74, 143, 216, 0.2);
        color: #4a8fd8;
        border-left-color: #4a8fd8;
    }

    .dropdown-btn i.fa-caret-down {
        margin-left: auto;
        transition: transform 0.3s ease;
        font-size: 12px;
    }

    .dropdown-btn.active i.fa-caret-down {
        transform: rotate(180deg);
    }

    /* Dropdown Container */
    .dropdown-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background-color: rgba(0, 0, 0, 0.1);
    }

    .dropdown-container.show {
        max-height: 500px;
    }

    .dropdown-container .dropdown-a {
        padding-left: 50px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }

    .dropdown-container .dropdown-a:hover {
        color: rgba(255, 255, 255, 0.9);
        padding-left: 46px;
    }

    /* Submenu Styles */
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-submenu-btn i {
        margin-left: auto;
        transition: transform 0.3s ease;
    }

    .dropdown-submenu-btn.active i {
        transform: rotate(90deg);
    }

    .dropdown-submenu-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background-color: rgba(0, 0, 0, 0.15);
    }

    .dropdown-submenu-container.show {
        max-height: 300px;
    }

    .dropdown-submenu-container .dropdown-a {
        padding-left: 70px;
        font-size: 12px;
    }

    .dropdown-submenu-container .dropdown-a:hover {
        padding-left: 66px;
    }

    /* Logout Button */
    .logout-section {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logout-btn {
        display: block;
        padding: 12px 20px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        width: 100%;
    }

    .logout-btn:hover {
        background: rgba(255, 50, 50, 0.2);
        border-color: rgba(255, 50, 50, 0.5);
        color: #ff9999;
    }

    @media (max-width: 768px) {
        .sidenav {
            width: 250px;
            transform: translateX(-100%);
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidenav.active {
            transform: translateX(0);
        }

        .close-btn {
            display: block !important;
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            z-index: 1001;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    }
</style>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
@auth
@php
    $authUser = auth()->user();
@endphp
@if($authUser)
<div class="sidenav" id="sidenav">
    <button class="close-btn" id="closeSidebar">
        <i class="fas fa-times"></i>
    </button>
    @php
        $role = $authUser->role ?? null;
        $user = $authUser;
        $initials = strtoupper(substr($user->firstname ?? '', 0, 1) . substr($user->lastname ?? '', 0, 1));
    @endphp

    @if(in_array($role, ['admin', 'registrar']))
        <!-- Logo Section (Admin/Registrar) -->
        <div class="logo-section">
            <img src="{{ asset('img/logo.png') }}" alt="San Roque High School Logo">
            <h1>San Roque</h1>
            <p>High School</p>
        </div>
    @else
        <!-- User Profile Section (Student/Teacher) -->
        <div class="user-profile-section">
            <div class="avatar">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ $initials }}
                @endif
            </div>
            <div class="user-name">
                @if($user->role === 'teacher')
                    Teacher {{ $user->firstname }} {{ $user->lastname }}
                @else
                    {{ $user->firstname }} {{ $user->lastname }}
                @endif
            </div>
            <div class="user-mail">{{ $user->email }}</div>
        </div>
    @endif

    <!-- Navigation Menu -->
    <div class="nav-container">
        @if ($role === 'admin')
            @php
                // Get all unique school years from enrollments
                $adminSchoolYears = \App\Models\Enrollment::select('school_year')
                    ->distinct()
                    ->orderBy('school_year', 'desc')
                    ->pluck('school_year')
                    ->toArray();
                
                // Also get school years from school_years table that might not have enrollments yet
                $schoolYearsFromTable = \App\Models\SchoolYear::select('school_year')
                    ->orderBy('school_year', 'desc')
                    ->pluck('school_year')
                    ->toArray();
                
                // Merge both arrays and get unique values
                $allAdminSchoolYears = array_unique(array_merge($adminSchoolYears, $schoolYearsFromTable));
                rsort($allAdminSchoolYears); // Sort descending
                
                // Group JHS and SHS grades by school year
                $adminJhsBySchoolYear = [];
                $adminShsBySchoolYear = [];
                
                foreach($allAdminSchoolYears as $schoolYear) {
                    $jhsGrades = \App\Models\Enrollment::where('school_year', $schoolYear)
                        ->whereIn('grade_level', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])
                        ->distinct()
                        ->pluck('grade_level')
                        ->toArray();
                    
                    $shsGrades = \App\Models\Enrollment::where('school_year', $schoolYear)
                        ->whereIn('grade_level', ['Grade 11', 'Grade 12'])
                        ->distinct()
                        ->pluck('grade_level')
                        ->toArray();
                    
                    // Show school year if it has any grades OR if it exists in school_years table
                    $schoolYearExists = in_array($schoolYear, $schoolYearsFromTable);
                    
                    // If school year exists in school_years table, show all grade levels
                    // Otherwise, only show grade levels that have enrollments
                    if ($schoolYearExists) {
                        // Show all JHS grades if school year exists in table
                        $adminJhsBySchoolYear[$schoolYear] = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];
                        // Show all SHS grades if school year exists in table
                        $adminShsBySchoolYear[$schoolYear] = ['Grade 11', 'Grade 12'];
                    } else {
                        // Only show grade levels that have enrollments
                        if (!empty($jhsGrades)) {
                            $adminJhsBySchoolYear[$schoolYear] = $jhsGrades;
                        }
                        if (!empty($shsGrades)) {
                            $adminShsBySchoolYear[$schoolYear] = $shsGrades;
                        }
                    }
                }
            @endphp
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('admin.index') }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('admin.reports.index') }}">
<i class="fas fa-chart-bar"></i> Reports
                </a>
            </div>

            <!-- Logout for admin -->
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>

        @elseif ($role === 'registrar')
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.index') }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.admissions.index') }}">
                    <i class="fas fa-file-alt"></i> Admissions
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.enrollment.index') }}">
                    <i class="fas fa-clipboard-list"></i> Enrollments
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.teachers.index') }}">
                    <i class="fas fa-chalkboard-teacher"></i> Teachers
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.teacher-assignments.index') }}">
                    <i class="fas fa-user-tie"></i> Teacher Assignments
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.subjects.index') }}">
                    <i class="fas fa-book"></i> Subjects
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.sections.index') }}">
                    <i class="fas fa-users"></i> Sections
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.school-years.index') }}">
                    <i class="fas fa-calendar-alt"></i> School Years
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('registrar.grades.index') }}">
                    <i class="fas fa-graduation-cap"></i> Grades
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <button class="dropdown-btn" onclick="toggleDropdown(this); return false;">
                    <span>Junior High School</span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container">
                    <a class="dropdown-a" href="{{ route('registrar.jhs.gradeSeven') }}">Grade 7</a>
                    <a class="dropdown-a" href="{{ route('registrar.jhs.gradeEight') }}">Grade 8</a>
                    <a class="dropdown-a" href="{{ route('registrar.jhs.gradeNine') }}">Grade 9</a>
                    <a class="dropdown-a" href="{{ route('registrar.jhs.gradeTen') }}">Grade 10</a>
                </div>
            </div>
            <div class="dropdownSmsprofile">
                <button class="dropdown-btn" onclick="toggleDropdown(this); return false;">
                    <span>Senior High School</span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container">
                    <a class="dropdown-a" href="{{ route('registrar.shs.gradeEleven') }}">Grade 11</a>
                    <a class="dropdown-a" href="{{ route('registrar.shs.gradeTwelve') }}">Grade 12</a>
                </div>
            </div>

            <!-- Logout for registrar -->
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>

        @elseif ($role === 'teacher')
            @php
                $teacherAssignments = \App\Models\TeacherSectionAssignment::where('teacher_id', auth()->id())
                    ->get();
                
                // Get only the active school year from school_years table
                $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)
                    ->first();
                
                $activeSchoolYearValue = $activeSchoolYear ? $activeSchoolYear->school_year : null;
                
                // Group by school year first, then by grade level
                $assignmentsBySchoolYear = $teacherAssignments->groupBy('school_year');
                
                // Get JHS and SHS assignments grouped by school year and grade level
                // Only show the active school year
                $jhsBySchoolYear = [];
                $shsBySchoolYear = [];
                
                if ($activeSchoolYearValue) {
                    $assignmentsForYear = $assignmentsBySchoolYear->get($activeSchoolYearValue, collect());
                    $gradesInYear = $assignmentsForYear->groupBy('grade_level');
                    
                    $jhsGrades = [];
                    $shsGrades = [];
                    
                    foreach($gradesInYear as $gradeLevel => $gradeAssignments) {
                        if (in_array($gradeLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
                            if (!in_array($gradeLevel, $jhsGrades)) {
                                $jhsGrades[] = $gradeLevel;
                            }
                        } elseif (in_array($gradeLevel, ['Grade 11', 'Grade 12'])) {
                            if (!in_array($gradeLevel, $shsGrades)) {
                                $shsGrades[] = $gradeLevel;
                            }
                        }
                    }
                    
                    // Show active school year if teacher has assignments for it
                    $hasAssignments = !$assignmentsForYear->isEmpty();
                    
                    if ($hasAssignments) {
                        if (!empty($jhsGrades)) {
                            $jhsBySchoolYear[$activeSchoolYearValue] = $jhsGrades;
                        }
                        if (!empty($shsGrades)) {
                            $shsBySchoolYear[$activeSchoolYearValue] = $shsGrades;
                        }
                    }
                }
            @endphp
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('teacher.index') }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('teacher.profile.edit') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('teacher.grades.index') }}">
                    <i class="fas fa-graduation-cap"></i> Grades
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('teacher.school-years.index') }}">
                    <i class="fas fa-calendar-alt"></i> School Years
                </a>
            </div>
            @if(count($jhsBySchoolYear) > 0)
            <div class="dropdownSmsprofile">
                <button class="dropdown-btn" onclick="toggleDropdown(this); return false;">
                    <span>Junior High School</span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container">
                    @foreach($jhsBySchoolYear as $schoolYear => $grades)
                        <div class="dropdown-submenu">
                            <button class="dropdown-a dropdown-submenu-btn" onclick="toggleSubmenu(this); return false;">
                                <span>{{ $schoolYear }}</span>
                                <i class="fa fa-caret-right"></i>
                            </button>
                            <div class="dropdown-submenu-container">
                                @if(in_array('Grade 7', $grades))
                                    <a class="dropdown-a" href="{{ route('teacher.jhs.gradeSeven') }}?school_year={{ $schoolYear }}">Grade 7</a>
                                @endif
                                @if(in_array('Grade 8', $grades))
                                    <a class="dropdown-a" href="{{ route('teacher.jhs.gradeEight') }}?school_year={{ $schoolYear }}">Grade 8</a>
                                @endif
                                @if(in_array('Grade 9', $grades))
                                    <a class="dropdown-a" href="{{ route('teacher.jhs.gradeNine') }}?school_year={{ $schoolYear }}">Grade 9</a>
                                @endif
                                @if(in_array('Grade 10', $grades))
                                    <a class="dropdown-a" href="{{ route('teacher.jhs.gradeTen') }}?school_year={{ $schoolYear }}">Grade 10</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(count($shsBySchoolYear) > 0)
            <div class="dropdownSmsprofile">
                <button class="dropdown-btn" onclick="toggleDropdown(this); return false;">
                    <span>Senior High School</span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container">
                    @foreach($shsBySchoolYear as $schoolYear => $grades)
                        <div class="dropdown-submenu">
                            <button class="dropdown-a dropdown-submenu-btn" onclick="toggleSubmenu(this); return false;">
                                <span>{{ $schoolYear }}</span>
                                <i class="fa fa-caret-right"></i>
                            </button>
                            <div class="dropdown-submenu-container">
                                @if(in_array('Grade 11', $grades))
                                    <a class="dropdown-a" href="{{ route('teacher.shs.gradeEleven') }}?school_year={{ $schoolYear }}">Grade 11</a>
                                @endif
                                @if(in_array('Grade 12', $grades))
                                    <a class="dropdown-a" href="{{ route('teacher.shs.gradeTwelve') }}?school_year={{ $schoolYear }}">Grade 12</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        @elseif ($role === 'student')
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('student.index') }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('student.profile.edit') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('student.enrollment.index') }}">
                    <i class="fas fa-clipboard-list"></i> Enrollments
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('student.subjects.index') }}">
                    <i class="fas fa-book"></i> Subjects
                </a>
            </div>
            <div class="dropdownSmsprofile">
                <a class="dropdown-a" href="{{ route('student.grades.index') }}">
                    <i class="fas fa-graduation-cap"></i> Grades
                </a>
            </div>
        @endif
    </div>

    </div>
</div>
@endif
@endauth

<script>
    function toggleDropdown(button) {
        const container = button.nextElementSibling;
        
        if (!container || !container.classList.contains('dropdown-container')) {
            return false;
        }
        
        // Close other dropdowns
        document.querySelectorAll('.dropdown-container.show').forEach(el => {
            if (el !== container) {
                el.classList.remove('show');
                const btn = el.previousElementSibling;
                if (btn && btn.classList.contains('dropdown-btn')) {
                    btn.classList.remove('active');
                }
            }
        });

        // Toggle current dropdown
        container.classList.toggle('show');
        button.classList.toggle('active');
        
        return false;
    }

    function toggleSubmenu(button) {
        const container = button.nextElementSibling;
        
        if (!container || !container.classList.contains('dropdown-submenu-container')) {
            return false;
        }
        
        // Close other submenus in the same parent
        const parent = button.closest('.dropdown-container');
        if (parent) {
            parent.querySelectorAll('.dropdown-submenu-container.show').forEach(el => {
                if (el !== container) {
                    el.classList.remove('show');
                    const btn = el.previousElementSibling;
                    if (btn && btn.classList.contains('dropdown-submenu-btn')) {
                        btn.classList.remove('active');
                    }
                }
            });
        }

        // Toggle current submenu
        container.classList.toggle('show');
        button.classList.toggle('active');
        
        return false;
    }
</script>