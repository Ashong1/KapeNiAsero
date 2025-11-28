<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier | Kape Ni Asero</title>
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
        
        .btn-primary { background: var(--primary-coffee); border: none; padding: 0.8rem 2rem; border-radius: 12px; font-weight: 600; box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); }
        .btn-primary:hover { background: #5D4037; transform: translateY(-1px); }
    </style>
</head>
<body>

<div class="container" style="max-width: 700px;">
    <a href="{{ route('suppliers.index') }}" class="navbar-brand">
        <img src="{{ asset('ka.png') }}" style="height: 32px;"> KAPE NI ASERO
    </a>

    <div class="card card-custom">
        <div class="card-body p-5">
            <h4 class="fw-bold mb-4">Register New Supplier</h4>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                
                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">Company Details</h6>
                <div class="mb-4">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="name" class="form-control fw-bold" placeholder="e.g. Beans & Grains Co." required>
                </div>

                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2 mt-4">Contact Info</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Representative Name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+63 900 000 0000">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="contact@supplier.com" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('suppliers.index') }}" class="text-secondary text-decoration-none fw-bold small">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>