<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PostController;
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

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'adminLoginPage'])->name('admin.login');
    Route::post('login', [AuthenticatedSessionController::class, 'loginAdmin']);
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('posts',[PostController::class,'index'])->name('admin.posts');
    Route::get('/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post_id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/update/{post_id?}', [PostController::class, 'update'])->name('admin.posts.update');

    Route::get('pages',[PagesController::class,'index'])->name('admin.pages');
    Route::get('/pages/create', [PagesController::class, 'create'])->name('admin.pages.create');
    Route::post('/pages', [PagesController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page_id}', [PagesController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/update/{page_id}', [PagesController::class, 'update'])->name('admin.pages.update');


});


//require __DIR__.'/auth.php';





