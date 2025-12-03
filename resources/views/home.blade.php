@extends('layouts.app')

@section('styles')
<style>
    /* Dashboard Specific Styles */
    .kpi-card {
        position: relative; overflow: hidden; border: none; border-radius: 20px;
        background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
        height: 100%;
        cursor: pointer;
    }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
    
    .kpi-bg-icon {
        position: absolute; right: -10px; bottom: -10px; font-size: 5rem;
        opacity: 0.05; transform: rotate(-15deg); z-index: 0;
    }

    .card-content-wrapper { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; }

    .table-card-header {
        background: transparent; border-bottom: 1px solid var(--border-light);
        padding: 1.2rem 1.5rem; display: flex; justify-content: space-between; align-items: center;
    }

    .progress-thin { height: 6px; border-radius: 3px; }
    .badge-soft-void { background-color: #FEF2F2; color: #DC2626; }
    
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; color: white; font-size: 1.1rem;
    }
    
    .chart-container { position: relative; height: 250px; width: 100%; }

    /* Nav Tabs Custom */
    .nav-tabs-custom { border-bottom: none; }
    .nav-tabs-custom .nav-link {
        border: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem;
        background: transparent; padding: 0.5rem 1rem; border-radius: 20px;
        cursor: pointer;
    }
    .nav-tabs-custom .nav-link.active {
        background-color: var(--surface-bg); color: var(--primary-coffee);
    }

    /* Buttons */
    .btn-icon { 
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; 
        border-radius: 10px; transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1); 
        border: none; font-size: 0.85rem; margin-left: 4px; text-decoration: none; 
        cursor: pointer;
    }
    .btn-icon-print { background-color: #F3F4F6; color: #4B5563; }
    .btn-icon-print:hover { background-color: var(--primary-coffee); color: white; transform: translateY(-2px); }

    .btn-brand-coffee { background: var(--primary-coffee); color: white; border: none; box-shadow: 0 4px 10px rgba(111, 78, 55, 0.2); transition: all 0.2s; cursor: pointer; }
    .btn-brand-coffee:hover { background: var(--primary-coffee-hover); color: white; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(111, 78, 55, 0.3); }

    .btn-brand-danger { background: #DC2626; color: white; border: none; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2); transition: all 0.2s; cursor: pointer; }
    .btn-brand-danger:hover { background: #B91C1C; color: white; transform: translateY(-2px); }

    .rank-badge { width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.75rem; font-weight: bold; margin-right: 10px; }
    .rank-1 { background-color: #FEF08A; color: #854D0E; border: 1px solid #FDE047; }
    .rank-2 { background-color: #E5E7EB; color: #374151; border: 1px solid #D1D5DB; }
    .rank-3 { background-color: #FDBA74; color: #9A3412; border: 1px solid #FB923C; }
    
    .card-link { text-decoration: none; color: inherit; display: block; height: 100%; }
    .card-link:hover { text-decoration: none; color: inherit; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- WELCOME SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Dashboard</h4>
            <p class="text-secondary small m-0">Overview & Statistics</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-sm">
                <i class="far fa-calendar-alt me-1"></i> {{ now()->format('l, F d, Y') }}
            </span>
        </div>
    </div>
    
    {{-- SHIFT STATUS ALERT --}}
    @if(Auth::user()->role !== 'admin')
        <div class="mb-4">
            @if(isset($activeShift))
                <div class="alert alert-success d-flex justify-content-between align-items-center shadow-sm border-0" role="alert">
                    <div>
                        <i class="fas fa-cash-register me-2"></i>
                        <strong>Register OPEN</strong> 
                        <span class="text-muted ms-2 small">Started: {{ $activeShift->started_at->format('M d, h:i A') }}</span>
                    </div>
                    <a href="{{ route('shifts.edit', $activeShift->id) }}" id="btn-end-shift" class="btn btn-sm btn-brand-danger fw-bold rounded-pill px-3">
                        End Shift
                    </a>
                </div>
            @else
                <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0" role="alert">
                    <div>
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Register CLOSED</strong> 
                        <span class="text-muted ms-2 small">You must open the register to record sales accurately.</span>
                    </div>
                    <a href="{{ route('shifts.create') }}" class="btn btn-sm btn-brand-coffee fw-bold rounded-pill px-3">
                        Open Register
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- KPI CARDS ROW --}}
    <div class="row mb-4 g-3">
        {{-- Sales KPI --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('reports.index') }}" class="card-link" title="View Sales Reports">
                <div class="card kpi-card p-4">
                    <i class="fas fa-coins kpi-bg-icon text-warning"></i>
                    <div class="card-content-wrapper">
                        <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Total Sales</span>
                        <h3 class="fw-bold mb-0 text-dark">₱{{ number_format($todaySales ?? 0, 0) }}</h3>
                        <div class="mt-3 d-flex justify-content-between align-items-end">
                            <div class="text-success small fw-medium">
                                <i class="fas fa-chart-line me-1"></i> AOV: ₱{{ number_format($averageOrderValue ?? 0, 0) }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Orders KPI --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('orders.index') }}" class="card-link" title="View Order History">
                <div class="card kpi-card p-4">
                    <i class="fas fa-receipt kpi-bg-icon text-primary"></i>
                    <div class="card-content-wrapper">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-uppercase text-secondary fw-bold small tracking-wide">Orders</span>
                            @if($parkedCount > 0)
                                <span class="badge bg-warning text-dark border border-warning-subtle">{{ $parkedCount }} Hold</span>
                            @endif
                        </div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $todayOrders ?? 0 }}</h3>
                    </div>
                </div>
            </a>
        </div>

        {{-- Top Server KPI --}}
        <div class="col-md-6 col-xl-3">
            <div class="card kpi-card p-4" style="border-bottom: 4px solid var(--success-green);">
                <i class="fas fa-trophy kpi-bg-icon text-success"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Top Star</span>
                    @if($topServer)
                        <h5 class="fw-bold mb-0 text-dark text-truncate">{{ $topServer->user->name }}</h5>
                        <div class="mt-auto pt-2 text-success small fw-bold">
                            ₱{{ number_format($topServer->total_sales, 0) }}
                        </div>
                    @else
                        <h5 class="fw-bold mb-0 text-muted">--</h5>
                    @endif
                </div>
            </div>
        </div>

        {{-- Critical Stock KPI --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('ingredients.index') }}" class="card-link" title="View Ingredients Inventory">
                <div class="card kpi-card p-4" style="border-bottom: 4px solid var(--danger-red);">
                    <i class="fas fa-exclamation-triangle kpi-bg-icon text-danger"></i>
                    <div class="card-content-wrapper">
                        <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Critical Stock</span>
                        <h3 class="fw-bold mb-0" style="color: var(--danger-red);">{{ $lowStockIngredients->count() }} Items</h3>
                        <div class="mt-auto pt-2 text-danger small fw-medium">
                            <i class="fas fa-arrow-right me-1"></i> Check Inventory
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- MAIN CONTENT ROW --}}
    <div class="row g-4 mb-4">
        
        {{-- ======================================================= --}}
        {{-- [UPDATED SECTION] Quick Product Lookup (Internal API + External Barcode API) --}}
        {{-- ======================================================= --}}
        <div class="col-12">
            <div class="card card-custom border-primary">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary-coffee m-0">
                            <i class="fas fa-search me-2"></i>Quick Product & Barcode Lookup
                        </h5>
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-2">
                                Internal API
                            </span>
                            <span class="badge bg-dark text-white border border-dark">
                                External Barcode API
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="apiSearchInput" class="form-control form-control-lg" placeholder="Start typing product name (e.g. 'Coffee')...">
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <small class="text-muted fst-italic">
                                <i class="fas fa-magic me-1"></i> 
                                Search fetches data internally, then generates barcodes via <strong>External API</strong>.
                            </small>
                        </div>
                    </div>

                    {{-- API Results Container --}}
                    <div id="apiResults" class="row mt-3 g-2" style="min-height: 50px;">
                        <div class="col-12 text-muted small fst-italic">Results will appear here...</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ======================================================= --}}

        {{-- Left Column: Charts --}}
        <div class="col-lg-8">
            <div class="card card-custom h-100 mb-4">
                <div class="table-card-header border-0 pb-0">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-chart-area me-2 text-primary-coffee"></i>Weekly Analytics</h5>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Top Products --}}
        <div class="col-lg-4">
            {{-- Best Sellers --}}
            <div class="card card-custom mb-4">
                <div class="table-card-header bg-warning-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-star me-2 text-warning"></i>Best Sellers</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($topProducts ?? [] as $index => $item)
                        <div class="list-group-item p-3 border-light d-flex align-items-center">
                            <span class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : 'rank-3') }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $item->product->name }}</div>
                                <div class="small text-muted">{{ $item->total_sold }} sold</div>
                            </div>
                            <div class="fw-bold text-success">
                                ₱{{ number_format($item->total_sold * $item->product->price, 0) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">No sales data yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Active Staff --}}
            <div class="card card-custom mb-4">
                <div class="table-card-header bg-success-subtle bg-opacity-10">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="m-0 fw-bold text-success"><i class="fas fa-users me-2"></i>On Duty</h5>
                        <span class="badge bg-success rounded-pill">{{ $activeStaff->count() }}</span>
                    </div>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('users.create') }}" class="btn btn-sm bg-white text-success border border-success-subtle shadow-sm rounded-pill fw-bold px-3" style="font-size: 0.75rem;">
                            <i class="fas fa-user-plus me-1"></i> Add
                        </a>
                    @endif
                </div>
                <div class="list-group list-group-flush">
                    @forelse($activeStaff as $shift)
                    <div class="list-group-item p-3 border-light d-flex align-items-center">
                        <div class="avatar-circle bg-primary-coffee me-3">
                            {{ substr($shift->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $shift->user->name }}</div>
                            <small class="text-muted">In: {{ $shift->started_at->format('h:i A') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted small">No active shifts.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ACTIVITY WITH TABS --}}
    <div class="card card-custom mb-4">
        <div class="table-card-header">
            <h5 class="m-0 fw-bold text-dark"><i class="fas fa-clock me-2 text-secondary"></i>Live Activity Feed</h5>
            <ul class="nav nav-tabs-custom" id="activityTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-pane" type="button">Transactions</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system-pane" type="button">System Logs</button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-0">
            <div class="tab-content" id="activityTabContent">
                {{-- SALES TAB --}}
                <div class="tab-pane fade show active" id="sales-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Barista</th>
                                    <th>Time</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr class="{{ $order->status === 'voided' ? 'text-muted fst-italic' : '' }}">
                                    <td class="ps-4 fw-bold font-monospace">#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="small text-secondary">{{ $order->created_at->format('h:i A') }}</td>
                                    <td class="fw-bold {{ $order->status !== 'voided' ? 'text-success' : '' }}">₱{{ number_format($order->total_price, 2) }}</td>
                                    <td>
                                        @if($order->status === 'voided')
                                            <span class="badge badge-soft-void rounded-pill">Voided</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Paid</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($order->status !== 'voided')
                                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn-icon btn-icon-print" title="Receipt">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">No transactions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- SYSTEM LOGS TAB --}}
                <div class="tab-pane fade" id="system-pane" role="tabpanel">
                    <div class="list-group list-group-flush">
                        @forelse($recentLogs as $log)
                        <div class="list-group-item p-3">
                            <div class="d-flex justify-content-between">
                                <div class="fw-bold text-dark">{{ $log->action }} <span class="fw-normal text-muted">by {{ $log->user->name }}</span></div>
                                <small class="text-secondary">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted d-block">{{ $log->details }}</small>
                        </div>
                        @empty
                        <div class="text-center py-5 text-muted">No system activity logs found.</div>
                        @endforelse
                    </div>
                    <div class="p-2 text-center border-top bg-light">
                        <a href="{{ route('activity-logs.index') }}" class="small text-decoration-none fw-bold">View All Logs &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Chart Logic (Weekly Analytics)
    const ctx = document.getElementById('salesChart');
    if(ctx) {
        const labels = @json($salesLabels);
        const data = @json($salesData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: data,
                    borderColor: '#6F4E37', // Coffee Color
                    backgroundColor: 'rgba(111, 78, 55, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6F4E37'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5] },
                        ticks: { callback: v => '₱' + v } 
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. API Consumer Logic (Internal Search + External Barcode)
    const searchInput = document.getElementById('apiSearchInput');
    const resultsDiv = document.getElementById('apiResults');
    const storagePath = "{{ asset('storage') }}"; // [ADDED] Base Path for Images

    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value;
            
            // Clear if empty or too short
            if(query.length < 2) {
                resultsDiv.innerHTML = '<div class="col-12 text-muted small fst-italic">Type at least 2 characters...</div>';
                return;
            }

            // Show loading indicator
            resultsDiv.innerHTML = '<div class="col-12 text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Searching...</div>';

            // A. CONSUME INTERNAL API
            fetch("{{ url('/api/pos/products') }}?search=" + query)
                .then(response => response.json())
                .then(res => {
                    if(res.status === 'success' && res.data.length > 0) {
                        let html = '';
                        res.data.forEach(product => {
                            // B. CONSUME EXTERNAL API (Barcode)
                            const barcodeUrl = `https://bwipjs-api.metafloor.com/?bcid=code128&text=${product.id}&scale=2&height=10&incltext=true`;

                            // [ADDED] Image Logic
                            let imageHtml = `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fas fa-mug-hot text-secondary opacity-25"></i></div>`;
                            if(product.image_path) {
                                imageHtml = `<img src="${storagePath}/${product.image_path}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;" alt="Product">`;
                            }

                            html += `
                                <div class="col-md-4 col-sm-6">
                                    <div class="p-3 border rounded bg-white h-100 shadow-sm position-relative">
                                        
                                        {{-- Product Details --}}
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 140px;">${product.name}</div>
                                                <div class="text-success fw-bold">₱${product.price}</div>
                                            </div>
                                            ${imageHtml}
                                        </div>
                                        
                                        <hr class="my-2" style="opacity: 0.1">
                                        
                                        {{-- External Barcode --}}
                                        <div class="text-center mt-2">
                                            <span class="d-block small text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">EXTERNAL API GENERATED</span>
                                            <img src="${barcodeUrl}" alt="Barcode" style="max-width: 100%; height: 35px; opacity: 0.8;">
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = html;
                    } else {
                        resultsDiv.innerHTML = '<div class="col-12 text-secondary small">No products found.</div>';
                    }
                })
                .catch(err => {
                    console.error('API Error:', err);
                    resultsDiv.innerHTML = '<div class="col-12 text-danger small">Error connecting to API.</div>';
                });
        });
    }

    // 3. End Shift Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const endShiftBtn = document.getElementById('btn-end-shift');
        if(endShiftBtn) {
            endShiftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                Swal.fire({
                    title: 'End Shift?',
                    text: "Register will close. Proceed?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'Yes, Close It'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = url;
                });
            });
        }
    });
</script>
@endsection