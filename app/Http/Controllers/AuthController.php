<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'satkerid' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:255',
            'usergroupid' => 'nullable|integer',
            'email' => 'required|email|max:255|unique:users,email',
            'pangkat' => 'nullable|string|max:50',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'fullname' => $validated['fullname'],
            'jabatan' => $validated['jabatan'] ?? '',
            'satkerid' => $validated['satkerid'] ?? '',
            'nip' => $validated['nip'] ?? '-',
            'usergroupid' => $validated['usergroupid'] ?? null,
            'email' => $validated['email'],
            'pangkat' => $validated['pangkat'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 