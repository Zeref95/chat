<?php

use App\Http\Controllers\DialogController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/chat/{id?}', function () {
        return Inertia::render('Chat');
    })->name('chat');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/dialogs', [DialogController::class, 'index'])->name('dialogs.index');
    Route::post('/dialogs/create', [DialogController::class, 'create'])->name('dialogs.create');

    Route::get('/messages/{id}', [MessageController::class, 'index'])->name('message.index');
    Route::post('/messages/create', [MessageController::class, 'create'])->name('message.create');
});



require __DIR__.'/auth.php';
