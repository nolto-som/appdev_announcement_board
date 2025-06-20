@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Archived Announcements</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($announcements as $a)
    <!-- Make the entire card clickable by adding data attributes -->
    <div class="card mb-3" 
         role="button"
         data-bs-toggle="modal" 
         data-bs-target="#announcementModal{{ $a->id }}">
        <div class="card-body">
    <h5 class="card-title">{{ $a->title }}</h5>
    <p class="card-text">{{ \Illuminate\Support\Str::limit($a->content, 100) }}</p>

    <form action="{{ route('admin.announcements.restore', $a->id) }}" method="POST" onClick="event.stopPropagation();" class="d-inline">
        @csrf
        <button class="btn btn-warning btn-sm">Undo Archive</button>
    </form>

    <form action="{{ route('admin.announcements.destroy', $a->id) }}" method="POST" onClick="event.stopPropagation();" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this announcement permanently?')">Delete</button>
    </form>
</div>
    </div>

    <!-- Modal for this announcement -->
    <div class="modal fade" id="announcementModal{{ $a->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $a->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{ $a->id }}">{{ $a->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ $a->content }}</p>
                    @if($a->image)
                        <img src="{{ asset('storage/' . $a->image) }}" alt="Announcement Image" class="img-fluid mt-3">
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <p>No archived announcements found.</p>
@endforelse
</div>
@endsection
