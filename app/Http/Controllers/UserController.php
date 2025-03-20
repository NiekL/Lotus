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
        })->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email, // Zorg dat 'place' bestaat in je model of pas dit aan
            ];
        })->sortBy('name');

        return Inertia::render('Users/MemberAdministration', [
            'members' => $users
        ]);
    }

    public function customers()
    {

        $customers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        })->sortBy('name');

        return Inertia::render('Users/CustomerAdministration', [
            'customers' => $customers
        ]);
    }

    public function getCustomersOnly()
    {

        $customers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })->get(['id', 'name', 'email'])->sortBy('name');

        return response()->json(['customers' => $customers]);
    }

    public function getNonCustomers()
    {
        $nonCustomers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 4);
        })->get(['id', 'name', 'email'])->sortBy('name');

        return response()->json(['nonCustomers' => $nonCustomers]);
    }


    public function show($id)
    {
        $user = User::with(['lotusRequests' => function ($query) {
            $query->withPivot('user_played_time', 'user_amount_km', 'user_feedback'); // Voeg extra velden toe indien nodig
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
