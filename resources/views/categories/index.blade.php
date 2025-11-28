<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Shared CSS from Home */
        :root {
            --primary-coffee: #6F4E37; --primary-coffee-hover: #5A3D2B; --surface-glass: rgba(255, 255, 255, 0.92);
            --text-dark: #2C1810; --text-secondary: #6D5E57; --border-light: #EFEBE9;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F5F5F5 0%, #E0E0E0 100%);
            background-image: radial-gradient(at 0% 0%, rgba(111, 78, 55, 0.05) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(197, 160, 101, 0.1) 0px, transparent 50%);
            color: var(--text-dark); min-height: 100vh; padding-bottom: 3rem;
        }
        .navbar-premium {
            background-color: var(--surface-glass); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 4px 24px -1px rgba(62, 39, 35, 0.06);
            padding: 0.8rem 1rem; margin-bottom: 2rem; border-radius: 24px; margin-top: 1rem;
        }
        .logo-container { background: white; padding: 6px; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .brand-title { font-weight: 800; font-size: 1.1rem; color: var(--text-dark); }
        .brand-subtitle { font-size: 0.75rem; color: var(--text-secondary); font-weight: 500; }
        .nav-pill-custom {
            border-radius: 12px; padding: 0.5rem 1rem; font-weight: 600; font-size: 0.9rem; color: var(--text-secondary);
            text-decoration: none; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;
        }
        .nav-pill-custom:hover { background: rgba(111, 78, 55, 0.08); color: var(--primary-coffee); }
        .nav-pill-custom.active { background: var(--primary-coffee); color: white; box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25); }
        .navbar-toggler { border: none; padding: 0.5rem; border-radius: 10px; color: var(--primary-coffee); background-color: rgba(111, 78, 55, 0.05); }
        
        .card-custom { border: none; border-radius: 20px; background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden; }
        .btn-create {
            background: var(--primary-coffee); color: white; border-radius: 12px; padding: 0.6rem 1.2rem; font-weight: 600; border: none;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-create:hover { background: var(--primary-coffee-hover); color: white; transform: translateY(-2px); }
        
        .table > :not(caption) > * > * { padding: 1.2rem 1rem; background: transparent; border-bottom-color: var(--border-light); }
        .table thead th { font-size: 0.75rem; text-transform: uppercase; color: var(--text-secondary); font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid px-1">
            <a class="navbar-brand p-0" href="{{ route('home') }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-container"><img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px;"></div>
                    <div><div class="brand-title">KAPE NI ASERO</div><div class="brand-subtitle">Category Manager</div></div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent"><i class="fas fa-bars"></i></button>
            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navContent">
                <div class="ms-auto d-flex flex-column flex-lg-row gap-2">
                    <div class="d-flex flex-column flex-lg-row gap-1 bg-light p-1 rounded-4 border border-light">
                        <a href="{{ route('home') }}" class="nav-pill-custom"><i class="fas fa-chart-pie fa-sm"></i> Dashboard</a>
                        <a href="{{ route('ingredients.index') }}" class="nav-pill-custom"><i class="fas fa-boxes fa-sm"></i> Stock</a>
                        <a href="{{ route('categories.index') }}" class="nav-pill-custom active"><i class="fas fa-tags fa-sm"></i> Categories</a>
                        <a href="{{ route('suppliers.index') }}" class="nav-pill-custom"><i class="fas fa-truck fa-sm"></i> Suppliers</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('categories.create') }}" class="btn-create">
            <i class="fas fa-plus-circle"></i> New Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
    @endif

    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Category Name</th>
                        <th>Slug</th>
                        <th>Items</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            <span class="d-inline-block bg-light rounded p-2 me-2 text-primary-coffee"><i class="fas fa-tag"></i></span>
                            {{ $cat->name }}
                        </td>
                        <td class="text-secondary small font-monospace">/{{ $cat->slug }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">{{ $cat->products->count() }} items</span></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-light text-primary me-1"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete category?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($categories->isEmpty())
            <div class="text-center py-5 text-muted"><p>No categories found.</p></div>
        @endif
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>