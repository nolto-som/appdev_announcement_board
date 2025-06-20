@extends('layouts.app')

@section('content')
<h2>Recent Announcements</h2>

@foreach($recentAnnouncements as $announcement)
    <!-- Clickable card -->
    <div class="card mb-3" 
         role="button" 
         data-bs-toggle="modal" 
         data-bs-target="#announcementModal{{ $announcement->id }}">
        <div class="card-body">
            <h5 class="card-title">{{ $announcement->title }}</h5>
            <p class="card-text">{{ \Illuminate\Support\Str::limit($announcement->content, 100) }}</p>
            <small class="text-muted">Posted on {{ $announcement->created_at->format('M d, Y') }}</small>
        </div>
    </div>

    <!-- Modal for each announcement -->
    <div class="modal fade" id="announcementModal{{ $announcement->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $announcement->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{ $announcement->id }}">{{ $announcement->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($announcement->image)
                        <img src="{{ asset('storage/' . $announcement->image) }}" alt="Announcement Image" class="img-fluid mt-3">
                    @endif
                    <p>{{ $announcement->content }}</p>
                    <small class="text-muted d-block mt-2">Posted on {{ $announcement->created_at->format('M d, Y') }}</small>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
