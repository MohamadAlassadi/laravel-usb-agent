<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [WebController::class, 'showLogin'])->name('login');
Route::get('/', [WebController::class, 'showLogin']);
Route::get('/dashboard', [WebController::class, 'showDashboard'])->name('dashboard');
Route::get('/logout', [WebController::class, 'logout'])->name('logout');
