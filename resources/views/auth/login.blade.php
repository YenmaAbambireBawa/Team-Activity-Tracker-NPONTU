<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Activity Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border-radius: 12px;
        }
        .login-header {
            background-color: #1a2236;
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <div class="fs-5 fw-bold">Team Activity Tracker</div>
            <div class="small opacity-75 mt-1">Applications Support Team</div>
        </div>
        <div class="card-body p-4">
            <h6 class="mb-4 text-muted">Sign in to your account</h6>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" autofocus required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                </div>
                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label small">Keep me signed in</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>

            <div class="text-center text-muted small mt-4">
                Contact your administrator if you need an account created.
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
