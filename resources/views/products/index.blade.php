<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kape Ni Asero - POS</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            /* Palette matching the Edit Page */
            --primary-coffee: #6F4E37;
            --dark-coffee: #3E2723;
            --accent-gold: #8B7355;
            --surface-cream: #FFF8E7;
            --surface-white: #FFFFFF;
            --text-dark: #2C1810;
            --text-light: #FFF8E7;
            --success-green: #689F38;
            --border-light: #F0E5D0;
            --input-border: #E8DCC8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden; 
        }

        /* HEADER styling */
        .pos-header {
            background-color: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid var(--border-light);
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-coffee) !important;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }

        /* LEFT SIDE: PRODUCT GRID */
        .product-area {
            height: calc(100vh - 70px);
            overflow-y: auto;
            padding: 2rem;
            background-color: transparent; /* Let body gradient show or use light overlay */
        }
        
        /* Grid Container with backdrop for readability */
        .grid-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            min-height: 100%;
        }

        /* Product Card Styling - Matching Edit Page Card Style */
        .coffee-card {
            border: none;
            border-radius: 16px;
            background: var(--surface-white);
            box-shadow: 0 4px 12px rgba(62, 39, 35, 0.08);
            transition: all 0.2s ease;
            cursor: pointer;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border-light);
        }
        
        .coffee-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(111, 78, 55, 0.15);
            border-color: var(--primary-coffee);
        }

        .card-img-wrapper {
            height: 140px;
            background: var(--surface-cream);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-coffee);
            position: relative;
        }
        
        .card-img-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to top, rgba(0,0,0,0.05), transparent);
        }

        .category-badge {
            font-size: 0.7rem;
            background-color: var(--surface-cream);
            color: var(--primary-coffee);
            padding: 0.35em 0.8em;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid var(--input-border);
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        /* RIGHT SIDE: CART PANEL */
        .cart-panel {
            height: calc(100vh - 70px);
            background-color: var(--surface-white);
            border-left: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }

        .cart-header {
            background-color: var(--primary-coffee);
            color: var(--surface-cream);
            padding: 1.25rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .cart-items-container {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #FAFAFA;
        }

        .cart-item {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        
        .cart-item:hover {
            border-color: var(--accent-gold);
            transform: translateX(-2px);
        }

        .cart-footer {
            background-color: white;
            border-top: 1px solid var(--border-light);
            padding: 1.5rem;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
        }

        /* Buttons */
        .btn-mocha {
            background-color: var(--primary-coffee);
            border-color: var(--primary-coffee);
            color: white;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-mocha:hover {
            background-color: #5A3D2B;
            border-color: #5A3D2B;
            color: white;
            transform: translateY(-1px);
        }
        
        .btn-outline-dark {
            border-color: var(--text-dark);
            color: var(--text-dark);
            border-radius: 10px;
        }
        .btn-outline-dark:hover {
            background-color: var(--text-dark);
            color: white;
        }

        .btn-pay {
            background: var(--primary-coffee);
            color: white;
            font-weight: 700;
            border: none;
            padding: 1rem;
            font-size: 1.1rem;
            border-radius: 12px;
            width: 100%;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }
        .btn-pay:hover {
            background-color: #5A3D2B;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(111, 78, 55, 0.4);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #D7CCC8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-gold); }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    
    <!-- HEADER -->
    <div class="pos-header justify-content-between">
        <div class="navbar-brand">
            <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 40px;" class="me-3"> 
            <span>Kape Ni Asero <span class="fw-normal text-muted ms-2 fs-6">| POS Terminal</span></span>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <div class="d-none d-md-flex align-items-center bg-light px-3 py-2 rounded-pill border">
                <i class="fas fa-user-circle me-2 text-secondary"></i>
                <span class="text-muted small me-1">Cashier:</span>
                <strong class="text-dark">{{ Auth::user()->name }}</strong>
            </div>
            
            @if(Auth::user()->role == 'admin')
                <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm px-3 py-2 d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            @endif
            
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="fas fa-power-off"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <div class="row g-0">
        
        <!-- LEFT: PRODUCT GRID -->
        <div class="col-md-8 col-lg-9 product-area">
            <div class="grid-container">
                @if(session('success'))
                    <div class="alert alert-success shadow-sm border-0 mb-4 rounded-3" style="background-color: #D1E7DD; color: #0F5132;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold m-0" style="color: var(--primary-coffee);"><i class="fas fa-mug-hot me-2"></i>Menu Items</h4>
                    <div class="text-muted small">Select items to add to order</div>
                </div>

                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        
                        <!-- Product Card -->
                        <div class="card h-100 coffee-card"
                             @if(Auth::user()->role != 'admin')
                                onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                             @endif
                             title="{{ $product->name }}">
                            
                            <div class="card-img-wrapper">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 100px; object-fit: contain;">
                                @else
                                    <i class="fas fa-coffee fa-3x opacity-50"></i>
                                @endif
                            </div>

                            <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                <div>
                                    <span class="category-badge">
                                        {{ $product->category->name ?? 'General' }}
                                    </span>
                                    <h6 class="fw-bold text-dark m-0 text-truncate mb-1">{{ $product->name }}</h6>
                                </div>
                                <h5 class="fw-bold m-0 mt-2" style="color: var(--primary-coffee);">₱{{ number_format($product->price, 0) }}</h5>

                                <!-- Admin Controls (Only visible to Admin) -->
                                @if(Auth::user()->role == 'admin')
                                    <div class="mt-3 border-top pt-2">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary border-0 p-1" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" 
                                                        onclick="event.stopPropagation(); return confirm('Delete item?');" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @if($product->ingredients->isEmpty())
                                            <small class="d-block text-danger mt-1 fw-bold" style="font-size: 0.65rem;"><i class="fas fa-exclamation-circle"></i> No Recipe</small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- RIGHT: CART PANEL -->
        <div class="col-md-4 col-lg-3 cart-panel">
            <div class="cart-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shopping-basket me-2"></i>
                    <span class="fs-5">Current Order</span>
                </div>
                <span class="badge bg-white text-dark rounded-pill shadow-sm px-3 py-2" id="cart-count">0</span>
            </div>
            
            <!-- Cart Items List -->
            <div class="cart-items-container" id="cart-items">
                <div class="text-center text-muted mt-5 pt-5 opacity-50">
                    <div class="mb-3">
                        <i class="fas fa-mug-hot fa-4x" style="color: var(--input-border);"></i>
                    </div>
                    <p class="fw-medium">Cart is empty</p>
                    <small>Select items from the menu</small>
                </div>
            </div>

            <!-- Cart Totals & Checkout -->
            @if(Auth::user()->role != 'admin')
            <div class="cart-footer">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary fw-medium">Subtotal</span>
                    <span class="fw-bold text-dark" id="subtotal">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-light">
                    <span class="text-secondary small">VAT (12%)</span>
                    <span class="small text-dark" id="tax">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="h5 m-0 fw-bold text-dark">Total</span>
                    <span class="h3 m-0 fw-bold" style="color: var(--primary-coffee);" id="grand-total">₱0.00</span>
                </div>

                <button class="btn-pay" onclick="checkout()">
                    <i class="fas fa-check-circle me-2"></i> Charge Payment
                </button>
            </div>
            @else
            <div class="cart-footer text-center">
                <div class="alert alert-warning border-0 small m-0 rounded-3 shadow-sm">
                    <i class="fas fa-info-circle me-1"></i> <strong>Admin View</strong><br>
                    Log in as an employee to process sales.
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<!-- JAVASCRIPT FOR CART LOGIC -->
@if(Auth::user()->role != 'admin')
<script>
    let cart = [];

    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++; 
        } else {
            cart.push({ id: id, name: name, price: price, quantity: 1 }); 
        }
        renderCart(); 
        
        // Visual feedback
        const countBadge = document.getElementById('cart-count');
        countBadge.style.transform = 'scale(1.2)';
        setTimeout(() => countBadge.style.transform = 'scale(1)', 100);
    }

    function renderCart() {
        const cartElement = document.getElementById('cart-items');
        const countBadge = document.getElementById('cart-count');
        
        // Update Count
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        countBadge.innerText = totalItems;

        if (cart.length === 0) {
            cartElement.innerHTML = `
                <div class="text-center text-muted mt-5 pt-5 opacity-50">
                    <div class="mb-3"><i class="fas fa-mug-hot fa-4x" style="color: var(--input-border);"></i></div>
                    <p class="fw-medium">Cart is empty</p>
                    <small>Select items from the menu</small>
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
                <div class="cart-item animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="bg-light rounded-circle p-2 me-3 text-dark border" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                            <span class="fw-bold small">x${item.quantity}</span>
                        </div>
                        <div style="line-height: 1.2;">
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">${item.name}</div>
                            <small class="text-muted">₱${item.price} each</small>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="fw-bold mb-1" style="color: var(--primary-coffee);">₱${itemTotal.toFixed(2)}</span>
                        <button class="btn btn-sm text-danger border-0 p-0" 
                                onclick="removeFromCart(${index})" title="Remove">
                            <small><i class="fas fa-trash-alt me-1"></i> Remove</small>
                        </button>
                    </div>
                </div>`;
        });
        
        cartElement.innerHTML = html;
        updateTotals(total);
    }

    function updateTotals(subtotal) {
        const tax = subtotal * 0.12;
        const grandTotal = subtotal + tax;
        document.getElementById('subtotal').innerText = '₱' + subtotal.toFixed(2);
        document.getElementById('tax').innerText = '₱' + tax.toFixed(2);
        document.getElementById('grand-total').innerText = '₱' + grandTotal.toFixed(2);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function checkout() {
        if(cart.length === 0) { 
            alert("Cart is empty!"); 
            return; 
        }
        
        if(!confirm("Proceed to payment?")) return;

        fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ cart: cart })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                cart = [];
                renderCart();
                
                // Show print receipt prompt
                if(confirm("Payment Successful! ₱" + document.getElementById('grand-total').innerText + "\n\nDo you want to print the receipt?")) {
                    window.open('/orders/' + data.order_id + '/receipt', '_blank');
                }
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("System Error. Check console.");
        });
    }
</script>
@endif

</body>
</html>