@extends('layouts.app')
@section('content')
    <h3>Edit Profile</h3>
     @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="/profile/update">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection