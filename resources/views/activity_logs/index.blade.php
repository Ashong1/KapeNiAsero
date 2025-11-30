@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fas fa-shield-alt me-2 text-primary-coffee"></i>Audit Logs</h4>
            <p class="text-secondary small m-0">Track system activities and sensitive actions.</p>
        </div>
    </div>

    <div class="card card-custom">
        <div class="table-card-header bg-white border-bottom py-3">
            <h6 class="m-0 fw-bold text-secondary text-uppercase small ls-1">System Events</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 20%;">Date & Time</th>
                        <th style="width: 15%;">User</th>
                        <th style="width: 20%;">Action</th>
                        <th class="pe-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $log->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $log->created_at->format('h:i:s A') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border" style="width:32px;height:32px;font-size:0.8rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="fw-medium text-dark">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            {{-- Color coding based on action keywords --}}
                            @php
                                $badgeColor = 'bg-secondary-subtle text-secondary';
                                if(Str::contains(strtolower($log->action), 'void')) $badgeColor = 'bg-danger-subtle text-danger border border-danger-subtle';
                                elseif(Str::contains(strtolower($log->action), 'create')) $badgeColor = 'bg-success-subtle text-success border border-success-subtle';
                                elseif(Str::contains(strtolower($log->action), 'update')) $badgeColor = 'bg-info-subtle text-info border border-info-subtle';
                                elseif(Str::contains(strtolower($log->action), 'login')) $badgeColor = 'bg-primary-subtle text-primary border border-primary-subtle';
                            @endphp
                            <span class="badge {{ $badgeColor }} rounded-pill px-3 py-2 fw-bold">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="text-secondary pe-4 text-wrap" style="max-width: 400px;">
                            {{ $log->details }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-history fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">No activity logs found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection