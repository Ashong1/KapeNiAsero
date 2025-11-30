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
                    <div>
                        <h4 class="fw-bold text-dark m-0">Edit User</h4>
                        <div class="small text-secondary">Updating profile for {{ $user->name }}</div>
                    </div>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Account Information</label>
                        
                        <div class="mb-3">
                            <label class="form-label small">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name', $user->name) }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email', $user->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" 
                                {{-- DISABLE ROLE SELECT IF EDITING SELF --}}
                                {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Staff (Employee)</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            
                            {{-- IF DISABLED, WE NEED A HIDDEN INPUT SO THE VALUE IS SUBMITTED --}}
                            @if(Auth::id() === $user->id)
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <div class="form-text text-warning"><i class="fas fa-exclamation-triangle me-1"></i> You cannot change your own role.</div>
                            @endif
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <label class="form-label text-secondary small fw-bold text-uppercase mb-3"><i class="fas fa-lock me-1"></i> Change Password</label>
                        <p class="small text-muted mb-3">Leave these fields blank if you don't want to change the password.</p>
                        
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary-coffee py-2 fw-bold shadow-sm">
                            <i class="fas fa-check-circle me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection