@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fas fa-cash-register me-2 text-primary"></i>Shift History</h4>
            <p class="text-secondary small m-0">Monitor register audits and cash discrepancies.</p>
        </div>
    </div>

    <div class="card card-custom shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Cashier</th>
                        <th>Started</th>
                        <th>Ended</th>
                        <th class="text-end">Start Cash</th>
                        <th class="text-end">Cash Sales</th>
                        <th class="text-end">Expected</th>
                        <th class="text-end">Actual Count</th>
                        <th class="text-end pe-4">Variance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                        @php
                            $variance = $shift->end_cash ? ($shift->end_cash - $shift->expected_cash) : 0;
                            // Calculate Sales if not stored directly: Expected - Start
                            $sales = $shift->expected_cash ? ($shift->expected_cash - $shift->start_cash) : 0;
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $shift->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary" style="width:24px;height:24px;font-size:0.7rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    {{ $shift->user->name }}
                                </div>
                            </td>
                            <td class="small">{{ $shift->started_at->format('M d, h:i A') }}</td>
                            <td class="small">
                                @if($shift->ended_at)
                                    {{ $shift->ended_at->format('M d, h:i A') }}
                                @else
                                    <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                @endif
                            </td>
                            <td class="text-end font-monospace">₱{{ number_format($shift->start_cash, 2) }}</td>
                            <td class="text-end font-monospace text-primary">
                                @if($shift->ended_at)
                                    +₱{{ number_format($sales, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end font-monospace">
                                @if($shift->ended_at)
                                    ₱{{ number_format($shift->expected_cash, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end font-monospace fw-bold">
                                @if($shift->ended_at)
                                    ₱{{ number_format($shift->end_cash, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($shift->ended_at)
                                    @if($variance < 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">
                                            -₱{{ number_format(abs($variance), 2) }}
                                        </span>
                                    @elseif($variance > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2">
                                            +₱{{ number_format($variance, 2) }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-secondary border px-2">
                                            Match
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted small">--</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                No shift records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $shifts->links() }}
        </div>
    </div>
</div>
@endsection