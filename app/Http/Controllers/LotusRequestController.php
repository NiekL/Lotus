<?php
namespace App\Http\Controllers;

use App\Models\LotusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LotusRequestController extends Controller
{
    public function index()
    {
        $lotusRequests = LotusRequest::with('users')->get();
        return inertia('LotusRequests/Index', compact('lotusRequests'));
    }

    public function show($id)
    {
        $lotusRequest = LotusRequest::with('users')->findOrFail($id);
        return inertia('LotusRequests/Show', compact('lotusRequest'));
    }

    public function enroll(Request $request)
    {
        $user = Auth::user();
        $lotusRequest = LotusRequest::findOrFail($request->lotus_request_id);
        $user->lotusRequests()->attach($lotusRequest);

        return redirect()->back()->with('message', 'Aangemeld voor ' . $lotusRequest->name);
    }

    public function create()
    {
        return inertia('LotusRequests/Create');
    }

    public function store(Request $request)
    {
        // Validatie
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'nullable|date|after_or_equal:start_date_time',
            'applicant' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'postal_code' => 'required|string|max:10',
            'number_of_people' => 'required|integer|min:1',
            'rate_group' => 'required|string|max:255',
            'special_requests' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        // 'LotusRequest' aanmaken
        LotusRequest::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_date_time' => $request->input('start_date_time'),
            'end_date_time' => $request->input('end_date_time'),
            'applicant' => $request->input('applicant'),
            'location' => $request->input('location'),
            'street_name' => $request->input('street_name'),
            'house_number' => $request->input('house_number'),
            'postal_code' => $request->input('postal_code'),
            'number_of_people' => $request->input('number_of_people'),
            'rate_group' => $request->input('rate_group'),
            'special_requests' => $request->input('special_requests'),
            'contact_person' => $request->input('contact_person'),
            'contact_phone' => $request->input('contact_phone'),
            'contact_email' => $request->input('contact_email'),
            'payment_reference' => $request->input('payment_reference'),
            'user_id' => Auth::id(),
        ]);

        // Redirect naar de lijst met 'LotusRequests'
        return redirect()->route('lotus-requests.index')->with('message', 'Lotus request succesvol aangemaakt!');
    }

}
