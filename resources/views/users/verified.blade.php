@extends('layouts.master')
@section('content')
<div class="container">
    <div class="alert alert-success mt-5">
        <h3>Email Verified</h3>
        <p>Thank you, {{ $user->name }}, your email has been verified successfully!</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
    </div>
</div>
@endsection
