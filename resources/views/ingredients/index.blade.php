<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | Kape Ni Asero</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            /* Consistent Palette */
            --primary-coffee: #6F4E37;
            --dark-coffee: #3E2723;
            --accent-gold: #8B7355;
            --surface-cream: #FFF8E7;
            --surface-white: #FFFFFF;
            --text-dark: #2C1810;
            --text-light: #FFF8E7;
            --success-green: #689F38;
            --border-light: #F0E5D0;
            --input-border: #E8DCC8;
            --color-danger: #D84315;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        /* HEADER styling */
        .page-header {
            background-color: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-coffee) !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        /* CARD STYLING */
        .card {
            border: none;
            border-radius: 20px;
            background: var(--surface-white);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            transition: transform 0.2s;
        }
        
        .card-header {
            background-color: var(--surface-cream);
            border-bottom: 1px solid var(--border-light);
            color: var(--primary-coffee);
            font-weight: 700;
            padding: 1.25rem 1.5rem;
        }

        /* FORM STYLING */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border: 2px solid var(--input-border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
        }

        /* BUTTONS */
        .btn-primary {
            background-color: var(--primary-coffee);
            border-color: var(--primary-coffee);
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2);
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #5A3D2B;
            border-color: #5A3D2B;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-color: var(--input-border);
            color: var(--text-dark);
            border-radius: 10px;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--surface-cream);
            border-color: var(--accent-gold);
            color: var(--primary-coffee);
        }

        /* TABLE STYLING */
        .table thead th {
            background-color: var(--surface-cream);
            color: var(--primary-coffee);
            font-weight: 600;
            border-bottom: 2px solid var(--border-light);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .badge-stock {
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>

<div class="container mt-4 pb-5">
    
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-0" style="background: #D1E7DD; color: #0F5132;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0" style="background: #F8D7DA; color: #842029;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="page-header">
        <div class="d-flex align-items-center">
            <div class="bg-white p-2 rounded-circle me-3 shadow-sm border border-light">
                <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 40px;"> 
            </div>
            <div>
                <h4 class="fw-bold m-0 text-dark">Warehouse Inventory</h4>
                <p class="text-muted mb-0 small">Manage raw materials and stock levels</p>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 d-flex align-items-center fw-bold bg-white shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm px-3 py-2 d-flex align-items-center fw-bold shadow-sm">
                <i class="fas fa-cash-register me-2"></i> POS
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- ADD FORM -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add Material</h5>
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
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-truck text-muted"></i></span>
                                <select name="supplier_id" class="form-select border-start-0 ps-0">
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text text-end"><a href="{{ route('suppliers.create') }}" class="text-decoration-none" style="color: var(--accent-gold);">+ New Supplier</a></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <option value="g">Grams (g)</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="shots">Shots</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Initial Stock</label>
                                <input type="number" name="stock" class="form-control" placeholder="0" step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-danger">Low Stock Alert Level</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-danger"><i class="fas fa-bell"></i></span>
                                <input type="number" name="reorder_level" class="form-control border-start-0" value="100" required>
                            </div>
                            <div class="form-text">Alert when stock falls below this amount.</div>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i> Save to Inventory
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- LIST TABLE -->
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Current Stock Levels</h5>
                    <span class="badge bg-white text-dark border shadow-sm">{{ $ingredients->count() }} Items</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Ingredient</th>
                                <th>Supplier</th>
                                <th>Current Stock</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingredients as $ing)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">{{ $ing->name }}</span>
                                    <small class="text-muted">Alert at: {{ $ing->reorder_level }} {{ $ing->unit }}</small>
                                </td>
                                <td>
                                    @if($ing->supplier)
                                        <span class="badge bg-light text-dark border"><i class="fas fa-truck fa-xs me-1 text-muted"></i> {{ $ing->supplier->name }}</span>
                                    @else
                                        <span class="text-muted small fst-italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Inline Edit Form -->
                                    <form action="{{ route('ingredients.update', $ing->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 140px;">
                                            <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control" step="0.01">
                                            <span class="input-group-text bg-light">{{ $ing->unit }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-success border-0 p-1" title="Save Update">
                                            <i class="fas fa-check-circle fa-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($ing->stock <= $ing->reorder_level)
                                        <span class="badge bg-danger badge-stock shadow-sm">LOW STOCK</span>
                                    @else
                                        <span class="badge bg-success badge-stock shadow-sm" style="background-color: var(--success-green) !important;">GOOD</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger border-0 rounded-circle p-2" title="Delete Item" onclick="return confirm('Delete this material? Any recipes using it will be affected.')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-boxes fa-3x mb-3 opacity-25" style="color: var(--primary-coffee);"></i><br>
                                    No ingredients found. Add your first item!
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