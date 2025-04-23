@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Verify Your Email Address</h2>
                </div>
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <p class="mb-4">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on the 
                        link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                    </p>
                    
                    <div class="mt-4 d-flex align-items-center justify-content-between">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Resend Verification Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
