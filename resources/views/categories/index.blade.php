@extends('layouts.app')

@section('styles')
<style>
    /* --- ACTION BUTTON SYNC --- */
    .btn-action-icon {
        width: 32px; 
        height: 32px; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center;
        border-radius: 8px; 
        transition: all 0.2s ease; 
        border: 1px solid transparent;
        text-decoration: none;
    }

    /* EDIT: Coffee on Cream */
    .btn-action-edit {
        background-color: var(--surface-cream); 
        color: var(--primary-coffee);
        border-color: rgba(111, 78, 55, 0.1);
    }
    .btn-action-edit:hover {
        background-color: var(--primary-coffee); 
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(111, 78, 55, 0.2);
    }

    /* DELETE: System Red on Light Red */
    .btn-action-delete {
        background-color: #FEF2F2; /* Light Red */
        color: var(--danger-red);
        border-color: rgba(211, 47, 47, 0.1);
    }
    .btn-action-delete:hover {
        background-color: var(--danger-red); 
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(211, 47, 47, 0.2);
    }
</style>
@endsection

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div> {{-- Spacer to push button to right --}}
        <a href="{{ route('categories.create') }}" class="btn btn-primary-coffee btn-action shadow-sm">
            <i class="fas fa-plus-circle"></i> New Category
        </a>
    </div>

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
                            {{-- UPDATED: Edit Button --}}
                            <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-action-icon btn-action-edit shadow-sm me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            {{-- UPDATED: Delete Button --}}
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline delete-form" data-record-title="{{ $cat->name }}">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-sm btn-action-icon btn-action-delete shadow-sm" title="Delete">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const title = this.getAttribute('data-record-title') || 'this category';
                
                Swal.fire({
                    title: 'Delete Category?',
                    text: `Are you sure you want to delete "${title}"? This might affect products attached to it.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#EFEBE9',
                    cancelButtonText: '<span style="color: #6F4E37; font-weight: 600;">Cancel</span>',
                    confirmButtonText: 'Yes, delete it',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>