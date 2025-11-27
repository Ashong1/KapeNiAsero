<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white p-4">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Menu Item</h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Product Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g. Hazelnut Latte" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief description (optional)"></textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Price (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="" disabled selected>Select...</option>
                                    <option value="Hot Coffee">Hot Coffee</option>
                                    <option value="Iced Coffee">Iced Coffee</option>
                                    <option value="Pastry">Pastry</option>
                                    <option value="Non-Coffee">Non-Coffee</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Note:</strong> You can add the recipe (ingredients) on the next screen after saving.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Save & Set Recipe &rarr;</button>
                            <a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none text-muted">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>