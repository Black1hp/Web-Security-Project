@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary">Email Verification</h1>
                <p class="text-muted">One more step to secure your account</p>
            </div>
            
            <div class="card shadow border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-gradient-primary-to-secondary text-white text-center py-4">
                    <h2 class="h4 mb-0">Verify Your Email Address</h2>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                        </div>
                    @endif
                    
                    @if (session('info'))
                        <div class="alert alert-info mb-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="text-center mb-4">
                        <div class="verification-icon mb-3">
                            <i class="fas fa-envelope-open-text fa-3x text-primary"></i>
                        </div>
                        <p>
                            We've sent a verification code to your email address. Please enter the 6-digit code below to verify your account.
                        </p>
                    </div>
                    
                    <form method="POST" action="{{ route('verification.verify') }}" class="mb-4">
                        @csrf
                        <div class="verification-code-container mb-4">
                            <label for="verification_code" class="form-label fw-bold mb-3">Verification Code</label>
                            <div class="code-input-wrapper text-center">
                                <input type="text" name="code" id="verification_code" 
                                       class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                       placeholder="Enter 6-digit code" maxlength="6" required autocomplete="off"
                                       style="letter-spacing: 8px; font-size: 24px; font-weight: bold;">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Verify Email
                            </button>
                        </div>
                    </form>
                    
                    <div class="resend-container text-center">
                        <p class="text-muted mb-3">Didn't receive the code?</p>
                        <div class="d-flex justify-content-center align-items-center">
                            <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary" id="resend-button">
                                    <i class="fas fa-redo-alt me-2"></i> Resend Code
                                </button>
                            </form>
                            <span id="countdown" class="text-muted ms-3 d-none">
                                <i class="fas fa-clock me-1"></i> Available in <span id="timer" class="fw-bold">60</span>s
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">
                    If you're having trouble, please contact <a href="mailto:support@eut.edu">support@eut.edu</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary-to-secondary {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    }
    
    .verification-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .code-input-wrapper {
        max-width: 300px;
        margin: 0 auto;
    }
    
    #verification_code:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #86b7fe;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resendForm = document.getElementById('resend-form');
        const resendButton = document.getElementById('resend-button');
        const countdown = document.getElementById('countdown');
        const timer = document.getElementById('timer');
        
        // Check if there's a stored timestamp
        const lastResendTime = localStorage.getItem('lastResendTime');
        if (lastResendTime) {
            const elapsedSeconds = Math.floor((Date.now() - parseInt(lastResendTime)) / 1000);
            const remainingSeconds = 60 - elapsedSeconds;
            
            if (remainingSeconds > 0) {
                startCountdown(remainingSeconds);
            }
        }
        
        resendForm.addEventListener('submit', function(e) {
            localStorage.setItem('lastResendTime', Date.now());
            startCountdown(60);
        });
        
        function startCountdown(seconds) {
            resendButton.disabled = true;
            countdown.classList.remove('d-none');
            
            let timeLeft = seconds;
            timer.textContent = timeLeft;
            
            const interval = setInterval(function() {
                timeLeft--;
                timer.textContent = timeLeft;
                
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    resendButton.disabled = false;
                    countdown.classList.add('d-none');
                }
            }, 1000);
        }
        
        // Auto-focus the verification code input
        const codeInput = document.getElementById('verification_code');
        if (codeInput) {
            codeInput.focus();
        }
    });
</script>
@endpush
@endsection
