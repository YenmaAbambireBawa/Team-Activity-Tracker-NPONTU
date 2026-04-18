@extends('layouts.app')

@section('title', 'Manage Activities')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-0">Activities</h4>
        <small class="text-muted">Manage the activity checklist for the team</small>
    </div>
    <a href="{{ route('activities.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Add Activity
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td>
                        <div class="fw-medium">{{ $activity->title }}</div>
                        @if($activity->description)
                        <div class="text-muted small">{{ Str::limit($activity->description, 70) }}</div>
                        @endif
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $activity->category }}</span></td>
                    <td class="small text-muted">{{ $activity->creator->name }}</td>
                    <td>
                        @if($activity->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('activities.edit', $activity) }}"
                           class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                        <form method="POST" action="{{ route('activities.destroy', $activity) }}"
                              style="display:inline"
                              onsubmit="return confirm('Remove this activity? All its logs will also be deleted.')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No activities yet. <a href="{{ route('activities.create') }}">Create one.</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($activities->hasPages())
    <div class="card-footer bg-white">{{ $activities->links() }}</div>
    @endif
</div>
@endsection
