@extends('layouts.app')

@section('title', isset($activity) ? 'Edit Activity' : 'New Activity')

@section('content')
<div class="page-header">
    <a href="{{ route('activities.index') }}" class="text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Back to Activities
    </a>
    <h4 class="mb-0 mt-1">{{ isset($activity) ? 'Edit Activity' : 'Add New Activity' }}</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ isset($activity) ? route('activities.update', $activity) : route('activities.store') }}">
                    @csrf
                    @if(isset($activity)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $activity->title ?? '') }}"
                               placeholder="e.g. Daily SMS count vs SMS count from logs">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                               value="{{ old('category', $activity->category ?? '') }}"
                               placeholder="e.g. SMS Monitoring, System Health, Operations">
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Activities are grouped by category on the daily view.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="What should the team member check or do?">{{ old('description', $activity->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if(isset($activity))
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   value="1" {{ old('is_active', $activity->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active (visible on daily view)</label>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            {{ isset($activity) ? 'Save Changes' : 'Create Activity' }}
                        </button>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
