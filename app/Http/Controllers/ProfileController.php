<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withInput()
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->fullname = $validated['fullname'];
        $user->username = $validated['username'];
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile berhasil diupdate');
    }
} 