<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminUserController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!auth()->check() || auth()->user()->role !== 'admin') {
                    abort(403, 'Unauthorized action. Only administrators can manage user accounts.');
                }
                return $next($request);
            }),
        ];
    }

    /**
     * Guard account-management operations strictly to Admin role.
     */
    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can manage user accounts.');
        }
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAdmins = User::whereIn('role', ['admin', 'registrar'])->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'totalStudents', 'totalTeachers', 'totalAdmins'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
{
    $this->authorizeAdmin();

    $validated = $request->validate([
        'firstname' => ['required', 'string', 'max:255'],
        'middlename' => ['nullable', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            function ($attribute, $value, $fail) {
                if (trim((string) $value) === '') {
                    $fail('The password cannot consist only of whitespace.');
                }
            },
        ],
        'role' => ['required', 'in:admin,registrar,teacher,student'],
        'birthdate' => ['required', 'date'],
        'sex' => ['required', 'in:male,female,other'],
        'contact_no' => ['nullable', 'string', 'max:20'],
        'emergency_contact' => ['nullable', 'string', 'max:20'],
        'address' => ['required', 'string', 'max:500'],
    ]);

    $email = strtolower(trim($validated['email']));

    // DepEd email addresses must always have the Teacher role.
    $role = str_ends_with($email, '@deped.gov.ph')
        ? 'teacher'
        : $validated['role'];

    $user = User::create([
        'firstname' => $validated['firstname'],
        'middlename' => $validated['middlename'] ?? '',
        'lastname' => $validated['lastname'],
        'email' => $email,
        'password' => Hash::make($validated['password']),
        'role' => $role,
        'birthdate' => $validated['birthdate'],
        'sex' => $validated['sex'],
        'contact_no' => $validated['contact_no'] ?? '',
        'emergency_contact' => $validated['emergency_contact'] ?? '',
        'address' => $validated['address'],
    ]);

    return redirect()->route('admin.users.index')
        ->with('success', 'User created successfully!');
}

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $this->authorizeAdmin();

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $this->authorizeAdmin();

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,registrar,teacher,student'],
            'birthdate' => ['required', 'date'],
            'sex' => ['required', 'in:male,female,other'],
            'contact_no' => ['nullable', 'string', 'max:20'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $email = strtolower(trim($validated['email']));

$validated['email'] = $email;

if (str_ends_with($email, '@deped.gov.ph')) {
    $validated['role'] = 'teacher';
}

$user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    // Delete functionality removed to preserve all data
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.users.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }

    /**
     * Generate a random password (for preview before saving)
     */
    public function generatePassword()
    {
        $this->authorizeAdmin();

        // Cryptographically secure random password generation (12 characters: uppercase, lowercase, numbers, special chars)
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*';
        $allChars = $uppercase . $lowercase . $numbers . $special;

        // Ensure at least one character from each required set
        $chars = [
            $uppercase[random_int(0, strlen($uppercase) - 1)],
            $lowercase[random_int(0, strlen($lowercase) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        // Securely fill remaining characters up to length 12
        for ($i = count($chars); $i < 12; $i++) {
            $chars[] = $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Fisher-Yates shuffle using cryptographically secure random_int
        for ($i = count($chars) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $chars[$i];
            $chars[$i] = $chars[$j];
            $chars[$j] = $temp;
        }

        return response()->json(['password' => implode('', $chars)]);
    }

    /**
     * Change user password (for admin use) - saves the provided password
     */
    public function changePassword(Request $request, User $user)
    {
        $this->authorizeAdmin();

        // Audit log the incoming request metadata without sensitive values
        \Log::info('Admin changePassword initiated', [
            'target_user_id' => $user->id,
            'admin_id' => auth()->id(),
            'request_method' => $request->method(),
        ]);
        
        // Validate the password
        try {
            $validated = $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    function ($attribute, $value, $fail) {
                        if (trim((string) $value) === '') {
                            $fail('The password cannot consist only of whitespace.');
                        }
                    },
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Password validation failed', [
                'target_user_id' => $user->id,
                'admin_id' => auth()->id(),
                'validation_errors' => array_keys($e->errors()),
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        // Preserve password exactly as entered without trimming
        $plainPassword = (string) $validated['password'];
        
        if (trim($plainPassword) === '') {
            \Log::error('Password is only whitespace after validation', [
                'target_user_id' => $user->id,
                'admin_id' => auth()->id(),
            ]);
            return back()->withErrors(['password' => 'The password cannot consist only of whitespace.']);
        }
        
        // Update password - ensure it's properly hashed using bcrypt
        $hashedPassword = Hash::make($plainPassword);
        
        // Directly update the password attribute to ensure it's saved
        $user->password = $hashedPassword;
        $saved = $user->save();

        if (!$saved) {
            \Log::error('Failed to save password', [
                'target_user_id' => $user->id,
                'admin_id' => auth()->id(),
            ]);
            return back()->withErrors(['password' => 'Failed to update password. Please try again.']);
        }

        // Refresh the user model to ensure we have the latest data from database
        $user->refresh();

        // Verify the password was saved correctly by checking the hash
        if (!Hash::check($plainPassword, $user->password)) {
            \Log::error('Password verification failed after save', [
                'target_user_id' => $user->id,
                'admin_id' => auth()->id(),
            ]);
            return back()->withErrors(['password' => 'Password verification failed. Please try generating a new password.']);
        }
        
        \Log::info('Password updated successfully by admin', [
            'target_user_id' => $user->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'Password updated successfully for ' . $user->firstname . ' ' . $user->lastname . '! The user can now login with the new password.');
    }
}

