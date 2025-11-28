@extends('layouts.app')

@section('content')
<div class="container">
    
    {{-- Global Alerts are handled by layout, but if you have specific ones here --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle fs-4 me-3"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card card-custom h-100">
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
                            <input type="text" name="name" class="form-control" placeholder="e.g. Arabica Beans" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">-- Select Source --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-end mt-1">
                                <a href="{{ route('suppliers.create') }}" class="text-decoration-none small fw-bold text-warning">
                                    <i class="fas fa-plus me-1"></i> New Supplier
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
                                    <option value="g">Grams</option>
                                    <option value="ml">mL</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="shots">Shots</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-danger">Alert Threshold</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger-subtle text-danger border-0">
                                    <i class="fas fa-bell"></i>
                                </span>
                                <input type="number" name="reorder_level" class="form-control" value="100" required>
                            </div>
                        </div>

                        <button class="btn btn-primary-coffee w-100 fw-bold py-2">Save to Inventory</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="card-header-custom p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-dark">Current Stock Levels</h6>
                    <span class="badge bg-light text-secondary border rounded-pill px-3">{{ $ingredients->count() }} Items</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Item Name</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingredients as $ing)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">{{ $ing->name }}</span>
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
                                    <form action="{{ route('ingredients.update', $ing->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf 
                                        @method('PUT')
                                        {{-- Hidden fields to preserve other values if controller requires all --}}
                                        <input type="hidden" name="name" value="{{ $ing->name }}">
                                        <input type="hidden" name="reorder_level" value="{{ $ing->reorder_level }}">
                                        <input type="hidden" name="unit" value="{{ $ing->unit }}">
                                        @if($ing->supplier_id)
                                            <input type="hidden" name="supplier_id" value="{{ $ing->supplier_id }}">
                                        @endif

                                        <div class="input-group input-group-sm" style="width: 130px;">
                                            <input type="number" name="stock" value="{{ $ing->stock }}" class="form-control text-center fw-bold" step="0.01" style="border-right:none;">
                                            <span class="input-group-text bg-white border-start-0 text-muted small">{{ $ing->unit }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-light text-success border shadow-sm" title="Save New Quantity">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($ing->stock <= $ing->reorder_level)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle badge-stock rounded-pill px-3">LOW</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle badge-stock rounded-pill px-3">OK</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger border shadow-sm" onclick="return confirm('Remove {{ $ing->name }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection