<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-coffee: #6F4E37; --dark-coffee: #3E2723; --input-bg: #F5F5F7; }
        body { font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; background: #fff; }
        .split-screen { display: flex; height: 100%; width: 100%; }
        
        .left-pane {
            flex: 1;
            background: linear-gradient(135deg, var(--dark-coffee) 0%, var(--primary-coffee) 100%);
            display: flex; justify-content: center; align-items: center; color: white;
            position: relative; overflow: hidden;
        }
        .brand-content { z-index: 2; text-align: center; padding: 2rem; }
        
        /* ANIMATED LOGO */
        .logo-img { 
            width: 150px; 
            margin-bottom: 1.5rem; 
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.3)); 
            transition: transform 0.5s ease; 
        }
        
        .logo-img:hover { 
            transform: scale(1.05) rotate(-3deg); 
        }
        
        .right-pane {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 2rem; overflow-y: auto; background: #fff;
        }
        .auth-card { width: 100%; max-width: 450px; }
        
        .form-control {
            background-color: var(--input-bg); border: 1px solid transparent; padding: 0.8rem 1rem 0.8rem 2.5rem;
            border-radius: 12px; font-size: 0.95rem; transition: all 0.3s;
        }
        .form-control:focus { background-color: #fff; border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
        .input-group-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9CA3AF; z-index: 4; }
        
        /* PASSWORD TOGGLE STYLE */
        .password-toggle-icon { 
            position: absolute; 
            right: 1rem; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #9CA3AF; 
            z-index: 4; 
            cursor: pointer;
            transition: color 0.2s, transform 0.1s;
        }
        .password-toggle-icon:hover { color: var(--primary-coffee); }
        .password-toggle-icon:active { transform: translateY(-50%) scale(0.9); }

        .position-relative { margin-bottom: 1rem; }
        .btn-coffee {
            background: var(--primary-coffee); color: white; width: 100%; padding: 0.8rem; border-radius: 12px; font-weight: 600; border: none;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); transition: all 0.2s;
        }
        .btn-coffee:hover { background: #5A3D2B; transform: translateY(-1px); }
        .auth-link { color: var(--primary-coffee); font-weight: 600; text-decoration: none; }
        @media (max-width: 768px) { .left-pane { display: none; } }
    </style>
</head>
<body>

<div class="split-screen">
    <div class="left-pane">
        <div class="brand-content">
            <img src="{{ asset('ka.png') }}" alt="Logo" class="logo-img">
            <h2 class="fw-bold mb-3">Join the Team</h2>
            <p class="opacity-75" style="max-width: 300px;">Create an account to start managing orders and inventory.</p>
        </div>
    </div>

    <div class="right-pane">
        <div class="auth-card">
            <div class="mb-4">
                <h3 class="fw-bold text-dark">Create Account</h3>
                <p class="text-secondary small">Register a new employee profile.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="position-relative">
                    <i class="fas fa-user input-group-icon"></i>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" value="{{ old('name') }}" required>
                    @error('name')<span class="text-danger small mt-1 d-block">{{ $message }}</span>@enderror
                </div>
                <div class="position-relative">
                    <i class="fas fa-envelope input-group-icon"></i>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}" required>
                    @error('email')<span class="text-danger small mt-1 d-block">{{ $message }}</span>@enderror
                </div>
                
                <div class="position-relative">
                    <i class="fas fa-lock input-group-icon"></i>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" style="padding-right: 2.5rem;" required>
                    <i class="fas fa-eye password-toggle-icon" id="togglePassword"></i>
                    
                    @error('password')<span class="text-danger small mt-1 d-block">{{ $message }}</span>@enderror
                </div>

                <div class="position-relative">
                    <i class="fas fa-check-circle input-group-icon"></i>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn-coffee mt-2">Register</button>
                <p class="text-center mt-4 small text-secondary">Already registered? <a href="{{ route('login') }}" class="auth-link">Sign In</a></p>
            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // 1. Toggle the type attribute (Show/Hide password)
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // 2. Toggle the ICON classes
        // This toggles BOTH classes. If one exists, it removes it; if it doesn't, it adds it.
        // This ensures they swap correctly.
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>