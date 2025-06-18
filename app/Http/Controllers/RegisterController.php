<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'admin_code' => 'nullable|string',
    ]);

    $adminSecret = config('ADMIN_SECRET');

    // Only assign 'admin' if a non-empty secret exists AND matches
    $role = ($adminSecret && $request->input('admin_code') === $adminSecret) ? 'admin' : 'user';

//     dd([
//     'provided_code' => $request->input('admin_code'),
//     'env_code' => env('ADMIN_SECRET'),
//     'match' => $request->input('admin_code') === env('ADMIN_SECRET')
// ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
             'role' => $role,
        ]);

        return redirect('/login')->with('success', 'Account created. Please log in.');
    }
}
