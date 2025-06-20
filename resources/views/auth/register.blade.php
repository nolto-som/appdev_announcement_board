@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
        <h2 class="text-center mb-4">Register</h2>

        {{-- Show all validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password:</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <!-- Optional field (still kept if needed later) -->
            <!--
            <div class="mb-3">
                <label class="form-label">Admin Code (Optional):</label>
                <input type="text" name="admin_code" class="form-control">
            </div>
            -->

            <button class="btn btn-primary w-100">Sign Up</button>
        </form>

    </div>
</div>
@endsection
