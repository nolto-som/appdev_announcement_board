<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
   public function home()
{
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.announcements.index');
    }

    $announcements = Announcement::where('status', 'approved')->latest()->get();
    return view('home', compact('announcements'));
}

public function recent()
{
    $recentAnnouncements = Announcement::where('status', 'approved')
        ->orderBy('created_at', 'desc')
        ->take(5) // You can change the number of recent items
        ->get();

    return view('user.recent', compact('recentAnnouncements'));
}

    

     // Show all announcements (admin view)
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    // Show form to create a new announcement
    public function create()
    {
        return view('admin.announcements.create');
    }

    // Store a new announcement
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
    ]);

    // Create the announcement
    $announcement = Announcement::create([
        'title' => $request->title,
        'content' => $request->content,
        'status' => 'approved', // Auto-approved if created by admin
    ]);

    // Notify all users
    $users = User::where('role', 'user')->get();
    foreach ($users as $user) {
        $user->notify(new NewAnnouncementNotification($announcement));
    }

    return redirect()->route('admin.announcements.index')->with('success', 'Announcement posted.');
}


    // Edit an announcement
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    // Update the announcement
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $announcement->update($request->only(['title', 'content']));

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    // Delete an announcement
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }

    // Approve a pending announcement
    public function approve($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = 'approved';
        $announcement->save();

        return redirect()->back()->with('success', 'Announcement approved.');
    }

    // Archive an announcement
    public function archive($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = 'archived';
        $announcement->save();

        return redirect()->back()->with('success', 'Announcement archived.');
    }

//     public function archived()
// {
//     $announcements = Announcement::where('status', 'archived')
//         ->orderBy('created_at', 'desc')
//         ->get();

//     return view('admin.announcements.archived', compact('announcements'));
// }

}
