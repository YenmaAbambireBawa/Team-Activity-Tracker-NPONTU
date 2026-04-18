<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Team Activity Tracker')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background-color: #1a2236;
            padding-top: 1rem;
        }
        .sidebar .nav-link {
            color: #b0b8cc;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: 0.9rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #2d3f5e;
        }
        .sidebar .nav-link i { margin-right: 8px; width: 18px; }
        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.5rem 1.2rem 1.5rem;
            display: block;
            border-bottom: 1px solid #2d3f5e;
            margin-bottom: 0.5rem;
        }
        .sidebar-section-label {
            color: #5a6a85;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 1rem 1.2rem 0.3rem;
        }
        .main-content { padding: 1.5rem 2rem; }
        .badge-pending { background-color: #ffc107; color: #212529; }
        .badge-done { background-color: #198754; }
        .status-row-pending { border-left: 4px solid #ffc107; }
        .status-row-done { border-left: 4px solid #198754; }
        .page-header {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- sidebar --}}
        <div class="col-md-2 p-0 sidebar d-none d-md-block">
            <a class="sidebar-brand" href="{{ route('logs.daily') }}">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> Activity Tracker
            </a>

            <div class="sidebar-section-label">Tracking</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('logs.daily') ? 'active' : '' }}"
                   href="{{ route('logs.daily') }}">
                    <i class="bi bi-calendar-check"></i> Daily View
                </a>
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                   href="{{ route('reports.index') }}">
                    <i class="bi bi-bar-chart-line"></i> Reports
                </a>
            </nav>

            @if(auth()->user()->isAdmin())
            <div class="sidebar-section-label">Management</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}"
                   href="{{ route('activities.index') }}">
                    <i class="bi bi-list-task"></i> Activities
                </a>
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                   href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i> Team Members
                </a>
            </nav>
            @endif

            <div class="sidebar-section-label">Account</div>
            <nav class="nav flex-column">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100">
                        <i class="bi bi-box-arrow-left"></i> Sign Out
                    </button>
                </form>
            </nav>
        </div>

        {{-- main area --}}
        <div class="col-md-10 main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

{{-- user info modal --}}
<div class="modal fade" id="userInfoModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Your Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <p class="mb-1"><strong>Name:</strong> {{ auth()->user()->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                @if(auth()->user()->employee_id)
                <p class="mb-1"><strong>Employee ID:</strong> {{ auth()->user()->employee_id }}</p>
                @endif
                @if(auth()->user()->department)
                <p class="mb-1"><strong>Department:</strong> {{ auth()->user()->department }}</p>
                @endif
                <p class="mb-0"><strong>Role:</strong>
                    <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'secondary' }}">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
