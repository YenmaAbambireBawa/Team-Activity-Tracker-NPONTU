@extends('layouts.app')

@section('title', 'Activity Reports')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-0">Activity Reports</h4>
        <small class="text-muted">Query activity history across any date range</small>
    </div>
</div>

{{-- filter form --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-semibold">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Activity</label>
                <select name="activity_id" class="form-select form-select-sm">
                    <option value="">All Activities</option>
                    @foreach($activities as $activity)
                        <option value="{{ $activity->id }}"
                            {{ ($validated['activity_id'] ?? '') == $activity->id ? 'selected' : '' }}>
                            {{ $activity->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Team Member</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">All Members</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ ($validated['user_id'] ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="done" {{ ($validated['status'] ?? '') === 'done' ? 'selected' : '' }}>Done</option>
                    <option value="pending" {{ ($validated['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Run Report
                </button>
            </div>
        </form>
    </div>
</div>

{{-- summary --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-2 fw-bold">{{ $logs->total() }}</div>
            <div class="text-muted small">Total Updates in Range</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3 border-start border-success border-3">
            <div class="fs-2 fw-bold text-success">{{ $summary['done'] }}</div>
            <div class="text-muted small">Done</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3 border-start border-warning border-3">
            <div class="fs-2 fw-bold text-warning">{{ $summary['pending'] }}</div>
            <div class="text-muted small">Pending</div>
        </div>
    </div>
</div>

{{-- results table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Updated By</th>
                    <th>Time</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr class="status-row-{{ $log->status }}">
                    <td class="small">{{ $log->log_date->format('d M Y') }}</td>
                    <td>
                        <div class="fw-medium small">{{ $log->activity->title }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark small">{{ $log->activity->category }}</span></td>
                    <td>
                        <span class="badge badge-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td>
                        <div class="small fw-medium">{{ $log->user->name }}</div>
                        @if($log->user->employee_id)
                        <div class="text-muted" style="font-size:0.75rem">{{ $log->user->employee_id }}</div>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $log->created_at->format('H:i') }}</td>
                    <td class="small text-muted fst-italic">{{ $log->remark ? Str::limit($log->remark, 60) : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No activity logs found for the selected filters.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
