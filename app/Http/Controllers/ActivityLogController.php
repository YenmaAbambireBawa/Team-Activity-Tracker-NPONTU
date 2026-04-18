<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /*
     * Daily view: shows all active activities with their latest status for the selected date.
     * This is the main screen team members use during their shift.
     */
    public function dailyView(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $activities = Activity::where('is_active', true)
            ->with(['logs' => function ($query) use ($date) {
                $query->where('log_date', $date)
                    ->with('user')
                    ->orderBy('created_at', 'desc');
            }])
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        return view('logs.daily', compact('activities', 'date'));
    }

    /*
     * Update form: pre-populates with the latest status so the user
     * can see what was last recorded before making their own update.
     */
    public function updateForm(Request $request, Activity $activity)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $latestLog = $activity->latestLogForDate($date);

        return view('logs.update', compact('activity', 'date', 'latestLog'));
    }

    /*
     * Stores a new log entry — we never overwrite existing logs.
     * Every update is appended so we have a full trail for handovers.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'status'      => 'required|in:pending,done',
            'remark'      => 'nullable|string|max:1000',
            'log_date'    => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        ActivityLog::create($validated);

        return redirect()->route('logs.daily', ['date' => $validated['log_date']])
            ->with('success', 'Activity status updated.');
    }

    /*
     * Full update trail for a single activity on a specific date —
     * useful for reviewing what happened and who made each change.
     */
    public function history(Request $request, Activity $activity)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $logs = ActivityLog::where('activity_id', $activity->id)
            ->where('log_date', $date)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('logs.history', compact('activity', 'logs', 'date'));
    }
}
