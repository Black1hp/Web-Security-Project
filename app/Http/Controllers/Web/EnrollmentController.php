<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        return view('web.enrollments.index');
    }

    public function create()
    {
        return view('web.enrollments.create');
    }

    public function store(Request $request)
    {
        // Store enrollment logic
        return redirect()->route('web.enrollments.index')->with('success', 'Enrollment created successfully');
    }

    public function show($id)
    {
        return view('web.enrollments.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.enrollments.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update enrollment logic
        return redirect()->route('web.enrollments.index')->with('success', 'Enrollment updated successfully');
    }

    public function destroy($id)
    {
        // Delete enrollment logic
        return redirect()->route('web.enrollments.index')->with('success', 'Enrollment deleted successfully');
    }
} 