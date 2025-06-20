@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit User</h3>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role_id" class="form-control">
                <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>User</option>
                <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Admin</option>
            </select>
        </div>


        <button class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
