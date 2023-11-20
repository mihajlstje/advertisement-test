<?php

use App\Enums\Roles;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;

Route::middleware('role:'.Roles::ADMIN->name())->group(function(){

    Route::resource('category', CategoryController::class);

    Route::patch('customer/{customer}', [CustomerController::class, 'ban']);
    
    Route::resource('customer', CustomerController::class)->only(['index', 'destroy']);

});