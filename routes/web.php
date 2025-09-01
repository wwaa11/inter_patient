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

    // Patient Management Routes (accessible to all authenticated users)
    Route::get('/', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{hn}', [PatientController::class, 'show'])->name('patients.show');

    // Patient Notes Routes (accessible to all authenticated users)
    Route::post('/patients/{hn}/notes', [PatientController::class, 'storeNote'])->name('patients.notes.store');

    // Admin-only routes
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/new/patient', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/get-info', [PatientController::class, 'getPatientInfo'])->name('patients.getInfo');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{hn}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::post('/patients/{hn}/update', [PatientController::class, 'update'])->name('patients.update');
        Route::post('/patients/{hn}/delete', [PatientController::class, 'destroy'])->name('patients.destroy');

        // Patient Passport Routes
        Route::post('/patients/{hn}/passports', [PatientController::class, 'storePassport'])->name('patients.passports.store');
        Route::post('/patients/{hn}/passports/{id}', [PatientController::class, 'destroyPassport'])->name('patients.passports.destroy');

        // Patient Medical Records Routes
        Route::post('/patients/{hn}/medical-reports', [PatientController::class, 'storeMedicalReport'])->name('patients.medical-reports.store');
        Route::post('/patients/{hn}/medical-reports/{id}', [PatientController::class, 'destroyMedicalReport'])->name('patients.medical-reports.destroy');

        // Patient Notes Routes (admin only)
        Route::post('/patients/{hn}/notes/{id}', [PatientController::class, 'destroyNote'])->name('patients.notes.destroy');
    });

    // File viewing routes
    Route::get('/files/{hn}/{filename}', [PatientController::class, 'viewFile'])->name('files.view');

    // Dashboard Routes
    Route::get('/dashboard', [WebController::class, 'Dashboard'])->name('dashboard');

});
