<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- Prevent auto-zoom on mobile inputs --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kape Ni Asero') }}</title>

    {{-- Fonts and Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            /* BRAND PALETTE */
            --primary-coffee: #6F4E37;
            --primary-coffee-hover: #5A3D2B;
            --dark-coffee: #3E2723;
            --accent-gold: #C5A065;
            --surface-cream: #FFF8E7;
            --surface-glass: rgba(255, 255, 255, 0.92);
            --surface-bg: #F5F5F7;
            --text-dark: #1D1D1F;
            --text-secondary: #86868B;
            --success-green: #34C759;
            --danger-red: #D32F2F;
            --border-light: #EFEBE9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F5F5F5 0%, #E0E0E0 100%);
            background-image: radial-gradient(at 0% 0%, rgba(111, 78, 55, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(197, 160, 101, 0.1) 0px, transparent 50%);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* --- PREMIUM NAVBAR (Original Design) --- */
        .navbar-premium {
            background-color: var(--surface-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 24px -1px rgba(62, 39, 35, 0.06);
            padding: 0.8rem 1rem;
            margin-bottom: 2rem;
            margin-top: 1rem;
            border-radius: 24px;
            /* Fix z-index for dropdowns */
            position: relative; 
            z-index: 1050; 
        }

        .navbar-brand-wrapper { display: flex; align-items: center; gap: 1rem; }
        .logo-container {
            background: white; padding: 6px; border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .navbar-brand:hover .logo-container { transform: scale(1.05) rotate(-3deg); }
        .brand-title { font-weight: 800; font-size: 1.1rem; color: var(--text-dark); letter-spacing: -0.02em; }
        .brand-subtitle { font-size: 0.75rem; color: var(--text-secondary); font-weight: 500; }

        /* --- NAVIGATION PILLS --- */
        .nav-pill-custom {
            border-radius: 12px; padding: 0.5rem 1rem; font-weight: 600; font-size: 0.9rem;
            color: var(--text-secondary); transition: all 0.2s ease; border: 1px solid transparent;
            display: flex; align-items: center; gap: 0.5rem; text-decoration: none;
            white-space: nowrap; 
        }
        .nav-pill-custom:hover, .nav-pill-custom:focus { background-color: rgba(111, 78, 55, 0.08); color: var(--primary-coffee); }
        .nav-pill-custom.active { background-color: var(--primary-coffee); color: white; box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25); }

        /* --- DROPDOWN CUSTOM STYLES --- */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.5rem;
            z-index: 1051;
        }
        .dropdown-item {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-dark);
            transition: all 0.2s;
            border-radius: 8px;
        }
        .dropdown-item:hover {
            background-color: var(--surface-cream);
            color: var(--primary-coffee);
            transform: translateX(3px);
        }
        .dropdown-toggle::after {
            vertical-align: 0.15em;
            opacity: 0.5;
        }

        /* --- BUTTONS --- */
        .btn-action {
            border-radius: 12px; padding: 0.5rem 1.2rem; font-weight: 600; font-size: 0.9rem;
            transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem;
            white-space: nowrap; 
        }
        
        .btn-primary-coffee, .btn-pos {
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: white; border: none; box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }
        .btn-primary-coffee:hover, .btn-pos:hover { 
            transform: translateY(-2px); color: white; box-shadow: 0 6px 20px rgba(111, 78, 55, 0.4); 
        }

        .btn-create {
            background: white; border: 1px solid var(--border-light); color: var(--text-dark);
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .btn-create:hover {
            border-color: var(--success-green); color: var(--success-green); background: #F1F8E9;
        }

        /* --- CARDS & TABLES --- */
        .card-custom, .kpi-card {
            border: none; border-radius: 20px; background: white;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden;
        }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
        .table-card-header {
            background: transparent; border-bottom: 1px solid var(--border-light);
            padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;
        }
        .table > :not(caption) > * > * {
            padding: 1rem 1rem; background-color: transparent; border-bottom-color: var(--border-light);
        }
        .table thead th {
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--text-secondary); font-weight: 600; background-color: #FAFAFA;
        }

        /* --- FORMS & ALERTS --- */
        .form-control, .form-select {
            border-radius: 12px; border: 1px solid var(--border-light);
            padding: 0.8rem 1rem; font-size: 0.95rem; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
        }
        /* Custom SweetAlert Style Override (Optional) */
        div:where(.swal2-container) div:where(.swal2-popup) {
            border-radius: 24px !important;
            font-family: 'Inter', sans-serif !important;
        }
        
        @yield('styles')
    </style>
</head>
<body>

<div class="container">
    {{-- Added sticky-top here: Navbar stays visible while scrolling, but keeps the "floating" design --}}
    <nav class="navbar navbar-expand-xl navbar-premium sticky-top">
        <div class="container-fluid px-1">
            <a class="navbar-brand p-0" href="{{ route('home') }}">
                <div class="navbar-brand-wrapper">
                    <div class="logo-container">
                        <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px; width: auto;">
                    </div>
                    <div class="brand-info">
                        <div class="brand-title">KAPE NI ASERO</div>
                        <div class="brand-subtitle">
                            @auth {{ Auth::user()->role === 'admin' ? 'Admin' : 'Staff' }} @endauth Portal
                        </div>
                    </div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="fas fa-bars fs-5"></i>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-xl-0" id="mainNav">
                @auth
                <div class="ms-xl-auto d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center gap-2 gap-xl-3">
                    
                    <div class="d-flex flex-column flex-xl-row gap-1 bg-light p-1 rounded-4 border border-light">
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('home') }}" class="nav-pill-custom {{ request()->routeIs('home') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i> Dashboard
                            </a>
                            <a href="{{ route('users.index') }}" class="nav-pill-custom {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Staff
                            </a>
                        @endif

                        <a href="{{ route('orders.index') }}" class="nav-pill-custom {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> History
                        </a>

                        @if(Auth::user()->role == 'admin')
                            <div class="dropdown">
                                <a href="#" class="nav-pill-custom dropdown-toggle {{ request()->routeIs('ingredients.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') || request()->routeIs('products.create') ? 'active' : '' }}" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-boxes"></i> Inventory
                                </a>
                                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2">
                                    <li><a class="dropdown-item mb-1 px-3 py-2" href="{{ route('ingredients.index') }}"><i class="fas fa-cubes me-2 text-secondary"></i> Stock List</a></li>
                                    <li><a class="dropdown-item mb-1 px-3 py-2" href="{{ route('categories.index') }}"><i class="fas fa-tags me-2 text-secondary"></i> Categories</a></li>
                                    <li><a class="dropdown-item mb-1 px-3 py-2" href="{{ route('suppliers.index') }}"><i class="fas fa-truck me-2 text-secondary"></i> Suppliers</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item px-3 py-2" href="{{ route('products.create') }}"><i class="fas fa-plus-circle me-2 text-success"></i> New Product</a></li>
                                </ul>
                            </div>

                            <div class="dropdown">
                                <a href="#" class="nav-pill-custom dropdown-toggle {{ request()->routeIs('reports.*') || request()->routeIs('shifts.*') || request()->routeIs('activity-logs.*') ? 'active' : '' }}" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-clipboard-list"></i> Logs
                                </a>
                                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2 dropdown-menu-xl-end">
                                    <li><a class="dropdown-item mb-1 px-3 py-2" href="{{ route('reports.index') }}"><i class="fas fa-chart-line me-2 text-primary"></i> Sales Reports</a></li>
                                    <li><a class="dropdown-item mb-1 px-3 py-2" href="{{ route('shifts.index') }}"><i class="fas fa-clock me-2 text-secondary"></i> Shift History</a></li>
                                    <li><a class="dropdown-item px-3 py-2" href="{{ route('activity-logs.index') }}"><i class="fas fa-shield-alt me-2 text-secondary"></i> Audit Logs</a></li>
                                </ul>
                            </div>

                            <a href="{{ route('settings.index') }}" class="nav-pill-custom {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="fas fa-cogs"></i> Settings
                            </a>
                        @endif
                    </div>
                    
                    <div class="vr d-none d-xl-block mx-1 opacity-25"></div>
                    
                    <a href="{{ route('products.index') }}" class="btn btn-action btn-pos">
                        <i class="fas fa-cash-register"></i> <span>Open POS</span>
                    </a>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('logout') }}" class="btn btn-action btn-light text-danger border-0 justify-content-center" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Sign Out">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        <a href="{{ route('logout.action') }}" class="btn btn-action btn-light text-danger border-0 justify-content-center" title="End Shift & Sign Out">
                            <i class="fas fa-power-off"></i>
                        </a>
                    @endif
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SCRIPT: Handle Flash Messages as SweetAlert Modals --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Success Alert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#6F4E37', // Matches your Brand Coffee Color
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true
            });
        @endif

        // Error Alert
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#D32F2F',
                confirmButtonText: 'OK'
            });
        @endif
        
    });
</script>

@yield('scripts')
</body>
</html>