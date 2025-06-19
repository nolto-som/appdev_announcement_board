<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
     public function showLoginForm() {
        return view('auth.login');
    }

  public function login(Request $request) {
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        'status' => 'active',
    ];

    if (Auth::attempt($credentials)) {
        return redirect()->intended('/');
    }

    // Check if user exists but is suspended
    $user = User::where('email', $request->email)->first();
    if ($user && $user->status !== 'active') {
        return back()->withErrors(['email' => 'Your account is suspended.']);
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
}


    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}
