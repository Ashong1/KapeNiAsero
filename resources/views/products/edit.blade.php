<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Shared Styles from Create Page */
        :root {
            --primary-coffee: #6F4E37; --primary-coffee-hover: #5A3D2B; --surface-glass: rgba(255, 255, 255, 0.92);
            --text-dark: #2C1810; --text-secondary: #6D5E57; --border-light: #EFEBE9; --accent-gold: #C5A065;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F5F5F5 0%, #E0E0E0 100%);
            color: var(--text-dark); min-height: 100vh; padding-bottom: 3rem;
        }
        .navbar-premium {
            background-color: var(--surface-glass); backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 4px 24px -1px rgba(62, 39, 35, 0.06);
            padding: 0.8rem 1rem; margin-bottom: 2rem; border-radius: 24px; margin-top: 1rem;
        }
        .logo-container { background: white; padding: 6px; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        
        .card-custom { border: none; border-radius: 20px; background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 2rem; }
        .card-header-custom { background: transparent; border-bottom: 1px solid var(--border-light); padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;}
        
        .form-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.5rem; letter-spacing: 0.05em; }
        .form-control, .form-select { border-radius: 12px; border: 1px solid var(--border-light); padding: 0.8rem 1rem; font-size: 0.95rem; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-coffee) 0%, #3E2723 100%);
            color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); transition: transform 0.2s;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); color: white; }

        /* Recipe Item */
        .recipe-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem; border-bottom: 1px solid var(--border-light); transition: background 0.2s;
        }
        .recipe-item:last-child { border-bottom: none; }
        .recipe-item:hover { background: #FAFAFA; }
        
        .img-preview { width: 120px; height: 120px; border-radius: 16px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid px-1">
            <a class="navbar-brand p-0" href="{{ route('products.index') }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-container"><img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px;"></div>
                    <div><div class="fw-bold fs-5 text-dark">Edit Product</div><div class="small text-secondary">Updating details</div></div>
                </div>
            </a>
            <div class="ms-auto">
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold text-secondary">
                    <i class="fas fa-times me-1"></i> Close
                </a>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h6 class="m-0 fw-bold text-dark">Basic Information</h6>
                    <span class="badge bg-light text-secondary border">ID: {{ $product->id }}</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="d-flex gap-4 align-items-start">
                            <div class="d-flex flex-column gap-2 align-items-center">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="img-preview" alt="Product">
                                @else
                                    <div class="img-preview bg-light d-flex align-items-center justify-content-center text-muted"><i class="fas fa-image fa-2x"></i></div>
                                @endif
                                <label class="btn btn-sm btn-outline-secondary w-100" style="border-radius:10px;">
                                    Change <input type="file" name="image" class="d-none">
                                </label>
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" value="{{ $product->name }}" class="form-control fw-bold" required>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 fw-bold">₱</span>
                                            <input type="number" name="price" value="{{ $product->price }}" class="form-control border-start-0" step="0.01" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4 pt-3 border-top">
                            <button class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i> Update Details</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-header-custom bg-light bg-opacity-50">
                    <h6 class="m-0 fw-bold text-primary-coffee"><i class="fas fa-blender me-2"></i>Recipe Ingredients</h6>
                    <small class="text-muted">Inventory Deductions</small>
                </div>
                <div class="card-body p-0">
                    <div class="recipe-list">
                        @forelse($product->ingredients as $ingredient)
                            <div class="recipe-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-warning-subtle text-warning p-2 rounded-circle border border-warning-subtle">
                                        <i class="fas fa-cube fa-sm"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $ingredient->name }}</div>
                                        <small class="text-muted">Use: <strong>{{ $ingredient->pivot->quantity_needed }} {{ $ingredient->unit }}</strong> per order</small>
                                    </div>
                                </div>
                                <form action="{{ route('products.removeIngredient', [$product->id, $ingredient->id]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('Remove ingredient?')"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">No ingredients added yet.</div>
                        @endforelse
                    </div>
                    
                    <div class="p-3 bg-light border-top">
                        <form action="{{ route('products.addIngredient', $product->id) }}" method="POST" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label mb-1">Add Ingredient</label>
                                <select name="ingredient_id" class="form-select form-select-sm">
                                    @foreach($ingredients as $ing)
                                        <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Quantity</label>
                                <input type="number" name="quantity" class="form-control form-control-sm" step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-dark w-100 h-100" style="border-radius:10px;"><i class="fas fa-plus me-1"></i> Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>