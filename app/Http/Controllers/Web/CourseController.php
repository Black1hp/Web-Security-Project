<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('web.courses.index');
    }

    public function create()
    {
        return view('web.courses.create');
    }

    public function store(Request $request)
    {
        // Store course logic
        return redirect()->route('web.courses.index')->with('success', 'Course created successfully');
    }

    public function show($id)
    {
        return view('web.courses.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.courses.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update course logic
        return redirect()->route('web.courses.index')->with('success', 'Course updated successfully');
    }

    public function destroy($id)
    {
        // Delete course logic
        return redirect()->route('web.courses.index')->with('success', 'Course deleted successfully');
    }
} 