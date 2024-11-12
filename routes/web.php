<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LotusRequestController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);

    if (auth()->check()) {
        // Redirect to dashboard if already logged in
        return redirect()->route('dashboard');
    }

    return Inertia::render('Auth/Login', [  // Assume 'Auth/Login' is your login component
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'noAccountYet' => true,
    ]);
});

//Overzicht
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

//Mededelingen
Route::resource('announcements', AnnouncementsController::class)
    ->only(['index', 'store', 'destroy'])
    ->middleware(['auth', 'verified']);

//Lotusaanvraag aanmaken
Route::get('/lotus-requests/create', function () {
    return Inertia::render('LotusRequests/Create');
})->middleware(['auth', 'verified'])->name('lotus-requests.create');

Route::post('/lotus-requests', [LotusRequestController::class, 'store'])->name('lotus-requests.store');


//Mijn lotus aanvragen
Route::get('/lotus-requests/mylotusrequests', [LotusRequestController::class, 'showUserRequests'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.mylotusrequests');


//Bekijk open aanvragen
Route::get('/lotus-requests/openlotusrequests', [LotusRequestController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.openlotusrequests');


//Aanvragen single
Route::get('/lotus-requests/viewlotusrequest', [LotusRequestController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.viewlotusrequest');

//Aanvragen accepteren of afwijzen overzicht
Route::get('/lotus-requests/acceptlotusrequests', [LotusRequestController::class, 'showAcceptLotusRequests'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.acceptlotusrequests');

//Lotus aanvragen daadwerkelijk accepteren of afwijzen
Route::post('/lotus-requests/accept/{id}', [LotusRequestController::class, 'acceptRequest'])->name('lotus-requests.accept');
Route::post('/lotus-requests/decline/{id}', [LotusRequestController::class, 'declineRequest'])->name('lotus-requests.decline');
Route::post('/lotus-requests/accept/{id}', [LotusRequestController::class, 'acceptRequest'])->name('lotus-requests.accept');
Route::post('/lotus-requests/decline/{id}', [LotusRequestController::class, 'declineRequest'])->name('lotus-requests.decline');


//Gebruikers aanmelden voor aanvraag
Route::post('/lotus-requests/{id}/signup', [LotusRequestController::class, 'signup'])
    ->name('lotus-requests.signup');

//Gebruikers afmelding voor aanvraag
Route::post('/lotus-requests/{id}/cancel-signup', [LotusRequestController::class, 'cancelSignup'])
    ->name('lotus-requests.cancelSignup');
Route::get('/lotus-requests/{id}/check-signup', [LotusRequestController::class, 'checkSignup'])
    ->name('lotus-requests.checkSignup');

//Gebruikers feedback (km vergoeding etc) op aanvraag
Route::post('/lotus-requests/{id}/submit-details', [LotusRequestController::class, 'submitDetails'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.submitDetails');


//Leden administratie
//Krijg alle gebruikers
Route::get('/users/memberadministration', [UserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('users.memberadministration');

//Leden administratie
Route::get('/users/customeradministration', function () {
    return Inertia::render('Users/CustomerAdministration');
})->middleware(['auth', 'verified'])->name('users.customeradministration');

//Bekijk lid
Route::get('/users/viewmember/{id}', [UserController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('users.viewmember');

//Bekijk klant
Route::get('/users/viewcustomer', function () {
    return Inertia::render('Users/ViewCustomer');
})->middleware(['auth', 'verified'])->name('users.viewcustomer');

//Gebruiker toevoegen
Route::get('/users/adduser', function () {
    return Inertia::render('Users/AddUser');
})->middleware(['auth', 'verified'])->name('users.adduser');

//Declaratie info
Route::get('/declarationinfo', function () {
    return Inertia::render('DeclarationInfo');
})->middleware(['auth', 'verified'])->name('declarationinfo');

//Factuurgegevens
Route::get('/profile/invoiceinfo', [ProfileController::class, 'invoiceInfo'])
    ->middleware(['auth', 'verified'])
    ->name('profile.invoiceinfo');

//Factuurgegevens opslaan
Route::post('/profile/billing-info', [ProfileController::class, 'saveBillingInfo'])
    ->name('profile.billing-info.save');

//Nieuwe gebruiker toevoegen wanner ingelogd
Route::post('/users', [RegisteredUserController::class, 'storeNewMember'])
    ->middleware(['auth', 'verified'])
    ->name('users.store');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
