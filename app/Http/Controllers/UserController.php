<?php

namespace App\Http\Controllers;

use App\Models\MasterSatker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $group = $request->input('group');

        $userGroups = ['Administrator', 'Pribadi', 'Eksekutif', 'TU Persuratan', 'TU Satker'];

        $user = User::when($group, function ($query, $group) {
            return $query->where('jabatan', $group);
        })->get();

        $masterSatkers = MasterSatker::all(); // ambil semua satker

        return view('user.index', compact('user', 'userGroups', 'group', 'masterSatkers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'   => 'required|string|unique:users,username',
            'password'   => 'required|string|min:6',
            'fullname'   => 'required|string',
            'nip'        => 'nullable|string',
            'pangkat'    => 'nullable|string',
            'jabatan'    => 'nullable|string',
            'satkerid'   => 'required|exists:master_satkers,id',
            'email'      => 'nullable|email|unique:users,email',
        ]);

        $user = new User();
        $user->username   = $validated['username'];
        $user->password   = Hash::make($validated['password']);
        $user->fullname   = $validated['fullname'];
        $user->nip        = $validated['nip'] ?? null;
        $user->pangkat    = $validated['pangkat'] ?? null;
        $user->jabatan    = $validated['jabatan'] ?? null;
        $user->satkerid   = $validated['satkerid']; // foreign key to master_satkers
        $user->email      = $validated['email'] ?? null;

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'pangkat' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'satkerid' => 'required|exists:master_satkers,id',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        $user->username = $request->username;
        $user->fullname = $request->fullname;
        $user->nip = $request->nip;
        $user->pangkat = $request->pangkat;
        $user->jabatan = $request->jabatan;
        $user->satkerid = $request->satkerid;
        $user->email = $request->email;

        // Jika field password diisi, maka update password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            $user->delete(); // Jika menggunakan SoftDeletes, ini hanya akan menandai sebagai terhapus
            return redirect()->back()->with('success', 'Data pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
