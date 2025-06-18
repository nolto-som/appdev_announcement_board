@extends('layouts.app')

@section('content')
<h3>Create Announcement</h3>
<form method="POST" action="{{ route('admin.announcements.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Body</label>
        <textarea name="content" class="form-control" rows="4"></textarea>
    </div>
    <button class="btn btn-success">Post</button>
</form>
@endsection
