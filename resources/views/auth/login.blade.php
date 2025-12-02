<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-coffee: #6F4E37;
            --dark-coffee: #3E2723;
            --accent-gold: #C5A065;
            --text-dark: #2C1810;
            --input-bg: #F5F5F7;
        }
        body { font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; background: #fff; }
        
        .split-screen { display: flex; height: 100%; width: 100%; }
        
        /* Left Side - Artistic */
        .left-pane {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 3rem;
            overflow: hidden;
        }
        
        .circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.05); }
        .c1 { width: 400px; height: 400px; top: -100px; left: -100px; }
        .c2 { width: 300px; height: 300px; bottom: 50px; right: -50px; }
        
        .brand-content { position: relative; z-index: 2; text-align: center; }
        
        .logo-img { 
            width: 180px; 
            margin-bottom: 2rem; 
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3)); 
            transition: transform 0.5s ease;
        }
        .logo-img:hover { transform: scale(1.05) rotate(-3deg); }
        
        /* Right Side - Form */
        .right-pane {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #fff;
            overflow-y: auto;
        }
        
        .auth-card { width: 100%; max-width: 420px; padding: 2rem; }
        
        .form-control {
            background-color: var(--input-bg);
            border: 1px solid transparent;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-coffee);
            box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
        }
        
        .input-group-icon {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9CA3AF; z-index: 4;
        }
        .position-relative { margin-bottom: 1.25rem; }
        
        .btn-coffee {
            background: var(--primary-coffee); color: white; width: 100%; padding: 0.8rem;
            border-radius: 12px; font-weight: 600; border: none; transition: all 0.2s;
        }
        .btn-coffee:hover { background: #5A3D2B; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25); }
        
        .auth-link { color: var(--primary-coffee); text-decoration: none; font-weight: 600; }
        .auth-link:hover { text-decoration: underline; }

        @media (max-width: 768px) { .left-pane { display: none; } }
    </style>
</head>
<body>

<div class="split-screen">
    <div class="left-pane">
        <div class="circle c1"></div>
        <div class="circle c2"></div>
        <div class="brand-content">
            <img src="{{ asset('ka.png') }}" alt="Logo" class="logo-img">
            <h1 class="fw-bold display-5 mb-2">Kape Ni Asero</h1>
            <p class="lead opacity-75">Brewing Excellence, Managing Success.</p>
        </div>
    </div>

    <div class="right-pane">
        <div class="auth-card">
            <div class="mb-4 text-center text-md-start">
                <h3 class="fw-bold text-dark">Welcome Back</h3>
                <p class="text-secondary small">Please enter your credentials to access the system.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="position-relative">
                    <i class="fas fa-envelope input-group-icon"></i>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                    
                    {{-- CHANGED: Loop through ALL errors to show both the failure message and the attempts left --}}
                    @if ($errors->has('email'))
                        @foreach ($errors->get('email') as $message)
                            <span class="text-danger small mt-1 d-block">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </span>
                        @endforeach
                    @endif
                </div>

                <div class="position-relative">
                    <i class="fas fa-lock input-group-icon"></i>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Password" required>
                    @error('password')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-secondary" for="remember">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small auth-link">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-coffee">
                    Sign In <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>