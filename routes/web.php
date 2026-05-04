<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// DELETE ROUTE
Route::delete('/users/{id}', [HomeController::class, 'destroy'])->name('users.delete');