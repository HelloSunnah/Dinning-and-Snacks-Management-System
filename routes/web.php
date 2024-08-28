<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;

Route::get('/', function () {
    return view('Authentication.Login');
});

Route::get('/login', [LoginController::class, 'login_form'])->name('login.form');
Route::post('/login/post', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/register', [RegisterController::class, 'register_form'])->name('register');
Route::post('/register/post', [RegisterController::class, 'register'])->name('register');



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
