@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Register New Supplier</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Company Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Benguet Beans Co." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="e.g. Mr. Dela Cruz">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. orders@company.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 0917-xxx-xxxx">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Supplier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection