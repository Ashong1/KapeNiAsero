<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kape Ni Asero - POS</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* CSS to hide the number input arrows */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield; /* Firefox hide arrows */
        }

        :root {
            /* PALETTE COMPLIMENTING LOGO */
            --color-mocha: #3A241D;    /* Deep Mocha Brown / Primary & Background */
            --color-sienna: #B48C6B;   /* Warm Toffee/Caramel Accent */
            --color-cream: #F9F4F0;    /* Light Cream / Page BG & Foreground Text */
            --color-crema: #E0C7A6;    /* Light Caramel/Tan (Info/Light Accent) */
            --color-olive: #689F38;    /* Olive Green / Success */
            --color-danger: #D84315;   /* Accent Red/Danger */

            --bs-primary: var(--color-mocha);
            --bs-primary-rgb: 58, 36, 29;
            --bs-success: var(--color-olive);
            --bs-danger: var(--color-danger);
            --bs-light: var(--color-cream);
            
            --bs-info: var(--color-crema); 
            --bs-info-rgb: 224, 199, 166;
            --bs-link-color: var(--color-sienna);
            --bs-link-hover-color: var(--color-mocha);
            --bs-link-decoration: none;
        }
        
        /* 1. BODY/BACKGROUND STYLING (Mocha Brown) */
        body {
            background-color: var(--color-mocha) !important;
            color: var(--color-cream);
        }

        /* 2. SPLIT LAYOUT CONTAINER */
        .auth-split-container {
            min-height: calc(100vh - 56px);
            display: flex;
        }

        /* 3. LEFT SIDE: CARD WRAPPER */
        .auth-card-wrapper {
            width: 60%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-height: 100%;
            padding-right: 50px;
        }

        /* 4. RIGHT SIDE: IMAGE CONTAINER */
        .auth-image-container {
            width: 40%;
            min-height: 100%;
            background-image: url("{{ asset('coffee_right_transparent.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        
        /* 5. CARD STYLING (FULLY TRANSPARENT) */
        .card { 
            /* FIX: Make card background fully transparent */
            background-color: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
            
            max-width: 750px;
            width: 90%;
            margin: 0; 
        }

        /* FIX: Set ALL text and labels inside the transparent card to light Cream */
        .card-body,
        .card .col-form-label,
        .card .form-check-label {
            color: var(--color-cream) !important; 
        }

        /* Input Styling (Transparent Background and White Outline) */
        .card .form-control {
            background-color: transparent !important;
            border: 1px solid var(--color-cream) !important; /* White Outline */
            border-radius: 4px !important; 
            padding-left: 0.8rem !important; 
            height: 48px;
            color: var(--color-cream) !important; /* Text entered is light */
        }

        /* Redefine focus state for the transparent input */
        .form-control:focus {
            border-color: var(--color-sienna) !important; 
            box-shadow: 0 0 0 0.25rem rgba(180, 140, 107, 0.25);
        }
        
        /* Card Header Styling (Transparent Mocha) */
        .card-header {
            /* Full transparency for the header background */
            background-color: rgba(58, 36, 29, 0.85) !important; 
            border-bottom: 1px solid var(--color-sienna) !important;
            color: var(--color-cream) !important; /* Header text remains light */
        }
        
        /* General Theming */
        .bg-primary { background-color: var(--color-mocha) !important; }
        .text-primary { color: var(--color-sienna) !important; } 
        .btn-primary { 
            background-color: var(--color-sienna) !important; 
            border-color: var(--color-sienna) !important; 
            color: var(--color-mocha) !important; 
            border-radius: 8px !important; 
        }
        .btn-primary:hover { 
            background-color: #A06F51 !important; 
            border-color: #A06F51 !important; 
        }
        .form-control:focus {
            border-color: var(--color-sienna); 
            box-shadow: 0 0 0 0.25rem rgba(180, 140, 107, 0.25);
        }
        .card a {
            color: var(--color-sienna);
        }
        .badge.bg-info {
            color: var(--color-mocha) !important;
        }
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="auth-background">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark navbar-transparent">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <img src="{{ asset('ka.png') }}" alt="Kape Ni Asero Logo" style="height: 45px;" class="me-2">
                    Kape Ni Asero POS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-cash-register me-1"></i> POS System</a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} ({{ Auth::user()->role }})
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role == 'admin')
                                        <a class="dropdown-item" href="{{ route('products.create') }}">
                                            <i class="fas fa-plus me-2"></i> Add New Product
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{ route('categories.index') }}">
                                            <i class="fas fa-tags me-2"></i> Manage Categories
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
        
        <div class="auth-split-container">
            <div class="auth-card-wrapper">
                <main class="py-4" style="width: 100%;">
                    @yield('content')
                </main>
            </div>
            
            <div class="auth-image-container">
            </div>
        </div>
    </div>
</body>
</html>