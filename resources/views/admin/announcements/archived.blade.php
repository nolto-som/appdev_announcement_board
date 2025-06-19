@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Archived Announcements</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($announcements as $a)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $a->title }}</h5>
                <p>{{ \Illuminate\Support\Str::limit($a->content, 100) }}</p>
                <form action="{{ route('admin.announcements.restore', $a->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-warning btn-sm">Undo Archive</button>
                </form>
            </div>
        </div>
    @empty
        <p>No archived announcements found.</p>
    @endforelse

</div>
@endsection
