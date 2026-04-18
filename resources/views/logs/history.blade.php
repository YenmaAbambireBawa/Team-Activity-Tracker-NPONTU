@extends('layouts.app')

@section('title', 'Activity Update History')

@section('content')
<div class="page-header">
    <a href="{{ route('logs.daily', ['date' => $date]) }}" class="text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Back to Daily View
    </a>
    <h4 class="mb-0 mt-1">Update History</h4>
    <small class="text-muted">{{ $activity->title }} — {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</small>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        @if($logs->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-clock-history display-4 d-block mb-3"></i>
                No updates have been recorded for this activity on this date.
            </div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                {{ $logs->count() }} update(s) recorded
            </div>
            <div class="card-body p-0">
                {{--
                    Timeline of updates — oldest first so you can see how status evolved.
                    This supports the handover requirement — anyone opening this can see
                    every change made throughout the day and who made it.
                --}}
                <div class="list-group list-group-flush">
                @foreach($logs as $log)
                    <div class="list-group-item status-row-{{ $log->status }} py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge badge-{{ $log->status }} me-2">
                                    {{ ucfirst($log->status) }}
                                </span>
                                <span class="fw-semibold">{{ $log->user->name }}</span>
                                @if($log->user->employee_id)
                                    <span class="text-muted small ms-1">({{ $log->user->employee_id }})</span>
                                @endif
                                @if($log->user->department)
                                    <span class="badge bg-light text-muted ms-2 small">{{ $log->user->department }}</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="fw-medium small">{{ $log->created_at->format('H:i:s') }}</div>
                                <div class="text-muted" style="font-size:0.75rem">{{ $log->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        @if($log->remark)
                        <div class="mt-2 text-muted small fst-italic border-start ps-2 ms-1">
                            {{ $log->remark }}
                        </div>
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="mt-3 text-end">
            <a href="{{ route('logs.update.form', [$activity, 'date' => $date]) }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square me-1"></i>Add New Update
            </a>
        </div>
    </div>
</div>
@endsection
