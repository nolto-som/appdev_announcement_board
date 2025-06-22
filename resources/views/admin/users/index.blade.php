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

    @if($promotedAdmins->count())
        <button type="button" id="togglePromotedAdmins" class="btn btn-secondary">Show Promoted Admins</button>
    @endif
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
                        <td>{{ ucfirst($user->role->name ?? 'Unknown') }}</td>
                        <td>
                            @if($user->status_id == 3)

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
                                        {{ $user->status_id == 1 ? 'Suspend' : 'Reactivate' }}
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

@if($promotedAdmins->count())
<div id="promotedAdminsTable" class="mt-4 d-none">
    <h4>Admins You Promoted</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mt-3">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Promoted At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotedAdmins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.users.demote', $admin->id) }}" onsubmit="return confirm('Are you sure you want to demote this admin?');">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-danger btn-sm">Demote to User</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif


        <div class="d-flex justify-content-center mt-4">
            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info">No users found.</div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePromotedAdmins');
        const table = document.getElementById('promotedAdminsTable');

        if (toggleBtn && table) {
            toggleBtn.addEventListener('click', function () {
                table.classList.toggle('d-none');
                toggleBtn.textContent = table.classList.contains('d-none') 
                    ? 'Show Promoted Admins' 
                    : 'Hide Promoted Admins';
            });
        }
    });
</script>
@endpush

@endsection
