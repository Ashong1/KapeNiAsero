@extends('layouts.app')

@section('styles')
<style>
    /* --- LAYOUT OVERRIDES --- */
    .container { max-width: 100% !important; padding: 0 !important; }
    body { padding-bottom: 0 !important; overflow: hidden; background: #F8F9FA; }
    .pos-layout { height: calc(100vh - 80px); display: flex; overflow: hidden; }

    /* --- LEFT: PRODUCT AREA --- */
    .product-section { flex: 1; display: flex; flex-direction: column; height: 100%; overflow: hidden; }
    .pos-header { padding: 1.5rem 2rem 1rem 2rem; background: white; border-bottom: 1px solid var(--border-light); z-index: 10; flex-shrink: 0; }
    .search-wrapper { position: relative; margin-bottom: 1rem; }
    .search-input { width: 100%; padding: 0.8rem 1rem 0.8rem 3rem; border-radius: 12px; border: 1px solid var(--border-light); background: #F5F5F7; font-weight: 500; transition: all 0.2s; }
    .search-input:focus { background: white; border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); outline: none; }
    .search-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 0.9rem; }
    .category-scroll { display: flex; gap: 0.8rem; overflow-x: auto; padding-bottom: 5px; scrollbar-width: none; }
    .category-scroll::-webkit-scrollbar { display: none; }
    .cat-pill { white-space: nowrap; padding: 0.6rem 1.2rem; border-radius: 100px; font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); background: #fff; border: 1px solid var(--border-light); cursor: pointer; transition: all 0.2s; user-select: none; display: flex; align-items: center; gap: 0.5rem; }
    .cat-pill:hover { background: #FAFAFA; color: var(--text-dark); }
    .cat-pill.active { background: var(--primary-coffee); color: white; border-color: var(--primary-coffee); box-shadow: 0 4px 10px rgba(111, 78, 55, 0.2); }
    .product-scroll-area { flex: 1; overflow-y: auto; padding: 2rem; background: radial-gradient(circle at top left, #fff8f0 0%, transparent 40%); }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 1.5rem; padding-bottom: 5rem; }
    .coffee-card { background: white; border: 1px solid rgba(0,0,0,0.04); border-radius: 20px; overflow: hidden; transition: all 0.2s; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.03); height: 100%; display: flex; flex-direction: column; cursor: pointer; }
    .coffee-card:active { transform: scale(0.98); }
    .coffee-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); border-color: var(--primary-coffee); }
    .card-img-box { height: 130px; background: #FAFAFA; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
    .card-img-box img { transition: transform 0.3s; }
    .coffee-card:hover .card-img-box img { transform: scale(1.1); }
    .card-content { padding: 1rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .product-title { font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; color: var(--text-dark); line-height: 1.3; }
    .product-price { font-weight: 800; color: var(--primary-coffee); font-size: 1.1rem; }

    /* --- RIGHT: CART SECTION --- */
    .cart-section { width: 420px; background: white; border-left: 1px solid var(--border-light); display: flex; flex-direction: column; box-shadow: -5px 0 25px rgba(0,0,0,0.03); z-index: 50; height: 100%; }
    .cart-header { padding: 1.5rem; border-bottom: 1px solid var(--border-light); background: rgba(255,255,255,0.95); display: flex; justify-content: space-between; align-items: center; }
    .cart-body { flex: 1; overflow-y: auto; padding: 1.5rem; background: #FAFAFA; }
    .cart-item { background: white; border-radius: 16px; padding: 1rem; margin-bottom: 0.8rem; box-shadow: 0 2px 6px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; border: 1px solid transparent; transition: all 0.2s; }
    .cart-item:hover { border-color: var(--primary-coffee); transform: translateX(2px); }
    .qty-control { display: flex; align-items: center; gap: 0.8rem; background: #F5F5F7; padding: 0.3rem 0.5rem; border-radius: 8px; }
    .btn-qty { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: white; color: var(--text-dark); font-size: 0.7rem; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.1s; }
    .btn-qty:hover { background: var(--primary-coffee); color: white; }
    .btn-qty:active { transform: scale(0.9); }
    .cart-footer { padding: 1.5rem; background: white; border-top: 1px solid var(--border-light); box-shadow: 0 -10px 40px rgba(0,0,0,0.05); }
    .btn-checkout { width: 100%; padding: 1rem; border-radius: 14px; font-weight: 700; font-size: 1.1rem; border: none; background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%); color: white; box-shadow: 0 8px 20px rgba(111, 78, 55, 0.25); transition: all 0.3s; display: flex; justify-content: space-between; align-items: center; }
    .btn-checkout:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(111, 78, 55, 0.35); }

    /* --- NEW TOGGLE STYLES (MATCHING PREMIUM DESIGN) --- */
    .toggle-track {
        background-color: #F5F5F7; /* Matches App Input BG */
        border-radius: 16px;
        padding: 4px;
        display: flex;
        border: 1px solid var(--border-light);
    }
    .toggle-option {
        flex: 1;
        border: none;
        background: transparent;
        padding: 0.7rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-secondary);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .toggle-option:hover { color: var(--primary-coffee); }
    .toggle-option.active {
        background-color: white;
        color: var(--primary-coffee);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: scale(1.02);
    }

    /* --- ADMIN BUTTONS --- */
    .btn-admin-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid transparent; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .btn-edit { background: var(--surface-cream); color: var(--primary-coffee); border-color: rgba(111, 78, 55, 0.1); }
    .btn-edit:hover { background: var(--primary-coffee); color: white; box-shadow: 0 4px 10px rgba(111, 78, 55, 0.2); }
    .btn-delete { background: #FFF5F5; color: var(--danger-red); border-color: rgba(211, 47, 47, 0.1); }
    .btn-delete:hover { background: var(--danger-red); color: white; box-shadow: 0 4px 10px rgba(211, 47, 47, 0.2); }

    /* --- PAYMENT MODAL STYLES --- */
    .modal-backdrop.show { opacity: 0.2; backdrop-filter: blur(4px); }
    .modal-content-premium {
        border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    }
    .modal-header-premium {
        background: var(--surface-bg); border-bottom: 1px solid var(--border-light); padding: 1.5rem;
    }
    .display-amount {
        font-size: 2.5rem; font-weight: 800; color: var(--primary-coffee); line-height: 1;
    }
    .input-tendered {
        border: 2px solid var(--border-light); border-radius: 16px; padding: 1rem;
        font-size: 1.5rem; font-weight: 700; color: var(--text-dark); width: 100%; text-align: right;
        transition: all 0.2s;
    }
    .input-tendered:focus {
        border-color: var(--primary-coffee); outline: none; box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
    }
    .quick-cash-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; margin-top: 1rem; }
    .btn-quick-cash {
        background: white; border: 1px solid var(--border-light); padding: 0.75rem; border-radius: 12px;
        font-weight: 600; color: var(--text-dark); transition: all 0.1s;
    }
    .btn-quick-cash:hover { background: var(--surface-bg); border-color: var(--text-secondary); }
    .btn-quick-cash:active { transform: scale(0.95); background: var(--primary-coffee); color: white; }
    
    .change-display {
        background: #F0FDF4; border: 1px dashed #4ADE80; color: #15803D;
        padding: 1rem; border-radius: 12px; text-align: right;
    }
    
    /* SUCCESS ANIMATION */
    .success-animation { animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes popIn { 0% { transform: scale(0); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }

    @media (max-width: 991px) {
        body { overflow: auto !important; height: auto !important; }
        .pos-layout { flex-direction: column; overflow: visible; height: auto; padding-bottom: 80px; }
        .product-section { height: auto; overflow: visible; }
        .product-scroll-area { overflow: visible; padding: 1.5rem; }
        .cart-section { width: 100%; position: fixed; bottom: 0; left: 0; height: auto; transform: translateY(calc(100% - 85px)); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-top-left-radius: 24px; border-top-right-radius: 24px; box-shadow: 0 -5px 30px rgba(0,0,0,0.15); border-left: none; }
        .cart-section.expanded { transform: translateY(0); height: 85vh; }
        .cart-body { padding-bottom: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="pos-layout">
    
    <div class="product-section">
        <div class="pos-header">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search menu..." onkeyup="filterProducts()">
            </div>
            <div class="category-scroll">
                <div class="cat-pill active" onclick="filterCategory('all', this)">
                    <i class="fa-solid fa-layer-group"></i> All Items
                </div>
                @foreach($categories as $cat)
                    <div class="cat-pill" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</div>
                @endforeach
            </div>
        </div>

        <div class="product-scroll-area">
            <div class="product-grid" id="productGrid">
                @foreach($products as $product)
                <div class="coffee-card product-item" 
                     data-name="{{ strtolower($product->name) }}"
                     data-category="{{ $product->category_id }}"
                     @if(Auth::user()->role != 'admin')
                     onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                     @endif
                     >
                    <div class="card-img-box">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid" style="height: 100px; object-fit: contain;">
                        @else
                            <i class="fas fa-mug-hot fa-3x text-secondary opacity-25"></i>
                        @endif
                    </div>
                    <div class="card-content">
                        <div class="product-title text-truncate">{{ $product->name }}</div>
                        <div class="d-flex justify-content-between align-items-end mt-auto">
                            <div class="product-price">₱{{ number_format($product->price, 0) }}</div>
                            @if(Auth::user()->role == 'admin')
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-admin-icon btn-edit" title="Edit" onclick="event.stopPropagation()">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="event.stopPropagation(); return confirm('Delete {{ $product->name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-admin-icon btn-delete" title="Delete" onclick="event.stopPropagation()">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="rounded-circle bg-light text-primary-coffee d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px;">
                                    <i class="fa-solid fa-plus fa-xs"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div id="noResults" class="text-center py-5 d-none">
                <i class="fa-solid fa-magnifying-glass fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="text-secondary">No items found</h6>
            </div>
        </div>
    </div>

    @if(Auth::user()->role != 'admin')
    <div class="cart-section" id="cartSection">
        <div class="cart-header" onclick="toggleCart()">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-basket-shopping text-primary-coffee fs-5"></i>
                <h5 class="fw-bold m-0 text-dark">Current Order</h5>
                <div class="bg-primary-coffee text-white rounded-circle d-flex align-items-center justify-content-center fw-bold ms-2" 
                     style="width: 24px; height: 24px; font-size: 0.75rem;" id="cart-badge-count">0</div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light text-danger border-0 p-1" onclick="event.stopPropagation(); clearCart()" title="Clear Cart">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <i class="fas fa-chevron-up d-lg-none text-secondary" id="cartToggleIcon"></i>
            </div>
        </div>

        {{-- NEW TOGGLE DESIGN --}}
        <div class="px-4 pb-3 pt-3 bg-white">
            <div class="toggle-track">
                <button class="toggle-option active" id="btn-dine-in" onclick="setOrderType('dine_in')">
                    <i class="fas fa-utensils"></i> Dine In
                </button>
                <button class="toggle-option" id="btn-take-out" onclick="setOrderType('take_out')">
                    <i class="fas fa-bag-shopping"></i> Take Out
                </button>
            </div>
        </div>

        <div class="cart-body" id="cart-items">
            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center text-muted opacity-50">
                <i class="fa-solid fa-basket-shopping fa-4x mb-3"></i>
                <p class="fw-medium">Start adding items</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="d-flex justify-content-between mb-2 small text-secondary">
                <span>Subtotal</span>
                <span class="fw-bold text-dark" id="subtotal">₱0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-3 small text-secondary">
                <span>VAT (12%)</span>
                <span class="fw-bold text-dark" id="tax">₱0.00</span>
            </div>
            <button class="btn-checkout" onclick="openCheckoutModal()">
                <span><i class="fa-solid fa-file-invoice-dollar me-2"></i> Charge</span>
                <span id="grand-total">₱0.00</span>
            </button>
        </div>
    </div>
    @endif

</div>

{{-- MODALS (Checkout & Success) --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-premium">
            <div class="modal-header-premium">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-cash-register me-2 text-primary-coffee"></i>Confirm Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <small class="text-uppercase text-secondary fw-bold">Total Amount</small>
                    <div class="display-amount" id="modalTotalAmount">₱0.00</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cash Received</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-secondary fw-bold" style="border-radius: 16px 0 0 16px;">₱</span>
                        <input type="number" id="cashInput" class="form-control input-tendered border-start-0" placeholder="0.00" style="border-radius: 0 16px 16px 0;">
                    </div>
                    <div class="quick-cash-grid">
                        <button class="btn-quick-cash" onclick="setCash(100)">₱100</button>
                        <button class="btn-quick-cash" onclick="setCash(500)">₱500</button>
                        <button class="btn-quick-cash" onclick="setCash(1000)">₱1k</button>
                        <button class="btn-quick-cash text-primary-coffee" onclick="setExactCash()">Exact</button>
                    </div>
                </div>
                <div class="change-display">
                    <div class="small fw-bold text-success opacity-75 text-uppercase">Change</div>
                    <div class="fs-4 fw-bold" id="changeAmount">₱0.00</div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light w-100 py-3 rounded-4 fw-bold text-secondary mb-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-pos w-100 py-3 rounded-4 fw-bold fs-5 shadow-sm" onclick="confirmPayment()">
                    Complete Payment <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-premium text-center p-4">
            <div class="modal-body">
                <div class="mb-4">
                    <div class="success-animation">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 5rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-2">Payment Successful!</h4>
                <p class="text-secondary mb-4">Transaction has been recorded.</p>

                <div class="receipt-summary bg-light p-3 rounded-4 mb-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary small">Total Amount</span>
                        <span class="fw-bold text-dark" id="successTotal">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary small">Cash Tendered</span>
                        <span class="fw-bold text-dark" id="successCash">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <span class="text-success fw-bold">Change</span>
                        <span class="text-success fw-bold fs-5" id="successChange">₱0.00</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary-coffee py-3 rounded-4 fw-bold shadow-sm" onclick="printReceipt()">
                        <i class="fa-solid fa-print me-2"></i> Print Receipt
                    </button>
                    <button class="btn btn-light py-3 rounded-4 fw-bold text-secondary" onclick="finishTransaction()">
                        <i class="fa-solid fa-arrow-rotate-right me-2"></i> New Order (Skip)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // --- ORDER TYPE TOGGLE LOGIC ---
    function setOrderType(type) {
        orderType = type;
        
        // Toggle Classes Cleaner
        const btnDine = document.getElementById('btn-dine-in');
        const btnTake = document.getElementById('btn-take-out');
        
        if (type === 'dine_in') {
            btnDine.classList.add('active');
            btnTake.classList.remove('active');
        } else {
            btnTake.classList.add('active');
            btnDine.classList.remove('active');
        }
    }

    // --- SEARCH & FILTER ---
    let activeCategory = 'all';
    function filterCategory(catId, element) {
        document.querySelectorAll('.cat-pill').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        activeCategory = catId;
        applyFilters();
    }
    function filterProducts() { applyFilters(); }
    function applyFilters() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.product-item');
        let visibleCount = 0;
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            const cat = item.getAttribute('data-category');
            const matchesSearch = name.includes(query);
            const matchesCat = activeCategory === 'all' || cat === activeCategory;
            if (matchesSearch && matchesCat) { item.style.display = ''; visibleCount++; } 
            else { item.style.display = 'none'; }
        });
        document.getElementById('noResults').classList.toggle('d-none', visibleCount > 0);
    }

    // --- CART ---
    @if(Auth::user()->role != 'admin')
    let cart = [];
    let isCartExpanded = false;
    let currentTotal = 0;
    let lastOrderId = null; // Store order ID for printing
    let orderType = 'dine_in'; // Default

    function addToCart(id, name, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) { existing.quantity++; } 
        else { cart.push({ id, name, price, quantity: 1 }); }
        renderCart();
    }

    function updateQty(index, change) {
        if (cart[index].quantity + change <= 0) {
            if(confirm('Remove item?')) cart.splice(index, 1);
        } else { cart[index].quantity += change; }
        renderCart();
    }

    function clearCart() {
        if(cart.length > 0 && confirm('Clear order?')) { cart = []; renderCart(); }
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const badge = document.getElementById('cart-badge-count');
        const count = cart.reduce((acc, item) => acc + item.quantity, 0);
        if(badge) badge.innerText = count;

        if (cart.length === 0) {
            container.innerHTML = `<div class="h-100 d-flex flex-column align-items-center justify-content-center text-center text-muted opacity-50"><i class="fa-solid fa-basket-shopping fa-4x mb-3"></i><p class="fw-medium">Start adding items</p></div>`;
            updateTotals(0);
            return;
        }

        let html = '';
        let total = 0;
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            html += `
                <div class="cart-item animate__animated animate__fadeInRight animate__faster">
                    <div style="min-width: 0; flex: 1;">
                        <div class="fw-bold text-dark text-truncate">${item.name}</div>
                        <div class="small text-muted">₱${item.price}</div>
                    </div>
                    <div class="d-flex align-items-center gap-3 ms-2">
                        <div class="qty-control">
                            <button class="btn-qty" onclick="event.stopPropagation(); updateQty(${index}, -1)"><i class="fa-solid fa-minus"></i></button>
                            <span class="fw-bold small" style="min-width:20px; text-align:center;">${item.quantity}</span>
                            <button class="btn-qty" onclick="event.stopPropagation(); updateQty(${index}, 1)"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <div class="fw-bold text-primary-coffee text-end" style="width: 60px;">₱${itemTotal.toFixed(0)}</div>
                    </div>
                </div>`;
        });
        container.innerHTML = html;
        updateTotals(total);
    }

    function updateTotals(subtotal) {
        const tax = subtotal * 0.12;
        const total = subtotal + tax;
        currentTotal = total; // Store for checkout
        document.getElementById('subtotal').innerText = '₱' + subtotal.toFixed(2);
        document.getElementById('tax').innerText = '₱' + tax.toFixed(2);
        document.getElementById('grand-total').innerText = '₱' + total.toFixed(2);
    }

    function toggleCart() {
        if(window.innerWidth >= 992) return;
        const cartSection = document.getElementById('cartSection');
        const icon = document.getElementById('cartToggleIcon');
        isCartExpanded = !isCartExpanded;
        if(isCartExpanded) {
            cartSection.classList.add('expanded');
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        } else {
            cartSection.classList.remove('expanded');
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    }

    // --- CHECKOUT LOGIC ---
    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const cashInput = document.getElementById('cashInput');

    function openCheckoutModal() {
        if(cart.length === 0) return alert('Cart is empty');
        
        // Reset fields
        document.getElementById('modalTotalAmount').innerText = '₱' + currentTotal.toFixed(2);
        cashInput.value = '';
        document.getElementById('changeAmount').innerText = '₱0.00';
        
        checkoutModal.show();
        setTimeout(() => cashInput.focus(), 500); 
    }

    // Real-time Change Calculation
    cashInput.addEventListener('keyup', calculateChange);
    cashInput.addEventListener('change', calculateChange);

    function calculateChange() {
        const cash = parseFloat(cashInput.value) || 0;
        const change = cash - currentTotal;
        const changeDisplay = document.getElementById('changeAmount');
        
        if (change >= 0) {
            changeDisplay.innerText = '₱' + change.toFixed(2);
            changeDisplay.classList.remove('text-danger');
            changeDisplay.classList.add('text-success');
        } else {
            changeDisplay.innerText = 'Insufficient';
            changeDisplay.classList.add('text-danger');
            changeDisplay.classList.remove('text-success');
        }
    }

    function setCash(amount) {
        cashInput.value = amount;
        calculateChange();
    }

    function setExactCash() {
        cashInput.value = currentTotal.toFixed(2);
        calculateChange();
    }

    function confirmPayment() {
        const cash = parseFloat(cashInput.value) || 0;
        
        if (cash < currentTotal) {
            alert('Insufficient cash tendered!');
            return;
        }

        // Proceed with checkout
        fetch('/checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ 
                cart: cart,
                order_type: orderType // <--- SEND TOGGLE VALUE
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // 1. Save Data & Hide Checkout
                lastOrderId = data.order_id;
                checkoutModal.hide();

                // 2. Populate Success Modal
                document.getElementById('successTotal').innerText = document.getElementById('modalTotalAmount').innerText;
                document.getElementById('successCash').innerText = '₱' + parseFloat(cashInput.value).toFixed(2);
                document.getElementById('successChange').innerText = document.getElementById('changeAmount').innerText;

                // 3. Clear Cart Data immediately so background updates
                cart = []; renderCart();
                if(window.innerWidth < 992 && isCartExpanded) toggleCart();

                // 4. Show Success Modal & AUTO-PRINT
                successModal.show();
                printReceipt(); 
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => console.error(err));
    }

    // --- SUCCESS MODAL ACTIONS ---
    function printReceipt() {
        if(lastOrderId) {
            const url = '/orders/' + lastOrderId + '/receipt';
            const win = window.open(url, '_blank');
            if(!win || win.closed || typeof win.closed == 'undefined') {
                console.log('Popup blocked. User must click button manually.');
            }
        }
    }

    function finishTransaction() {
        successModal.hide();
        // Reset Order ID
        lastOrderId = null;
    }
    @endif
</script>
@endsection