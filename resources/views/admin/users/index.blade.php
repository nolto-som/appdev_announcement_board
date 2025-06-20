@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>User Management</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 mb-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="form-control" />
    
    <select name="status" class="form-select">
        <option value="">All Statuses</option>
        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
        <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Suspended</option>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
</form>


    @if($users->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role_id) }}</td>
                        <td>
                            @if($user->status === 'suspended')
                                <span class="badge bg-warning text-dark">Suspended</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a>

                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">
                                        {{ $user->status === 'active' ? 'Suspend' : 'Reactivate' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info">No users found.</div>
    @endif
</div>
@endsection
