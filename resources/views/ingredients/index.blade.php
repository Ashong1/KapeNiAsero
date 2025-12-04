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

    /* 1. EDIT & CORRECTION: Coffee on Cream */
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

    /* 2. HISTORY: Gold on Light Yellow */
    .btn-action-history {
        background-color: #FFF8E1; /* Light Amber */
        color: var(--accent-gold);
        border-color: rgba(197, 160, 101, 0.2);
    }
    .btn-action-history:hover {
        background-color: var(--accent-gold); 
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(197, 160, 101, 0.3);
    }

    /* 3. RESTOCK: System Green on Light Green */
    .btn-action-restock {
        background-color: #F0FDF4; /* Light Emerald */
        color: var(--success-green);
        border-color: rgba(52, 199, 89, 0.2);
    }
    .btn-action-restock:hover {
        background-color: var(--success-green); 
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(52, 199, 89, 0.3);
    }

    /* 4. DELETE: System Red on Light Red */
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
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- ADD MATERIAL CARD --}}
        <div class="col-lg-4">
            <div class="card card-custom h-100 position-sticky" style="top: 100px;">
                <div class="card-header-custom p-4 border-bottom bg-white">
                    <h6 class="m-0 fw-bold text-dark">
                        <i class="fas fa-plus-circle text-primary-coffee me-2"></i>Add Material
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ingredients.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Material Name</label>
                            <input type="text" name="name" class="form-control fw-bold" placeholder="e.g. Arabica Beans" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <div class="input-group">
                                <select name="supplier_id" class="form-select">
                                    <option value="">-- No Specific Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <a href="{{ route('suppliers.create') }}" class="btn btn-light border text-secondary" title="New Supplier">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" placeholder="0" step="0.01" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <option value="g">Grams (g)</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="shots">Shots</option>
                                    <option value="kg">Kilograms (kg)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-danger">Low Stock Alert Level</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger-subtle text-danger border-0">
                                    <i class="fas fa-bell"></i>
                                </span>
                                <input type="number" name="reorder_level" class="form-control" value="100" required>
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">Dashboard alert triggers when stock falls below this.</small>
                        </div>

                        <button class="btn btn-primary-coffee w-100 fw-bold py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i> Save to Inventory
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- INVENTORY LIST --}}
        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="card-header-custom p-4 border-bottom bg-white">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <h6 class="m-0 fw-bold text-dark">Stock Levels</h6>
                            <span class="badge bg-light text-secondary border rounded-pill px-3">{{ $ingredients->count() }} Items</span>
                        </div>
                        
                        {{-- Search Filter --}}
                        <div class="input-group" style="max-width: 250px;">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-secondary"></i></span>
                            <input type="text" id="inventorySearch" class="form-control border-start-0 bg-light" placeholder="Search item...">
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="inventoryTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Item Name</th>
                                <th>Supplier</th>
                                <th>Quantity (Quick Edit)</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingredients as $ing)
                            <tr class="inventory-row">
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block item-name">{{ $ing->name }}</span>
                                    <small class="text-muted" style="font-size:0.75rem;">Min: {{ $ing->reorder_level }} {{ $ing->unit }}</small>
                                </td>
                                <td>
                                    @if($ing->supplier)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-muted" style="width:24px;height:24px;font-size:0.6rem;">
                                                <i class="fas fa-truck"></i>
                                            </div>
                                            <span class="small text-secondary fw-medium">{{ $ing->supplier->name }}</span>
                                        </div>
                                    @else
                                        <span class="small text-muted fst-italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- MANUAL OVERRIDE (For Corrections) --}}
                                    {{-- API CONSUMPTION TARGET: 'api-quick-edit-form' --}}
                                    <form class="d-flex align-items-center gap-2 api-quick-edit-form" data-id="{{ $ing->id }}">
                                        
                                        <div class="input-group input-group-sm" style="width: 140px;">
                                            <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control text-center fw-bold text-dark" step="0.01" style="border-right:none;">
                                            <span class="input-group-text bg-white border-start-0 text-muted small">{{ $ing->unit }}</span>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-action-edit shadow-sm" title="Quick Update via API">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($ing->stock <= $ing->reorder_level)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">LOW</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">OK</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        
                                        {{-- 1. EDIT: Coffee Color --}}
                                        <button type="button" class="btn btn-sm btn-action-icon btn-action-edit shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $ing->id }}" title="Edit Details">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- 2. HISTORY: Gold Color --}}
                                        <a href="{{ route('ingredients.history', $ing->id) }}" class="btn btn-sm btn-action-icon btn-action-history shadow-sm" title="View Stock Card">
                                            <i class="fas fa-list-alt"></i>
                                        </a>

                                        {{-- 3. RESTOCK: Green Color --}}
                                        <button type="button" class="btn btn-sm btn-action-icon btn-action-restock shadow-sm" data-bs-toggle="modal" data-bs-target="#restockModal{{ $ing->id }}" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        {{-- 4. DELETE: Red Color (with SweetAlert) --}}
                                        <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST" class="d-inline delete-form" data-item-name="{{ $ing->name }}">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-action-icon btn-action-delete shadow-sm" title="Delete Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $ing->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-bottom-0">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-edit text-warning me-2"></i>Edit: {{ $ing->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('ingredients.update', $ing->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Material Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $ing->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Supplier</label>
                                                    <select name="supplier_id" class="form-select">
                                                        <option value="">-- No Specific Supplier --</option>
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}" {{ $ing->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                                {{ $supplier->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">Unit</label>
                                                        <select name="unit" class="form-select">
                                                            @foreach(['g', 'ml', 'pcs', 'shots', 'kg'] as $u)
                                                                <option value="{{ $u }}" {{ $ing->unit == $u ? 'selected' : '' }}>{{ $u }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">Alert Level</label>
                                                        <input type="number" name="reorder_level" class="form-control" value="{{ $ing->reorder_level }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning text-white fw-bold px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Restock Modal --}}
                            <div class="modal fade" id="restockModal{{ $ing->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-bottom-0">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-box-open text-success me-2"></i>Restock: {{ $ing->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('ingredients.restock', $ing->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-light border mb-3">
                                                    <small class="text-muted d-block">Current Stock</small>
                                                    <span class="fw-bold fs-5">{{ $ing->stock }} {{ $ing->unit }}</span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Quantity to Add ({{ $ing->unit }})</label>
                                                    <input type="number" step="0.01" name="quantity" class="form-control form-control-lg" placeholder="0.00" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Cost per Unit (₱) <span class="text-muted fw-normal small">(Optional)</span></label>
                                                    <input type="number" step="0.01" name="unit_cost" class="form-control" placeholder="0.00">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Reference / Remarks</label>
                                                    <input type="text" name="remarks" class="form-control" placeholder="e.g. Invoice #1234">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success fw-bold px-4">Confirm Restock</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted"><p class="mb-0 fw-medium">Inventory is empty.</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div id="noResults" class="text-center py-5 text-muted d-none"><p class="mb-0">No ingredients found.</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- AXIOS FOR API CONSUMPTION --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Search Script
    document.getElementById('inventorySearch').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.inventory-row');
        let hasVisible = false;
        rows.forEach(row => {
            const name = row.querySelector('.item-name').textContent.toLowerCase();
            if (name.includes(searchText)) { row.style.display = ''; hasVisible = true; } 
            else { row.style.display = 'none'; }
        });
        document.getElementById('noResults').classList.toggle('d-none', hasVisible);
        document.getElementById('inventoryTable').classList.toggle('d-none', !hasVisible && rows.length > 0);
    });

    // SweetAlert for Delete
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = this.getAttribute('data-item-name');
                Swal.fire({
                    title: 'Delete Item?',
                    text: `Remove "${name}" from inventory? This might affect recipes.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#EFEBE9',
                    cancelButtonText: '<span style="color: #6F4E37; font-weight: 600;">Cancel</span>',
                    confirmButtonText: 'Yes, remove it',
                    reverseButtons: true
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });

        // --- NEW: API CONSUMPTION FOR QUICK EDIT ---
        // This satisfies the requirement: "Your system must consume at least one (1) API"
        document.querySelectorAll('.api-quick-edit-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = this.getAttribute('data-id');
                const stockInput = this.querySelector('input[name="stock"]');
                const newStock = stockInput.value;

                // Show Loading
                const btn = this.querySelector('button');
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                // Call Internal API
                axios.put(`/api/inventory/ingredients/${id}`, {
                    stock: newStock
                })
                .then(response => {
                    if(response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'API Updated!',
                            text: 'Stock adjusted via Internal API.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // Optional: Reload to reflect changes if server logic requires it
                        // location.reload(); 
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Failed to update via API. Check console.', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalIcon;
                    btn.disabled = false;
                });
            });
        });
    });
</script>
@endsection