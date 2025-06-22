@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f5f6fa;
    }

    .announcement-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .announcement-header h3 {
        color: #046d8b;
        font-weight: 700;
    }

    .card-modern {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        cursor: pointer;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .card-modern h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .card-modern p {
        color: #555;
        margin-bottom: 0.3rem;
    }

    .modal-header {
        background-color: #046d8b;
        color: white;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .modal-content {
        border-radius: 1rem;
    }

    .modal-footer .btn {
        margin-left: 0.5rem;
    }

    .card-footer-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

    .btn {
        border-radius: 0.4rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background-color: #0190ba;
        border: none;
    }

    .btn-primary:hover {
        background-color: #046d8b;
    }

    .btn-success {
        background-color: #99dfbd;
        color: #003f2b;
    }

    .btn-success:hover {
        background-color: #78cda5;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #4e3d00;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-info {
        background-color: #0190ba;
        color: white;
    }

    .btn-info:hover {
        background-color: #046d8b;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #b52a37;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .form-control::placeholder {
        font-style: italic;
    }
</style>

<div class="announcement-header">
    <h3>All Announcements</h3>

   <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <!-- Search Form (inline input + button) -->
    <form method="GET" action="{{ route('admin.announcements.index') }}" class="d-flex align-items-center gap-2 flex-grow-1 me-3" style="max-width: 600px;">
        <input type="text" name="search" value="{{ request('search') }}"
            class="form-control" placeholder="Search by title, content, or date (YYYY-MM-DD)">
        <button class="btn btn-primary">Search</button>
    </form>

    <!-- New Announcement Button -->
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">+ New Announcement</a>
</div>


</div>

@if($announcements->count())
@foreach($announcements as $a)
    <!-- Card -->
    <div class="card-modern" data-bs-toggle="modal" data-bs-target="#announcementModal{{ $a->id }}">
        <h5>{{ $a->title }}</h5>
        <p>{{ \Illuminate\Support\Str::limit($a->content, 100) }}</p>
        <p>Status: <strong>{{ ucfirst($a->announcementStatus->name ?? 'Unknown') }}</strong></p>
        <div class="card-footer-text">Posted on {{ $a->created_at->format('M d, Y') }}</div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="announcementModal{{ $a->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $a->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{ $a->id }}">{{ $a->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($a->image)
                        <img src="{{ asset('storage/' . $a->image) }}" alt="Announcement Image" class="img-fluid rounded mb-3">
                    @endif

                    <p><strong>Status:</strong> {{ ucfirst($a->announcementStatus->name ?? 'Unknown') }}</p>
                    <hr>
                    <p>{{ $a->content }}</p>
                </div>
                <div class="modal-footer justify-content-start">
                    @if($a->announcementStatus->name === 'pending')
                        <form method="POST" action="{{ route('admin.announcements.approve', $a->id) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">Approve</button>
                        </form>
                    @endif

                    @if($a->announcementStatus->name !== 'archived')
                    <form method="POST" action="{{ route('admin.announcements.archive', $a->id) }}">
                        @csrf
                        <button class="btn btn-warning btn-sm">Archive</button>
                    </form>
                    @endif

                    <a href="{{ route('admin.announcements.edit', $a->id) }}" class="btn btn-info btn-sm">Edit</a>

                    <form method="POST" action="{{ route('admin.announcements.destroy', $a->id) }}"
                        onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>

                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@else
    <div class="alert alert-info mt-3">
        No announcements found.
    </div>
@endif

@endsection
