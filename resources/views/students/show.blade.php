@extends('layouts.master')

@section('title', 'Student Details')

@section('content')
    <div class="d-flex justify-content-between mb-4">
        <h2>Student Details</h2>
        <div>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $student->name }}</h5>
            <p class="card-text">
                <strong>ID:</strong> {{ $student->id }}<br>
                <strong>Email:</strong> {{ $student->email }}<br>
                
            </p>
        </div>
    </div>
@endsection
