<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-coffee: #6F4E37; --dark-coffee: #3E2723; --input-bg: #F5F5F7; --success-green: #2E7D32;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark-coffee) 0%, var(--primary-coffee) 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 24px;
            padding: 3rem 2rem; width: 100%; max-width: 450px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center;
        }
        
        .form-control {
            background-color: var(--input-bg); border: 1px solid transparent; padding: 0.8rem 1rem 0.8rem 2.5rem;
            border-radius: 12px; font-size: 0.95rem; transition: all 0.3s;
        }
        .form-control:focus { background-color: #fff; border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
        .input-group-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9CA3AF; z-index: 4; }
        
        /* Helper to ensure spacing when error appears */
        .input-wrapper { margin-bottom: 1.5rem; }
        
        .btn-coffee {
            background: var(--primary-coffee); color: white; width: 100%; padding: 0.8rem; border-radius: 12px;
            font-weight: 600; border: none; transition: all 0.2s; box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25);
        }
        .btn-coffee:hover { background: #5A3D2B; transform: translateY(-1px); }
        .auth-link { color: var(--primary-coffee); font-weight: 600; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="glass-card">
    <h3 class="fw-bold text-dark mb-2">Reset Password</h3>
    <p class="text-secondary small mb-4">Enter your email to receive a reset link.</p>

    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4 text-start small">
            <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-wrapper text-start">
            <div class="position-relative">
                <i class="fas fa-envelope input-group-icon"></i>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                       placeholder="Email Address">
            </div>
            {{-- Error moved OUTSIDE the position-relative div --}}
            @error('email')
                <span class="text-danger small mt-1 d-block ps-1">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-coffee">
            Send Reset Link
        </button>

        <div class="mt-4 pt-3 border-top">
            <a href="{{ route('login') }}" class="auth-link text-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>
</div>

</body>
</html>