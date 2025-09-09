<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'Login'])->name('login');
Route::post('/login', [LoginController::class, 'LoginRequest'])->name('login.request');
Route::post('/logout', [LoginController::class, 'LogoutRequest'])->name('logout');

Route::group(['middleware' => 'auth'], function () {

    // Patient Management Routes (accessible to all authenticated users)
    Route::get('/', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{hn}', [PatientController::class, 'view'])->name('patients.view');

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

        // Guarantee routes
        Route::get('/patients/{hn}/guarantees/main/create', [PatientController::class, 'createMainGuarantee'])->name('patients.guarantees.main.create');
        Route::post('/patients/{hn}/guarantees/main', [PatientController::class, 'storeMainGuarantee'])->name('patients.guarantees.main.store');
        Route::get('/patients/{hn}/guarantees/main/{id}/edit', [PatientController::class, 'editMainGuarantee'])->name('patients.guarantees.main.edit');
        Route::post('/patients/{hn}/guarantees/main/{id}/update', [PatientController::class, 'updateMainGuarantee'])->name('patients.guarantees.main.update');
        Route::post('/patients/{hn}/guarantees/main/extend/{id}', [PatientController::class, 'extendMainGuarantee'])->name('patients.guarantees.main.extend');
        Route::post('/patients/{hn}/guarantees/main/{id}', [PatientController::class, 'destroyMainGuarantee'])->name('patients.guarantees.main.destroy');

        Route::get('/patients/{hn}/guarantees/additional/create', [PatientController::class, 'createAdditionalGuarantee'])->name('patients.guarantees.additional.create');
        Route::post('/patients/{hn}/guarantees/additional', [PatientController::class, 'storeGuaranteeAdditional'])->name('patients.guarantees.additional.store');
        Route::get('/patients/{hn}/guarantees/additional/{id}/add-detail', [PatientController::class, 'createGuaranteeDetail'])->name('patients.guarantees.additional.detail.create');
        Route::post('/patients/{hn}/guarantees/additional/{id}/add-detail', [PatientController::class, 'storeGuaranteeDetail'])->name('patients.guarantees.additional.detail.store');
        Route::get('/patients/{hn}/guarantees/additional/{id}/edit', [PatientController::class, 'editGuaranteeAdditionalDetail'])->name('patients.guarantees.additional.edit');
        Route::post('/patients/{hn}/guarantees/additional/{id}', [PatientController::class, 'updateGuaranteeAdditionalDetail'])->name('patients.guarantees.additional.update');
        Route::post('/patients/{hn}/guarantees/additional/{id}/delete', [PatientController::class, 'destroyGuaranteeAdditionalDetail'])->name('patients.guarantees.additional.destroy');

        // User Management Routes (admin only)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

        // Settings Routes (admin only)
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

        // Embassy Routes
        Route::post('/settings/store/embassies', [SettingsController::class, 'storeEmbassy'])->name('settings.embassies.store');
        Route::post('/settings/update/embassies/{id}', [SettingsController::class, 'updateEmbassy'])->name('settings.embassies.update');
        Route::post('/settings/delete/embassies/{id}', [SettingsController::class, 'destroyEmbassy'])->name('settings.embassies.destroy');

        // Guarantee Case Routes
        Route::post('/settings/store/guarantee-cases', [SettingsController::class, 'storeGuaranteeCase'])->name('settings.guarantee-cases.store');
        Route::post('/settings/update/guarantee-cases/{id}', [SettingsController::class, 'updateGuaranteeCase'])->name('settings.guarantee-cases.update');
        Route::post('/settings/delete/guarantee-cases/{id}', [SettingsController::class, 'destroyGuaranteeCase'])->name('settings.guarantee-cases.destroy');
    });

    // File viewing routes
    Route::get('/files/{hn}/{filename}', [PatientController::class, 'viewFile'])->name('files.view');

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');

});
