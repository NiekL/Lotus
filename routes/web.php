<?php

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LotusRequestController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

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
    return Inertia::render('LotusRequests/Create');})
    ->middleware(['auth', 'verified', 'role:admin|klant|coordinator'])
    ->name('lotus-requests.create');

Route::post('/lotus-requests', [LotusRequestController::class, 'store'])->name('lotus-requests.store');


//Mijn lotus aanvragen
Route::get('/lotus-requests/mylotusrequests', [LotusRequestController::class, 'showUserRequests'])
    ->middleware(['auth', 'verified','role:admin|coordinator|lid|penningmeester|secretaris'])
    ->name('lotus-requests.mylotusrequests');

//Mijn klant aanvragen inzien
Route::get('/lotus-requests/mycustomerlotusrequests', [LotusRequestController::class, 'showCustomerRequests'])
    ->middleware(['auth', 'verified','role:admin|klant'])
    ->name('lotus-requests.mycustomerlotusrequests');


//Bekijk open aanvragen
Route::get('/lotus-requests/openlotusrequests', [LotusRequestController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator|lid|penningmeester|secretaris'])
    ->name('lotus-requests.openlotusrequests');


//Aanvragen single
//@TODO: Roles?
Route::get('/lotus-requests/viewlotusrequest', [LotusRequestController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.viewlotusrequest');

//Aanvragen bewerken
Route::middleware(['auth'])->group(function () {
    Route::get('/lotus-requests/{lotusRequest}/edit', [LotusRequestController::class, 'edit'])
        ->middleware(['auth', 'verified','role:admin|coordinator|klant'])
        ->name('lotus-requests.edit');
    Route::put('/lotus-requests/{lotusRequest}', [LotusRequestController::class, 'update'])
        ->middleware(['auth', 'verified','role:admin|coordinator|klant'])
        ->name('lotus-requests.update');
});


//Aanvragen accepteren of afwijzen overzicht
Route::get('/lotus-requests/acceptlotusrequests', [LotusRequestController::class, 'showAcceptLotusRequests'])
    ->middleware(['auth', 'verified','role:admin|coordinator|klant'])
    ->name('lotus-requests.acceptlotusrequests');

//Lotus aanvragen daadwerkelijk accepteren of afwijzen
Route::post('/lotus-requests/accept/{id}', [LotusRequestController::class, 'acceptRequest'])->name('lotus-requests.accept');
Route::post('/lotus-requests/decline/{id}', [LotusRequestController::class, 'declineRequest'])->name('lotus-requests.decline');
Route::post('/lotus-requests/accept/{id}', [LotusRequestController::class, 'acceptRequest'])->name('lotus-requests.accept');
Route::post('/lotus-requests/decline/{id}', [LotusRequestController::class, 'declineRequest'])->name('lotus-requests.decline');

//Als coordinator request sluiten
Route::post('/lotus-requests/toggle-closed/{id}', [LotusRequestController::class, 'toggleClosedStatus'])->name('lotus-requests.toggle-closed');



//Gebruikers aanmelden voor aanvraag
Route::post('/lotus-requests/{id}/signup', [LotusRequestController::class, 'signup'])
    ->name('lotus-requests.signup');

//Specifieke gebruiker aanmelden voor aanvraag als coordinator
Route::post('/lotus-requests/{id}/singupSepecificUser', [LotusRequestController::class, 'singupSepecificUser'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator'])
    ->name('lotus-requests.singupSepecificUser');

Route::delete('/lotus-requests/{userId}/removeUser', [LotusRequestController::class, 'removeUserFromRequest'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator']);



//Gebruikers afmelding voor aanvraag
Route::post('/lotus-requests/{id}/cancel-signup', [LotusRequestController::class, 'cancelSignup'])
    ->name('lotus-requests.cancelSignup');
Route::get('/lotus-requests/{id}/check-signup', [LotusRequestController::class, 'checkSignup'])
    ->name('lotus-requests.checkSignup');
Route::post('/lotus-requests/{lotusRequest}/unregister', [LotusRequestController::class, 'unregister'])
    ->middleware('auth');


//Gebruikers feedback (km vergoeding etc) op aanvraag
Route::post('/lotus-requests/{id}/submit-details', [LotusRequestController::class, 'submitDetails'])
    ->middleware(['auth', 'verified'])
    ->name('lotus-requests.submitDetails');


//Leden administratie
//Krijg alle gebruikers
Route::get('/users/memberadministration', [UserController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator|lid|penningmeester|secretaris'])
    ->name('users.memberadministration');

//Leden administratie
Route::get('/users/customeradministration', [UserController::class, 'customers'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator|penningmeester|secretaris'])
    ->name('users.customeradministration');

//Voor de lotus aanvraag om alleen de klanten in te zien om een aanvraag aan een klant te koppelen
Route::get('/users/customersOnly', [UserController::class, 'getCustomersOnly'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator'])
    ->name('users.customersOnly');

//Coordinator functies voor leden toevoegen aan een aanvraag
Route::get('/users/nonCustomers', [UserController::class, 'getNonCustomers'])
    ->middleware(['auth', 'verified', 'role:admin|coordinator'])
    ->name('users.nonCustomers');


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
    $roles = Role::all();
    return Inertia::render('Users/AddUser', [
        'roles' => $roles,  // Pass roles to the view
    ]);
})->middleware(['auth', 'verified', 'role:admin|coordinator|secretaris'])->name('users.adduser');

//Declaratie info
Route::get('/declarationinfo', function () {
    return Inertia::render('DeclarationInfo');
})->middleware(['auth', 'verified'])->name('declarationinfo');

//Handleidingen
Route::get('/userguide', function () {
    return Inertia::render('UserGuide');
})->middleware(['auth', 'verified'])->name('userguide');

//Factuurgegevens
Route::get('/profile/invoiceinfo', [ProfileController::class, 'invoiceInfo'])
    ->middleware(['auth', 'verified','role:admin|coordinator|klant|penningmeester|secretaris'])
    ->name('profile.invoiceinfo');

//Factuurgegevens opslaan
Route::post('/profile/billing-info', [ProfileController::class, 'saveBillingInfo'])
    ->name('profile.billing-info.save');

//Nieuwe gebruiker toevoegen wanner ingelogd
Route::post('/users/adduser', [RegisteredUserController::class, 'storeNewMember'])
    ->middleware(['auth', 'verified'])
    ->name('users.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::get('/testmail', function () {
    Mail::raw('Dit is een testmail.', function ($message) {
        $message->to('niekluttikhof8@gmail.com')
            ->subject('Testmail vanaf Laravel');
    });

    return 'Mail verzonden!';
});

require __DIR__.'/auth.php';
