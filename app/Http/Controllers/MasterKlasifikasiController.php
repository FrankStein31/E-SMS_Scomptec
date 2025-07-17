<?php

namespace App\Http\Controllers;

use App\Models\MasterKlasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterKlasifikasiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $data = MasterKlasifikasi::when($q, function($query) use ($q) {
            $query->where('kodeklasifikasi', 'like', "%$q%")
                  ->orWhere('klasifikasi', 'like', "%$q%");
        })->orderBy('kodeklasifikasi')->paginate(10);
        return view('klasifikasi.index', compact('data', 'q'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodeklasifikasi' => 'required|string|max:255',
            'klasifikasi' => 'required|string',
            'retensi_aktif' => 'required|integer',
            'retensi_inaktif' => 'required|integer',
            'keterangan' => 'required|in:1,2,3',
            'retensi' => 'nullable|integer',
            'parent' => 'nullable|string',
        ]);
        $data = $request->except(['_token','id','_method']);
        $data['id'] = (string) Str::ulid();
        $klasifikasi = MasterKlasifikasi::create($data);
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kodeklasifikasi' => 'required|string|max:255',
            'klasifikasi' => 'required|string',
            'retensi_aktif' => 'required|integer',
            'retensi_inaktif' => 'required|integer',
            'keterangan' => 'required|in:1,2,3',
            'retensi' => 'nullable|integer',
            'parent' => 'nullable|string',
        ]);
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        $klasifikasi->update($request->except(['_token','id','_method']));
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }

    public function destroy($id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        $klasifikasi->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }
}
