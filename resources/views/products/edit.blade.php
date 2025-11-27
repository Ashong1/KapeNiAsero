<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="row">
        <!-- PRODUCT DETAILS -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h4>✏️ Edit Product</h4>
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" name="price" value="{{ $product->price }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option {{ $product->category == 'Hot Coffee' ? 'selected' : '' }}>Hot Coffee</option>
                            <option {{ $product->category == 'Iced Coffee' ? 'selected' : '' }}>Iced Coffee</option>
                        </select>
                    </div>
                    <button class="btn btn-success">Save Changes</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>

        <!-- RECIPE MANAGER -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>📜 Recipe</h4>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-primary">Manage Stock</a>
                </div>
                <p class="text-muted small">Ingredients deducted per sale.</p>

                <ul class="list-group mb-3">
                    @foreach($product->ingredients as $ing)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $ing->name }} ({{ $ing->pivot->quantity_needed }} {{ $ing->unit }})</span>
                        <form action="{{ route('products.removeIngredient', [$product->id, $ing->id]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0">&times;</button>
                        </form>
                    </li>
                    @endforeach
                </ul>

                <hr>
                <h6>Add Ingredient:</h6>
                <form action="{{ route('products.addIngredient', $product->id) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-6">
                        <select name="ingredient_id" class="form-select" required>
                            <option value="">Select...</option>
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" name="quantity" class="form-control" placeholder="Qty" step="0.1" required>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-primary w-100">+</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>