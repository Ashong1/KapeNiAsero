<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kape Ni Asero</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            /* NEW PALETTE FROM LOGIN PAGE */
            --primary-coffee: #6F4E37;
            --dark-coffee: #3E2723;
            --text-dark: #2C1810;
            --text-medium: #5D4E37;
            --accent-gold: #8B7355;
            --surface-cream: #FFF8E7;
            --surface-white: #FFFFFF;
            --border-light: #F0E5D0;
            --input-border: #E8DCC8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-coffee) !important;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }

        .nav-link {
            color: var(--text-medium) !important;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-coffee) !important;
        }

        /* Global Card Styling */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: var(--surface-white);
            overflow: hidden;
        }

        .card-header {
            background-color: var(--surface-cream);
            border-bottom: 1px solid var(--border-light);
            color: var(--primary-coffee);
            font-weight: 700;
            padding: 1.25rem 1.5rem;
        }

        /* Inputs */
        .form-control, .form-select {
            border: 2px solid var(--input-border);
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-coffee);
            box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-coffee);
            border-color: var(--primary-coffee);
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.2);
        }

        .btn-primary:hover {
            background-color: #5A3D2B;
            border-color: #5A3D2B;
            transform: translateY(-1px);
        }

        /* Table Styling for Index pages */
        .table thead th {
            background-color: var(--surface-cream);
            color: var(--primary-coffee);
            font-weight: 600;
            border-bottom: 2px solid var(--border-light);
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 0.5rem;
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: var(--text-dark);
        }
        .dropdown-item:hover {
            background-color: var(--surface-cream);
            color: var(--primary-coffee);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- UPDATED LOGO HERE -->
                    <img src="{{ asset('ka.png') }}" alt="Kape Ni Asero" style="height: 40px;" class="me-2">
                    Kape Ni Asero
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.index') }}">POS</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role == 'admin')
                                        <a class="dropdown-item" href="{{ route('ingredients.index') }}"><i class="fas fa-boxes me-2 text-muted"></i> Warehouse</a>
                                        <a class="dropdown-item" href="{{ route('categories.index') }}"><i class="fas fa-tags me-2 text-muted"></i> Categories</a>
                                        <a class="dropdown-item" href="{{ route('suppliers.index') }}"><i class="fas fa-truck me-2 text-muted"></i> Suppliers</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2 text-danger"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>