<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\MasterSatker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UsersDataTable $dataTable)
    {
        if ($request->ajax() && $request->id != null) {
                $userById = User::with('masterSatker')->findOrFail($request->id);
                return response()->json($userById);
        }
        $userGroups = ['Administrator', 'Pribadi', 'Eksekutif', 'TU Persuratan', 'TU Satker'];
        $masterSatkers = MasterSatker::get();
        return $dataTable->render('user.index', compact('userGroups', 'masterSatkers'));
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
        $user->satkerid   = $validated['satkerid'];
        $user->email      = $validated['email'] ?? null;
        $user->save();
        return response()->json(['success' => true, 'data' => $user]);
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
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'fullname' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'pangkat' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'satkerid' => 'required|exists:master_satkers,id',
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
        ]);
        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->fullname = $request->fullname;
        $user->nip = $request->nip;
        $user->pangkat = $request->pangkat;
        $user->jabatan = $request->jabatan;
        $user->satkerid = $request->satkerid;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json(['success' => true, 'data' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            $user->delete(); // Jika menggunakan SoftDeletes, ini hanya akan menandai sebagai terhapus
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
