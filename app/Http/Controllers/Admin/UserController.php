<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(Request $request)
{
    // Exclude admin (role_id = 1)
    $query = User::where('role_id', '!=', 1);

    // Search filter
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    // Status filter (using status_id)
    if ($status = $request->input('status')) {
        $query->where('status_id', $status);
    }

    $users = $query->paginate(10);

     // Get promoted admins by current admin
    $promotedAdmins = User::where('role_id', 1)
        ->where('promoted_by', auth()->id())
        ->get();

    return view('admin.users.index', compact('users', 'promotedAdmins'));
}


   public function edit($id)
{
    $user = User::findOrFail($id);
    $roles = Role::all();
    return view('admin.users.edit', compact('user', 'roles'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'role_id' => 'required|exists:roles,id',
    ]);

    // Track original role before update
    $originalRoleId = $user->role_id;

    // Update basic fields
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role_id = $request->role_id;

    // If role is being changed to admin (1) and wasn't admin before
    if ($request->role_id == 1 && $originalRoleId != 1) {
        $user->promoted_by = auth()->id(); // set who promoted the user
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'User updated.');
}

public function promotedAdmins()
{
    $admins = User::where('role_id', 1)
                  ->whereNotNull('promoted_by')
                  ->where('promoted_by', auth()->id())
                  ->get();

    return view('admin.users.promoted_admins', compact('admins'));
}

public function demote($id)
{
    $user = User::findOrFail($id);

    if ($user->role_id === 1 && $user->promoted_by === auth()->id()) {
        $user->role_id = 2; // Back to regular user
        $user->promoted_by = null;
        $user->save();

        return back()->with('success', 'Admin demoted successfully.');
    }

    return back()->with('error', 'You are not authorized to demote this admin.');
}



    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    // Toggle between active (1) and suspended (3)
    $user->status_id = $user->status_id == 1 ? 3 : 1;
    $user->save();

    return back()->with('success', 'User status updated.');
}


}
