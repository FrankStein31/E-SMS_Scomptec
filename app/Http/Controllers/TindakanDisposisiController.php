<?php

namespace App\Http\Controllers;

use App\Models\MasterTindakanDisposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TindakanDisposisiController extends Controller
{
    public function index(Request $request)
    {
        $data = MasterTindakanDisposisi::orderBy('tindakan')->get();
        return view('tindakan_disposisi.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tindakan' => 'required|string|max:255',
            'satkerid' => 'required|string|max:255',
        ]);
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