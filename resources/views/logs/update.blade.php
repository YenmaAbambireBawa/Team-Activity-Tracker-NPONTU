@extends('layouts.app')

@section('title', 'Update Activity')

@section('content')
<div class="page-header">
    <a href="{{ route('logs.daily', ['date' => $date]) }}" class="text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Back to Daily View
    </a>
    <h4 class="mb-0 mt-1">Update Activity Status</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                {{-- show what the activity is so the user knows what they are updating --}}
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Activity</div>
                    <div class="fw-semibold fs-5">{{ $activity->title }}</div>
                    @if($activity->description)
                    <div class="text-muted small mt-1">{{ $activity->description }}</div>
                    @endif
                    <div class="mt-2">
                        <span class="badge bg-secondary">{{ $activity->category }}</span>
                        <span class="text-muted small ms-2">Date: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- show the previous status if this is not the first update of the day --}}
                @if($latestLog)
                <div class="alert alert-info py-2 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Last updated at <strong>{{ $latestLog->created_at->format('H:i') }}</strong>
                    by <strong>{{ $latestLog->user->name }}</strong> —
                    status was <strong>{{ ucfirst($latestLog->status) }}</strong>
                    @if($latestLog->remark)
                    with remark: "{{ $latestLog->remark }}"
                    @endif
                </div>
                @endif

                {{-- the update form itself --}}
                <form method="POST" action="{{ route('logs.store') }}">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                    <input type="hidden" name="log_date" value="{{ $date }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check form-check-inline p-0">
                                <input class="btn-check" type="radio" name="status" id="status_done"
                                       value="done" {{ old('status', $latestLog?->status) === 'done' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="status_done">
                                    <i class="bi bi-check-circle me-1"></i>Done
                                </label>
                            </div>
                            <div class="form-check form-check-inline p-0">
                                <input class="btn-check" type="radio" name="status" id="status_pending"
                                       value="pending" {{ old('status', $latestLog?->status) === 'pending' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="status_pending">
                                    <i class="bi bi-hourglass-split me-1"></i>Pending
                                </label>
                            </div>
                        </div>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="remark" class="form-label fw-semibold">Remark</label>
                        <textarea name="remark" id="remark" rows="4"
                                  class="form-control @error('remark') is-invalid @enderror"
                                  placeholder="Add any notes, observations, or context for this update...">{{ old('remark', $latestLog?->remark) }}</textarea>
                        @error('remark')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">This remark will be visible on the daily handover view.</div>
                    </div>

                    {{-- capture who is making the update — displayed for confirmation --}}
                    <div class="p-3 bg-light rounded mb-4 small">
                        <i class="bi bi-person-check me-1 text-muted"></i>
                        This update will be recorded under your account:
                        <strong>{{ auth()->user()->name }}</strong>
                        @if(auth()->user()->employee_id)
                            ({{ auth()->user()->employee_id }})
                        @endif
                        at the current time.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Save Update
                        </button>
                        <a href="{{ route('logs.daily', ['date' => $date]) }}"
                           class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
