<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two Factor Verification | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-coffee: #6F4E37; --dark-coffee: #3E2723; --accent-gold: #C5A065; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark-coffee) 0%, var(--primary-coffee) 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%; max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .icon-circle {
            width: 70px; height: 70px; background: rgba(111, 78, 55, 0.1);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem; color: var(--primary-coffee); font-size: 1.8rem;
        }
        .otp-input {
            letter-spacing: 0.8rem; font-weight: 700; font-size: 1.5rem; text-align: center;
            border-radius: 12px; padding: 0.8rem; border: 2px solid #E0E0E0;
        }
        .otp-input:focus { border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
        .btn-verify {
            background: var(--primary-coffee); color: white; width: 100%; padding: 0.9rem;
            border-radius: 12px; font-weight: 600; border: none; margin-top: 1.5rem;
            transition: all 0.2s;
        }
        .btn-verify:hover { background: #5A3D2B; transform: translateY(-1px); }
    </style>
</head>
<body>

<div class="glass-card">
    <div class="icon-circle"><i class="fas fa-shield-alt"></i></div>
    <h3 class="fw-bold text-dark mb-2">Two-Factor Authentication</h3>
    <p class="text-muted small mb-4">For your security, please enter the 6-digit code sent to your email.</p>

    @if(session()->has('message'))
        <div class="alert alert-success py-2 small border-0 shadow-sm">{{ session()->get('message') }}</div>
    @endif
    @if($errors->has('msg'))
        <div class="alert alert-danger py-2 small border-0 shadow-sm">{{ $errors->first('msg') }}</div>
    @endif

    <form method="POST" action="{{ route('verify.store') }}">
        @csrf
        <div class="mb-3">
            <input id="two_factor_code" name="two_factor_code" type="text" inputmode="numeric"
                   class="form-control otp-input @error('two_factor_code') is-invalid @enderror" 
                   required autofocus maxlength="6" placeholder="000000">
            @error('two_factor_code')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center small mt-3">
            <span class="text-muted">Expires in: <span id="otp-timer" class="fw-bold text-danger">10:00</span></span>
            <button type="button" id="resend-button" class="btn btn-link p-0 text-decoration-none fw-bold" disabled style="color: var(--primary-coffee);">Resend Code</button>
        </div>

        <button type="submit" class="btn-verify">Verify Identity</button>
    </form>
    
    <div class="mt-4 border-top pt-3">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-link text-secondary text-decoration-none small">Cancel & Logout</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let timeRemaining = 600; 
        const timerDisplay = document.getElementById('otp-timer');
        const resendButton = document.getElementById('resend-button');

        function updateTimer() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            timerDisplay.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

            if (timeRemaining <= 0) {
                clearInterval(countdown);
                timerDisplay.textContent = 'EXPIRED';
                resendButton.disabled = false;
            } else {
                timeRemaining--;
            }
        }
        
        resendButton.addEventListener('click', function() {
            if (!resendButton.disabled) {
                fetch('{{ route('verify.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                }).then(() => {
                    alert('New code sent!');
                    timeRemaining = 600;
                    resendButton.disabled = true;
                });
            }
        });

        const countdown = setInterval(updateTimer, 1000);
        updateTimer();
    });
</script>
</body>
</html>