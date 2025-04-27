<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['professor', 'students'])->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        // Get all users who can be professors (you can modify this based on your needs)
        $professors = User::all();
        return view('courses.create', compact('professors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'professor_id' => 'nullable|exists:users,id',
        ]);

        $course = Course::create($validated);

        // If a professor is assigned, create the relationship
        if ($request->professor_id) {
            $course->professor()->attach($request->professor_id, ['role_type' => 'professor']);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show($id)
    {
        $course = Course::with(['professor', 'students', 'grades'])->findOrFail($id);
        return view('courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $professors = User::all();
        return view('courses.edit', compact('course', 'professors'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code,' . $id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'professor_id' => 'nullable|exists:users,id',
        ]);

        $course->update($validated);

        // Update professor relationship
        if ($request->professor_id) {
            $course->professor()->sync([$request->professor_id => ['role_type' => 'professor']]);
        } else {
            $course->professor()->detach();
        }

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        // Detach all relationships before deleting
        $course->professor()->detach();
        $course->students()->detach();
        
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    public function students($id)
    {
        $course = Course::with(['students' => function($query) {
            $query->with('grades');
        }])->findOrFail($id);
        
        return view('courses.students', compact('course'));
    }

    public function grades($id)
    {
        $course = Course::with(['students' => function($query) {
            $query->with(['grades' => function($query) {
                $query->where('course_id', $id);
            }]);
        }])->findOrFail($id);
        
        return view('courses.grades', compact('course'));
    }
} 