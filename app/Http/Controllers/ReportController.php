<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /*
     * Reporting view — allows filtering activity history by date range,
     * specific activity, specific team member, and status.
     * Results are paginated so large date ranges stay manageable.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from'        => 'nullable|date',
            'to'          => 'nullable|date|after_or_equal:from',
            'activity_id' => 'nullable|exists:activities,id',
            'user_id'     => 'nullable|exists:users,id',
            'status'      => 'nullable|in:pending,done',
        ]);

        $from = $validated['from'] ?? Carbon::today()->subDays(7)->toDateString();
        $to   = $validated['to']   ?? Carbon::today()->toDateString();

        $query = ActivityLog::with(['activity', 'user'])
            ->whereBetween('log_date', [$from, $to])
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc');

        if (!empty($validated['activity_id'])) {
            $query->where('activity_id', $validated['activity_id']);
        }

        if (!empty($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $logs = $query->paginate(25)->withQueryString();

        // summary counts shown at the top of the report
        $summary = [
            'total'   => $query->count(),
            'done'    => (clone $query)->where('status', 'done')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
        ];

        $activities = Activity::orderBy('title')->get();
        $users      = User::orderBy('name')->get();

        return view('reports.index', compact('logs', 'activities', 'users', 'from', 'to', 'summary', 'validated'));
    }
}
