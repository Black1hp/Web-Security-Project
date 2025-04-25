<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function index()
    {
        return view('web.professors.index');
    }

    public function create()
    {
        return view('web.professors.create');
    }

    public function store(Request $request)
    {
        // Store professor logic
        return redirect()->route('web.professors.index')->with('success', 'Professor created successfully');
    }

    public function show($id)
    {
        return view('web.professors.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.professors.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update professor logic
        return redirect()->route('web.professors.index')->with('success', 'Professor updated successfully');
    }

    public function destroy($id)
    {
        // Delete professor logic
        return redirect()->route('web.professors.index')->with('success', 'Professor deleted successfully');
    }
} 