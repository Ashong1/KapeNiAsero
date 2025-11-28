@extends('layouts.app')

@section('styles')
<style>
    /* Preserving Old Design Styles */
    :root { --primary-coffee: #6F4E37; --text-dark: #2C1810; --border-light: #EFEBE9; }
    
    .card-custom { 
        border: none; 
        border-radius: 24px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.05); 
        background: white; 
        overflow: hidden; 
    }
    
    .form-label { 
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: #8D6E63; 
        margin-bottom: 0.4rem; 
        letter-spacing: 0.05em; 
    }
    
    .form-control { 
        border-radius: 10px; 
        padding: 0.7rem 1rem; 
        border: 1px solid var(--border-light); 
        font-size: 0.95rem; 
    }
    
    .form-control:focus { 
        border-color: var(--primary-coffee); 
        box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); 
    }
    
    .btn-primary-custom { 
        background: var(--primary-coffee); 
        border: none; 
        padding: 0.8rem 2rem; 
        border-radius: 12px; 
        font-weight: 600; 
        box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); 
        color: white;
    }
    
    .btn-primary-custom:hover { 
        background: #5D4037; 
        transform: translateY(-1px); 
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container" style="max-width: 700px;">
    
    <div class="card card-custom">
        <div class="card-body p-5">
            <h4 class="fw-bold mb-4">Register New Supplier</h4>
            
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                
                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">Company Details</h6>
                <div class="mb-4">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="name" class="form-control fw-bold" placeholder="e.g. Beans & Grains Co." value="{{ old('name') }}" required autofocus>
                </div>

                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2 mt-4">Contact Info</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Representative Name" value="{{ old('contact_person') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+63 900 000 0000" value="{{ old('phone') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="contact@supplier.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('suppliers.index') }}" class="text-secondary text-decoration-none fw-bold small">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection