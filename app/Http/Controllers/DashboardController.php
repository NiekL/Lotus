<?php
namespace App\Http\Controllers;

use App\Models\LotusRequest;
use App\Models\Announcements;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {

        // Haal de lotusRequests op (je kunt hier filteren of sorteren indien nodig)
        $announcements = Announcements::all();

        $user = auth()->user();

        $activeUserLotusRequestIds = $user->lotusRequests()
            ->where('status', 2)
            ->where('date', '>=', now()->toDateString())
            ->pluck('id'); // Haalt alleen de IDs op

        $lotusRequests = LotusRequest::where('status', 2)
            ->whereDate('date', '>=', now()->toDateString())
            ->where('is_closed', 0)
            ->whereNotIn('id', $activeUserLotusRequestIds) // Filtert de aanvragen waar de gebruiker al voor is aangemeld
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();

        //Haal alle actieve aanvragen op per gebruiker
        $today = now()->startOfDay();
        $activeUserLotusRequests = $user->lotusRequests()
            ->where('status', 2)
            ->where('date', '>=', $today)
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();

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

        // Geef de lotusRequests door aan de view
        return Inertia::render('Dashboard', [
//            'userRoles' => auth()->user()->roles->pluck('name')->toArray(),
            'announcements' => $announcements,
            'lotusRequests' => $lotusRequests,
            'activeUserLotusRequests' => $activeUserLotusRequests,
            'pendingCustomerLotusRequests' => $pendingCustomerLotusRequests,
            'activeCustomerLotusRequests' => $activeCustomerLotusRequests,
            'success' => session('success'), // als je een succesbericht wilt doorgeven
        ]);
    }
}
