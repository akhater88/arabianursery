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
use App\Http\Controllers\NurseryUserDashboardController;
use App\Http\Controllers\NurseryWarehouseEntityController;
use App\Http\Controllers\NurserySeedsSaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeedlingPurchaseRequestController;
use App\Http\Controllers\SeedlingServiceController;
use App\Http\Controllers\SeedTypeController;
use App\Http\Controllers\NurseryController;
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
    Route::get('/dashboard', [NurseryUserDashboardController::class, 'index'])->name('dashboard');

    Route::get('farm-users/search', [FarmUserController::class, 'search'])->name('farmer.search');
    Route::post('farm-users/quick-add', [FarmUserController::class, 'quickStore'])->name('farmer.quick-store');

    Route::get('agricultural-supply-store-user/search', [AgriculturalSupplyStoreUserController::class, 'search'])->name('agricultural-supply-store-user.search');
    Route::post('agricultural-supply-store-user/quick-store', [AgriculturalSupplyStoreUserController::class, 'quickStore'])->name('agricultural-supply-store-user.quick-store');

    Route::get('seed-types/search', [SeedTypeController::class, 'search'])->name('seed-types.search');
    Route::post('seed-types', [SeedTypeController::class, 'store'])->name('seed-types.store');

    Route::post('seedling-services/media', [SeedlingServiceController::class, 'storeMedia'])->name('seedling-services.store-media');
    Route::get('seedling-services/search', [SeedlingServiceController::class, 'search'])->name('seedling-services.search');
    Route::get('seedling-services/export', [SeedlingServiceController::class, 'export'])->name('seedling-services.export');
    Route::get('seedling-services/get/{id}', [SeedlingServiceController::class, 'get'])->name('seedling-services.get');
    Route::put('seedling-services/{seedling_service}/status', [SeedlingServiceController::class, 'updateStatus'])->name('seedling-services.update-status');
    Route::resource('seedling-services', SeedlingServiceController::class);
    Route::post('seedling-services/share/{seedling_service}', [SeedlingServiceController::class, 'share'])->name('seedling-services.share');


    Route::put('nursery-seeds-sales/{nursery_seeds_sale}/status', [NurserySeedsSaleController::class, 'updateStatus'])->name('nursery-seeds-sales.update-status');
    Route::get('nursery-seeds-sales/search', [NurserySeedsSaleController::class, 'search'])->name('nursery-seeds-sales.search');
    Route::get('nursery-seeds-sales/export', [NurserySeedsSaleController::class, 'export'])->name('nursery-seeds-sales.export');
    Route::get('nursery-seeds-sales/get/{id}', [NurserySeedsSaleController::class, 'get'])->name('nursery-seeds-sales.get');
    Route::resource('nursery-seeds-sales', NurserySeedsSaleController::class);

    Route::get('seedling-purchase-requests/export', [SeedlingPurchaseRequestController::class, 'export'])->name('seedling-purchase-requests.export');
    Route::resource('seedling-purchase-requests', SeedlingPurchaseRequestController::class);

    Route::get('warehouse-entities/export', [NurseryWarehouseEntityController::class, 'export'])->name('warehouse-entities.export');
    Route::resource('warehouse-entities', NurseryWarehouseEntityController::class)->parameters([
        'warehouse-entities' => 'nursery_warehouse_entity'
    ]);


    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('nursery/operators', [NurseryUserController::class,'showNurseriesUsers'])->name('nursery-operators');
    Route::get('nursery/operator/create', [NurseryUserController::class,'createNurseriesUsers'])->name('nursery-operators.create');
    Route::post('nursery/operator/create', [NurseryUserController::class,'storeNurseriesUsers'])->name('nursery-operators.store');
    Route::get('nursery/operator/{nursery_user}', [NurseryUserController::class,'editNurseriesUsers'])->name('nursery-operators.edit');
    Route::put('nursery/operator/{nursery_user}', [NurseryUserController::class,'updateNurseriesUsers'])->name('nursery-operators.update');
    Route::put('nursery/operator/delete/{nursery_user}', [NurseryUserController::class,'destroyNurseriesUsers'])->name('nursery-operators.destroy');

    Route::get('nursery/farmers', [NurseryController::class,'showNurseryFarmers'])->name('nursery-farmers');
    Route::get('nursery/farmers/details/{farmer}', [NurseryController::class,'showNurseryFarmerDetails'])->name('nursery-farmers.details');


    Route::get('nursery/reports', [NurseryUserController::class,'showNurseriesUsers'])->name('nursery-reports');

    Route::get('nursery/shared/seedlings', [SeedlingServiceController::class,'getSharedSeedlings'])->name('shared-seedlings');


});

Route::middleware(['auth:nursery_web', 'completed-registration'])->group(function () {
    Route::get('complete-registration', [NurseryUserController::class, 'createCompleteRegistration'])->name('nursery.create-complete-registration');

    Route::post('complete-registration', [NurseryUserController::class, 'storeCompleteRegistration'])->name('nursery.store-complete-registration');
});

//require __DIR__.'/auth.php';
