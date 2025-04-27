@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Courses</h2>
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">Add New Course</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Credits</th>
                                    <th>Professor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                <tr>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->credits }}</td>
                                    <td>{{ $course->professor->name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 