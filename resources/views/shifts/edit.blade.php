@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">Close Register / End Shift</div>
                <div class="card-body">
                    
                    <div class="alert alert-info">
                        <h5>Shift Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Starting Cash:</span>
                            <span>₱{{ number_format($shift->start_cash, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>+ Cash Sales:</span>
                            <span>₱{{ number_format($cashSales, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>= Expected Total:</span>
                            <span>₱{{ number_format($expectedCash, 2) }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('shifts.update', $shift->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="end_cash" class="form-label text-danger"><strong>Actual Cash Counted (₱)</strong></label>
                            <input type="number" step="0.01" class="form-control form-control-lg" name="end_cash" required placeholder="0.00">
                            <small class="text-muted">Count the physical money in the drawer and enter it here.</small>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 btn-lg" onclick="return confirm('Are you sure you want to close the register? You will be logged out.')">Close Register & Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection