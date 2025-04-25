<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionOfficerController extends Controller
{
    public function index()
    {
        return view('web.admission-officers.index');
    }

    public function create()
    {
        return view('web.admission-officers.create');
    }

    public function store(Request $request)
    {
        // Store admission officer logic
        return redirect()->route('web.admission-officers.index')->with('success', 'Admission Officer created successfully');
    }

    public function show($id)
    {
        return view('web.admission-officers.show', compact('id'));
    }

    public function edit($id)
    {
        return view('web.admission-officers.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update admission officer logic
        return redirect()->route('web.admission-officers.index')->with('success', 'Admission Officer updated successfully');
    }

    public function destroy($id)
    {
        // Delete admission officer logic
        return redirect()->route('web.admission-officers.index')->with('success', 'Admission Officer deleted successfully');
    }
} 