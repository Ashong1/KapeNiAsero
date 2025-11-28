<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Email | Kape Ni Asero</title>

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
            --success-green: #198754;
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

        .verify-container {
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

        .verify-card {
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
            text-align: center;
        }

        .alert-success {
            background-color: #D1E7DD;
            color: var(--success-green);
            border: 1px solid #BADBCC;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-text {
            color: var(--text-medium);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .btn-resend {
            display: inline-block;
            background: transparent;
            color: var(--primary-coffee);
            border: 2px solid var(--primary-coffee);
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-resend:hover {
            background: var(--surface-cream);
            transform: translateY(-1px);
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

    <div class="verify-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('ka.png') }}" alt="Kape Ni Asero Logo" class="logo-img">
            <h1 class="brand-name">Verification Required</h1>
        </div>

        <!-- Verify Card -->
        <div class="verify-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-envelope-open-text"></i> Verify Your Email
                </h2>
            </div>
            
            <div class="card-body">
                @if (session('resent'))
                    <div class="alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <div class="info-text">
                    <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                    <p>{{ __('If you did not receive the email, click below to request another.') }}</p>
                </div>

                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn-resend">
                        <i class="fas fa-paper-plane me-1"></i> {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <div class="footer-text">
                    <i class="fas fa-shield-alt me-1"></i> Secure Account Verification
                </div>
            </div>
        </div>
    </div>

</body>
</html>