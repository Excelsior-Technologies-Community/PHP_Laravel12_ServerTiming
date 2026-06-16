<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::delete('/users/{id}', [HomeController::class, 'destroy'])->name('users.delete');

Route::delete('/users/bulk-delete', [HomeController::class, 'bulkDestroy'])->name('users.bulkDelete');