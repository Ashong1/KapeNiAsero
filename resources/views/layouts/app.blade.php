<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kape Ni Asero') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* --- PREMIUM NAVBAR --- */
        .navbar-premium {
            background-color: var(--surface-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 24px -1px rgba(62, 39, 35, 0.06);
            padding: 0.8rem 1rem;
            margin-bottom: 2rem;
            border-radius: 24px;
            margin-top: 1rem;
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
        }
        .nav-pill-custom:hover { background-color: rgba(111, 78, 55, 0.08); color: var(--primary-coffee); }
        .nav-pill-custom.active { background-color: var(--primary-coffee); color: white; box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25); }

        /* --- BUTTONS --- */
        .btn-action {
            border-radius: 12px; padding: 0.5rem 1.2rem; font-weight: 600; font-size: 0.9rem;
            transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem;
        }
        
        /* POS Button (Gradient) */
        .btn-primary-coffee, .btn-pos {
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: white; border: none; box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }
        .btn-primary-coffee:hover, .btn-pos:hover { 
            transform: translateY(-2px); color: white; box-shadow: 0 6px 20px rgba(111, 78, 55, 0.4); 
        }

        /* Create Button (White with Green Hover) */
        .btn-create {
            background: white; border: 1px solid var(--border-light); color: var(--text-dark);
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .btn-create:hover {
            border-color: var(--success-green); color: var(--success-green); background: #F1F8E9;
        }

        /* Warning/Edit Button */
        .btn-warning-custom {
            background: #FFB300; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 600; color: #3E2723;
        }
        .btn-warning-custom:hover { background: #FFCA28; color: #3E2723; }

        /* --- CARDS --- */
        .card-custom {
            border: none; border-radius: 20px; background: white;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden;
        }
        
        /* KPI Cards (Dashboard) */
        .kpi-card {
            position: relative; overflow: hidden; border: none; border-radius: 20px;
            background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
        }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
        .kpi-bg-icon {
            position: absolute; right: -10px; bottom: -10px; font-size: 5rem;
            opacity: 0.05; transform: rotate(-15deg); z-index: 0;
        }
        .card-content-wrapper { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; }

        /* --- TABLES --- */
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

        /* --- FORMS --- */
        .form-label {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            color: var(--text-secondary); margin-bottom: 0.5rem; letter-spacing: 0.05em;
        }
        .form-control, .form-select {
            border-radius: 12px; border: 1px solid var(--border-light);
            padding: 0.8rem 1rem; font-size: 0.95rem; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
        }

        /* Upload Box */
        .upload-box {
            border: 2px dashed var(--border-light); border-radius: 16px; height: 250px;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            background: #FAFAFA; transition: all 0.2s; cursor: pointer; position: relative; overflow: hidden;
        }
        .upload-box:hover { border-color: var(--accent-gold); background: #FFF8E1; }
        .upload-box i { font-size: 3rem; color: #D7CCC8; margin-bottom: 1rem; }

        /* --- ALERTS --- */
        .alert-floating {
            border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 1.5rem;
        }
        
        /* Audit Log Badges */
        .badge-soft-void { background-color: #FEF2F2; color: #DC2626; }
        .badge-soft-stock { background-color: #FFFBEB; color: #D97706; }
        .badge-soft-system { background-color: #F3F4F6; color: #4B5563; }

        /* View Specific Styles Injection */
        @yield('styles')
    </style>
</head>
<body>

<div class="container">
    
    <nav class="navbar navbar-expand-lg navbar-premium">
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

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNav">
                @auth
                <div class="ms-auto d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 gap-lg-3">
                    
                    <div class="d-flex flex-column flex-lg-row gap-1 bg-light p-1 rounded-4 border border-light">
                        <a href="{{ route('home') }}" class="nav-pill-custom {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                        <a href="{{ route('orders.index') }}" class="nav-pill-custom {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> History
                        </a>
                        
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('ingredients.index') }}" class="nav-pill-custom {{ request()->routeIs('ingredients.*') ? 'active' : '' }}">
                                <i class="fas fa-boxes"></i> Stock
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-pill-custom {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i> Category
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="nav-pill-custom {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                                <i class="fas fa-truck"></i> Suppliers
                            </a>
                            <a href="{{ route('products.create') }}" class="nav-pill-custom {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle"></i> New Item
                            </a>
                        @endif
                    </div>

                    <div class="vr d-none d-lg-block mx-1 opacity-25"></div>

                    <a href="{{ route('products.index') }}" class="btn btn-action btn-pos">
                        <i class="fas fa-cash-register"></i> <span class="d-none d-md-inline">Open POS</span>
                    </a>

                    <a href="{{ route('logout') }}" 
                       class="btn btn-action btn-light text-danger border-0 justify-content-center" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       title="Sign Out">
                        <i class="fas fa-power-off"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-floating d-flex align-items-center animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-floating d-flex align-items-center animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>