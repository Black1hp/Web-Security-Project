<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        return view('web.grades.index');
    }

    public function create()
    {
        return view('web.grades.create');
    }

    public function store(Request $request)
    {
        // Store grade logic
        return redirect()->route('web.grades.index')->with('success', 'Grade created successfully');
    }

    public function show($id)
    {
        return view('web.grades.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.grades.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update grade logic
        return redirect()->route('web.grades.index')->with('success', 'Grade updated successfully');
    }

    public function destroy($id)
    {
        // Delete grade logic
        return redirect()->route('web.grades.index')->with('success', 'Grade deleted successfully');
    }
} 