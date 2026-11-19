<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\AdmissionStatusHistory;
use App\Observers\UserObserver;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\ImportPhilippineGeo::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default timezone to Philippine Time
        date_default_timezone_set(config('app.timezone', 'Asia/Manila'));
        
        // Role gates
        Gate::define('is-admin', fn(User $user) => $user->role === User::ROLE_ADMIN);
        Gate::define('is-registrar', fn(User $user) => $user->role === User::ROLE_REGISTRAR);
        Gate::define('is-teacher', fn(User $user) => $user->role === User::ROLE_TEACHER);
        Gate::define('is-student', fn(User $user) => $user->role === User::ROLE_STUDENT);

        // Admission permissions
        Gate::define('admission.view', function (User $user, Admission $admission) {
            if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR])) return true;
            return $user->id === $admission->user_id;
        });
        Gate::define('admission.manage', fn(User $user) => in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR]));

        // Enrollment permissions
        Gate::define('enrollment.view', function (User $user, Enrollment $enrollment) {
            if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR, User::ROLE_TEACHER])) return true;
            return $user->id === $enrollment->user_id;
        });
        Gate::define('enrollment.manage', fn(User $user) => in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR]));

        // Register User Observer for teacher account update notifications
        User::observe(UserObserver::class);

        // Log admission status changes
        Admission::updated(function (Admission $admission) {
            if ($admission->wasChanged('status')) {
                AdmissionStatusHistory::create([
                    'admission_id' => $admission->id,
                    'user_id' => auth()->id(),
                    'from_status' => $admission->getOriginal('status'),
                    'to_status' => $admission->status,
                    'comment' => request()->input('status_comment')
                ]);
            }
        });
    }
}
