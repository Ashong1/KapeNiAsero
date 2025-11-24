<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Kape Ni Asero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4">Edit Coffee Item</h2>
        
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $product->description }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Price (₱)</label>
                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Stock Quantity</label>
                    <!-- The value="{{ $product->stock }}" part is what pre-fills the box -->
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Category</label>
                    <select name="category" class="form-select">
                        <option {{ $product->category == 'Hot Coffee' ? 'selected' : '' }}>Hot Coffee</option>
                        <option {{ $product->category == 'Iced Coffee' ? 'selected' : '' }}>Iced Coffee</option>
                        <option {{ $product->category == 'Pastry' ? 'selected' : '' }}>Pastry</option>
                        <option {{ $product->category == 'Non-Coffee' ? 'selected' : '' }}>Non-Coffee</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-warning">Update Product</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>