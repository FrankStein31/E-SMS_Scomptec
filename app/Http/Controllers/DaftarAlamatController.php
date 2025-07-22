<?php

namespace App\Http\Controllers;

use App\Models\MasterInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\DataTables\MasterInstansiDataTable;
class DaftarAlamatController extends Controller
{
    public function index(Request $request, MasterInstansiDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->ajax();
        }
        return $dataTable->render('daftar_alamat.index');
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
        // Cek duplikat manual
        if (\App\Models\MasterInstansi::where('instansi', $request->instansi)->exists()) {
            return response()->json(['success' => false, 'message' => 'Nama instansi sudah ada, tidak boleh duplikat.'], 422);
        }
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
        // Cek duplikat manual kecuali dirinya sendiri
        if (\App\Models\MasterInstansi::where('instansi', $request->instansi)->where('id', '!=', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Nama instansi sudah ada, tidak boleh duplikat.'], 422);
        }
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