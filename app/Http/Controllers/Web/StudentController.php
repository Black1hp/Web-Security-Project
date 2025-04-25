<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('web.students.index');
    }

    public function create()
    {
        return view('web.students.create');
    }

    public function store(Request $request)
    {
        // Store student logic
        return redirect()->route('web.students.index')->with('success', 'Student created successfully');
    }

    public function show($id)
    {
        return view('web.students.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.students.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update student logic
        return redirect()->route('web.students.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        // Delete student logic
        return redirect()->route('web.students.index')->with('success', 'Student deleted successfully');
    }
} 