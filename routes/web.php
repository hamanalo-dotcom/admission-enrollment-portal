<?php



use App\Http\Controllers\Admin\AdminJuniorHighController;
use App\Http\Controllers\Admin\AdminSeniorHighController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminTeacherAssignmentController;
use App\Http\Controllers\Admin\AdminSectionController;
use App\Http\Controllers\Admin\AdminSubjectController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminGradeController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Registrar\RegistrarAdmissionController;
use App\Http\Controllers\Registrar\RegistrarEventController;
use App\Http\Controllers\Student\StudentAdmissionController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\StudentNotificationController;
use App\Http\Controllers\Student\StudentGradeController;
use App\Http\Middleware\Staff;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Registrar\RegistrarDashboardController;
use App\Http\Controllers\Registrar\RegistrarEnrollmentController;
use App\Http\Controllers\Registrar\RegistrarJuniorHighController;
use App\Http\Controllers\Registrar\RegistrarSeniorHighController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherJuniorHighController;
use App\Http\Controllers\Teacher\TeacherSeniorHighController;
use App\Http\Controllers\Teacher\TeacherNotificationController;
use App\Http\Controllers\Teacher\TeacherGradeController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\TeacherController;

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\Registrar;
use App\Http\Middleware\Student;
use App\Http\Middleware\Teacher;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request as HttpRequest;

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Google OAuth routes
Route::get('/register/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirectToGoogle'])->name('register.google');
Route::get('/login/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirectToGoogleLogin'])->name('login.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Public admission route (for guests)
Route::get('/admission/create', [\App\Http\Controllers\Student\StudentAdmissionController::class, 'createPublic'])->name('admission.create.public');
Route::post('/admission', [\App\Http\Controllers\Student\StudentAdmissionController::class, 'storePublic'])->name('admission.store.public');
Route::get('/admission/success/{admission}', [\App\Http\Controllers\Student\StudentAdmissionController::class, 'success'])->name('admission.success');
Route::get('/admission/status', [\App\Http\Controllers\Student\StudentAdmissionController::class, 'statusForm'])->name('admission.status');

// Admission PDF download (guest session or authorized user)
Route::get('/admission/{admission}/download-pdf', [
    \App\Http\Controllers\Student\StudentAdmissionController::class,
    'downloadPdf'
])->name('admission.download.pdf');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::get('/login/student', [LoginController::class, 'showStudentLogin'])->name('login.student');
Route::get('/login/teacher', [LoginController::class, 'showTeacherLogin'])->name('login.teacher');
Route::get('/login/admin', [LoginController::class, 'showAdminLogin'])->name('login.admin');
Route::get('/login/registrar', [LoginController::class, 'showRegistrarLogin'])->name('login.registrar');
Route::middleware(PreventBackHistory::class)->post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (HttpRequest $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token, 'email' => request('email')]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (HttpRequest $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => ['required', 'confirmed', Illuminate\Validation\Rules\Password::defaults()],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill(['password' => Hash::make($request->string('password'))])->save();
        }
    );

    return $status == Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Email verification routes
Route::get('/email/verify', function () {
    $user = auth()->user();
    if ($user && $user->hasVerifiedEmail()) {
        // Redirect verified users away from the verify page
        if ($user->role === 'admin') {
            return redirect()->intended('/admin-dashboard');
        } elseif ($user->role === 'registrar') {
            return redirect()->intended('/registrar-dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->intended('/teacher-dashboard');
        } elseif ($user->role === 'student') {
            return redirect()->intended('/student-dashboard');
        }
        return redirect()->intended('/dashboard');
    }
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    
    $user = $request->user();
    
    // Redirect based on user role
    if ($user->role === 'admin') {
        return redirect()->intended('/admin-dashboard')->with('success', 'Email verified successfully!');
    } elseif ($user->role === 'registrar') {
        return redirect()->intended('/registrar-dashboard')->with('success', 'Email verified successfully!');
    } elseif ($user->role === 'teacher') {
        return redirect()->intended('/teacher-dashboard')->with('success', 'Email verified successfully!');
    } elseif ($user->role === 'student') {
        return redirect()->intended('/student-dashboard')->with('success', 'Email verified successfully!');
    } else {
        return redirect()->intended('/dashboard')->with('success', 'Email verified successfully!');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (HttpRequest $request) {
    if ($request->user()->hasVerifiedEmail()) {
        // If already verified, redirect to the appropriate dashboard
        $user = $request->user();
        if ($user->role === 'admin') {
            return redirect()->intended('/admin-dashboard');
        } elseif ($user->role === 'registrar') {
            return redirect()->intended('/registrar-dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->intended('/teacher-dashboard');
        } elseif ($user->role === 'student') {
            return redirect()->intended('/student-dashboard');
        }
        return redirect()->intended('/dashboard');
    }
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::middleware('auth')->group(function () {
    // Doctor routes
    Route::middleware(Admin::class)->group(function () {

        //Dashboard routes
        Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.index');

        // User Management Routes
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/generate-password', [AdminUserController::class, 'generatePassword'])->name('admin.users.generatePassword');
        Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::put('/admin/users/{user}/change-password', [AdminUserController::class, 'changePassword'])->name('admin.users.changePassword');
    });

    //Registrar routes
    Route::middleware(Registrar::class)->group(function () {

        //Dashboard routes
        Route::get('/registrar-dashboard', [RegistrarDashboardController::class, 'index'])->name('registrar.index');

        //Admission routes 
        Route::get('/registrar/admissions', [RegistrarAdmissionController::class, 'index'])->name('registrar.admissions.index');
        Route::get('/registrar/admissions/{admission}', [RegistrarAdmissionController::class, 'show'])->name('registrar.admissions.show');
        Route::get('/registrar/admissions/create', [RegistrarAdmissionController::class, 'create'])->name('registrar.admissions.create');
        Route::post('/registrar/admissions', [RegistrarAdmissionController::class, 'store'])->name('registrar.admissions.store');
        Route::get('/registrar/admissions/{admission}/edit', [RegistrarAdmissionController::class, 'edit'])->name('registrar.admissions.edit');
        Route::put('/registrar/admissions/{admission}', [RegistrarAdmissionController::class, 'update'])->name('registrar.admissions.update');
        // Delete functionality removed to preserve all data
        // Route::delete('/registrar/admissions/{admission}', [RegistrarAdmissionController::class, 'destroy'])->name('registrar.admissions.destroy');
        Route::post('/registrar/admissions/toggle', [RegistrarAdmissionController::class, 'toggleAdmissions'])->name('registrar.admissions.toggle');

        // Update status route
        Route::put('/registrar/admissions/{admission}/status', [RegistrarAdmissionController::class, 'updateAdmissionStatus'])->name('registrar.admissions.updateAdmissionStatus');
        Route::put('/registrar/admissions/{admission}/file/{field}', [RegistrarAdmissionController::class, 'updateFileStatus'])->name('registrar.admissions.updateFileStatus');
        Route::put('/registrar/enrollments/{enrollment}/status', [RegistrarEnrollmentController::class, 'updateEnrollmentStatus'])->name('registrar.enrollments.updateEnrollmentStatus');

        // Enrollment routes (creation and submission handled by StudentEnrollmentController)
        Route::get('/registrar/enrollment', [RegistrarEnrollmentController::class, 'index'])->name('registrar.enrollment.index');
        Route::get('/registrar/enrollment/{enrollment}', [RegistrarEnrollmentController::class, 'show'])->name('registrar.enrollment.show');
        Route::delete('/registrar/enrollment/{enrollment}', [RegistrarEnrollmentController::class, 'destroy'])->name('registrar.enrollment.destroy');
        Route::put('/registrar/enrollment/{id}/restore', [RegistrarEnrollmentController::class, 'restore'])->name('registrar.enrollment.restore');

        //Assign section to enrollment
        Route::put('/registrar/enrollments/{enrollment}/assign-section', [RegistrarEnrollmentController::class, 'assignSection'])->name('registrar.enrollments.assignSection');

        // Events management routes (delete functionality removed to preserve all data)
        Route::get('/registrar/events', [RegistrarEventController::class, 'index'])->name('registrar.events.index');
        Route::get('/registrar/events/create', [RegistrarEventController::class, 'create'])->name('registrar.events.create');
        Route::post('/registrar/events', [RegistrarEventController::class, 'store'])->name('registrar.events.store');
        Route::get('/registrar/events/{event}', [RegistrarEventController::class, 'show'])->name('registrar.events.show');
        Route::get('/registrar/events/{event}/edit', [RegistrarEventController::class, 'edit'])->name('registrar.events.edit');
        Route::put('/registrar/events/{event}', [RegistrarEventController::class, 'update'])->name('registrar.events.update');
        // Route::delete('/registrar/events/{event}', [RegistrarEventController::class, 'destroy'])->name('registrar.events.destroy');

        //Junior High School Routes
        Route::get('/registrar/jhs-gradeSeven', [RegistrarJuniorHighController::class, 'jhsGradeSeven'])->name('registrar.jhs.gradeSeven');
        Route::get('/registrar/jhs-gradeEight', [RegistrarJuniorHighController::class, 'jhsGradeEight'])->name('registrar.jhs.gradeEight');
        Route::get('/registrar/jhs-gradeNine', [RegistrarJuniorHighController::class, 'jhsGradeNine'])->name('registrar.jhs.gradeNine');
        Route::get('/registrar/jhs-gradeTen', [RegistrarJuniorHighController::class, 'jhsGradeTen'])->name('registrar.jhs.gradeTen');

        // Registrar PDF download routes for JHS
        Route::get('/registrar/jhs-grade{grade}/section-{section}/pdf', [RegistrarJuniorHighController::class, 'downloadSectionPdf'])
            ->name('registrar.jhs.section.pdf')
            ->where(['grade' => '[7-9]|10', 'section' => '.+']);

        //Senior High School Routes
        Route::get('/registrar/shs-gradeEleven', [RegistrarSeniorHighController::class, 'shsGradeEleven'])->name('registrar.shs.gradeEleven');
        Route::get('/registrar/shs-gradeTwelve', [RegistrarSeniorHighController::class, 'shsGradeTwelve'])->name('registrar.shs.gradeTwelve');

        // Registrar PDF download routes for SHS
        Route::get('/registrar/shs-grade{grade}/{strand}/pdf', [RegistrarSeniorHighController::class, 'downloadStrandPdf'])
            ->name('registrar.shs.strand.pdf')
            ->where(['grade' => '11|12', 'strand' => '.+']);
        Route::get('/registrar/shs-grade{grade}/{strand}/section-{section}/pdf', [RegistrarSeniorHighController::class, 'downloadStrandPdf'])
            ->name('registrar.shs.strand-section.pdf')
            ->where(['grade' => '11|12', 'strand' => '.+', 'section' => '.+']);

        // School Year Management Routes (reusing AdminSchoolYearController)
        Route::get('/registrar/school-years', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'index'])->name('registrar.school-years.index');
        Route::get('/registrar/school-years/{schoolYear}', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'show'])->name('registrar.school-years.show')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::get('/registrar/school-years/{schoolYear}/download', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'download'])->name('registrar.school-years.download')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::post('/registrar/school-years', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'store'])->name('registrar.school-years.store');
        Route::get('/registrar/school-years/{schoolYear}/edit', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'edit'])->name('registrar.school-years.edit')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::put('/registrar/school-years/{schoolYear}', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'update'])->name('registrar.school-years.update')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::post('/registrar/school-years/{schoolYear}/toggle-active', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'toggleActive'])->name('registrar.school-years.toggle-active')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::post('/registrar/school-years/{schoolYear}/toggle-hidden', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'toggleHidden'])->name('registrar.school-years.toggle-hidden')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::delete('/registrar/school-years/{schoolYear}', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'destroy'])->name('registrar.school-years.destroy')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::get('/registrar/school-years-trashed', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'trashed'])->name('registrar.school-years.trashed');
        Route::post('/registrar/school-years/{schoolYear}/restore', [\App\Http\Controllers\Admin\AdminSchoolYearController::class, 'restore'])->name('registrar.school-years.restore')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        // Permanent deletion is disabled: Route::delete('/registrar/school-years/{schoolYear}/force-delete', ...) removed to preserve all historical records.

        // Sections management (reusing AdminSectionController)
        Route::get('/registrar/sections', [AdminSectionController::class, 'index'])->name('registrar.sections.index');
        Route::post('/registrar/sections', [AdminSectionController::class, 'store'])->name('registrar.sections.store');
        Route::post('/registrar/sections/add-all-defaults', [AdminSectionController::class, 'addAllDefaultSections'])->name('registrar.sections.add-all-defaults');
        Route::get('/registrar/sections/grade{grade}/section-{section}/pdf', [AdminSectionController::class, 'downloadSectionPdf'])
            ->name('registrar.sections.download-pdf')
            ->where(['grade' => '[7-9]|10|11|12', 'section' => '.+']);
        Route::get('/registrar/sections/grade{grade}/{strand}/section-{section}/pdf', [AdminSectionController::class, 'downloadStrandPdf'])
            ->name('registrar.sections.download-strand-section-pdf')
            ->where(['grade' => '11|12', 'strand' => 'gas|tvl', 'section' => '.+']);
        Route::get('/registrar/sections/grade{grade}/{strand}/pdf', [AdminSectionController::class, 'downloadStrandPdf'])
            ->name('registrar.sections.download-strand-pdf')
            ->where(['grade' => '11|12', 'strand' => 'gas|tvl']);

        // Subjects management (reusing AdminSubjectController)
        Route::get('/registrar/subjects', [AdminSubjectController::class, 'index'])->name('registrar.subjects.index');
        Route::get('/registrar/subjects/create', [AdminSubjectController::class, 'create'])->name('registrar.subjects.create');
        Route::post('/registrar/subjects', [AdminSubjectController::class, 'store'])->name('registrar.subjects.store');
        Route::get('/registrar/subjects/{subject}/edit', [AdminSubjectController::class, 'edit'])->name('registrar.subjects.edit');
        Route::put('/registrar/subjects/{subject}', [AdminSubjectController::class, 'update'])->name('registrar.subjects.update');
        // Delete functionality removed to preserve all data
        // Route::delete('/registrar/subjects/{subject}', [AdminSubjectController::class, 'destroy'])->name('registrar.subjects.destroy');

        // Teacher Assignment Routes (reusing AdminTeacherAssignmentController)
        Route::get('/registrar/teacher-assignments', [AdminTeacherAssignmentController::class, 'index'])->name('registrar.teacher-assignments.index');
        Route::get('/registrar/teacher-assignments/create', [AdminTeacherAssignmentController::class, 'create'])->name('registrar.teacher-assignments.create');
        Route::post('/registrar/teacher-assignments', [AdminTeacherAssignmentController::class, 'store'])->name('registrar.teacher-assignments.store');
        Route::get('/registrar/teacher-assignments/{teacherAssignment}/edit', [AdminTeacherAssignmentController::class, 'edit'])->name('registrar.teacher-assignments.edit');
        Route::put('/registrar/teacher-assignments/{teacherAssignment}', [AdminTeacherAssignmentController::class, 'update'])->name('registrar.teacher-assignments.update');
        // Delete functionality removed to preserve all data
        // Route::delete('/registrar/teacher-assignments/{teacherAssignment}', [AdminTeacherAssignmentController::class, 'destroy'])->name('registrar.teacher-assignments.destroy');
        Route::get('/registrar/teacher-assignments/sections', [AdminTeacherAssignmentController::class, 'getSections'])->name('registrar.teacher-assignments.sections');

        // Grades management (reusing AdminGradeController)
        Route::get('/registrar/grades', [AdminGradeController::class, 'index'])->name('registrar.grades.index');
        Route::get('/registrar/grades/student/{student}', [AdminGradeController::class, 'showStudentRecord'])->name('registrar.grades.student-record');
        Route::get('/registrar/grades/{grade}', [AdminGradeController::class, 'show'])->name('registrar.grades.show');
        Route::get('/registrar/grades/{grade}/edit', [AdminGradeController::class, 'edit'])->name('registrar.grades.edit');
        Route::put('/registrar/grades/{grade}', [AdminGradeController::class, 'update'])->name('registrar.grades.update');
        // Delete functionality removed to preserve all data
        // Route::delete('/registrar/grades/{grade}', [AdminGradeController::class, 'destroy'])->name('registrar.grades.destroy');

        // Operational Reports and Statistics (reusing AdminReportController)
        Route::get('/registrar/reports', [AdminReportController::class, 'index'])->name('registrar.reports.index');
        Route::get('/registrar/reports/enrollment', [AdminReportController::class, 'enrollment'])->name('registrar.reports.enrollment');
        Route::get('/registrar/reports/admission', [AdminReportController::class, 'admission'])->name('registrar.reports.admission');
        Route::get('/registrar/reports/grade', [AdminReportController::class, 'grade'])->name('registrar.reports.grade');
        Route::get('/registrar/reports/teacher-assignment', [AdminReportController::class, 'teacherAssignment'])->name('registrar.reports.teacherAssignment');
        Route::get('/registrar/reports/statistics', [AdminReportController::class, 'statistics'])->name('registrar.reports.statistics');
    });



    // File download (auth users: owner or staff)
    Route::get('/admissions/{admission}/file/{field}', [\App\Http\Controllers\AdmissionFileController::class, 'download'])
        ->name('admissions.files.download');

    //Student routes
    Route::middleware([Student::class, 'verified'])->group(function () {

        //Dashboard routes
        Route::get('/student-dashboard', [StudentDashboardController::class, 'index'])->name('student.index');

        //Admission routes
        Route::get('/admissions', [StudentAdmissionController::class, 'index'])->name('student.admissions.index');
        Route::get('/admissions/create', [StudentAdmissionController::class, 'create'])->name('student.admissions.create');
        Route::post('/admissions', [StudentAdmissionController::class, 'store'])->name('student.admissions.store');
        Route::get('/admissions/{admission}', [StudentAdmissionController::class, 'show'])->name('student.admissions.show');
        Route::get('/admissions/{admission}/edit', [StudentAdmissionController::class, 'edit'])->name('student.admissions.edit');
        Route::put('/admissions/{admission}', [StudentAdmissionController::class, 'update'])->name('student.admissions.update');
        // Delete functionality removed to preserve all data
        // Route::delete('/admissions/{admission}', [StudentAdmissionController::class, 'destroy'])->name('student.admissions.destroy');

        //Address routes for dynamic dropdowns

        Route::get('/student-admissions/provinces/{regCode}', [StudentAdmissionController::class, 'getProvinces']);


        Route::get('/student-admissions/cities/{provCode}', [StudentAdmissionController::class, 'getCities']);
        Route::get('/student-admissions/barangays/{citymunCode}', [StudentAdmissionController::class, 'getBarangays']);

        // Notification routes
        Route::get('/student/notifications', [StudentNotificationController::class, 'index'])->name('student.notifications.index');
        Route::post('/student/notifications/mark-all-read', [StudentNotificationController::class, 'markAllAsRead'])->name('student.notifications.markAllRead');

        // Profile routes
        Route::get('/student/profile/edit', [\App\Http\Controllers\Student\StudentProfileController::class, 'edit'])->name('student.profile.edit');
        Route::put('/student/profile', [\App\Http\Controllers\Student\StudentProfileController::class, 'update'])->name('student.profile.update');

        // Enrollment routes
        Route::get('/enrollment', [StudentEnrollmentController::class, 'index'])->name('student.enrollment.index');
        Route::get('/enrollment/create', [StudentEnrollmentController::class, 'create'])->name('student.enrollment.create');
        Route::post('/student/enrollment', [StudentEnrollmentController::class, 'store'])->name('student.enrollment.store');
        Route::get('/enrollment/{enrollment}', [StudentEnrollmentController::class, 'show'])->name('student.enrollment.show');
        Route::get('/enrollment/{enrollment}/edit', [StudentEnrollmentController::class, 'edit'])->name('student.enrollment.edit');
        Route::put('/enrollment/{enrollment}', [StudentEnrollmentController::class, 'update'])->name('student.enrollment.update');
        // Delete functionality removed to preserve all data
        // Route::delete('/enrollment/{enrollment}', [StudentEnrollmentController::class, 'destroy'])->name('student.enrollment.destroy');

        // Grade viewing routes
        Route::get('/grades', [StudentGradeController::class, 'index'])->name('student.grades.index');
        Route::get('/grades/{subject}', [StudentGradeController::class, 'show'])->name('student.grades.show');
        
        Route::get('/subjects', [\App\Http\Controllers\Student\StudentSubjectController::class, 'index'])->name('student.subjects.index');
    });


    // Teacher routes
    Route::middleware(Teacher::class)->group(function () {
        //Dashboard routes
        Route::get('/teacher-dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.index');
        
        // Teacher Notifications
        Route::get('/teacher/notifications', [TeacherNotificationController::class, 'index'])->name('teacher.notifications.index');
        Route::post('/teacher/notifications/{id}/read', [TeacherNotificationController::class, 'markAsRead'])->name('teacher.notifications.markAsRead');
        Route::post('/teacher/notifications/mark-all-read', [TeacherNotificationController::class, 'markAllAsRead'])->name('teacher.notifications.markAllRead');

        // Profile routes
        Route::get('/teacher/profile/edit', [\App\Http\Controllers\Teacher\TeacherProfileController::class, 'edit'])->name('teacher.profile.edit');
        Route::put('/teacher/profile', [\App\Http\Controllers\Teacher\TeacherProfileController::class, 'update'])->name('teacher.profile.update');

        // Grade submission routes
        Route::get('/teacher/grades', [TeacherGradeController::class, 'index'])->name('teacher.grades.index');
        Route::get('/teacher/grades/create', [TeacherGradeController::class, 'create'])->name('teacher.grades.create');
        Route::post('/teacher/grades', [TeacherGradeController::class, 'store'])->name('teacher.grades.store');

        // School Year Viewing Routes (assigned sections only; visibility management restricted to Registrar)
        Route::get('/teacher/school-years', [\App\Http\Controllers\Teacher\TeacherSchoolYearController::class, 'index'])->name('teacher.school-years.index');
        Route::get('/teacher/school-years/{schoolYear}', [\App\Http\Controllers\Teacher\TeacherSchoolYearController::class, 'show'])->name('teacher.school-years.show')->where('schoolYear', '[0-9]{4}-[0-9]{4}');
        Route::get('/teacher/school-years/{schoolYear}/download', [\App\Http\Controllers\Teacher\TeacherSchoolYearController::class, 'download'])->name('teacher.school-years.download')->where('schoolYear', '[0-9]{4}-[0-9]{4}');

        //Junior High School Routes
        Route::get('/teacher/jhs-gradeSeven', [TeacherJuniorHighController::class, 'jhsGradeSeven'])->name('teacher.jhs.gradeSeven');
        Route::get('/teacher/jhs-gradeEight', [TeacherJuniorHighController::class, 'jhsGradeEight'])->name('teacher.jhs.gradeEight');
        Route::get('/teacher/jhs-gradeNine', [TeacherJuniorHighController::class, 'jhsGradeNine'])->name('teacher.jhs.gradeNine');
        Route::get('/teacher/jhs-gradeTen', [TeacherJuniorHighController::class, 'jhsGradeTen'])->name('teacher.jhs.gradeTen');

        // Teacher PDF download routes
        Route::get('/teacher/jhs-grade{grade}/section-{section}/pdf', [TeacherJuniorHighController::class, 'downloadSectionPdf'])
            ->name('teacher.jhs.section.pdf')
            ->where(['grade' => '[7-9]|10', 'section' => '.+']);

        Route::get('/teacher/shs-grade{grade}/{strand}/pdf', [TeacherSeniorHighController::class, 'downloadStrandPdf'])
            ->name('teacher.shs.strand.pdf')
            ->where(['grade' => '11|12', 'strand' => '.+']);

        //Senior High School Routes
        Route::get('/teacher/shs-gradeEleven', [TeacherSeniorHighController::class, 'shsGradeEleven'])->name('teacher.shs.gradeEleven');
        Route::get('/teacher/shs-gradeTwelve', [TeacherSeniorHighController::class, 'shsGradeTwelve'])->name('teacher.shs.gradeTwelve');
    });
});

// Homepage with announcements
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/login', function () {
//     return response(view('auth.login'))->withHeaders([
//         'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
//         'Pragma' => 'no-cache',
//     ]);
// })->middleware(RedirectIfAuthenticated::class);

// Error 404 Not Found
Route::get('/error', function () {
    return view('error');
})->name('error');

// Fallback route - catch all unmatched routes and return error page
Route::fallback(function () {
    return view('error');
});




//// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
