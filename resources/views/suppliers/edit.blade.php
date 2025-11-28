<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier | Kape Ni Asero</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-coffee: #6F4E37; --text-dark: #2C1810; --border-light: #EFEBE9; }
        body { font-family: 'Inter', sans-serif; background: #F5F5F7; color: var(--text-dark); padding: 3rem 1rem; }
        .navbar-brand { font-weight: 800; font-size: 1.2rem; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--text-dark); margin-bottom: 2rem; justify-content: center;}
        .card-custom { border: none; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        .form-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #8D6E63; margin-bottom: 0.4rem; letter-spacing: 0.05em; }
        .form-control { border-radius: 10px; padding: 0.7rem 1rem; border: 1px solid var(--border-light); font-size: 0.95rem; }
        .form-control:focus { border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
        .btn-warning { background: #FFB74D; border: none; padding: 0.8rem 2rem; border-radius: 12px; font-weight: 600; box-shadow: 0 4px 15px rgba(255, 183, 77, 0.3); color: #4E342E; }
        .btn-warning:hover { background: #FFA726; transform: translateY(-1px); }
    </style>
</head>
<body>

<div class="container" style="max-width: 700px;">
    <a href="{{ route('suppliers.index') }}" class="navbar-brand">
        <img src="{{ asset('ka.png') }}" style="height: 32px;"> KAPE NI ASERO
    </a>

    <div class="card card-custom">
        <div class="card-body p-5">
            <h4 class="fw-bold mb-4">Edit Supplier Details</h4>
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="name" value="{{ $supplier->name }}" class="form-control fw-bold" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ $supplier->contact_person }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ $supplier->phone }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ $supplier->email }}" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('suppliers.index') }}" class="text-secondary text-decoration-none fw-bold small">Discard Changes</a>
                    <button type="submit" class="btn btn-warning">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>