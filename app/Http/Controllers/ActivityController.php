<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct()
    {
        // only admins can create, edit or delete activity definitions
        $this->middleware('role:admin')->except(['index']);
    }

    public function index()
    {
        $activities = Activity::with('creator')
            ->orderBy('category')
            ->orderBy('title')
            ->paginate(20);

        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category'    => 'required|string|max:100',
        ]);

        $validated['created_by'] = auth()->id();

        Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category'    => 'required|string|max:100',
            'is_active'   => 'boolean',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity removed.');
    }
}
