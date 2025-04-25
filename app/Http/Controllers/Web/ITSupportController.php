<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ITSupportController extends Controller
{
    public function index()
    {
        return view('web.it-support.index');
    }

    public function create()
    {
        return view('web.it-support.create');
    }

    public function store(Request $request)
    {
        // Store IT support logic
        return redirect()->route('web.it-support.index')->with('success', 'IT Support created successfully');
    }

    public function show($id)
    {
        return view('web.it-support.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.it-support.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update IT support logic
        return redirect()->route('web.it-support.index')->with('success', 'IT Support updated successfully');
    }

    public function destroy($id)
    {
        // Delete IT support logic
        return redirect()->route('web.it-support.index')->with('success', 'IT Support deleted successfully');
    }
} 