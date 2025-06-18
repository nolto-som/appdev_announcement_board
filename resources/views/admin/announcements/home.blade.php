@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Admin Dashboard</h1>

        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary mb-3">➕ Create Announcement</a>

        <div class="card">
            <div class="card-header">All Announcements</div>
            <div class="card-body">
                @if($announcements->count())
                    <ul class="list-group">
                        @foreach($announcements as $announcement)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $announcement->title }}</strong>
                                    <br>
                                    <small>Status: {{ ucfirst($announcement->status) }}</small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No announcements yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
