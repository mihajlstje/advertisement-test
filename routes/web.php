<?php

use App\Enums\Roles;
use App\Http\Controllers\AdvertController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
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

Route::get('/', SearchController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:'.implode("|",[Roles::CUSTOMER->name(), Roles::ADMIN->name()]))->group(function(){

        Route::resource('advert', AdvertController::class)->except('show');

    });
});

Route::get('advert/{advert}', [AdvertController::class, 'show'])->name('advert.show');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';