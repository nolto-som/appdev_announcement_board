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

  public function login(Request $request)
{
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check status: if not active, log out and show error
        if ($user->status_id != 1) { // 1 = active
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is suspended.']);
        }

        // Redirect based on role_id
        switch ($user->role_id) {
            case 1:
                return redirect()->intended('/admin/announcements');
            case 2:
                return redirect()->intended('/');

            default:
                return redirect('/'); // fallback
        }
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
}

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}
