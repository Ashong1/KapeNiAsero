@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">🔐 Two Factor Verification</div>

                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{ session()->get('message') }}</div>
                    @endif
                    
                    @if($errors->has('msg'))
                        <div class="alert alert-warning">{{ $errors->first('msg') }}</div>
                    @endif

                    <form method="POST" action="{{ route('verify.store') }}">
                        @csrf
                        <p class="mb-3">
                            We sent a 6-digit code to your email. Please enter it below to continue.
                        </p>

                        <div class="form-group mb-3">
                            <input name="two_factor_code" type="number" 
                                class="form-control form-control-lg text-center letter-spacing-2 {{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" 
                                required autofocus placeholder="123456" style="letter-spacing: 5px; font-weight: bold;">
                            
                            @if($errors->has('two_factor_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('two_factor_code') }}
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Verify Login</button>
                            <a class="btn btn-link" href="{{ route('verify.resend') }}">Resend Code</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection