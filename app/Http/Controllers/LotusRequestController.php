<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\LotusRequest;

class LotusRequestController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'customer_id' => 'required|integer',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'arrival_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'amount_lotus' => 'required|integer',
            'payment_mark' => 'nullable|string',
            'rate_group' => 'required|integer',
            'details' => 'nullable|string',
            'city' => 'required|string',
            'street_name' => 'required|string',
            'house_number' => 'required|string',
            'zipcode' => 'required|string',
            'contact_person' => 'required|string',
            'contact_phone' => 'required|string',
            'contact_email' => 'required|email',
            'filled_spots' => 'integer',
            'status' => 'integer',
        ]);

        LotusRequest::create($validated);

        // Redirect naar het overzicht van open aanvragen met een succesmelding
//        return redirect()->route('lotus-requests.openlotusrequests')->with('success', 'Lotus-aanvraag succesvol aangemaakt.');
        $user = auth()->user();

        if ($user->roles->contains('name', 'klant')) {
            return redirect()->route('dashboard')->with('success', 'Lotus-aanvraag succesvol aangemaakt.');
        } else {
            return redirect()->route('lotus-requests.acceptlotusrequests')->with('success', 'Lotus-aanvraag succesvol aangemaakt.');
        }

    }

    public function index()
    {
        //Voor lid etc
        $lotusRequests = LotusRequest::where('status', 2)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();

        $user = auth()->user();
        if ($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'penningmeester')){
            $allLotusRequests = LotusRequest::orderBy('date', 'desc')->get();
        } else {
            $allLotusRequests = [];
        }

        // Geef de aanvragen door aan de Inertia view
        return Inertia::render('LotusRequests/OpenLotusRequests', [
            'lotusRequests' => $lotusRequests,
            'penningmeesterAllLotusRequests' => $allLotusRequests,
        ]);
    }

    public function show(Request $request)
    {
        $id = $request->query('id');


        // Retrieve the Lotus request along with signed-up users and their pivot data
        $lotusRequest = LotusRequest::with(['users' => function($query) {
            $query->withPivot('user_played_time', 'user_amount_km', 'user_feedback', 'user_expenses');
        }])->findOrFail($id);

        // Retrieve billing info for the logged-in user
        $customer = User::findOrFail($lotusRequest->customer_id); // Find user with ID

        $billingInfo = $customer->billingInfo()->firstOrCreate([], [
            'billing_name' => '',
            'billing_contactperson' => '',
            'billing_phone' => '',
            'billing_email' => '',
            'billing_address' => '',
            'billing_zipcode' => '',
            'billing_city' => ''
        ]);



        // Retrieve the current user's pivot data for this request, if it exists
        $user = auth()->user();
        $pivotData = $user->lotusRequests()->where('lotus_request_id', $id)->first()->pivot ?? null;

        return Inertia::render('LotusRequests/ViewLotusRequest', [
            'lotusRequest' => $lotusRequest,
            'signedUpUsers' => $lotusRequest->users,  // Users with pivot data
            'billingInfo' => $billingInfo,
            'userRequestData' => [
                'user_played_time' => $pivotData->user_played_time ?? null,
                'user_amount_km' => $pivotData->user_amount_km ?? null,
                'user_feedback' => $pivotData->user_feedback ?? null,
                'user_expenses' => $pivotData->user_expenses ?? null,
            ],
        ]);
    }

    public function showUserRequests($user_id = null)
    {
        // Verkrijg de huidige ingelogde gebruiker
        if(!$user_id){
            $user = auth()->user();
        } else {
            $user = User::find($user_id);
        }

        // De huidige datum ophalen
        $today = now()->startOfDay();

        // Haal de Lotus-aanvragen op die bij de ingelogde gebruiker horen en nog niet verstreken zijn
        $activeUserLotusRequests = $user->lotusRequests()
            ->where('status', 2)
            ->where('date', '>=', $today)
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();

        // Haal de verlopen Lotus-aanvragen op die bij de ingelogde gebruiker horen
        $expiredUserLotusRequests = $user->lotusRequests()
            ->where('status', 2)
            ->where('date', '<', $today)
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();

        // Geef de aanvragen door aan de Inertia view
        return Inertia::render('LotusRequests/MyLotusRequests', [
            'activeUserLotusRequests' => $activeUserLotusRequests,
            'expiredUserLotusRequests' => $expiredUserLotusRequests,
        ]);
    }

    public function showCustomerRequests($user_id = null)
    {
        // Verkrijg de huidige ingelogde gebruiker of de opgegeven gebruiker
        $user = $user_id ? User::find($user_id) : auth()->user();

        if (!$user) {
            abort(404, 'Gebruiker niet gevonden.');
        }

        // De huidige datum ophalen
        $today = now()->startOfDay();

        // Haal de Lotus-aanvragen op die bij de gebruiker horen
        $pendingCustomerLotusRequests = LotusRequest::where('customer_id', $user->id)
            ->where('status', 1) // Status 0 = in afwachting
            ->whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->get();

        $activeCustomerLotusRequests = LotusRequest::where('customer_id', $user->id)
            ->where('status', 2) // Status 1 = geaccepteerd / lopend
            ->whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->get();

        $expiredCustomerLotusRequests = LotusRequest::where('customer_id', $user->id)
            ->whereDate('date', '<', $today)
            ->orderBy('date', 'desc')
            ->get();

        // Geef de aanvragen door aan de Inertia view
        return Inertia::render('LotusRequests/MyCustomerRequests', [
            'pendingUserLotusRequests' => $pendingCustomerLotusRequests,
            'activeUserLotusRequests' => $activeCustomerLotusRequests,
            'expiredUserLotusRequests' => $expiredCustomerLotusRequests,
        ]);
    }


    public function showAcceptLotusRequests()
    {
        $success = session('success');

        // 1- new, 2- accepted, 3-declined
        $newLotusRequests = LotusRequest::where('status', 1)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        $acceptedLotusRequests = LotusRequest::where('status', 2)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();
        $declinedLotusRequests = LotusRequest::where('status', 3)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        $expiredLotusRequests = LotusRequest::whereDate('date', '<=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        // Return the filtered requests to the Inertia view
        return Inertia::render('LotusRequests/AcceptLotusRequests', [
            'newLotusRequests' => $newLotusRequests,
            'acceptedLotusRequests' => $acceptedLotusRequests,
            'declinedLotusRequests' => $declinedLotusRequests,
            'expiredLotusRequests' => $expiredLotusRequests,
            'success' => $success,
        ]);
    }

    public function acceptRequest(Request $request, $id)
    {
        $lotusRequest = LotusRequest::findOrFail($id);
        $lotusRequest->update(['status' => 2]); // 2 staat voor geaccepteerd
        return redirect()->route('lotus-requests.acceptlotusrequests')->with('success', 'Aanvraag geaccepteerd.');
    }

    public function declineRequest(Request $request, $id)
    {
        $lotusRequest = LotusRequest::findOrFail($id);
        $lotusRequest->update(['status' => 3]); // 3 staat voor afgewezen
        return redirect()->route('lotus-requests.acceptlotusrequests')->with('success', 'Aanvraag afgewezen.');
    }

    public function signup(Request $request, $id)
    {
        $user = auth()->user(); // Verkrijg de huidige ingelogde gebruiker
        $lotusRequest = LotusRequest::findOrFail($id);

        // Check if filled_spots is greater than or equal to amount_lotus
        if ($lotusRequest->filled_spots >= $lotusRequest->amount_lotus) {
            return response()->json(['message' => 'Aanmelden mislukt. De aanvraag zit vol.'], 400);
        }

        // Controleer of de gebruiker zich al heeft aangemeld
        if ($lotusRequest->users()->where('user_id', $user->id)->exists()) {
            // Als de gebruiker al is aangemeld, geef een foutmelding terug
            return response()->json(['message' => 'Aanmelden mislukt. Je bent al aangemeld voor deze aanvraag.'], 400);
        }

        // Koppel de gebruiker aan de Lotus aanvraag
        $lotusRequest->users()->attach($user->id);

        // Verhoog `filled_spots`
        $lotusRequest->increment('filled_spots');

        return redirect()->route('lotus-requests.viewlotusrequest', ['id' => $id])
            ->with('success', 'Succesvol aangemeld voor de aanvraag.');
    }

    public function cancelSignup(Request $request, $id)
    {
        $user = auth()->user(); // Haal de huidige ingelogde gebruiker op
        $lotusRequest = LotusRequest::findOrFail($id);

        // Controleer of de gebruiker zich heeft aangemeld
        if (!$lotusRequest->users()->where('user_id', $user->id)->exists()) {
            // Als de gebruiker niet is aangemeld, geef een foutmelding terug
            return response()->json(['message' => 'Afmelden mislukt. Je bent niet aangemeld voor deze aanvraag.'], 400);
        }

        // Ontkoppel de gebruiker van de Lotus-aanvraag
        $lotusRequest->users()->detach($user->id);

        // Verminder `filled_spots`
        $lotusRequest->decrement('filled_spots');

        return redirect()->route('lotus-requests.viewlotusrequest', ['id' => $id])
            ->with('success', 'Succesvol afgemeld voor de aanvraag.');
    }

    public function checkSignup(Request $request, $id)
    {
        $user = auth()->user();
        $lotusRequest = LotusRequest::findOrFail($id);
        $isUserSignedUp = $lotusRequest->users()->where('user_id', $user->id)->exists();

        return response()->json(['isUserSignedUp' => $isUserSignedUp]);
    }

    //User feedback (km vergoeding etc)
    public function submitDetails(Request $request, $id)
    {
        $user = auth()->user();

        // Valideer de invoer
        $validatedData = $request->validate([
            'user_played_time' => 'required|integer|min:0',
            'user_amount_km' => 'required|integer|min:0',
            'user_feedback' => 'nullable|string',
            'user_expenses' => 'nullable|numeric|min:0',
        ]);

        // Update de pivot table met de extra gegevens
        $user->lotusRequests()->updateExistingPivot($id, $validatedData);


        return redirect()->route('lotus-requests.viewlotusrequest', ['id' => $id])
            ->with('success', 'Gegevens succesvol doorgegeven.');
    }

}
