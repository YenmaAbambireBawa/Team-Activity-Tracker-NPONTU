@extends('layouts.app')

@section('title', 'Daily Activity View')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-0">Daily Activity View</h4>
        <small class="text-muted">Track and update team activity statuses</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        {{-- date picker to view any past day --}}
        <form method="GET" action="{{ route('logs.daily') }}" class="d-flex gap-2">
            <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm"
                   max="{{ \Carbon\Carbon::today()->toDateString() }}">
            <button class="btn btn-sm btn-outline-secondary">View</button>
        </form>
        <span class="badge bg-dark fs-6 ms-2">
            {{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}
        </span>
    </div>
</div>

{{-- quick summary row --}}
@php
    $totalActivities = $activities->count();
    $doneCount = $activities->filter(fn($a) => $a->logs->isNotEmpty() && $a->logs->first()->status === 'done')->count();
    $pendingCount = $totalActivities - $doneCount;
@endphp
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-2 fw-bold">{{ $totalActivities }}</div>
            <div class="text-muted small">Total Activities</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3 border-start border-success border-3">
            <div class="fs-2 fw-bold text-success">{{ $doneCount }}</div>
            <div class="text-muted small">Completed</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3 border-start border-warning border-3">
            <div class="fs-2 fw-bold text-warning">{{ $pendingCount }}</div>
            <div class="text-muted small">Pending</div>
        </div>
    </div>
</div>

{{-- group activities by category for easier scanning --}}
@php $grouped = $activities->groupBy('category'); @endphp

@foreach($grouped as $category => $categoryActivities)
<div class="mb-4">
    <h6 class="text-uppercase text-muted fw-bold small mb-2 border-bottom pb-1">{{ $category }}</h6>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:35%">Activity</th>
                        <th style="width:12%">Status</th>
                        <th style="width:20%">Last Updated By</th>
                        <th style="width:13%">Time</th>
                        <th style="width:20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($categoryActivities as $activity)
                    @php $latestLog = $activity->logs->first(); @endphp
                    <tr class="{{ $latestLog ? 'status-row-' . $latestLog->status : '' }}">
                        <td>
                            <div class="fw-medium">{{ $activity->title }}</div>
                            @if($activity->description)
                            <div class="text-muted small">{{ Str::limit($activity->description, 80) }}</div>
                            @endif
                            @if($latestLog && $latestLog->remark)
                            <div class="text-muted small fst-italic mt-1">
                                <i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($latestLog->remark, 80) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($latestLog)
                                <span class="badge badge-{{ $latestLog->status }}">
                                    {{ ucfirst($latestLog->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">No update</span>
                            @endif
                        </td>
                        <td>
                            @if($latestLog)
                                <div class="fw-medium small">{{ $latestLog->user->name }}</div>
                                @if($latestLog->user->employee_id)
                                <div class="text-muted" style="font-size:0.75rem">{{ $latestLog->user->employee_id }}</div>
                                @endif
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            @if($latestLog)
                                {{ $latestLog->created_at->format('H:i') }}
                                <div style="font-size:0.75rem">{{ $latestLog->created_at->format('d M') }}</div>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('logs.update.form', [$activity, 'date' => $date]) }}"
                               class="btn btn-sm btn-primary me-1">
                                <i class="bi bi-pencil-square me-1"></i>Update
                            </a>
                            <a href="{{ route('logs.history', [$activity, 'date' => $date]) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach

@if($activities->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-inbox display-4 d-block mb-3"></i>
        No active activities configured yet.
        @if(auth()->user()->isAdmin())
            <a href="{{ route('activities.create') }}" class="d-block mt-2">Create your first activity</a>
        @endif
    </div>
@endif
@endsection
