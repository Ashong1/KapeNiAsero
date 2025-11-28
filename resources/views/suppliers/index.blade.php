@extends('layouts.app')

@section('content')
<div class="container">
    
    <!-- Header with Navigation Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold" style="color: var(--color-cream); text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                <i class="fas fa-truck me-2"></i>Supplier Management
            </h2>
            <p class="text-light opacity-75 mb-0">Manage your vendor relationships and contacts.</p>
        </div>
        
        <div class="d-flex gap-2">
            <!-- NEW: Dashboard Button -->
            <a href="{{ route('home') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
            
            <!-- Add Supplier Button -->
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary" style="background-color: var(--color-sienna); border-color: var(--color-sienna); color: var(--color-mocha);">
                <i class="fas fa-plus-circle me-1"></i> Add Supplier
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="background-color: var(--color-olive); color: white;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="card shadow-lg border-0" style="background-color: rgba(255, 255, 255, 0.95);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color: var(--color-mocha); color: var(--color-cream);">
                        <tr>
                            <th class="ps-4 py-3">Company Name</th>
                            <th class="py-3">Contact Person</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Phone</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $supplier->name }}</td>
                            <td class="text-secondary">{{ $supplier->contact_person ?? '-' }}</td>
                            <td>
                                <a href="mailto:{{ $supplier->email }}" class="text-decoration-none" style="color: var(--color-sienna);">
                                    <i class="far fa-envelope me-1"></i> {{ $supplier->email }}
                                </a>
                            </td>
                            <td class="text-secondary">{{ $supplier->phone ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning me-1 shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier? This might affect ingredient records.');">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger shadow-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($suppliers->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-truck-loading fa-3x mb-3 opacity-25"></i>
                    <p class="h5">No suppliers found.</p>
                    <p class="small">Click "Add Supplier" to register your first vendor.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection