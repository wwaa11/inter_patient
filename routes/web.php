<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'Login'])->name('login');
Route::post('/login', [LoginController::class, 'LoginRequest'])->name('login.request');
Route::post('/logout', [LoginController::class, 'LogoutRequest'])->name('logout');

Route::group(['middleware' => 'auth'], function () {

    // Patient Management Routes
    Route::get('/', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{hn}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{hn}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::post('/patients/{hn}/update', [PatientController::class, 'update'])->name('patients.update');
    Route::post('/patients/{hn}/delete', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::post('/patients/get-info', [PatientController::class, 'getPatientInfo'])->name('patients.getInfo');

    // Dashboard Routes
    Route::get('/dashboard', [WebController::class, 'Dashboard'])->name('dashboard');
    

});
