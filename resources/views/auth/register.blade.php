<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register | Kape Ni Asero</title>

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

        .register-container {
            width: 100%;
            max-width: 500px;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-img {
            width: 80px;
            height: 80px;
            background: var(--surface-cream);
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            margin-bottom: 0.75rem;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--surface-cream);
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .register-card {
            background: var(--surface-white);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .card-header {
            background-color: var(--surface-cream);
            padding: 1.25rem;
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

        .btn-register {
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
            margin-top: 1rem;
        }

        .btn-register:hover {
            background: #5A3D2B;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(111, 78, 55, 0.35);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .login-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-medium);
        }

        .login-link {
            color: var(--primary-coffee);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link:hover {
            color: var(--dark-coffee);
            text-decoration: underline;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-light);
            font-size: 0.8rem;
            color: var(--accent-gold);
        }
    </style>
</head>
<body>

    <div class="register-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('ka.png') }}" alt="Kape Ni Asero Logo" class="logo-img">
            <h1 class="brand-name">Create Account</h1>
        </div>

        <!-- Register Card -->
        <div class="register-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-user-plus"></i> Register Employee
                </h2>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                                   placeholder="Enter full name">
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" 
                                   placeholder="Enter email address">
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
                                   name="password" required autocomplete="new-password" 
                                   placeholder="Create password">
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-check-circle input-icon"></i>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password" 
                                   placeholder="Repeat password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-check"></i> Complete Registration
                    </button>

                    <!-- Back to Login -->
                    <div class="login-text">
                        <p>Already have an account? <a href="{{ route('login') }}" class="login-link">Login Here</a></p>
                    </div>

                    <!-- Footer -->
                    <div class="footer-text">
                        <i class="fas fa-shield-alt me-1"></i> Employee Access Control
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>