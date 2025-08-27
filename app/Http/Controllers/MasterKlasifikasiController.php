<?php

namespace App\Http\Controllers;

use App\DataTables\MasterKlasifikasiDataTable;
use App\Models\MasterKlasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterKlasifikasiController extends Controller
{
    public function index(Request $request, MasterKlasifikasiDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->ajax();
        }

        $kodeUtama = MasterKlasifikasi::select('kodeklasifikasi', 'klasifikasi')
            ->orderBy('kodeklasifikasi')
            ->get()
            ->groupBy(function($item) {
                return substr($item->kodeklasifikasi, 0, 3);
            })
            ->map(function($group) {
                return $group->first();
            });

        return $dataTable->render('klasifikasi.index', compact('kodeUtama'));
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

        if (MasterKlasifikasi::where('kodeklasifikasi', $request->kodeklasifikasi)->exists()) {

            session()->flash('danger', 'Kode klasifikasi sudah ada, tidak boleh duplikat.');
            return response()->json([
                'success' => false,
                'message' => 'Kode klasifikasi sudah ada, tidak boleh duplikat.',
                'html' => view('layout.alert')->render()
            ], 422);
        }

        $data = $request->except(['_token','id','_method']);
        $data['id'] = (string) Str::ulid();
        $klasifikasi = MasterKlasifikasi::create($data);
        session()->flash('success', 'Klasifikasi berhasil ditambahkan.');

        return response()->json([
            'success' => true,
            'message' => 'Klasifikasi berhasil ditambahkan.',
            'html' => view('layout.alert')->render(),
            'data' => $klasifikasi
        ]);
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        $kodeBaru = $request->kodeklasifikasi;
        $kodeLama = $klasifikasi->kodeklasifikasi;
        $adaChild = MasterKlasifikasi::where('kodeklasifikasi', 'like', $kodeLama . '.%')->exists();

        if ($kodeBaru !== $kodeLama && $adaChild) {
            session()->flash('danger', 'Tidak bisa mengubah kode klasifikasi parent yang punya child.');
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mengubah kode klasifikasi parent yang punya child.',
                'html' => view('layout.alert')->render()
            ], 422);
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

        if (MasterKlasifikasi::where('kodeklasifikasi', $request->kodeklasifikasi)->where('id', '!=', $id)->exists()) {
            session()->flash('danger', 'Kode klasifikasi sudah ada, tidak boleh duplikat.');
            return response()->json([
                'success' => false,
                'message' => 'Kode klasifikasi sudah ada, tidak boleh duplikat.',
                'html' => view('layout.alert')->render()
            ], 422);
        }

        $klasifikasi->update($request->except(['_token','id','_method']));

        session()->flash('success', 'Klasifikasi berhasil diubah.');

        return response()->json([
            'success' => true,
            'message' => 'Klasifikasi berhasil diubah.',
            'html' => view('layout.alert')->render(),
            'data' => $klasifikasi
        ]);
    }

    public function destroy($id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);

        $kode = $klasifikasi->kodeklasifikasi;
        $adaChild = MasterKlasifikasi::where('kodeklasifikasi', 'like', $kode . '.%')->exists();

        if ($adaChild) {
            session()->flash('danger', 'Tidak bisa menghapus parent yang masih punya child. Hapus child-nya dulu.');
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus parent yang masih punya child. Hapus child-nya dulu.',
                'html' => view('layout.alert')->render()
            ], 422);
        }

        $klasifikasi->delete();

        session()->flash('success', 'Klasifikasi berhasil dihapus.');

        return response()->json([
            'success' => true,
            'message' => 'Klasifikasi berhasil dihapus.',
            'html' => view('layout.alert')->render()
        ]);
    }

    public function show($id)
    {
        $klasifikasi = MasterKlasifikasi::findOrFail($id);
        return response()->json(['success' => true, 'data' => $klasifikasi]);
    }
}
