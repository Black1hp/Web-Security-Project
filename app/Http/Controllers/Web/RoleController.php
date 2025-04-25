<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('web.roles.index');
    }

    public function create()
    {
        return view('web.roles.create');
    }

    public function store(Request $request)
    {
        // Store role logic
        return redirect()->route('web.roles.index')->with('success', 'Role created successfully');
    }

    public function show($id)
    {
        return view('web.roles.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.roles.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update role logic
        return redirect()->route('web.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        // Delete role logic
        return redirect()->route('web.roles.index')->with('success', 'Role deleted successfully');
    }
} 