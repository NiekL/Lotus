<?php

namespace App\Http\Controllers;

use App\Models\Announcements;
use App\Models\LotusRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string', // Changed from 'announcement' to 'message'
        ]);

        Announcements::create(['announcement' => $validated['message']]); // Map the validated message correctly

        return redirect()->route('dashboard')->with('success', 'Announcement created successfully!'); // Redirect after storing
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcements $announcements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcements $announcements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcements $announcements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcements $announcement)
    {
        $announcement->delete();
        return redirect()->route('dashboard')->with('success', 'Announcement deleted successfully!');
    }
}
