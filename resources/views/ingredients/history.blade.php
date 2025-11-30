@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Stock Card: {{ $ingredient->name }}</h4>
            <p class="text-secondary small m-0">Inventory movement history</p>
        </div>
        <a href="{{ route('ingredients.index') }}" class="btn btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-3 text-center h-100">
                <span class="text-muted small">Current Stock</span>
                <h2 class="fw-bold {{ $ingredient->stock <= $ingredient->reorder_level ? 'text-danger' : 'text-success' }}">
                    {{ $ingredient->stock }} <small class="fs-6 text-muted">{{ $ingredient->unit }}</small>
                </h2>
            </div>
        </div>
    </div>

    <div class="card card-custom shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Type</th>
                        <th>User</th>
                        <th class="text-end">Change</th>
                        <th class="text-end">Balance</th>
                        <th class="text-end">Unit Cost</th>
                        <th class="ps-4">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 small">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($log->type == 'restock')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Restock</span>
                                @elseif($log->type == 'used_in_order')
                                    <span class="badge bg-light text-secondary border">Sales</span>
                                @elseif($log->type == 'wastage')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Wastage</span>
                                @elseif($log->type == 'manual_adjustment')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Adjustment</span>
                                @elseif($log->type == 'void_return')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Void Return</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $log->type)) }}</span>
                                @endif
                            </td>
                            <td class="small">{{ $log->user->name ?? 'System' }}</td>
                            <td class="text-end fw-bold {{ $log->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $log->quantity_change > 0 ? '+' : '' }}{{ number_format($log->quantity_change, 2) }}
                            </td>
                            <td class="text-end fw-bold">{{ number_format($log->running_balance, 2) }}</td>
                            <td class="text-end">
                                @if($log->unit_cost)
                                    ₱{{ number_format($log->unit_cost, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="ps-4 text-muted small fst-italic">{{ $log->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No movement history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection