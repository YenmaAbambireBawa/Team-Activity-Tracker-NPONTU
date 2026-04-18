@extends('layouts.app')

@section('title', isset($user) ? 'Edit Team Member' : 'Add Team Member')

@section('content')
<div class="page-header">
    <a href="{{ route('users.index') }}" class="text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Back to Team Members
    </a>
    <h4 class="mb-0 mt-1">{{ isset($user) ? 'Edit Team Member' : 'Add Team Member' }}</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                    @csrf
                    @if(isset($user)) @method('PUT') @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name ?? '') }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Employee ID</label>
                            <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror"
                                   value="{{ old('employee_id', $user->employee_id ?? '') }}">
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email ?? '') }}"
                               {{ isset($user) ? 'readonly' : '' }}>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(isset($user))
                        <div class="form-text">Email cannot be changed after account creation.</div>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department</label>
                            <input type="text" name="department" class="form-control"
                                   value="{{ old('department', $user->department ?? '') }}"
                                   placeholder="e.g. Applications Support">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="member" {{ old('role', $user->role ?? 'member') === 'member' ? 'selected' : '' }}>
                                Member (can update activity statuses)
                            </option>
                            <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>
                                Admin (can also manage activities and users)
                            </option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Password {{ isset($user) ? '(leave blank to keep current)' : '' }}
                                @if(!isset($user)) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            {{ isset($user) ? 'Save Changes' : 'Create Account' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
