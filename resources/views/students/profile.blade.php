@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
    <div class="d-flex justify-content-between mb-4">
        <h2>My Profile</h2>
        <div>
            <a href="{{ route('students.edit', Auth::id()) }}" class="btn btn-warning">Edit Profile</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $student->name }}</h5>
            <p class="card-text">
                <strong>ID:</strong> {{ $student->id }}<br>
                <strong>Email:</strong> {{ $student->email }}<br>
                <strong>Member since:</strong> {{ $student->created_at->format('F Y') }}
            </p>
        </div>
    </div>
@endsection
