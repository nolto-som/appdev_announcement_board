@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f4eeee;
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .announcement-header h3 {
            color: #046d8b;
            font-weight: 700;
        }

        .btn-primary {
            background-color: #0190ba;
            border: none;
        }

        .btn-primary:hover {
            background-color: #046d8b;
        }

        .card-modern {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
            margin-bottom: 1.5rem;
        }

        .card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .card-modern h5 {
            font-weight: 600;
            color: #4e2031;
        }

        .card-modern p {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .modal-header {
            background-color: #046d8b;
            color: white;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .modal-footer .btn {
            margin-left: 0.25rem;
        }

        .btn-success {
            background-color: #99dfbd;
            border: none;
            color: #003f2b;
        }

        .btn-success:hover {
            background-color: #78cda5;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #4e3d00;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-info {
            background-color: #0190ba;
            border: none;
            color: white;
        }

        .btn-info:hover {
            background-color: #046d8b;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b52a37;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .modal-content {
            border-radius: 1rem;
        }
    </style>

    <div class="announcement-header">
        <h3> All Announcements</h3>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">+ New Announcement</a>
    </div>

    @foreach($announcements as $a)
        <!-- Card (Click to open modal) -->
        <div class="card-modern" data-bs-toggle="modal" data-bs-target="#announcementModal{{ $a->id }}">
            <h5>{{ $a->title }}</h5>
            <p>{{ \Illuminate\Support\Str::limit($a->content, 100) }}</p>
            <p>Status: <strong>{{ ucfirst($a->status) }}</strong></p>
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
                            <img src="{{ asset('storage/' . $a->image) }}" alt="Announcement Image" class="img-fluid mb-3" style="max-width: 100%; border-radius: 8px;">
                        @endif
                        
                        <p><strong>Status:</strong> {{ ucfirst($a->status) }}</p>
                        <hr>
                        <p>{{ $a->content }}</p>
                    </div>
                    <div class="modal-footer">
                        @if($a->status === 'pending')
                            <form method="POST" action="{{ route('admin.announcements.approve', $a->id) }}">
                                @csrf
                                <button class="btn btn-success btn-sm">Approve</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.announcements.archive', $a->id) }}">
                            @csrf
                            <button class="btn btn-warning btn-sm">Archive</button>
                        </form>

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
@endsection
