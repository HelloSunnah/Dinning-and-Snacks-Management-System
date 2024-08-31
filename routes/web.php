<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\LogoutController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Backend\ManpowerController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\PredictionController;
use App\Http\Controllers\Backend\DistributionController;

// Public routes
Route::get('/', function () {
    return view('Authentication.Login');
});
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('logout', [LogoutController::class, 'logout'])->name('logout');

// Routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('menu', MenuController::class);
    Route::resource('manpower', ManpowerController::class);
    
    Route::resource('distribution', DistributionController::class);
    Route::get('/get-details', [DistributionController::class, 'getDetails'])->name('get-details');
    Route::delete('/distributions/bulk-delete', [DistributionController::class, 'bulkDelete'])->name('distribution.bulkDelete');


    Route::get('/prediction', [PredictionController::class, 'index'])->name('predictions.index');
    Route::get('/prediction/calculate', [PredictionController::class, 'calculate'])->name('calculate');
});
