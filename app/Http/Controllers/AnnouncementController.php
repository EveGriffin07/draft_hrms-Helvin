<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest('publish_at')->get();
        return view('admin.dashboard_view', compact('announcements'));
    }

    public function create()
    {
        return view('admin.dashboard_add');
    }

    public function store(Request $request)
    {
        // 1. Validate Form Inputs
        $request->validate([
            'title'    => 'required|string|max:255',
            'message'  => 'required|string', // Form uses 'message'
            'audience' => 'required|string', // Form uses 'audience'
        ]);

        // 2. Map Form Data to Database Columns
        Announcement::create([
            'title'         => $request->title,
            'content'       => $request->message,  // MAPPING: message -> content
            'audience_type' => $request->audience, // MAPPING: audience -> audience_type
            'priority'      => $request->priority,
            'department'    => $request->department,
            'publish_at'    => now(),              // Default to current time
            'expires_at'    => $request->expires,
            'remarks'       => $request->remarks,
            'posted_by'     => Auth::id() ?? 1,
        ]);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Announcement posted successfully!');
    }

    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.dashboard_detail', compact('announcement'));
    }
}