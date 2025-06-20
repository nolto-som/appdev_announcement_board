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

    // Determine role_id: 1 for admin, 2 for user
    $roleId = ($adminSecret && $request->input('admin_code') === $adminSecret) ? 1 : 2;

    // Always assign status_id 1 (active)
    $statusId = 1;

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $roleId,
        'status_id' => $statusId,
    ]);

    return redirect('/login')->with('success', 'Account created. Please log in.');
}
}