<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/register', [RegisterController::class, 'register'])->name('register');
