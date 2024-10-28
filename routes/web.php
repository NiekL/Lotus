<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LotusRequestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Overzicht
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Lotusaanvraag aanmaken
Route::get('/lotus-requests/create', function () {
    return Inertia::render('LotusRequests/Create');
})->middleware(['auth', 'verified'])->name('lotus-requests.create');

//Mijn lotus aanvragen
Route::get('/lotus-requests/mylotusrequests', function () {
    return Inertia::render('LotusRequests/MyLotusRequests');
})->middleware(['auth', 'verified'])->name('lotus-requests.mylotusrequests');

//Bekijk open aanvragen
Route::get('/lotus-requests/openlotusrequests', function () {
    return Inertia::render('LotusRequests/OpenLotusRequests');
})->middleware(['auth', 'verified'])->name('lotus-requests.openlotusrequests');

//Aanvragen single
Route::get('/lotus-requests/viewlotusrequest', function () {
    return Inertia::render('LotusRequests/ViewLotusRequest');
})->middleware(['auth', 'verified'])->name('lotus-requests.viewlotusrequest');

//Aanvragen accepteren
Route::get('/lotus-requests/acceptlotusrequests', function () {
    return Inertia::render('LotusRequests/AcceptLotusRequests');
})->middleware(['auth', 'verified'])->name('lotus-requests.acceptlotusrequests');


//Leden administratie
Route::get('/users/memberadministration', function () {
    return Inertia::render('Users/MemberAdministration');
})->middleware(['auth', 'verified'])->name('users.memberadministration');

//Leden administratie
Route::get('/users/customeradministration', function () {
    return Inertia::render('Users/CustomerAdministration');
})->middleware(['auth', 'verified'])->name('users.customeradministration');

//Bekijk lid
Route::get('/users/viewmember', function () {
    return Inertia::render('Users/ViewMember');
})->middleware(['auth', 'verified'])->name('users.viewmember');

//Bekijk klant
Route::get('/users/viewcustomer', function () {
    return Inertia::render('Users/ViewCustomer');
})->middleware(['auth', 'verified'])->name('users.viewcustomer');

//Gebruiker toevoegen
Route::get('/users/adduser', function () {
    return Inertia::render('Users/AddUser');
})->middleware(['auth', 'verified'])->name('users.adduser');

//Gebruiker toevoegen
Route::get('/declarationinfo', function () {
    return Inertia::render('DeclarationInfo');
})->middleware(['auth', 'verified'])->name('declarationinfo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
