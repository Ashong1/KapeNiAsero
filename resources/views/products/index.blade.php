@extends('layouts.app')

@section('styles')
<style>
    /* --- POS SPECIFIC OVERRIDES --- */
    .container {
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    body {
        padding-bottom: 0 !important;
        overflow: hidden;
    }

    .pos-layout {
        height: calc(100vh - 80px);
        display: flex;
        overflow: hidden;
    }

    /* LEFT SIDE: PRODUCT AREA */
    .product-section {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        background: radial-gradient(circle at top left, #fff8f0 0%, transparent 40%);
        height: 100%;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
        padding-bottom: 5rem;
    }

    /* PRODUCT CARD */
    .coffee-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* CHANGED: Apply hover effect to ALL cards (Admin & Employee) */
    .coffee-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-color: var(--primary-coffee);
    }

    /* CHANGED: Only show pointer cursor for Employees (Interactive) */
    .coffee-card.interactive:hover {
        cursor: pointer;
    }

    .card-img-container {
        height: 140px;
        background: #FAFAFA;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .category-pill {
        position: absolute; top: 10px; left: 10px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        padding: 4px 10px; border-radius: 20px;
        font-size: 0.65rem; font-weight: 700;
        color: var(--text-secondary); text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .card-content {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }

    .product-title {
        font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem;
        color: var(--text-dark); line-height: 1.3;
    }

    .product-price {
        font-weight: 800; color: var(--primary-coffee); font-size: 1.1rem;
    }

    /* RIGHT SIDE: CART PANEL */
    .cart-section {
        width: 400px;
        background-color: white;
        border-left: 1px solid var(--border-light);
        display: flex;
        flex-direction: column;
        box-shadow: -5px 0 25px rgba(0,0,0,0.03);
        z-index: 100;
        height: 100%;
    }

    .cart-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-light);
        background: rgba(255,255,255,0.95);
    }

    .cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background-color: #FAFAFA;
    }

    .cart-footer {
        padding: 1.5rem;
        background: white;
        border-top: 1px solid var(--border-light);
        box-shadow: 0 -10px 40px rgba(0,0,0,0.03);
    }

    /* CART ITEMS */
    .cart-item {
        background: white; border-radius: 16px; padding: 1rem;
        margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        display: flex; justify-content: space-between; align-items: center;
    }
    
    .qty-badge {
        background: var(--surface-bg); width: 36px; height: 36px;
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: var(--primary-coffee); margin-right: 1rem;
    }

    .btn-checkout {
        width: 100%; padding: 1rem; border-radius: 16px; font-weight: 700; font-size: 1.1rem;
        border: none; background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
        color: white; box-shadow: 0 8px 20px rgba(111, 78, 55, 0.25); transition: all 0.3s;
    }
    .btn-checkout:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(111, 78, 55, 0.35); }

    /* Admin Action Buttons */
    .admin-actions {
        margin-top: 0.75rem; padding-top: 0.75rem;
        border-top: 1px solid var(--border-light);
        display: flex; justify-content: center; gap: 0.5rem;
    }
    .btn-admin-action {
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        border-radius: 8px; background: #F5F5F7; color: var(--text-secondary); border: none;
    }
    .btn-admin-action:hover { background: var(--text-dark); color: white; }
    .btn-admin-action.delete:hover { background: #FF3B30; color: white; }

    /* Mobile Responsive */
    @media (max-width: 991px) {
        .pos-layout { flex-direction: column; height: auto; overflow: auto; padding-bottom: 80px; }
        .cart-section { width: 100%; position: fixed; bottom: 0; left: 0; height: auto; transform: translateY(calc(100% - 85px)); transition: transform 0.3s; }
        .cart-section.expanded { transform: translateY(0); }
        .product-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
    }
</style>
@endsection

@section('content')
<div class="pos-layout">
    
    <div class="product-section">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Menu</h4>
                <p class="text-secondary small mb-0">
                    @if(Auth::user()->role != 'admin')
                        Tap items to add to order
                    @else
                        Manage your products
                    @endif
                </p>
            </div>
        </div>

        <div class="product-grid">
            @foreach($products as $product)
            <div class="coffee-card {{ Auth::user()->role != 'admin' ? 'interactive' : '' }}" 
                 @if(Auth::user()->role != 'admin')
                 onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                 @endif
                 >
                
                <div class="card-img-container">
                    <span class="category-pill">{{ $product->category->name ?? 'Item' }}</span>
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid" style="height: 100px; object-fit: contain;">
                    @else
                        <i class="fas fa-mug-hot fa-3x text-secondary opacity-25"></i>
                    @endif
                </div>

                <div class="card-content">
                    <div class="product-title text-truncate">{{ $product->name }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="product-price">₱{{ number_format($product->price, 0) }}</div>
                        
                        @if(Auth::user()->role != 'admin')
                        <div class="btn btn-sm btn-light rounded-circle shadow-sm" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-plus text-primary-coffee"></i>
                        </div>
                        @endif
                    </div>

                    @if(Auth::user()->role == 'admin')
                    <div class="admin-actions">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn-admin-action" onclick="event.stopPropagation();">
                            <i class="fas fa-pen fa-sm"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-admin-action delete" onclick="event.stopPropagation(); return confirm('Delete item?');">
                                <i class="fas fa-trash fa-sm"></i>
                            </button>
                        </form>
                    </div>
                    @if($product->ingredients->isEmpty())
                        <div class="text-center mt-2">
                            <small class="text-danger fw-bold" style="font-size: 0.65rem;">
                                <i class="fas fa-exclamation-circle"></i> No Recipe
                            </small>
                        </div>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @if(Auth::user()->role != 'admin')
    <div class="cart-section" id="cartSection">
        <div class="cart-header" onclick="toggleCart()">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h5 class="fw-bold m-0 text-dark"><i class="fas fa-shopping-basket me-2 text-primary-coffee"></i> Current Order</h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary-coffee rounded-pill me-2" id="cart-count">0</span>
                    <i class="fas fa-chevron-up d-lg-none text-secondary" id="cartToggleIcon"></i>
                </div>
            </div>
        </div>

        <div class="cart-body" id="cart-items">
            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center text-muted opacity-50">
                <i class="fas fa-receipt fa-4x mb-3"></i>
                <p class="fw-medium">No items yet</p>
            </div>
        </div>

        <div class="cart-footer">
            <div class="d-flex justify-content-between mb-2 small text-secondary">
                <span>Subtotal</span>
                <span class="fw-bold text-dark" id="subtotal">₱0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small text-secondary">
                <span>VAT (12%)</span>
                <span class="fw-bold text-dark" id="tax">₱0.00</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fs-5 fw-bold text-dark">Total</span>
                <span class="fs-4 fw-800 text-primary-coffee" id="grand-total">₱0.00</span>
            </div>

            <button class="btn-checkout" onclick="checkout()">
                <i class="fas fa-credit-card me-2"></i> Charge
            </button>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
@if(Auth::user()->role != 'admin')
<script>
    let cart = [];
    let isCartExpanded = false;

    // Toggle Bottom Sheet on Mobile
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

    function addToCart(id, name, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const badge = document.getElementById('cart-count');
        badge.innerText = cart.reduce((total, item) => total + item.quantity, 0);

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center text-muted opacity-50 py-5">
                    <i class="fas fa-receipt fa-3x mb-3"></i>
                    <p class="fw-medium">No items yet</p>
                </div>`;
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
                    <div class="d-flex align-items-center" style="min-width:0;">
                        <div class="qty-badge">${item.quantity}</div>
                        <div class="text-truncate">
                            <div class="fw-bold text-dark text-truncate" title="${item.name}">${item.name}</div>
                            <small class="text-muted">₱${item.price}</small>
                        </div>
                    </div>
                    <div class="text-end ms-2">
                        <div class="fw-bold text-primary-coffee mb-1">₱${itemTotal.toFixed(2)}</div>
                        <button class="btn btn-link text-danger p-0 text-decoration-none small" onclick="removeItem(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        updateTotals(total);
    }

    function updateTotals(subtotal) {
        const tax = subtotal * 0.12;
        const total = subtotal + tax;
        document.getElementById('subtotal').innerText = '₱' + subtotal.toFixed(2);
        document.getElementById('tax').innerText = '₱' + tax.toFixed(2);
        document.getElementById('grand-total').innerText = '₱' + total.toFixed(2);
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function checkout() {
        if(cart.length === 0) return alert('Cart is empty');
        if(!confirm('Process payment?')) return;

        fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ cart })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                cart = [];
                renderCart();
                if(window.innerWidth < 992 && isCartExpanded) toggleCart();
                if(confirm('Payment success! Print receipt?')) {
                    window.open('/orders/' + data.order_id + '/receipt', '_blank');
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endif
@endsection