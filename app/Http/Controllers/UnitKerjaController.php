<?php

namespace App\Http\Controllers;

use App\DataTables\MasterSatkerDataTable;
use Illuminate\Http\Request;
use App\Models\MasterSatker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use function Termwind\render;

class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, MasterSatkerDataTable $dataTable)
    
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

        return $dataTable->render('unitkerja.index', compact('unitkerja', 'userGroups', 'eselon'));
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'satker' => 'required|string|max:255',
            'kodesatker' => 'required|string|max:100|unique:master_satkers,kodesatker', // ganti nama tabel
        ]);

        MasterSatker::create([
            'satkerid' => \Illuminate\Support\Str::uuid(),
            'kodesatker' => $request->kodesatker,
            'satker' => $request->satker,
            'eselon' => 0,
            'userid' => Auth::id(),
        ]);

        return redirect()->route('unitkerja.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unit = MasterSatker::findOrFail($id);

        // Cek apakah ada child (kodesatker yang diawali parent dan lebih panjang)
        $childCount = MasterSatker::where('kodesatker', 'like', $unit->kodesatker . '%')
            ->whereRaw('LENGTH(kodesatker) > ?', [strlen($unit->kodesatker)])
            ->count();

        if ($childCount > 0) {
            return redirect()->route('unitkerja.index')->with('error', 'Tidak bisa mengubah unit kerja induk yang masih punya anak. Ubah anaknya dulu.');
        }

        // Cek 5 data induk pertama (opsional)
        $parentIds = MasterSatker::orderBy('created_at')->take(5)->pluck('id')->toArray();
        if (in_array($id, $parentIds)) {
            return redirect()->route('unitkerja.index')->with('error', 'Data induk utama tidak boleh diubah.');
        }

        $request->validate([
            'satker' => 'required|string|max:255',
            'kodesatker' => 'required|string|max:100|unique:master_satkers,kodesatker,' . $id,
        ]);

        $unit->update([
            'satker' => $request->satker,
            'kodesatker' => $request->kodesatker,
        ]);

        return redirect()->route('unitkerja.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $unit = MasterSatker::findOrFail($id);

        // Cek apakah ada child (kodesatker yang diawali parent dan lebih panjang)
        $childCount = MasterSatker::where('kodesatker', 'like', $unit->kodesatker . '%')
            ->whereRaw('LENGTH(kodesatker) > ?', [strlen($unit->kodesatker)])
            ->count();

        if ($childCount > 0) {
            return redirect()->route('unitkerja.index')->with('error', 'Tidak bisa menghapus unit kerja induk yang masih punya anak. Hapus anaknya dulu.');
        }

        // Cek 5 data induk pertama (opsional, jika tetap ingin proteksi)
        $parentIds = MasterSatker::orderBy('created_at')->take(5)->pluck('id')->toArray();
        if (in_array($id, $parentIds)) {
            return redirect()->route('unitkerja.index')->with('error', 'Data induk utama tidak boleh dihapus.');
        }

        $unit->forceDelete();

        return redirect()->route('unitkerja.index')->with('success', 'Unit kerja berhasil dihapus.');
    }

    /**
     * Get detail data untuk modal (anak dan user)
     */
    public function getDetailData($id)
    {
        try {
            $unit = MasterSatker::findOrFail($id);

            // Ambil data anak
            $children = MasterSatker::where('kodesatker', 'like', $unit->kodesatker . '%')
                ->whereRaw('LENGTH(kodesatker) > ?', [strlen($unit->kodesatker)])
                ->select('id', 'satker', 'kodesatker')
                ->orderBy('kodesatker')
                ->get();

            // Ambil data user
            $users = \App\Models\User::where('satkerid', $unit->satkerid)
                ->select('id', 'fullname', 'nip', 'jabatan', 'pangkat')
                ->orderBy('fullname')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'unit_name' => $unit->satker,
                    'unit_code' => $unit->kodesatker,
                    'children' => $children,
                    'users' => $users
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data detail'
            ], 500);
        }
    }
}
