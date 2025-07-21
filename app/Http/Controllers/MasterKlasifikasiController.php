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
        })->orderBy('kodeklasifikasi')->get();

        // Ambil kode utama unik dan label pertama sesuai urutan data
        $kodeUtama = MasterKlasifikasi::select('kodeklasifikasi', 'klasifikasi')
            ->orderBy('kodeklasifikasi')
            ->get()
            ->groupBy(function($item) {
                return substr($item->kodeklasifikasi, 0, 3);
            })
            ->map(function($group) {
                return $group->first();
            });

        return view('klasifikasi.index', compact('data', 'q', 'kodeUtama'));
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
        // Cek duplikat manual biar bisa kasih respon custom
        if (MasterKlasifikasi::where('kodeklasifikasi', $request->kodeklasifikasi)->exists()) {
            return response()->json(['success' => false, 'message' => 'Kode klasifikasi sudah ada, tidak boleh duplikat.'], 422);
        }
        $data = $request->except(['_token','id','_method']);
        $data['id'] = (string) Str::ulid();
        $klasifikasi = MasterKlasifikasi::create($data);
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);

        // Cek jika kodeklasifikasi diubah dan sudah ada child, tolak
        $kodeBaru = $request->kodeklasifikasi;
        $kodeLama = $klasifikasi->kodeklasifikasi;
        $adaChild = MasterKlasifikasi::where('kodeklasifikasi', 'like', $kodeLama . '.%')->exists();
        if ($kodeBaru !== $kodeLama && $adaChild) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa mengubah kode klasifikasi parent yang punya child.'], 422);
        }

        $request->validate([
            'kodeklasifikasi' => 'required|string|max:255',
            'klasifikasi' => 'required|string',
            'retensi_aktif' => 'required|integer',
            'retensi_inaktif' => 'required|integer',
            'keterangan' => 'required|in:1,2,3',
            'retensi' => 'nullable|integer',
            'parent' => 'nullable|string',
        ]);
        // Cek duplikat manual kecuali untuk dirinya sendiri
        if (MasterKlasifikasi::where('kodeklasifikasi', $request->kodeklasifikasi)->where('id', '!=', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Kode klasifikasi sudah ada, tidak boleh duplikat.'], 422);
        }
        $klasifikasi->update($request->except(['_token','id','_method']));
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }

    public function destroy($id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        // Cek apakah ada child
        $kode = $klasifikasi->kodeklasifikasi;
        $adaChild = MasterKlasifikasi::where('kodeklasifikasi', 'like', $kode . '.%')->exists();
        if ($adaChild) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus parent yang masih punya child. Hapus child-nya dulu.'], 422);
        }
        $klasifikasi->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }
}
