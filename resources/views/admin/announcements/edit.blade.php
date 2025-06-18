@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit Announcement</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Announcement</button>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
