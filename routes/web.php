<?php

use App\Http\Controllers\AgriculturalSupplyStoreUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\FarmUserController;
use App\Http\Controllers\NurseryUserController;
use App\Http\Controllers\NurseryWarehouseEntityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeedlingPurchaseRequestController;
use App\Http\Controllers\SeedlingServiceController;
use App\Http\Controllers\SeedTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::middleware('auth:nursery_web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('guest:nursery_web')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('{broker}/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('{broker}/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('{broker}/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('{broker}/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::get('auth/{provider}', [SocialLoginController::class, 'redirectToProvider'])->name('login.social');
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback'])->name('login.social.callback');

Route::middleware(['auth:nursery_web', 'complete-registration'])->group(function () {
    Route::get('/dashboard', function () {
        return view('home', ['page_title' => 'الرئيسية']);
    })->name('dashboard');

    Route::get('farm-users/search', [FarmUserController::class, 'search'])->name('farmer.search');
    Route::post('farm-users/quick-add', [FarmUserController::class, 'quickStore'])->name('farmer.quick-store');

    Route::get('agricultural-supply-store-user/search', [AgriculturalSupplyStoreUserController::class, 'search'])->name('agricultural-supply-store-user.search');
    Route::post('agricultural-supply-store-user/quick-store', [AgriculturalSupplyStoreUserController::class, 'quickStore'])->name('agricultural-supply-store-user.quick-store');

    Route::get('seed-types/search', [SeedTypeController::class, 'search'])->name('seed-types.search');
    Route::post('seed-types', [SeedTypeController::class, 'store'])->name('seed-types.store');

    Route::get('seedling-services/search', [SeedlingServiceController::class, 'search'])->name('seedling-services.search');
    Route::get('seedling-services/get/{id}', [SeedlingServiceController::class, 'get'])->name('seedling-services.get');
    Route::resource('seedling-services', SeedlingServiceController::class);

    Route::resource('seedling-purchase-requests', SeedlingPurchaseRequestController::class);

    Route::resource('warehouse-entities', NurseryWarehouseEntityController::class);


    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::middleware(['auth:nursery_web', 'completed-registration'])->group(function () {
    Route::get('complete-registration', [NurseryUserController::class, 'createCompleteRegistration'])->name('nursery.create-complete-registration');

    Route::post('complete-registration', [NurseryUserController::class, 'storeCompleteRegistration'])->name('nursery.store-complete-registration');
});

//require __DIR__.'/auth.php';
