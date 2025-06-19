@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>User Management</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name or email">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="row">
        @forelse ($users as $user)
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $user->name }} 
                            @if($user->status === 'suspended')
                                <span class="badge bg-warning text-dark">Suspended</span>
                            @endif
                        </h5>
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-2"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>

                        <div class="d-flex gap-2 justify-content-end">
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
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No users found.</div>
            </div>
        @endforelse
    </div>

    <<div class="d-flex justify-content-center mt-4">
        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
