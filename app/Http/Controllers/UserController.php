<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

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

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Gebruiker bijgewerkt.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 1. Verwijder alle LotusRequests waar deze gebruiker klant is
        LotusRequest::where('customer_id', $user->id)->delete();

        foreach ($user->lotusRequests as $lotusRequest) {
            // Verlaag filled_spots maar voorkom negatieve waarden
            if ($lotusRequest->filled_spots > 0) {
                $lotusRequest->decrement('filled_spots');
            }
        }

        // 2. Ontkoppel gebruiker van alle LotusRequests (pivot tabel)
        $user->lotusRequests()->detach();

        // 3. Verwijder billing info indien aanwezig
        if ($user->billingInfo) {
            $user->billingInfo->delete();
        }

        // 4. Verwijder gebruiker zelf
        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Gebruiker verwijderd.');
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
