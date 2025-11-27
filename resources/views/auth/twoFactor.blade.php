@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                {{-- Branded Header --}}
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="m-0"><i class="fas fa-shield-alt me-2"></i> {{ __('Two Factor Verification') }}</h4>
                </div>

                <div class="card-body p-4">
                    
                    {{-- Status/Error Messages --}}
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{ session()->get('message') }}</div>
                    @endif
                    
                    @if($errors->has('msg'))
                        <div class="alert alert-warning">{{ $errors->first('msg') }}</div>
                    @endif
                    
                    <p class="text-center text-muted">A secure 6-digit code has been sent to your email address. Please enter the code below to verify your account.</p>

                    {{-- TIMER DISPLAY (Styled with cream and sienna accent) --}}
                    <div class="alert text-center py-2 mb-4" style="background-color: var(--color-cream); border-color: var(--color-sienna);">
                        <strong class="text-dark">Code expires in: </strong>
                        <span id="otp-timer" class="fw-bold text-danger">10:00</span>
                    </div>
                    
                    {{-- Verification Form --}}
                    <form method="POST" action="{{ route('verify.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="two_factor_code" class="col-md-4 col-form-label text-md-end">{{ __('OTP Code') }}</label>

                            <div class="col-md-6">
                                <input id="two_factor_code" type="number" class="form-control form-control-lg text-center @error('two_factor_code') is-invalid @enderror" 
                                    name="two_factor_code" required autofocus maxlength="6" 
                                    placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">

                                @error('two_factor_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i> {{ __('Verify Login') }}
                            </button>
                        </div>
                    </form>

                    <hr>
                    
                    {{-- Resend Form (Disabled until timer expires) --}}
                    <div class="text-center">
                        <p class="m-0 text-muted small">Didn't receive the code?</p>
                        <form method="POST" action="{{ route('verify.resend') }}" class="mt-1">
                            @csrf
                            <button type="submit" id="resend-button" class="btn btn-link text-danger p-0 m-0 align-baseline" disabled>
                                {{ __('Resend Code') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initial time in seconds (10 minutes default expiration)
        let timeRemaining = 600; 
        const timerDisplay = document.getElementById('otp-timer');
        const resendButton = document.getElementById('resend-button');

        function updateTimer() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            
            const formattedTime = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            timerDisplay.textContent = formattedTime;

            if (timeRemaining <= 0) {
                clearInterval(countdown);
                timerDisplay.textContent = 'EXPIRED';
                
                // Enable resend button
                resendButton.disabled = false;
                resendButton.classList.add('fw-bold');
            } else {
                timeRemaining--;
            }
        }

        updateTimer();
        const countdown = setInterval(updateTimer, 1000);
    });
</script>
@endsection