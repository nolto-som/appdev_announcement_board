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

    return view('admin.users.index', compact('users'));
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

    $user->update($request->only(['name', 'email', 'role_id']));

    return redirect()->route('admin.users.index')->with('success', 'User updated.');
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
