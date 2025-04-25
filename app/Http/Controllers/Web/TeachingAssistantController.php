<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeachingAssistantController extends Controller
{
    public function index()
    {
        return view('web.teaching-assistants.index');
    }

    public function create()
    {
        return view('web.teaching-assistants.create');
    }

    public function store(Request $request)
    {
        // Store teaching assistant logic
        return redirect()->route('web.teaching-assistants.index')->with('success', 'Teaching Assistant created successfully');
    }

    public function show($id)
    {
        return view('web.teaching-assistants.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.teaching-assistants.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update teaching assistant logic
        return redirect()->route('web.teaching-assistants.index')->with('success', 'Teaching Assistant updated successfully');
    }

    public function destroy($id)
    {
        // Delete teaching assistant logic
        return redirect()->route('web.teaching-assistants.index')->with('success', 'Teaching Assistant deleted successfully');
    }
} 