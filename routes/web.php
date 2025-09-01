<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SettingsController;
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
        
        // Settings Routes (admin only)
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        
        // Embassy Routes
        Route::post('/settings/embassies', [SettingsController::class, 'storeEmbassy'])->name('settings.embassies.store');
        Route::put('/settings/embassies/{id}', [SettingsController::class, 'updateEmbassy'])->name('settings.embassies.update');
        Route::delete('/settings/embassies/{id}', [SettingsController::class, 'destroyEmbassy'])->name('settings.embassies.destroy');
        
        // Guarantee Case Routes
        Route::post('/settings/guarantee-cases', [SettingsController::class, 'storeGuaranteeCase'])->name('settings.guarantee-cases.store');
        Route::put('/settings/guarantee-cases/{id}', [SettingsController::class, 'updateGuaranteeCase'])->name('settings.guarantee-cases.update');
        Route::delete('/settings/guarantee-cases/{id}', [SettingsController::class, 'destroyGuaranteeCase'])->name('settings.guarantee-cases.destroy');
    });

    // File viewing routes
    Route::get('/files/{hn}/{filename}', [PatientController::class, 'viewFile'])->name('files.view');

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');

});
