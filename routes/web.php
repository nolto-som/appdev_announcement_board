<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


Route::get('/', [AnnouncementController::class, 'home'])->name('home');
//Route::get('/about', fn() => view('about'))->name('about');
Route::view('/about', 'about')->name('about');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);




Route::middleware(['auth'])->group(function () {
    Route::get('/announcements/recent', [AnnouncementController::class, 'recent'])->name('announcements.recent');
    Route::get('/announcements/{id}/json', [AnnouncementController::class, 'json']);

    Route::get('/inbox', [UserController::class, 'inbox'])->name('inbox');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('edit.profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('home', [AnnouncementController::class, 'adminHome'])->name('admin.home');
    Route::resource('announcements', AnnouncementController::class)->except(['show']);
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    //Announcement Status
    Route::post('/announcements/{id}/approve', [AnnouncementController::class, 'approve'])->name('announcements.approve');
    Route::post('/announcements/{id}/archive', [AnnouncementController::class, 'archive'])->name('announcements.archive');
    Route::get('/announcements/archived', [AnnouncementController::class, 'archived'])->name('announcements.archived');
    Route::post('/announcements/{id}/restore', [AnnouncementController::class, 'restore'])->name('announcements.restore');


    //Manage Users
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);
    Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');

    
    });
});
