@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Course</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="code">Course Code</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $course->code) }}" required>
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="title">Course Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $course->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="credits">Credits</label>
                            <input type="number" class="form-control @error('credits') is-invalid @enderror" id="credits" name="credits" value="{{ old('credits', $course->credits) }}" required>
                            @error('credits')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="professor_id">Professor</label>
                            <select class="form-control @error('professor_id') is-invalid @enderror" id="professor_id" name="professor_id">
                                <option value="">Select Professor</option>
                                @foreach($professors as $professor)
                                    <option value="{{ $professor->id }}" {{ old('professor_id', $course->professor_id) == $professor->id ? 'selected' : '' }}>
                                        {{ $professor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professor_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="schedule">Schedule</label>
                            <input type="text" class="form-control @error('schedule') is-invalid @enderror" id="schedule" name="schedule" value="{{ old('schedule', $course->schedule) }}">
                            @error('schedule')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Course</button>
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 