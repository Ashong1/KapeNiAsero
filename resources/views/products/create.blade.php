<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Kape Ni Asero</title>
    
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

        /* Image Preview Placeholder */
        .image-placeholder {
            width: 100%;
            height: 200px;
            background-color: var(--surface-cream);
            border: 2px dashed var(--input-border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-gold);
            flex-direction: column;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="page-header">
    <a href="{{ route('home') }}" class="navbar-brand">
        <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 40px;" class="me-3"> 
        <span>Kape Ni Asero <span class="fw-normal text-muted ms-2 fs-6">| Add Product</span></span>
    </a>
    <div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 d-flex align-items-center fw-bold bg-white">
            <i class="fas fa-arrow-left me-2"></i> Back to POS
        </a>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="fas fa-plus-circle me-2"></i>New Product Details</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <!-- Image Upload Section -->
                            <div class="col-md-4">
                                <label class="form-label">Product Image</label>
                                <div class="image-placeholder mb-2">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-2 opacity-50"></i>
                                    <small class="text-muted">Click to upload</small>
                                </div>
                                <input type="file" name="image" class="form-control form-control-sm">
                            </div>

                            <!-- Product Info Fields -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Caramel Macchiato" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description of the item..."></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="" disabled selected>Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price (₱)</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">₱</span>
                                            <input type="number" name="price" class="form-control" step="0.01" placeholder="0.00" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 bg-light text-secondary mt-3 mb-0 rounded-3 small">
                            <i class="fas fa-info-circle me-1 text-primary"></i> 
                            <strong>Next Step:</strong> After saving, you will be redirected to add the <u>recipe ingredients</u> for this product.
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Save & Continue
                            </button>
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