@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- WIDENED COLUMN for maximized modal look --}}
        <div class="col-md-12 col-lg-10"> 
            <div class="card shadow-lg">
                {{-- Header is now fully transparent with light text via global CSS --}}
                <div class="card-header text-white text-center">
                    <h4 class="m-0"><i class="fas fa-shield-alt me-2"></i> {{ __('Two Factor Verification') }}</h4>
                </div>

                {{-- card-body text is set to light color by global CSS (.card-body) --}}
                <div class="card-body p-4"> 
                    
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{ session()->get('message') }}</div>
                    @endif
                    
                    @if($errors->has('msg'))
                        <div class="alert alert-warning">{{ $errors->first('msg') }}</div>
                    @endif

                    <p class="text-center mb-3">
                        We sent a 6-digit code to your email. Please enter it below to continue.
                    </p>
                    
                    {{-- TIMER DISPLAY --}}
                    <div class="alert text-center py-2 mb-4" style="background-color: rgba(255, 255, 255, 0.15); border-color: var(--color-sienna);">
                        <strong class="text-white">Code expires in: </strong>
                        <span id="otp-timer" class="fw-bold text-danger">10:00</span>
                    </div>

                    <form method="POST" action="{{ route('verify.store') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <label for="two_factor_code" class="col-md-4 col-form-label text-md-end">{{ __('OTP Code') }}</label>

                            <div class="col-md-6">
                                <input id="two_factor_code" name="two_factor_code" type="number" 
                                    class="form-control form-control-lg text-center letter-spacing-2 @error('two_factor_code') is-invalid @enderror" 
                                    required autofocus maxlength="6" style="letter-spacing: 5px; font-weight: bold;">
                                
                                @if($errors->has('two_factor_code'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('two_factor_code') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary btn-lg">Verify Login</button>
                                
                                <div class="mt-3">
                                    {{-- Resend Code Button --}}
                                    <button type="button" id="resend-button" class="btn btn-link" disabled>
                                        Resend Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                
                // Enable resend button and change its color to alert user
                resendButton.disabled = false;
                resendButton.classList.remove('text-primary');
                resendButton.classList.add('text-danger', 'fw-bold');
            } else {
                timeRemaining--;
            }
        }
        
        // Setup Resend Form Submission
        resendButton.addEventListener('click', function() {
            if (!resendButton.disabled) {
                // Submit the form defined in the original twoFactor.blade.php logic
                fetch('{{ route('verify.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // CSRF Token is critical for Laravel security
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                }).then(() => {
                    alert('New code sent! Please wait 10 minutes before resending again.');
                    // Reset timer after successful resend (or attempt)
                    timeRemaining = 600;
                    resendButton.disabled = true;
                    resendButton.classList.add('text-primary');
                    resendButton.classList.remove('text-danger', 'fw-bold');
                });
            }
        });

        // Initialize and start the timer
        updateTimer();
        const countdown = setInterval(updateTimer, 1000);
    });
</script>
@endsection