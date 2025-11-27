<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kape Ni Asero - POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { overflow-x: hidden; }
        .coffee-card { cursor: pointer; transition: 0.2s; }
        .coffee-card:hover { transform: translateY(-3px); border-color: #0d6efd; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .receipt-area { background-color: #fff; border-left: 1px solid #ddd; height: 90vh; display: flex; flex-direction: column; }
        .order-list { flex-grow: 1; overflow-y: auto; padding-right: 5px; }
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
            <h4 class="fw-bold m-0 text-primary me-3"><i class="fas fa-mug-hot"></i> Kape Ni Asero</h4>
            <div class="border-start ps-3 text-muted">
                <small>User: <strong class="text-dark">{{ Auth::user()->name }}</strong></small>
                <span class="badge bg-info text-dark ms-1">{{ Auth::user()->role }}</span>
            </div>
        </div>
        <div>
            @if(Auth::user()->role == 'admin')
                <a href="{{ route('ingredients.index') }}" class="btn btn-outline-dark btn-sm me-2">
                    <i class="fas fa-boxes"></i> Warehouse
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-plus"></i> Add Item
                </a>
            @endif
            
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <div class="row px-2">
        <!-- LEFT SIDE: MENU GRID -->
        <!-- If Admin, take full width (col-12). If Employee, take partial width (col-8) to make room for cart. -->
        <div class="{{ Auth::user()->role == 'admin' ? 'col-12' : 'col-md-8' }}">
            
            @if(session('success')) <div class="alert alert-success py-2">{{ session('success') }}</div> @endif

            <div class="row g-3" style="height: 85vh; overflow-y: auto; padding-bottom: 100px;">
                @foreach($products as $product)
                <div class="{{ Auth::user()->role == 'admin' ? 'col-md-2 col-4' : 'col-md-3 col-6' }}">
                    
                    <!-- CARD LOGIC -->
                    <!-- Admin click -> Edit Page. Employee click -> Add to Cart. -->
                    <div class="card h-100 coffee-card border-0 shadow-sm" 
                         @if(Auth::user()->role != 'admin')
                             onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                         @endif
                         >
                        
                        <div class="bg-light d-flex align-items-center justify-content-center text-secondary" style="height: 100px;">
                            <i class="fas fa-coffee fa-3x"></i>
                        </div>

                        <div class="card-body p-2 text-center">
                            <h6 class="card-title fw-bold text-dark m-0">{{ $product->name }}</h6>
                            <span class="badge bg-light text-dark mb-2">{{ $product->category }}</span>
                            <h5 class="text-primary fw-bold">₱{{ number_format($product->price, 2) }}</h5>

                            <!-- ADMIN ONLY: Show Recipe Status -->
                            @if(Auth::user()->role == 'admin')
                                <div class="mt-2 pt-2 border-top">
                                    @if($product->ingredients->isEmpty())
                                        <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> No Recipe</span>
                                    @else
                                        <span class="text-success small"><i class="fas fa-check-circle"></i> Ready</span>
                                    @endif
                                    
                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- RIGHT SIDE: CART (ONLY FOR EMPLOYEES) -->
        @if(Auth::user()->role != 'admin')
        <div class="col-md-4">
            <div class="receipt-area shadow-sm rounded p-3">
                <h5 class="fw-bold border-bottom pb-2">Current Order</h5>
                <div class="order-list mt-2" id="cart-items">
                    <div class="text-center text-muted mt-5">
                        <i class="fas fa-cash-register fa-3x mb-3 opacity-50"></i>
                        <p>Tap items to add...</p>
                    </div>
                </div>
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
        @endif

    </div>
</div>

<!-- JS only needed for Employees -->
@if(Auth::user()->role != 'admin')
<script>
    let cart = [];
    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) { existingItem.quantity++; } 
        else { cart.push({ id: id, name: name, price: price, quantity: 1 }); }
        renderCart();
    }
    function renderCart() {
        const cartElement = document.getElementById('cart-items');
        if (cart.length === 0) {
            cartElement.innerHTML = `<div class="text-center text-muted mt-5"><i class="fas fa-cash-register fa-3x mb-3 opacity-50"></i><p>Tap items to add...</p></div>`;
            updateTotals(0); return;
        }
        let html = '<ul class="list-group list-group-flush">';
        let total = 0;
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            html += `
                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div><h6 class="m-0 fw-bold">${item.name}</h6><small class="text-muted">₱${item.price} x ${item.quantity}</small></div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold me-3 text-dark">₱${itemTotal.toFixed(2)}</span>
                        <button class="btn btn-sm btn-outline-danger border-0" onclick="removeFromCart(${index})"><i class="fas fa-times"></i></button>
                    </div>
                </li>`;
        });
        html += '</ul>';
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
    function removeFromCart(index) { cart.splice(index, 1); renderCart(); }
    function checkout() {
        if(cart.length === 0) { alert("Cart is empty!"); return; }
        if(!confirm("Process payment?")) return;
        fetch('/checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ cart: cart })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) { alert("Payment Successful!"); cart = []; renderCart(); } 
            else { alert("Error: " + data.message); }
        });
    }
</script>
@endif

</body>
</html>