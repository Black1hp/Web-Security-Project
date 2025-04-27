@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Course Details</h2>
                    <div>
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Course Code:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->code }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Title:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->title }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->description ?? 'No description available' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Credits:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->credits }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Professor:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->professor->name ?? 'Not assigned' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Schedule:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->schedule ?? 'No schedule available' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Enrolled Students:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $course->students->count() }} students
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to Courses</a>
                        <a href="{{ route('courses.students', $course->id) }}" class="btn btn-info">View Students</a>
                        <a href="{{ route('courses.grades', $course->id) }}" class="btn btn-success">View Grades</a>
                        <a href="{{ route('courses.schedule', $course->id) }}" class="btn btn-primary">View Schedule</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 