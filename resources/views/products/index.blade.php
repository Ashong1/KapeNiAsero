<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal | Kape Ni Asero</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-coffee: #6F4E37;
            --primary-coffee-hover: #5A3D2B;
            --dark-coffee: #3E2723;
            --accent-gold: #C5A065;
            --surface-bg: #F5F5F7;
            --surface-white: #FFFFFF;
            --text-dark: #1D1D1F;
            --text-secondary: #86868B;
            --success-green: #34C759;
            --border-light: #E5E5EA;
            --glass-header: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surface-bg);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden; /* Desktop default */
        }

        /* --- PREMIUM HEADER --- */
        .pos-header {
            background-color: var(--glass-header);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--text-dark);
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            letter-spacing: -0.02em;
        }

        /* --- LAYOUT GRID --- */
        .pos-container {
            margin-top: 70px;
            height: calc(100vh - 70px);
            display: flex;
        }

        /* LEFT SIDE: PRODUCT AREA */
        .product-section {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            background: radial-gradient(circle at top left, #fff8f0 0%, transparent 40%);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
            padding-bottom: 3rem;
        }

        /* PRODUCT CARD */
        .coffee-card {
            background: var(--surface-white);
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Hover effect only for interactive cards */
        .coffee-card.interactive:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-color: rgba(111, 78, 55, 0.2);
            cursor: pointer;
        }

        .card-img-container {
            height: 140px;
            background: #FAFAFA;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .card-img-container::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 80%, rgba(0,0,0,0.03));
        }

        .category-pill {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
            line-height: 1.3;
        }

        .product-price {
            font-weight: 800;
            color: var(--primary-coffee);
            font-size: 1.1rem;
        }

        /* ADMIN ACTIONS STYLING */
        .admin-actions {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-admin-action {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; transition: all 0.2s;
            background: #F5F5F7; color: var(--text-secondary); border: none; cursor: pointer;
        }
        .btn-admin-action:hover { background: var(--text-dark); color: white; }
        .btn-admin-action.delete:hover { background: #FF3B30; color: white; }

        /* RIGHT SIDE: CART PANEL */
        .cart-section {
            width: 400px;
            background-color: var(--surface-white);
            border-left: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 25px rgba(0,0,0,0.03);
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .cart-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            cursor: default;
        }

        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #FAFAFA;
        }

        /* CART ITEM STYLING (Improved for Mobile) */
        .cart-item {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border: 1px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px; /* Gap between left and right sides */
        }
        
        .cart-item-left {
            display: flex;
            align-items: center;
            flex-grow: 1;
            min-width: 0; /* Crucial for text-truncate to work in flex child */
        }

        .cart-item-right {
            text-align: right;
            white-space: nowrap; /* Keep price on one line */
        }
        
        .cart-item:hover {
            border-color: var(--accent-gold);
            transform: translateX(-3px);
        }

        .qty-badge {
            background: var(--surface-bg);
            width: 36px; height: 36px; flex-shrink: 0;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: var(--primary-coffee);
            margin-right: 1rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .cart-footer {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid var(--border-light);
            box-shadow: 0 -10px 40px rgba(0,0,0,0.03);
        }

        .total-row {
            display: flex; justify-content: space-between; margin-bottom: 0.5rem;
            font-size: 0.9rem; color: var(--text-secondary);
        }

        .grand-total {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 1rem; margin-bottom: 1.5rem;
            font-size: 1.5rem; font-weight: 800; color: var(--text-dark);
        }

        .btn-checkout {
            width: 100%; padding: 1rem; border-radius: 16px; font-weight: 700; font-size: 1.1rem;
            border: none; background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: white; box-shadow: 0 8px 20px rgba(111, 78, 55, 0.25); transition: all 0.3s;
        }
        .btn-checkout:hover {
            transform: translateY(-2px); box-shadow: 0 12px 25px rgba(111, 78, 55, 0.35);
            background: linear-gradient(135deg, #7D5A42 0%, #4E342E 100%);
        }

        /* --- MOBILE RESPONSIVENESS --- */
        @media (max-width: 991px) {
            body { height: auto; overflow: auto; padding-bottom: 80px; /* Space for fixed bottom bar */ }
            
            .pos-header { position: sticky; padding: 0 1rem; }
            .navbar-brand img { height: 32px; }
            .navbar-brand span { font-size: 1.1rem; }
            
            .pos-container { margin-top: 0; height: auto; flex-direction: column; }
            
            .product-section { 
                height: auto; 
                padding: 1rem; 
                padding-bottom: 140px; /* Extra padding so content isn't covered by cart */
            }
            
            .product-grid { 
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); 
                gap: 1rem;
            }

            /* Bottom Sheet Cart */
            .cart-section {
                width: 100%;
                position: fixed;
                bottom: 0;
                left: 0;
                border-left: none;
                border-top: 1px solid var(--border-light);
                height: auto;
                max-height: 85vh; /* Limit height when expanded */
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
                box-shadow: 0 -5px 30px rgba(0,0,0,0.15);
                transform: translateY(calc(100% - 85px)); /* Show only footer by default */
            }

            .cart-section.expanded {
                transform: translateY(0);
            }

            /* Header acts as toggle handle on mobile */
            .cart-header {
                padding: 1rem;
                display: flex;
                justify-content: center;
                cursor: pointer;
                background: #fff;
            }
            
            .cart-header::before {
                content: '';
                width: 40px;
                height: 5px;
                background-color: #E0E0E0;
                border-radius: 10px;
                position: absolute;
                top: 8px;
            }

            .cart-body {
                display: none; /* Hidden by default to save resources/rendering until expanded */
            }
            .cart-section.expanded .cart-body {
                display: block;
            }

            .cart-footer {
                padding: 1rem;
                background: white;
                z-index: 102;
            }
            
            /* Hide non-essential totals in collapsed view */
            .total-row { display: none; }
            .cart-section.expanded .total-row { display: flex; }
            
            .grand-total { margin: 0 0 10px 0; font-size: 1.2rem; }
            .btn-checkout { padding: 0.8rem; font-size: 1rem; }
        }
    </style>
</head>
<body>

<nav class="pos-header">
    <div class="d-flex align-items-center justify-content-between w-100">
        <div class="d-flex align-items-center gap-3">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px;" class="me-2 rounded-3 shadow-sm">
                <span class="d-none d-sm-inline">Kape Ni Asero</span>
                <span class="d-sm-none">POS</span>
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if(Auth::user()->role == 'admin')
                <a href="{{ route('home') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:40px;height:40px;" title="Dashboard">
                    <i class="fas fa-th-large text-secondary"></i>
                </a>
            @endif
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="btn btn-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:40px;height:40px;" title="Logout">
               <i class="fas fa-power-off"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
</nav>

<div class="pos-container">
    
    <div class="product-section">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center animate__animated animate__fadeInDown">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Menu</h4>
                <p class="text-secondary small mb-0">Tap items to add</p>
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
                        <a href="{{ route('products.edit', $product->id) }}" 
                           class="btn-admin-action" 
                           onclick="event.stopPropagation();" 
                           title="Edit Item">
                            <i class="fas fa-pen fa-sm"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="btn-admin-action delete" 
                                    onclick="event.stopPropagation(); return confirm('Delete {{ $product->name }}?');" 
                                    title="Delete Item">
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
                <small>Tap items to add</small>
            </div>
        </div>

        <div class="cart-footer">
            <div class="total-row">
                <span>Subtotal</span>
                <span class="fw-bold text-dark" id="subtotal">₱0.00</span>
            </div>
            <div class="total-row">
                <span>VAT (12%)</span>
                <span class="fw-bold text-dark" id="tax">₱0.00</span>
            </div>
            <div class="grand-total">
                <span>Total</span>
                <span class="text-primary-coffee" id="grand-total">₱0.00</span>
            </div>

            @if(Auth::user()->role != 'admin')
                <button class="btn-checkout" onclick="checkout()">
                    <i class="fas fa-credit-card me-2"></i> Charge
                </button>
            @else
                <div class="alert alert-secondary border-0 text-center small rounded-3 mb-0">
                    <i class="fas fa-lock me-1"></i> Admin View Only
                </div>
            @endif
        </div>
    </div>

</div>

@if(Auth::user()->role != 'admin')
<script>
    let cart = [];
    let isCartExpanded = false;

    // Toggle Bottom Sheet on Mobile
    function toggleCart() {
        if(window.innerWidth >= 992) return; // Only for mobile/tablet
        
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
                    <div class="cart-item-left">
                        <div class="qty-badge">${item.quantity}</div>
                        <div style="line-height:1.2; min-width: 0;">
                            <div class="fw-bold text-dark text-truncate" title="${item.name}">${item.name}</div>
                            <small class="text-muted">₱${item.price}</small>
                        </div>
                    </div>
                    <div class="cart-item-right ms-2">
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
                // Collapse cart on mobile after success
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>