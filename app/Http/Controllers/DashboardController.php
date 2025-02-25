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
        $lotusRequests = LotusRequest::where('status', 2)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();

        //Haal alle actieve aanvragen op per gebruiker
        $user = auth()->user();
        $today = now()->startOfDay();
        $activeUserLotusRequests = $user->lotusRequests()
            ->where('status', 2)
            ->where('date', '>=', $today)
            ->orderBy('date', 'asc') // Sorteert oplopend op datum
            ->get();




        // Geef de lotusRequests door aan de view
        return Inertia::render('Dashboard', [
            'userRoles' => auth()->user()->roles->pluck('name')->toArray(),
            'announcements' => $announcements,
            'lotusRequests' => $lotusRequests,
            'activeUserLotusRequests' => $activeUserLotusRequests,
            'success' => session('success'), // als je een succesbericht wilt doorgeven
        ]);
    }
}
