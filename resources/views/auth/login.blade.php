<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | Kape Ni Asero</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --primary-coffee: #6F4E37;
            --dark-coffee: #3E2723;
            --text-dark: #2C1810;
            --text-medium: #5D4E37;
            --accent-gold: #8B7355;
            --surface-cream: #FFF8E7;
            --surface-white: #FFFFFF;
            --border-light: #F0E5D0;
            --input-border: #E8DCC8;
            --error-red: #DC3545;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            color: var(--text-dark);
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-img {
            width: 90px;
            height: 90px;
            background: var(--surface-cream);
            border-radius: 24px;
            padding: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            margin-bottom: 1rem;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--surface-cream);
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .brand-tagline {
            color: rgba(255, 248, 231, 0.8);
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        .login-card {
            background: var(--surface-white);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .card-header {
            background-color: var(--surface-cream);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            text-align: center;
        }

        .card-title {
            color: var(--primary-coffee);
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-gold);
            font-size: 1rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border: 2px solid var(--input-border);
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-coffee);
            box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
        }

        .is-invalid {
            border-color: var(--error-red) !important;
            background-color: #FFF5F5;
        }

        .invalid-feedback {
            color: var(--error-red);
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: block;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-medium);
            cursor: pointer;
        }
        
        .remember-me input {
            accent-color: var(--primary-coffee);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary-coffee);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--dark-coffee);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: var(--primary-coffee);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25);
        }

        .btn-login:hover {
            background: #5A3D2B;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(111, 78, 55, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-text {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: var(--text-medium);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            font-size: 0.85rem;
            color: var(--accent-gold);
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('ka.png') }}" alt="Kape Ni Asero Logo" class="logo-img">
            <h1 class="brand-name">Kape Ni Asero</h1>
            <p class="brand-tagline">Point of Sale & Inventory System</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                </h2>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                                   placeholder="Enter your email">
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password" 
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="form-options">
                        <label class="remember-me" for="remember">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember Me
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <!-- New Employee Registration Option -->
                    <div class="register-text">
                        <p>New Employee? <a href="{{ route('register') }}" class="forgot-link">Register Here</a></p>
                    </div>

                    <!-- Footer -->
                    <div class="footer-text">
                        <i class="fas fa-shield-alt me-1"></i> Secure System Access
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>