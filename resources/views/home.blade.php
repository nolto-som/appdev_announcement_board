@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f4f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .announcement-title {
            font-size: 2rem;
            font-weight: 700;
            color: #046d8b;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .announcement-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 2.5rem;
        }

        .card-modern {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
            margin-bottom: 1.5rem;
        }

        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            cursor: pointer;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #4e2031;
        }

        .card-text {
            color: #444;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .card-footer-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .modal-header {
            background-color: #046d8b;
            color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .btn-secondary {
            background-color: #0190ba;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #046d8b;
        }

        .modal-content {
            border-radius: 1rem;
        }
    </style>

    <h1 class="announcement-title">📢 Community Announcement Board</h1>
    <p class="announcement-subtitle">Click any announcement to view more details.</p>
    <div class="container mb-4">
    <div class="container mb-4 d-flex justify-content-center">
      
    <form method="GET" action="{{ route('home') }}" class="w-100" style="max-width: 600px;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by title, content, or date..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

</div>
    
    
    @isset($announcements)
        @if($announcements->count())
        <div class="row justify-content-center px-3">
            <div class="col-md-9">
                @foreach($announcements as $announcement)
                    <!-- Announcement Card -->
                    <div class="card-modern" data-bs-toggle="modal" data-bs-target="#announcementModal{{ $announcement->id }}">
                        <h5 class="card-title">{{ $announcement->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($announcement->content, 400) }}</p>
                        <div class="card-footer-text">Posted on {{ $announcement->created_at->format('M d, Y') }}</div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="announcementModal{{ $announcement->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $announcement->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $announcement->id }}">{{ $announcement->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($announcement->image)
                                        <img src="{{ asset('storage/' . $announcement->image) }}" alt="Announcement Image" class="img-fluid mb-3" style="max-width: 100%; border-radius: 8px;">
                                    @endif
                                    
                                    <p>{{ $announcement->content }}</p>
                                    <hr>
                                    <p class="text-muted"><small>Posted on {{ $announcement->created_at->format('F d, Y h:i A') }}</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            </div>
        </div>
    @endisset
@endsection
