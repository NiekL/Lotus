<?php
namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all()->map(function ($user) {
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

    public function show($id)
    {
        $user = User::with(['lotusRequests' => function ($query) {
            $query->withPivot('user_played_time', 'user_amount_km', 'user_feedback'); // Voeg extra velden toe indien nodig
        }])->findOrFail($id);

        return Inertia::render('Users/ViewMember', [
            'member' => $user,
            'lotusRequests' => $user->lotusRequests
        ]);
    }

}
