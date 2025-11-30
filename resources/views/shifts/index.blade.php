@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fas fa-history me-2 text-primary-coffee"></i>Shift History</h4>
            <p class="text-secondary small m-0">Review cash register logs and variance reports.</p>
        </div>
    </div>

    <div class="card card-custom">
        <div class="table-card-header bg-white border-bottom py-3">
            <h6 class="m-0 fw-bold text-secondary text-uppercase small ls-1">Completed Shifts</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Staff</th>
                        <th>Duration</th>
                        <th class="text-end">Starting Cash</th>
                        <th class="text-end">Cash Sales</th>
                        <th class="text-end">Expected</th>
                        <th class="text-end">Actual</th>
                        <th class="text-end pe-4">Variance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                        @php
                            // Calculate Variance
                            $variance = $shift->end_cash - $shift->expected_cash;
                            $varianceColor = $variance < 0 ? 'text-danger fw-bold' : ($variance > 0 ? 'text-success fw-bold' : 'text-muted');
                            $varianceIcon = $variance < 0 ? 'fa-caret-down' : ($variance > 0 ? 'fa-caret-up' : 'fa-check');
                        @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $shift->started_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $shift->started_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border" style="width:32px;height:32px;font-size:0.8rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="fw-medium">{{ $shift->user->name }}</span>
                            </div>
                        </td>
                        <td class="small text-secondary">
                            {{ $shift->started_at->diffInHours($shift->ended_at) }} hrs 
                            {{ $shift->started_at->diffInMinutes($shift->ended_at) % 60 }} mins
                        </td>
                        <td class="text-end text-secondary">₱{{ number_format($shift->start_cash, 2) }}</td>
                        <td class="text-end text-success">+₱{{ number_format($shift->expected_cash - $shift->start_cash, 2) }}</td>
                        <td class="text-end fw-bold">₱{{ number_format($shift->expected_cash, 2) }}</td>
                        <td class="text-end fw-bold border-start bg-light">₱{{ number_format($shift->end_cash, 2) }}</td>
                        <td class="text-end pe-4 {{ $varianceColor }}">
                            <i class="fas {{ $varianceIcon }} me-1"></i>
                            ₱{{ number_format(abs($variance), 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-cash-register fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">No shift history available.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shifts->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection