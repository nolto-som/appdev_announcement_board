<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

public function home(Request $request)
{
    if (auth()->check() && auth()->user()->role_id == 1) {
        return redirect()->route('admin.announcements.index');
    }

    $search = $request->input('search');

    $announcements = Announcement::where('status', 'approved')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereRaw("DATE_FORMAT(created_at, '%M %d, %Y') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') LIKE ?", ["%{$search}%"]);
            });
        })
        ->latest()
        ->get();

    return view('home', compact('announcements'));
}



public function recent()
{
    $recentAnnouncements = Announcement::where('announcement_status_id', 1) // 1 = approved/published
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('user.recent', compact('recentAnnouncements'));
}


    

     // Show all announcements (admin view)
   public function index(Request $request)
{
    $query = Announcement::query();

    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhereDate('created_at', $search); // Search by date (e.g., "2025-06-19")
        });
    }

    $announcements = $query->orderBy('created_at', 'desc')->get();
    $announcements = Announcement::with('announcementStatus')->get();
    return view('admin.announcements.index', compact('announcements'));
}


    // Show form to create a new announcement
    public function create()
    {
         $announcement = null;
        return view('admin.announcements.create');
    }

    // Store a new announcement
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    try {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'announcement_status_id' => 1,
            'user_id' => auth()->id(),
            'image' => $imagePath,
        ]);

        //$users = User::where('role', 'user')->get();
        //foreach ($users as $user) {
            //$user->notify(new NewAnnouncementNotification($announcement));
        //}

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement posted.');
    } catch (\Exception $e) {
        return redirect()->route('admin.announcements.index')->with('error', 'Failed to post announcement.');
    }
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

    //delete
    public function destroy($id)
{
    $announcement = Announcement::findOrFail($id);
    
    // Delete the image if it exists
    if ($announcement->image && \Storage::disk('public')->exists($announcement->image)) {
        \Storage::disk('public')->delete($announcement->image);
    }

    $announcement->delete();

    return redirect()->back()->with('success', 'Announcement permanently deleted.');
}



    // Approve a pending announcement
    public function approve($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->announcement_status_id = 1;
        $announcement->save();

        return redirect()->back()->with('success', 'Announcement approved.');
    }

    // Archive an announcement
    public function archive($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->announcement_status_id = 2; // Archived
        $announcement->save();

        return redirect()->back()->with('success', 'Announcement archived.');
    }

public function archived()
{
    $announcements = Announcement::where('announcement_status_id', 2) // 2 = archived
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.announcements.archived', compact('announcements'));
}


// Restore archived announcement
public function restore($id)
{
    $announcement = Announcement::findOrFail($id);
    $announcement->announcement_status_id = 1; // Approved
    $announcement->save();

    return redirect()->route('admin.announcements.archived')->with('success', 'Announcement restored.');
}

public function json($id)
{
    $announcement = Announcement::findOrFail($id);
    return response()->json([
        'title' => $announcement->title,
        'content' => $announcement->content,
        'image' => $announcement->image,
    ]);
}




}
