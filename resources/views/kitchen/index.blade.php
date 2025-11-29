@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">🧑‍🍳 Kitchen Display System (Live)</h2>
    
    <div class="row" id="kitchen-board">
        </div>
</div>

{{-- Add compiled scripts --}}
@vite(['resources/js/app.js'])

<script type="module">
    // connect to the 'kitchen' channel we defined in the Event
    Echo.channel('kitchen')
        .listen('OrderPlaced', (e) => {
            console.log('Order received:', e.order);
            addTicketToBoard(e.order);
            playNotificationSound();
        });

    function addTicketToBoard(order) {
        // Simple HTML generation for the ticket
        const itemsHtml = order.items.map(item => 
            `<li>${item.quantity}x ${item.product.name}</li>`
        ).join('');

        const ticketHtml = `
            <div class="col-md-3 mb-3">
                <div class="card shadow border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0">Order #${order.id}</h5>
                        <small>Just Now</small>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled fw-bold mb-0">
                            ${itemsHtml}
                        </ul>
                    </div>
                </div>
            </div>
        `;
        
        // Prepend to list (newest first)
        document.getElementById('kitchen-board').insertAdjacentHTML('afterbegin', ticketHtml);
    }

    function playNotificationSound() {
        // Optional: Add a 'ding.mp3' to your public folder
        // new Audio('/ding.mp3').play().catch(e => console.log('Audio blocked'));
    }
</script>
@endsection