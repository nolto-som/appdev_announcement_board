<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function notifications() {
        return view('user.notifications');
    }

    public function messages() {
        return view('user.messages');
    }

    public function inbox() {
        return view('user.inbox');
    }

    public function editProfile() {
        return view('user.edit-profile');
    }

    public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . auth()->id(),
    ]);

    $user = auth()->user();
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return redirect()->route('edit.profile')->with('success', 'Profile updated successfully!');
}
}
