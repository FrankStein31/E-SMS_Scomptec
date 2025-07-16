<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntriSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('entrisurat.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entrisurat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate and store the entri surat data
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            // Add other validation rules as necessary
        ]);

        // Logic to save the entri surat data

        return redirect()->route('entrisurat.index')->with('success', 'Entri Surat created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
