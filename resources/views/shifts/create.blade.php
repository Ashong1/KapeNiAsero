@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Open Register / Clock In</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('shifts.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="start_cash" class="form-label"><strong>Starting Cash in Drawer (₱)</strong></label>
                            <input type="number" step="0.01" class="form-control form-control-lg" name="start_cash" required placeholder="0.00">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Open Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection