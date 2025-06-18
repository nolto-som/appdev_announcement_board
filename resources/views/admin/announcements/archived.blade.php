@extends('layouts.app')

@section('content')
<h3>Archived Announcements</h3>

@foreach($announcements as $a)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $a->title }}</h5>
            <p>{{ $a->content }}</p>
            <p>Status: <strong>{{ $a->status }}</strong></p>
        </div>
    </div>
@endforeach
@endsection
