<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(Request $request)
{
    $query = User::where('role', '!=', 'admin'); // ⬅ this is now the base

    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    if ($status = $request->input('status')) {
        $query->where('status', $status);
    }

    $users = $query->paginate(10);

    return view('admin.users.index', compact('users'));
}


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'role' => 'required|in:user,admin',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

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
    $user->status = $user->status === 'active' ? 'suspended' : 'active';
    $user->save();

    return back()->with('success', 'User status updated.');
    }

}
