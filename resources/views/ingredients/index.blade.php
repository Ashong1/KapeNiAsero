<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse | Kape Ni Asero</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-coffee: #6F4E37;
            --primary-coffee-hover: #5A3D2B;
            --dark-coffee: #3E2723;
            --accent-gold: #C5A065;
            --surface-cream: #FFF8E7;
            --surface-glass: rgba(255, 255, 255, 0.92);
            --text-dark: #2C1810;
            --text-secondary: #6D5E57;
            --success-green: #558B2F;
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

        /* NAVBAR */
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

        .navbar-brand:hover .logo-container { transform: scale(1.05) rotate(-3deg); }
        .logo-container {
            background: white; padding: 6px; border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.3s ease;
        }
        .brand-title { font-weight: 800; font-size: 1.1rem; color: var(--text-dark); letter-spacing: -0.02em; }
        .brand-subtitle { font-size: 0.75rem; color: var(--text-secondary); font-weight: 500; }

        .nav-pill-custom {
            border-radius: 12px; padding: 0.5rem 1rem; font-weight: 600; font-size: 0.9rem;
            color: var(--text-secondary); transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; text-decoration: none;
        }
        .nav-pill-custom:hover { background-color: rgba(111, 78, 55, 0.08); color: var(--primary-coffee); transform: translateY(-1px); }
        .nav-pill-custom.active { background-color: var(--primary-coffee); color: white; box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25); }

        .navbar-toggler { border: none; padding: 0.5rem; border-radius: 10px; color: var(--primary-coffee); background-color: rgba(111, 78, 55, 0.05); }

        /* CARDS & FORMS */
        .card-custom {
            border: none; border-radius: 20px; background: white;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden;
        }
        .card-header-custom {
            background: transparent; border-bottom: 1px solid var(--border-light); padding: 1.5rem;
        }

        .form-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .form-control, .form-select {
            border-radius: 12px; border: 1px solid var(--border-light); padding: 0.7rem 1rem; font-size: 0.95rem; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
        }
        .input-group-text { border-radius: 12px; border: 1px solid var(--border-light); background: #FAFAFA; color: var(--text-secondary); }

        .btn-primary-custom {
            background: var(--primary-coffee); color: white; border-radius: 12px; padding: 0.7rem; font-weight: 600; border: none;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); transition: all 0.2s; width: 100%;
        }
        .btn-primary-custom:hover { background: var(--primary-coffee-hover); transform: translateY(-2px); color: white; }

        /* TABLE */
        .table > :not(caption) > * > * { padding: 1rem; background: transparent; border-bottom-color: var(--border-light); }
        .table thead th { font-size: 0.75rem; text-transform: uppercase; color: var(--text-secondary); font-weight: 600; }
        
        .badge-stock { padding: 0.5em 0.8em; border-radius: 8px; font-weight: 600; font-size: 0.7rem; }
    </style>
</head>
<body>

<div class="container">
    
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid px-1">
            <a class="navbar-brand p-0" href="{{ route('home') }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-container">
                        <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px;">
                    </div>
                    <div>
                        <div class="brand-title">KAPE NI ASERO</div>
                        <div class="brand-subtitle">Warehouse Inventory</div>
                    </div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navContent">
                <div class="ms-auto d-flex flex-column flex-lg-row gap-2">
                    <div class="d-flex flex-column flex-lg-row gap-1 bg-light p-1 rounded-4 border border-light">
                        <a href="{{ route('home') }}" class="nav-pill-custom">
                            <i class="fas fa-chart-pie fa-sm"></i> Dashboard
                        </a>
                        <a href="{{ route('ingredients.index') }}" class="nav-pill-custom active">
                            <i class="fas fa-boxes fa-sm"></i> Stock
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-pill-custom">
                            <i class="fas fa-tags fa-sm"></i> Categories
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="nav-pill-custom">
                            <i class="fas fa-truck fa-sm"></i> Suppliers
                        </a>
                    </div>
                    <a href="{{ route('products.index') }}" class="nav-pill-custom text-success fw-bold bg-white border">
                        <i class="fas fa-cash-register"></i> POS
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle fs-4 me-3"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-custom h-100">
                <div class="card-header-custom">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-plus-circle text-primary-coffee me-2"></i>Add Material</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ingredients.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Material Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Arabica Beans" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">-- Select Source --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-end mt-1">
                                <a href="{{ route('suppliers.create') }}" class="text-decoration-none small fw-bold text-warning">+ New Supplier</a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" placeholder="0" step="0.01" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <option value="g">Grams</option>
                                    <option value="ml">mL</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="shots">Shots</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-danger">Alert Threshold</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger-subtle text-danger border-0"><i class="fas fa-bell"></i></span>
                                <input type="number" name="reorder_level" class="form-control" value="100" required>
                            </div>
                        </div>

                        <button class="btn btn-primary-custom">Save to Inventory</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-dark">Current Stock Levels</h6>
                    <span class="badge bg-light text-secondary border">{{ $ingredients->count() }} Items</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Item Name</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingredients as $ing)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">{{ $ing->name }}</span>
                                    <small class="text-muted" style="font-size:0.75rem;">Min: {{ $ing->reorder_level }} {{ $ing->unit }}</small>
                                </td>
                                <td>
                                    @if($ing->supplier)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-muted" style="width:24px;height:24px;font-size:0.6rem;">
                                                <i class="fas fa-truck"></i>
                                            </div>
                                            <span class="small text-secondary fw-medium">{{ $ing->supplier->name }}</span>
                                        </div>
                                    @else
                                        <span class="small text-muted fst-italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('ingredients.update', $ing->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 130px;">
                                            <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control text-center fw-bold" step="0.01" style="border-right:none;">
                                            <span class="input-group-text bg-white border-start-0 text-muted small">{{ $ing->unit }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-light text-success border-0" title="Update"><i class="fas fa-save"></i></button>
                                    </form>
                                </td>
                                <td>
                                    @if($ing->stock <= $ing->reorder_level)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle badge-stock">LOW</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle badge-stock">OK</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger border-0" onclick="return confirm('Remove {{ $ing->name }}?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-3 opacity-25"></i>
                                    <p class="mb-0">Inventory is empty.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>