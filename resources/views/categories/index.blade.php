@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div> {{-- Spacer to push button to right --}}
        <a href="{{ route('categories.create') }}" class="btn btn-primary-coffee btn-action shadow-sm">
            <i class="fas fa-plus-circle"></i> New Category
        </a>
    </div>

    {{-- The Global Layout already handles success/error alerts, so we don't need to repeat them here --}}

    <div class="card card-custom">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="fw-bold m-0 text-dark">
                <i class="fas fa-tags me-2 text-primary-coffee"></i> Category Manager
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Category Name</th>
                        <th class="text-secondary text-uppercase small fw-bold">Slug</th>
                        <th class="text-secondary text-uppercase small fw-bold">Items</th>
                        <th class="text-end pe-4 text-secondary text-uppercase small fw-bold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            <span class="d-inline-block bg-light rounded p-2 me-2 text-primary-coffee shadow-sm">
                                <i class="fas fa-tag"></i>
                            </span>
                            {{ $cat->name }}
                        </td>
                        <td class="text-secondary small font-monospace">/{{ $cat->slug }}</td>
                        <td>
                            <span class="badge bg-light text-secondary border rounded-pill px-3">
                                {{ $cat->products->count() }} items
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-light text-primary me-1 border" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete category? This might affect products attached to it.')">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger border" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <div class="opacity-50 mb-2">
                                <i class="fas fa-folder-open fa-3x"></i>
                            </div>
                            <p class="mb-0 fw-medium">No categories found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection