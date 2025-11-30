@extends('layouts.app')

@section('content')
<div class="container">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
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
                                    <form action="{{ route('ingredients.update', $ing->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf 
                                        @method('PUT')
                                        {{-- Hidden fields to satisfy strict validation if needed, or remove if Controller validates only stock --}}
                                        <input type="hidden" name="name" value="{{ $ing->name }}">
                                        
                                        <div class="input-group input-group-sm" style="width: 140px;">
                                            <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control text-center fw-bold text-dark" step="0.01" style="border-right:none;">
                                            <span class="input-group-text bg-white border-start-0 text-muted small">{{ $ing->unit }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-light text-warning border shadow-sm" title="Manual Correction (Audit)">
                                            <i class="fas fa-pen"></i>
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
                                        {{-- HISTORY BUTTON --}}
                                        <a href="{{ route('ingredients.history', $ing->id) }}" class="btn btn-sm btn-info text-white border shadow-sm" title="View Stock Card/History">
                                            <i class="fas fa-list-alt"></i>
                                        </a>

                                        {{-- RESTOCK BUTTON (Triggers Modal) --}}
                                        <button type="button" class="btn btn-sm btn-success border shadow-sm" data-bs-toggle="modal" data-bs-target="#restockModal{{ $ing->id }}" title="Add Stock (Restock)">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        {{-- DELETE BUTTON --}}
                                        <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light text-danger border shadow-sm" onclick="return confirm('Remove {{ $ing->name }} from inventory? This might affect product recipes.')" title="Delete Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- RESTOCK MODAL (Placed inside loop for unique IDs) --}}
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
                                                    <small class="text-muted">Used to calculate Cost of Goods Sold later.</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Reference / Remarks</label>
                                                    <input type="text" name="remarks" class="form-control" placeholder="e.g. Invoice #1234, Delivery from Supplier X">
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
                            {{-- END MODAL --}}

                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="opacity-50 mb-2">
                                        <i class="fas fa-box-open fa-3x"></i>
                                    </div>
                                    <p class="mb-0 fw-medium">Inventory is empty.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- No Results Message for Search --}}
                    <div id="noResults" class="text-center py-5 text-muted d-none">
                        <i class="fas fa-search fa-2x mb-3 opacity-25"></i>
                        <p class="mb-0">No ingredients found matching your search.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Client-side Search for Inventory
    document.getElementById('inventorySearch').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.inventory-row');
        let hasVisible = false;

        rows.forEach(row => {
            const name = row.querySelector('.item-name').textContent.toLowerCase();
            if (name.includes(searchText)) {
                row.style.display = '';
                hasVisible = true;
            } else {
                row.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noResults');
        const table = document.getElementById('inventoryTable');
        
        if (!hasVisible && rows.length > 0) {
            noResults.classList.remove('d-none');
            table.classList.add('d-none');
        } else {
            noResults.classList.add('d-none');
            table.classList.remove('d-none');
        }
    });
</script>
@endsection