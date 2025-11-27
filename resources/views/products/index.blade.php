<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kape Ni Asero - POS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { overflow-x: hidden; }
        .coffee-card { cursor: pointer; transition: 0.2s; }
        .coffee-card:hover { transform: translateY(-3px); border-color: #0d6efd; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        
        /* THE RECEIPT / CART SIDEBAR */
        .receipt-area { 
            background-color: #fff; 
            border-left: 1px solid #ddd; 
            height: 90vh; 
            display: flex; 
            flex-direction: column; 
        }
        .order-list { flex-grow: 1; overflow-y: auto; padding-right: 5px; }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center py-2 px-3 bg-white shadow-sm mb-3">
        <div class="d-flex align-items-center">
        <h4 class="fw-bold m-0 me-3">
    <img src="{{ asset('ka.png') }}" alt="Kape Ni Asero Logo" style="height: 35px; filter: invert(1);" class="me-2"> 
    Kape Ni Asero
</h4>   
            <div class="border-start ps-3 text-muted">
                <small>Cashier: <strong class="text-dark">{{ Auth::user()->name }}</strong></small>
                <span class="badge bg-info text-dark ms-1">{{ Auth::user()->role }}</span>
            </div>
        </div>
        <div>
            <!-- SECURITY: Only Admin sees "Add Item" button -->
            @if(Auth::user()->role == 'admin')
                <a href="{{ route('products.create') }}" class="btn btn-outline-primary btn-sm me-2">+ Add Item</a>
            @endif
            
            <!-- Logout Button -->
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <div class="row px-2">
        <!-- LEFT SIDE: MENU GRID -->
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            <div class="row g-3" style="height: 85vh; overflow-y: auto; padding-bottom: 100px;">
                @foreach($products as $product)
                <div class="col-md-3 col-6">
                    <!-- CLICK CARD TO ADD TO CART -->
                    <!-- This calls the addToCart() Javascript function below -->
                    <div class="card h-100 coffee-card border-0 shadow-sm" 
                         onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                        
                        <!-- Placeholder Icon Image -->
                        <div class="bg-light d-flex align-items-center justify-content-center text-secondary" style="height: 100px;">
                            <i class="fas fa-coffee fa-3x"></i>
                        </div>

                        <div class="card-body p-2 text-center">
                            <h6 class="card-title fw-bold text-dark m-0">{{ $product->name }}</h6>
                            <span class="badge bg-light text-dark mb-2">{{ $product->category }}</span>
                            <h5 class="text-primary fw-bold">₱{{ number_format($product->price, 2) }}</h5>
                        </div>
                        
                        <!-- SECURITY: Only Admin sees Edit/Delete inside the card -->
                        @if(Auth::user()->role == 'admin')
                            <div class="card-footer bg-white p-0 border-0 mb-2 d-flex justify-content-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-link text-warning p-0" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- stopPropagation stops the card click (Add to Cart) when deleting -->
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" 
                                            onclick="event.stopPropagation(); return confirm('Delete this item?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- RIGHT SIDE: CART / RECEIPT -->
        <div class="col-md-4">
            <div class="receipt-area shadow-sm rounded p-3">
                <h5 class="fw-bold border-bottom pb-2">Current Order</h5>
                
                <!-- Cart Items Container (Filled by Javascript) -->
                <div class="order-list mt-2" id="cart-items">
                    <div class="text-center text-muted mt-5">
                        <i class="fas fa-cash-register fa-3x mb-3 opacity-50"></i>
                        <p>Tap items to add...</p>
                    </div>
                </div>

                <!-- Totals & Checkout -->
                <div class="border-top pt-3 mt-auto">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Subtotal</span>
                        <span class="fw-bold" id="subtotal">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>VAT (12%)</span>
                        <span id="tax">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded">
                        <h4 class="fw-bold m-0">Total</h4>
                        <h3 class="fw-bold text-primary m-0" id="grand-total">₱0.00</h3>
                    </div>

                    <button class="btn btn-success w-100 btn-lg shadow-sm" onclick="checkout()">
                        <i class="fas fa-check-circle me-2"></i> Pay Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT FOR POS LOGIC -->
<script>
    // 1. State Management (Holds the shopping cart data)
    let cart = [];

    // 2. Add to Cart Function
    function addToCart(id, name, price) {
        // Check if item is already in cart
        const existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity++; // If yes, just increase number
        } else {
            cart.push({ id: id, name: name, price: price, quantity: 1 }); // If no, add new row
        }
        renderCart(); // Update the screen
    }

    // 3. Render HTML for Cart
    function renderCart() {
        const cartElement = document.getElementById('cart-items');
        
        // Empty State
        if (cart.length === 0) {
            cartElement.innerHTML = `<div class="text-center text-muted mt-5"><i class="fas fa-cash-register fa-3x mb-3 opacity-50"></i><p>Tap items to add...</p></div>`;
            updateTotals(0);
            return;
        }

        // Build HTML List
        let html = '<ul class="list-group list-group-flush">';
        let total = 0;
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            html += `
                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="m-0 fw-bold">${item.name}</h6>
                        <small class="text-muted">₱${item.price} x ${item.quantity}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold me-3 text-dark">₱${itemTotal.toFixed(2)}</span>
                        <button class="btn btn-sm btn-outline-danger border-0" onclick="removeFromCart(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </li>`;
        });
        html += '</ul>';
        cartElement.innerHTML = html;
        updateTotals(total);
    }

    // 4. Calculate Totals
    function updateTotals(subtotal) {
        const tax = subtotal * 0.12; // 12% VAT
        const grandTotal = subtotal + tax;

        document.getElementById('subtotal').innerText = '₱' + subtotal.toFixed(2);
        document.getElementById('tax').innerText = '₱' + tax.toFixed(2);
        document.getElementById('grand-total').innerText = '₱' + grandTotal.toFixed(2);
    }

    // 5. Remove Item
    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    // 6. Checkout Process (Connects to Laravel Backend)
    function checkout() {
        if(cart.length === 0) { alert("Cart is empty!"); return; }
        
        if(!confirm("Process payment?")) return;

        // Send data to the '/checkout' route
        fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Secure Token required by Laravel
            },
            body: JSON.stringify({ cart: cart })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert("Payment Successful!");
                cart = []; // Clear cart
                renderCart(); // Reset UI
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

</body>
</html>