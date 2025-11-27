<!DOCTYPE html>
<html lang="en">
<head>
    <title>Warehouse Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    
    <!-- UPDATED HEADER WITH NAVIGATION BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark"><i class="fas fa-boxes me-2"></i>Ingredient Inventory</h2>
            <p class="text-muted">Manage raw material stocks and alert levels.</p>
        </div>
        <div>
            <!-- Dashboard Button -->
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            
            <!-- Warehouse Button (Disabled/Active State) -->
            <a href="#" class="btn btn-dark btn-sm me-2 disabled">
                <i class="fas fa-boxes"></i> Warehouse
            </a>

            <!-- POS Button -->
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-cash-register"></i> Go to POS
            </a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="row">
        <!-- ADD FORM -->
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="mb-3 fw-bold text-primary">Add Material</h5>
                <form action="{{ route('ingredients.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="fw-bold small">Material Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Milk" required>
                    </div>
                    <div class="mb-2">
                        <label class="fw-bold small">Unit</label>
                        <select name="unit" class="form-select">
                            <option value="ml">Milliliters (ml)</option>
                            <option value="g">Grams (g)</option>
                            <option value="pcs">Pieces (pcs)</option>
                            <option value="shots">Shots</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="fw-bold small">Current Stock</label>
                        <input type="number" name="stock" class="form-control" placeholder="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small text-danger">Low Stock Alert Level</label>
                        <input type="number" name="reorder_level" class="form-control" value="100" required>
                    </div>
                    <button class="btn btn-primary w-100">Save to Inventory</button>
                </form>
            </div>
        </div>

        <!-- LIST TABLE -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 fw-bold">Current Stock Levels</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th>Current Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ingredients as $ing)
                            <tr>
                                <td class="fw-bold">{{ $ing->name }}</td>
                                <td>
                                    <!-- Inline Edit Form -->
                                    <form action="{{ route('ingredients.update', $ing->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf @method('PUT')
                                        <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control form-control-sm" style="width: 100px;" step="0.01">
                                        <span class="text-muted small">{{ $ing->unit }}</span>
                                        <button class="btn btn-sm btn-outline-success py-0" title="Save Update">✓</button>
                                    </form>
                                </td>
                                <td>
                                    @if($ing->stock <= $ing->reorder_level)
                                        <span class="badge bg-danger">LOW STOCK</span>
                                    @else
                                        <span class="badge bg-success">Good</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-link text-danger text-decoration-none" onclick="return confirm('Delete this material from inventory?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($ingredients->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No raw materials added yet.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>