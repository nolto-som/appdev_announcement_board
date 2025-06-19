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
    if (auth()->check() && auth()->user()->role === 'admin')
 {
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
    //  dd('store method hit');
    $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

         $imagePath = null;
        if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('announcements', 'public');
        }

    // Create the announcement
    $announcement = Announcement::create([
        'title' => $request->title,
        'content' => $request->content,
        'status' => 'approved', // Auto-approved if created by admin
        'user_id' => auth()->id(),
        'image' => $imagePath,
    ]);
    

    // Notify all users
    $users = User::where('role', 'user')->get();
    foreach ($users as $user) {
        $user->notify(new NewAnnouncementNotification($announcement));
    }

    // dd($announcement);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

        $data = $request->only(['title', 'content']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image && \Storage::disk('public')->exists($announcement->image)) {
                \Storage::disk('public')->delete($announcement->image);
        }

        // Store new image
        $data['image'] = $request->file('image')->store('announcements', 'public');
    }

        $announcement->update($data);

    return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
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

    public function archived()
{
    $announcements = Announcement::where('status', 'archived')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.announcements.archived', compact('announcements'));
}

// Restore archived announcement
public function restore($id)
{
    $announcement = Announcement::findOrFail($id);
    $announcement->status = 'approved';
    $announcement->save();

    return redirect()->route('admin.announcements.archived')->with('success', 'Announcement restored.');
}



}
