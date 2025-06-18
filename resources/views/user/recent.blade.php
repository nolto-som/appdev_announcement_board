@extends('layouts.app')

@section('content')
<h2>Recent Announcements</h2>
@foreach($recentAnnouncements as $announcement)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $announcement->title }}</h5>
            <p>{{ $announcement->content }}</p>
            <small>Posted on {{ $announcement->created_at->format('M d, Y') }}</small>
        </div>
    </div>
@endforeach
@endsection
