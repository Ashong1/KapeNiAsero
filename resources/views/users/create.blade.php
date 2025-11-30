@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom p-4 shadow-lg border-0">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('users.index') }}" class="btn btn-light rounded-circle me-3 text-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold text-dark m-0">Create New User</h4>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Account Information</label>
                        <div class="mb-3">
                            <label class="form-label small">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}" placeholder="e.g. Juan Cruz">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}" placeholder="e.g. juan@kapeniasero.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="employee" selected>Staff (Employee)</option>
                                <option value="admin">Administrator (Full Access)</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Security</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary-coffee py-2 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection