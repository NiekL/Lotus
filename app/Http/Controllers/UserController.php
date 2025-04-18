<?php
namespace App\Http\Controllers;

use App\Models\LotusRequest;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('role_id', 3);
        })->whereDoesntHave('roles', function ($query) {
            $query->where('role_id', 1);
        })
            ->get()
            ->sortBy('name') // Sorteer hier op naam
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email, // Zorg dat 'place' bestaat in je model of pas dit aan
                ];
            });

        return Inertia::render('Users/MemberAdministration', [
            'members' => $users->values()->all()
        ]);
    }

    public function customers()
    {

        $customers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })
        ->get()
        ->sortBy('name') // Sorteer hier op naam
        ->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email, // Zorg dat 'place' bestaat in je model of pas dit aan
            ];
        });

        return Inertia::render('Users/CustomerAdministration', [
            'customers' => $customers->values()->all()
        ]);
    }

    public function getCustomersOnly()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })
            ->orderBy('name') // Sort alphabetically by name in the query itself
            ->get(['id', 'name', 'email']);

        return response()->json(['customers' => $customers]);
    }

    public function getNonCustomers()
    {
        $nonCustomers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 4);
        })
            ->orderBy('name') // Sorteer alfabetisch op naam
            ->get(['id', 'name', 'email']);

        return response()->json(['nonCustomers' => $nonCustomers]);
    }


    public function show($id)
    {
        $user = User::with(['lotusRequests' => function ($query) {
            $query->withPivot('user_played_time', 'user_amount_km', 'user_feedback', 'registration_number', 'request_number'); // Voeg extra velden toe indien nodig
        }])->findOrFail($id);

        if ($user && $user->roles->contains('name', 'klant')){
            //Logica
            $lotusRequests = LotusRequest::where('customer_id', $id)->get();
        } else {
            $lotusRequests = $user->lotusRequests;
        }

        return Inertia::render('Users/ViewMember', [
            'member' => $user,
            'lotusRequests' => $lotusRequests,
        ]);
    }

}
