<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('web.users.index');
    }

    public function create()
    {
        return view('web.users.create');
    }

    public function store(Request $request)
    {
        // Store user logic
        return redirect()->route('web.users.index')->with('success', 'User created successfully');
    }

    public function show($id)
    {
        return view('web.users.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.users.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update user logic
        return redirect()->route('web.users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        // Delete user logic
        return redirect()->route('web.users.index')->with('success', 'User deleted successfully');
    }
} 