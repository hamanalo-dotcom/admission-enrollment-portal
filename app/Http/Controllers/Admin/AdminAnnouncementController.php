<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AdminAnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        // Automatically deactivate expired announcements
        Announcement::deactivateExpired();

        $query = Announcement::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $announcements = $query->latest('published_at')->latest('created_at')->paginate(15);

        $totalAnnouncements = Announcement::count();
        $activeAnnouncements = Announcement::where('is_active', true)->count();
        $inactiveAnnouncements = Announcement::where('is_active', false)->count();

        return view('admin.announcements.index', compact('announcements', 'totalAnnouncements', 'activeAnnouncements', 'inactiveAnnouncements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'published_at' => 'nullable|date',
            'expiration_date' => ['nullable', 'date', function ($attribute, $value, $fail) use ($request) {
                if ($value && $request->published_at && $value <= $request->published_at) {
                    $fail('The expiration date must be after the published date.');
                }
            }],
        ]);

        // Handle checkbox: checkbox sends '1' when checked, nothing when unchecked
        // Default to active (1) for new announcements unless explicitly unchecked
        $validated['is_active'] = ($request->has('is_active') && $request->is_active == '1') ? 1 : 1;
        
        // Convert empty string to null for published_at
        if (empty($request->published_at) || $request->published_at === '') {
            $validated['published_at'] = null;
        }

        // Convert empty string to null for expiration_date
        if (empty($request->expiration_date) || $request->expiration_date === '') {
            $validated['expiration_date'] = null;
        }

        \Log::info('Creating Announcement', $validated);
        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Show the form for editing an announcement
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'published_at' => 'nullable|date',
            'expiration_date' => ['nullable', 'date', function ($attribute, $value, $fail) use ($request) {
                if ($value && $request->published_at && $value <= $request->published_at) {
                    $fail('The expiration date must be after the published date.');
                }
            }],
        ]);

        // Handle checkbox: checkbox sends '1' when checked, nothing when unchecked
        // For updates, respect the checkbox value (1 if checked, 0 if not)
        $validated['is_active'] = ($request->has('is_active') && $request->is_active == '1') ? 1 : 0;
        
        // Convert empty string to null for published_at
        if (empty($request->published_at) || $request->published_at === '') {
            $validated['published_at'] = null;
        }

        // Convert empty string to null for expiration_date
        if (empty($request->expiration_date) || $request->expiration_date === '') {
            $validated['expiration_date'] = null;
        }

        \Log::info('Updating Announcement', ['id' => $announcement->id, 'data' => $validated]);
        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement
     */
    // Delete functionality removed to preserve all data
    public function destroy(Announcement $announcement)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.announcements.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }
}

