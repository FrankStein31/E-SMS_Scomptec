<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSatker;
use Illuminate\Support\Str;


class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $eselon = $request->input('eselon');

        $unitkerja = MasterSatker::when($eselon, function ($query, $eselon) {
            return $query->where('id', $eselon);
        })->get();

        $userGroups = MasterSatker::select('id', 'satker')
            ->whereNotNull('id')
            ->whereNotNull('satker')
            ->distinct()
            ->get();

        return view('unitkerja.index', compact('unitkerja', 'userGroups', 'eselon'));
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'satker' => 'required|string|max:255',
            'kodesatker' => 'nullable|string|max:100',
        ]);

        MasterSatker::create([
            'satkerid' => Str::uuid(), // atau pakai custom logic numerik
            'kodesatker' => $request->kodesatker,
            'satker' => $request->satker,
            'eselon' => 0, // default
            'userid' => auth()->user()->id,
        ]);

        return redirect()->route('unitkerja.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'satker' => 'required|string|max:255',
            'kodesatker' => 'nullable|string|max:100',
        ]);

        $data = MasterSatker::findOrFail($id);
        $data->update([
            'satker' => $request->satker,
            'kodesatker' => $request->kodesatker,
            // Tidak update 'satkerid', 'userid', atau 'eselon' kecuali memang perlu
        ]);

        return redirect()->route('unitkerja.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $unit = MasterSatker::findOrFail($id);
        $unit->forceDelete(); // Menghapus secara permanen dari database

        return redirect()->route('unitkerja.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
