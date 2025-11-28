<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Kape Ni Asero</title>
    
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
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-coffee) !important;
            font-size: 1.25rem;
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
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--input-border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
        }

        .input-group-text {
            background-color: var(--surface-cream);
            border: 2px solid var(--input-border);
            border-right: none;
            color: var(--accent-gold);
        }
        
        .input-group .form-control {
            border-left: none;
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

        /* RECIPE SECTION */
        .recipe-list-item {
            background-color: #FAFAFA;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            padding: 1rem;
            transition: all 0.2s;
        }
        
        .recipe-list-item:hover {
            border-color: var(--accent-gold);
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        /* Responsive image preview */
        .img-preview-container {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            background-color: var(--surface-cream);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px dashed var(--input-border);
            color: var(--accent-gold);
        }
        
        .img-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="page-header">
    <a href="{{ route('home') }}" class="navbar-brand">
        <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 40px;" class="me-3"> 
        <span>Kape Ni Asero <span class="fw-normal text-muted ms-2 fs-6">| Edit Product</span></span>
    </a>
    <div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 d-flex align-items-center fw-bold bg-white">
            <i class="fas fa-arrow-left me-2"></i> Back to POS
        </a>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <!-- Main Edit Form -->
        <div class="col-lg-8">
            
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 mb-4 rounded-3" style="background-color: #D1E7DD; color: #0F5132;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-edit me-2"></i>Product Details</span>
                    <span class="badge bg-secondary rounded-pill">ID: {{ $product->id }}</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Image Upload Section -->
                            <div class="col-md-3 d-flex flex-column align-items-center">
                                <div class="img-preview-container mb-2">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="Product Image">
                                    @else
                                        <i class="fas fa-camera fa-2x"></i>
                                    @endif
                                </div>
                                <label class="btn btn-sm btn-outline-secondary w-100" style="font-size: 0.8rem;">
                                    Change Image <input type="file" name="image" class="d-none">
                                </label>
                            </div>

                            <!-- Product Info Fields -->
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price (₱)</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">₱</span>
                                            <input type="number" name="price" value="{{ $product->price }}" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recipe / Ingredients Section -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <span class="text-dark"><i class="fas fa-clipboard-list me-2" style="color: var(--accent-gold);"></i>Recipe Ingredients</span>
                    <small class="text-muted">Auto-deducted upon sale</small>
                </div>
                <div class="card-body p-4 bg-light">
                    
                    <!-- Existing Ingredients List -->
                    <div class="mb-4">
                        @if($product->ingredients->isEmpty())
                            <div class="text-center py-4 text-muted border rounded-3 bg-white border-dashed">
                                <i class="fas fa-blender fa-2x mb-2 opacity-25"></i>
                                <p class="mb-0 small">No ingredients linked yet.</p>
                            </div>
                        @else
                            @foreach($product->ingredients as $ingredient)
                                <div class="recipe-list-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-circle border me-3 text-secondary">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $ingredient->name }}</h6>
                                            <small class="text-muted">Deduct: <strong>{{ $ingredient->pivot->quantity_needed }} {{ $ingredient->unit }}</strong> per sale</small>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('products.removeIngredient', [$product->id, $ingredient->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" title="Remove Ingredient" onclick="return confirm('Remove this ingredient from recipe?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Add Ingredient Form -->
                    <form action="{{ route('products.addIngredient', $product->id) }}" method="POST" class="bg-white p-3 rounded-3 shadow-sm border">
                        @csrf
                        <h6 class="fw-bold mb-3 text-dark small text-uppercase">Add Ingredient to Recipe</h6>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1">Select Ingredient</label>
                                <select name="ingredient_id" class="form-select form-select-sm">
                                    @foreach($ingredients as $ing)
                                        <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Quantity Needed</label>
                                <input type="number" name="quantity" class="form-control form-control-sm" step="0.01" placeholder="0.00" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100 h-100" style="min-height: 38px;">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>