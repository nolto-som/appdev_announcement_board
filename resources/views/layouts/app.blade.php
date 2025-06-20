<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Board</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 16px;
        }

        .btn {
            border-radius: 12px;
        }

        .sidebar {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        @auth
            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                ☰
            </button>
        @endauth
        <a class="navbar-brand fw-bold" href="/">📢 Community Board</a>
  

        <div class="d-flex ms-auto align-items-center gap-2">
        <div>
            <a class="nav-link me-3" href="{{ route('about') }}">ℹ️ About</a>
        </div>
            @auth
                @php
    $unread = auth()->user()->unreadNotifications;
@endphp

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        👤 {{ auth()->user()->name }}
                        @if(auth()->check() && auth()->user()->role_id == 1)
                            <span class="badge bg-danger">Admin</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('edit.profile') }}">Edit Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a class="btn btn-outline-primary" href="{{ route('login') }}">Login</a>
                <a class="btn btn-primary" href="{{ route('register') }}">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        @auth
        <!-- Sidebar -->
        <div class="col-md-2 collapse sidebar" id="sidebarMenu">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a class="nav-link" href="/">🏠 Home</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="{{ route('announcements.recent') }}">🕒 Recent</a></li>
                
                 @if(auth()->check() && auth()->user()->role_id == 1)
                    <!-- <li class="nav-item mb-2"><a class="nav-link" href="{{ route('admin.announcements.index') }}">🛠 Manage Announcements</a></li> -->
                    <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">👥 User Management</a>
                    </li>
                    <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ route('admin.announcements.archived') }}">🗃 Archived Announcements</a>
                    </li>
                @endif
                
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            @yield('content')
        </div>
        @else
        <div class="col-lg-8 col-md-10 mx-auto">
            @yield('content')
        </div>
        @endauth
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function () {
            const announcementId = this.dataset.id;
            const notificationId = this.dataset.notificationId;

            // Mark as read
            fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(res => res.ok && this.closest('.dropdown').querySelector('.badge')?.remove());

            // Fetch announcement content
            fetch(`/announcements/${announcementId}/json`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('announcementModalLabel').textContent = data.title;
                    document.getElementById('announcementContent').textContent = data.content;

                    const img = document.getElementById('announcementImage');
                    if (data.image) {
                        img.src = `/storage/${data.image}`;
                        img.classList.remove('d-none');
                    } else {
                        img.classList.add('d-none');
                    }
                });
        });
    });
});

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
