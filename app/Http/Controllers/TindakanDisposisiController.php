<?php

namespace App\Http\Controllers;

use App\Models\MasterTindakanDisposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\DataTables\MasterTindakanDisposisiDataTable;


class TindakanDisposisiController extends Controller
{
    public function index(Request $request, MasterTindakanDisposisiDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->ajax();
        }
        return $dataTable->render('tindakan_disposisi.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tindakan' => 'required|string|max:255',
            'satkerid' => 'required|string|max:255',
        ]);
        // Cek duplikat manual
        if (\App\Models\MasterTindakanDisposisi::where('tindakan', $request->tindakan)->where('satkerid', $request->satkerid)->exists()) {
            return response()->json(['success' => false, 'message' => 'Nama tindakan untuk satker ini sudah ada, tidak boleh duplikat.'], 422);
        }
        $data = $request->only(['tindakan','satkerid']);
        $data['id'] = (string) Str::ulid();
        $tindakan = MasterTindakanDisposisi::create($data);
        return response()->json(['success' => true, 'data' => $tindakan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|string|max:255',
            'satkerid' => 'required|string|max:255',
        ]);
        // Cek duplikat manual kecuali dirinya sendiri
        if (\App\Models\MasterTindakanDisposisi::where('tindakan', $request->tindakan)->where('satkerid', $request->satkerid)->where('id', '!=', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Nama tindakan untuk satker ini sudah ada, tidak boleh duplikat.'], 422);
        }
        $tindakan = MasterTindakanDisposisi::findOrFail($id);
        $tindakan->update($request->only(['tindakan','satkerid']));
        return response()->json(['success' => true, 'data' => $tindakan]);
    }

    public function destroy($id)
    {
        $tindakan = MasterTindakanDisposisi::findOrFail($id);
        $tindakan->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $tindakan = MasterTindakanDisposisi::findOrFail($id);
        return response()->json(['success' => true, 'data' => $tindakan]);
    }
} 