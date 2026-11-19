<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class RegistrarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by active status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(10);
        return view('registrar.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registrar.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'type' => 'required|in:admission,enrollment,general',
            'is_active' => 'boolean',
        ]);

        Event::create($validated);

        return redirect()->route('registrar.events.index')->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('registrar.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('registrar.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'type' => 'required|in:admission,enrollment,general',
            'is_active' => 'boolean',
        ]);

        $event->update($validated);

        return redirect()->route('registrar.events.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Delete functionality removed to preserve all data
    public function destroy(Event $event)
    {
        // Data preservation: Delete functionality has been disabled
        return redirect()->route('registrar.events.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }
}
