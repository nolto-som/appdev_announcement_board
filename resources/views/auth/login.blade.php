@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <p>Don't have an account?</p>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Sign Up</a>
        </div>
    </div>
</div>
@endsection
