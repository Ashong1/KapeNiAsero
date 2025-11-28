@extends('layouts.app')

@section('content')
<div class="container">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold" style="color: var(--primary-coffee);">
                <i class="fas fa-tags me-2"></i>Category Management
            </h2>
            <p class="text-secondary mb-0">Organize your menu items into groups.</p>
        </div>
        
        <div class="d-flex gap-2">
            <!-- EXIT BUTTON -->
            <a href="{{ route('home') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
            
            <!-- ADD BUTTON -->
            <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> New Category
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="background-color: var(--color-olive); color: white;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- MAIN CARD -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color: var(--surface-cream); color: var(--primary-coffee);">
                        <tr>
                            <th class="ps-4 py-3">Category Name</th>
                            <th class="py-3">Slug (URL)</th>
                            <th class="py-3">Items Count</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <span class="p-2 rounded bg-light me-2"><i class="fas fa-tag text-muted"></i></span>
                                {{ $category->name }}
                            </td>
                            <td class="text-secondary"><code>/{{ $category->slug }}</code></td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $category->products->count() }} Items
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category? Products might lose their grouping.');">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger border-0" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($categories->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-tags fa-3x mb-3 opacity-25"></i>
                    <p class="h5">No categories found.</p>
                    <p class="small">Start by creating a category like "Hot Coffee".</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection