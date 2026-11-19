<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
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
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,registrar,teacher,student'],
            'birthdate' => ['required', 'date'],
            'sex' => ['required', 'in:male,female,other'],
            'contact_no' => ['nullable', 'string', 'max:20'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Auto-assign teacher role if email ends with @deped.gov.ph
        if (str_ends_with(strtolower($validated['email']), '@deped.gov.ph')) {
            $validated['role'] = 'teacher';
        }

        $user = User::create([
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? '',
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'birthdate' => $validated['birthdate'],
            'sex' => $validated['sex'],
            'contact_no' => $validated['contact_no'] ?? '',
            'emergency_contact' => $validated['emergency_contact'] ?? '',
            'address' => $validated['address'] ?? '',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
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

        // Auto-assign teacher role if email ends with @deped.gov.ph
        if (str_ends_with(strtolower($validated['email']), '@deped.gov.ph')) {
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
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.users.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }

    /**
     * Generate a random password (for preview before saving)
     */
    public function generatePassword()
    {
        // Generate a random password (12 characters: letters, numbers, and special chars)
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*';
        
        // Ensure it has at least one of each type
        $password = '';
        $password .= $uppercase[rand(0, 25)];
        $password .= $lowercase[rand(0, 25)];
        $password .= $numbers[rand(0, 9)];
        $password .= $special[rand(0, 7)];
        
        // Fill the rest with random characters from all sets
        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = strlen($password); $i < 12; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to randomize the order
        $randomPassword = str_shuffle($password);

        return response()->json(['password' => $randomPassword]);
    }

    /**
     * Change user password (for admin use) - saves the provided password
     */
    public function changePassword(Request $request, User $user)
    {
        // Debug: Log the incoming request
        \Log::info('changePassword method called', [
            'user_id' => $user->id,
            'email' => $user->email,
            'request_method' => $request->method(),
            'has_password' => $request->has('password'),
            'password_length' => $request->has('password') ? strlen($request->input('password')) : 0
        ]);
        
        // Validate the password
        try {
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:8'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Password validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        // Get the plain password before hashing - ensure it's a string and trim whitespace
        $plainPassword = trim((string) $validated['password']);
        
        if (empty($plainPassword)) {
            \Log::error('Password is empty after validation', ['user_id' => $user->id]);
            return back()->withErrors(['password' => 'Password cannot be empty.']);
        }
        
        // Log for debugging
        \Log::info('Changing password', [
            'user_id' => $user->id,
            'email' => $user->email,
            'password_length' => strlen($plainPassword),
            'has_special_chars' => preg_match('/[!@#$%^&*]/', $plainPassword) ? 'Yes' : 'No'
        ]);
        
        // Update password - ensure it's properly hashed using bcrypt
        $hashedPassword = Hash::make($plainPassword);
        
        // Get the old password hash for comparison
        $oldPasswordHash = $user->password;
        
        // Directly update the password attribute to ensure it's saved
        $user->password = $hashedPassword;
        $saved = $user->save();

        if (!$saved) {
            \Log::error('Failed to save password', [
                'user_id' => $user->id,
                'errors' => $user->getErrors()
            ]);
            return back()->withErrors(['password' => 'Failed to update password. Please try again.']);
        }

        // Refresh the user model to ensure we have the latest data from database
        $user->refresh();

        // Verify the password was saved correctly by checking the hash
        if (!Hash::check($plainPassword, $user->password)) {
            \Log::error('Password verification failed after save', [
                'user_id' => $user->id,
                'email' => $user->email,
                'password_length' => strlen($plainPassword),
                'old_hash' => substr($oldPasswordHash, 0, 20),
                'new_hash' => substr($user->password, 0, 20)
            ]);
            return back()->withErrors(['password' => 'Password verification failed. Please try generating a new password.']);
        }
        
        \Log::info('Password updated successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'password_changed' => $oldPasswordHash !== $user->password
        ]);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'Password updated successfully for ' . $user->firstname . ' ' . $user->lastname . '! The student can now login with the new password.');
    }
}

