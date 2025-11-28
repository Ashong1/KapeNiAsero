<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Category | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-coffee: #6F4E37; --text-dark: #2C1810; --border-light: #EFEBE9; }
        body { font-family: 'Inter', sans-serif; background: #F5F5F7; color: var(--text-dark); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card-custom { border: none; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); overflow: hidden; width: 100%; max-width: 500px; background: white; }
        .form-control { padding: 0.8rem; border-radius: 12px; border: 1px solid var(--border-light); }
        .form-control:focus { border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
        .btn-primary { background: var(--primary-coffee); border: none; padding: 0.8rem; border-radius: 12px; font-weight: 600; width: 100%; }
        .btn-primary:hover { background: #5A3D2B; }
    </style>
</head>
<body>
    <div class="card card-custom p-4">
        <div class="text-center mb-4">
            <div class="bg-light rounded-circle d-inline-flex p-3 mb-3 text-primary"><i class="fas fa-tags fa-2x" style="color: var(--primary-coffee);"></i></div>
            <h4 class="fw-bold">Create New Category</h4>
            <p class="text-muted small">Organize your menu items</p>
        </div>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label text-uppercase small fw-bold text-secondary">Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Hot Coffee" required autofocus>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('categories.index') }}" class="btn btn-light w-50 text-secondary fw-bold" style="padding:0.8rem;">Cancel</a>
                <button type="submit" class="btn btn-primary w-50">Save</button>
            </div>
        </form>
    </div>
</body>
</html>