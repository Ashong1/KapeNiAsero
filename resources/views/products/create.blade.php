<!DOCTYPE html>
<html>
<head>
    <title>Add New Product - Kape Ni Asero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4">Add New Coffee Item</h2>
        
        <form action="{{ route('products.store') }}" method="POST">
            @csrf  <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Spanish Latte" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" placeholder="Ingredients, taste..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label>Current Stock / Quantity</label>
                    <input type="number" name="stock" class="form-control" placeholder="e.g. 50" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Category</label>
                    <select name="category" class="form-select">
                        <option>Hot Coffee</option>
                        <option>Iced Coffee</option>
                        <option>Pastry</option>
                        <option>Non-Coffee</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Save Product</button>
        </form>
    </div>
</div>

</body>
</html>