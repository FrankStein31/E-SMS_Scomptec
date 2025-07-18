<?php

namespace App\Http\Controllers;

use App\Models\MasterInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DaftarAlamatController extends Controller
{
    public function index(Request $request)
    {
        $data = MasterInstansi::orderBy('instansi')->get();
        return view('daftar_alamat.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instansi' => 'required|string|max:255',
            'kepala' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'telp' => 'required|string|max:255',
        ]);
        $data = $request->only(['instansi','kepala','alamat','kota','telp']);
        $data['id'] = (string) Str::ulid();
        $alamat = MasterInstansi::create($data);
        return response()->json(['success' => true, 'data' => $alamat]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'instansi' => 'required|string|max:255',
            'kepala' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'telp' => 'required|string|max:255',
        ]);
        $alamat = MasterInstansi::findOrFail($id);
        $alamat->update($request->only(['instansi','kepala','alamat','kota','telp']));
        return response()->json(['success' => true, 'data' => $alamat]);
    }

    public function destroy($id)
    {
        $alamat = MasterInstansi::findOrFail($id);
        $alamat->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $alamat = MasterInstansi::findOrFail($id);
        return response()->json(['success' => true, 'data' => $alamat]);
    }
} 