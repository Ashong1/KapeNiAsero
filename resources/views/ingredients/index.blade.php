<!DOCTYPE html>
<html lang="en">
<head>
    <title>Warehouse Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>📦 Ingredient Inventory</h2>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to POS</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="row">
        <!-- ADD FORM -->
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Add Material</h5>
                <form action="{{ route('ingredients.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Milk" required>
                    </div>
                    <div class="mb-2">
                        <label>Unit</label>
                        <select name="unit" class="form-select">
                            <option value="ml">Milliliters (ml)</option>
                            <option value="g">Grams (g)</option>
                            <option value="pcs">Pieces (pcs)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label>Alert Level</label>
                        <input type="number" name="reorder_level" class="form-control" value="100" required>
                    </div>
                    <button class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>

        <!-- LIST TABLE -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ingredient</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredients as $ing)
                        <tr>
                            <td>{{ $ing->name }}</td>
                            <td>
                                <!-- Inline Edit Form -->
                                <form action="{{ route('ingredients.update', $ing->id) }}" method="POST" class="d-flex">
                                    @csrf @method('PUT')
                                    <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control form-control-sm w-50 me-2">
                                    <button class="btn btn-sm btn-outline-success">Update</button>
                                </form>
                            </td>
                            <td>
                                @if($ing->stock <= $ing->reorder_level)
                                    <span class="badge bg-danger">Low</span>
                                @else
                                    <span class="badge bg-success">OK</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>