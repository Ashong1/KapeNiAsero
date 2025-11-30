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
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark m-0">Supplier Database</h4>
            <p class="text-secondary small m-0">Manage your material sources</p>
        </div>
        
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-secondary"></i></span>
                <input type="text" id="supplierSearch" class="form-control border-start-0 ps-0" placeholder="Find Supplier...">
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary-coffee shadow-sm fw-bold">
                <i class="fas fa-plus-circle me-1"></i> Add New
            </a>
        </div>
    </div>

    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Company</th>
                        <th class="text-secondary text-uppercase small fw-bold">Contact Person</th>
                        <th class="text-secondary text-uppercase small fw-bold">Contact Info</th>
                        <th class="text-end pe-4 text-secondary text-uppercase small fw-bold">Actions</th>
                    </tr>
                </thead>
                <tbody id="supplierTableBody">
                    @forelse($suppliers as $supp)
                    <tr class="supplier-row">
                        <td class="ps-4 fw-bold text-dark">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-primary-coffee" style="width: 40px; height: 40px;">
                                    <i class="fas fa-building"></i>
                                </div>
                                <span class="supplier-name">{{ $supp->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary fw-medium contact-name">{{ $supp->contact_person }}</td>
                        <td>
                            <div class="d-flex flex-column small">
                                <a href="mailto:{{ $supp->email }}" class="text-decoration-none text-secondary">
                                    <i class="far fa-envelope me-2 w-20"></i>{{ $supp->email }}
                                </a>
                                <span class="text-secondary mt-1">
                                    <i class="fas fa-phone me-2 w-20"></i>{{ $supp->phone }}
                                </span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            {{-- UPDATED: Edit Button --}}
                            <a href="{{ route('suppliers.edit', $supp->id) }}" class="btn btn-sm btn-action-icon btn-action-edit shadow-sm me-1" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            
                            {{-- UPDATED: Delete Button --}}
                            <form action="{{ route('suppliers.destroy', $supp->id) }}" method="POST" class="d-inline delete-form" data-name="{{ $supp->name }}">
                                @csrf @method('DELETE')
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
                                <i class="fas fa-truck-loading fa-3x"></i>
                            </div>
                            <p class="mb-0 fw-medium">No suppliers registered.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div id="noSuppliersMsg" class="text-center py-5 text-muted d-none">
                <i class="fas fa-search fa-2x mb-3 opacity-25"></i>
                <p>No matching suppliers found.</p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('supplierSearch').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        const rows = document.querySelectorAll('.supplier-row');
        let hasVisible = false;

        rows.forEach(row => {
            const name = row.querySelector('.supplier-name').innerText.toLowerCase();
            const contact = row.querySelector('.contact-name').innerText.toLowerCase();
            
            if(name.includes(val) || contact.includes(val)) {
                row.style.display = '';
                hasVisible = true;
            } else {
                row.style.display = 'none';
            }
        });

        const msg = document.getElementById('noSuppliersMsg');
        msg.classList.toggle('d-none', hasVisible);
    });

    // SweetAlert Logic
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = this.getAttribute('data-name');
                Swal.fire({
                    title: 'Delete Supplier?',
                    text: `Are you sure you want to delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, delete'
                }).then((r) => { if(r.isConfirmed) form.submit(); });
            });
        });
    });
</script>
@endsection
@endsection