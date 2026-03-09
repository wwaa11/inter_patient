<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotifierController;
use App\Http\Controllers\PatientApiController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientFileController;
use App\Http\Controllers\PatientGuaranteeController;
use App\Http\Controllers\PatientMedicalReportController;
use App\Http\Controllers\PatientNoteController;
use App\Http\Controllers\PatientPassportController;
use App\Http\Controllers\PreAuthorizationController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/login', [LoginController::class, 'Login'])->name('login');
Route::post('/login', [LoginController::class, 'LoginRequest'])->name('login.request');
Route::post('/logout', [LoginController::class, 'LogoutRequest'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Patient Basic Management
    Route::get('/', [PatientController::class, 'index'])->name('patients');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/preauth', [App\Http\Controllers\DashboardController::class, 'preauth'])->name('dashboard.preauth');
    Route::get('/dashboard/admissions', [App\Http\Controllers\DashboardController::class, 'admissions'])->name('dashboard.admissions');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{hn}', [PatientController::class, 'view'])->name('patients.view');
    Route::post('/patients/{hn}/notes', [PatientNoteController::class, 'store'])->name('patients.notes.store');

    // Secure File Viewing
    Route::get('/files/{hn}/{filename}', [PatientFileController::class, 'view'])->name('files.view');

            // Pre Authorization: list, show, and view/download attachments
            Route::get('/preauth', [PreAuthorizationController::class, 'index'])->name('preauth.index');
            Route::get('/preauth/create', [PreAuthorizationController::class, 'create'])->name('preauth.create')->middleware('admin');
            Route::get('/preauth/{preauth}', [PreAuthorizationController::class, 'show'])->name('preauth.show');
            Route::get('/preauth/{preauth}/attachments/{attachment}/download', [PreAuthorizationController::class, 'downloadAttachment'])->name('preauth.attachments.download');
            Route::get('/preauth/{preauth}/attachments/{attachment}/view', [PreAuthorizationController::class, 'viewAttachment'])->name('preauth.attachments.view');

            // Admissions: list and show for all roles
            Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');
            Route::get('/admissions/create', [AdmissionController::class, 'create'])->name('admissions.create')->middleware('admin');
            Route::get('/admissions/{admission}', [AdmissionController::class, 'show'])->name('admissions.show');
            Route::get('/admissions/{admission}/attachments/{attachment}/download', [AdmissionController::class, 'downloadAttachment'])->name('admissions.attachments.download');
            Route::get('/admissions/{admission}/attachments/{attachment}/view', [AdmissionController::class, 'viewAttachment'])->name('admissions.attachments.view');

    // Admin-Only Routes
    Route::middleware(['admin'])->group(function () {

        // Patient Advanced Management
        Route::get('/new/patient', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{hn}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::post('/patients/{hn}/update', [PatientController::class, 'update'])->name('patients.update');
        Route::post('/patients/{hn}/delete', [PatientController::class, 'destroy'])->name('patients.destroy');

        // Patient Sub-Resources
        Route::post('/patients/{hn}/passports', [PatientPassportController::class, 'store'])->name('patients.passports.store');
        Route::post('/patients/{hn}/passports/{id}/delete', [PatientPassportController::class, 'destroy'])->name('patients.passports.destroy');
        Route::post('/patients/{hn}/medical-reports', [PatientMedicalReportController::class, 'store'])->name('patients.medical-reports.store');
        Route::post('/patients/{hn}/medical-reports/{id}/delete', [PatientMedicalReportController::class, 'destroy'])->name('patients.medical-reports.destroy');
        Route::post('/patients/{hn}/notes/{id}/delete', [PatientNoteController::class, 'destroy'])->name('patients.notes.destroy');

        // Patient Guarantees
        Route::controller(PatientGuaranteeController::class)->prefix('patients/{hn}/guarantees')->name('patients.guarantees.')->group(function () {
            // Main Guarantees
            Route::get('main/create', 'createMain')->name('main.create');
            Route::post('main', 'storeMain')->name('main.store');
            Route::get('main/{id}/edit', 'editMain')->name('main.edit');
            Route::post('main/{id}/update', 'updateMain')->name('main.update');
            Route::post('main/extend/{id}', 'extendMain')->name('main.extend');
            Route::post('main/{id}/delete-file', 'deleteMainFile')->name('main.delete-file');
            Route::post('main/{id}/delete', 'destroyMain')->name('main.destroy');

            // Additional Guarantees
            Route::get('additional/create', 'createAdditional')->name('additional.create');
            Route::post('additional', 'storeAdditional')->name('additional.store');
            Route::get('additional/{id}/add-detail', 'createAdditionalDetail')->name('additional.detail.create');
            Route::post('additional/{id}/add-detail', 'storeAdditionalDetail')->name('additional.detail.store');
            Route::get('additional/{id}/edit', 'editAdditionalDetail')->name('additional.edit');
            Route::post('additional/{id}/update', 'updateAdditionalDetail')->name('additional.update');
            Route::post('additional/{id}/delete', 'destroyAdditionalDetail')->name('additional.destroy');
            Route::post('additional/detail/{id}/use', 'setAdditionalUseDate')->name('additional.detail.use');
        });

        // External API Integration
        Route::post('/get-info', [PatientApiController::class, 'getPatientInfo'])->name('patients.getInfo');

        // User Management
        Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');

        // Settings (Embassies & Guarantee Cases)
        Route::controller(SettingsController::class)->prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::prefix('embassies')->name('embassies.')->group(function () {
                Route::post('/', 'storeEmbassy')->name('store');
                Route::post('/{id}/update', 'updateEmbassy')->name('update');
                Route::post('/{id}/delete', 'destroyEmbassy')->name('destroy');
            });

            Route::prefix('guarantee-cases')->name('guarantee-cases.')->group(function () {
                Route::post('/', 'storeGuaranteeCase')->name('store');
                Route::post('/{id}/update', 'updateGuaranteeCase')->name('update');
                Route::post('/{id}/delete', 'destroyGuaranteeCase')->name('destroy');
            });
        });

        // Provider Management
        Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
        Route::get('/providers/create', [ProviderController::class, 'create'])->name('providers.create');
        Route::post('/providers', [ProviderController::class, 'store'])->name('providers.store');
        Route::get('/providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');
        Route::get('/providers/{provider}/edit', [ProviderController::class, 'edit'])->name('providers.edit');
        Route::post('/providers/{provider}/update', [ProviderController::class, 'update'])->name('providers.update');
        Route::post('/providers/{provider}/delete', [ProviderController::class, 'destroy'])->name('providers.destroy');

        // Pre Authorization CRUD
        Route::post('/preauth', [PreAuthorizationController::class, 'store'])->name('preauth.store');
        Route::post('/preauth/{preauth}/update', [PreAuthorizationController::class, 'update'])->name('preauth.update');
        Route::post('/preauth/{preauth}/delete', [PreAuthorizationController::class, 'destroy'])->name('preauth.destroy');
        Route::post('/preauth/{preauth}/attachments', [PreAuthorizationController::class, 'storeAttachment'])->name('preauth.attachments.store');
        Route::post('/preauth/{preauth}/attachments/{attachment}/delete', [PreAuthorizationController::class, 'destroyAttachment'])->name('preauth.attachments.destroy');

        // Admissions CRUD
        Route::post('/admissions', [AdmissionController::class, 'store'])->name('admissions.store');
        Route::post('/admissions/{admission}/update', [AdmissionController::class, 'update'])->name('admissions.update');
        Route::post('/admissions/{admission}/delete', [AdmissionController::class, 'destroy'])->name('admissions.destroy');
        Route::post('/admissions/{admission}/attachments', [AdmissionController::class, 'storeAttachment'])->name('admissions.attachments.store');
        Route::post('/admissions/{admission}/attachments/{attachment}/delete', [AdmissionController::class, 'destroyAttachment'])->name('admissions.attachments.destroy');

        // Service Types
        Route::get('/service-types', [ServiceTypeController::class, 'index'])->name('service-types.index');
        Route::get('/service-types/create', [ServiceTypeController::class, 'create'])->name('service-types.create');
        Route::post('/service-types', [ServiceTypeController::class, 'store'])->name('service-types.store');
        Route::get('/service-types/{service_type}/edit', [ServiceTypeController::class, 'edit'])->name('service-types.edit');
        Route::post('/service-types/{service_type}/update', [ServiceTypeController::class, 'update'])->name('service-types.update');
        Route::post('/service-types/{service_type}/delete', [ServiceTypeController::class, 'destroy'])->name('service-types.destroy');

        // Notifiers
        Route::get('/notifiers', [NotifierController::class, 'index'])->name('notifiers.index');
        Route::get('/notifiers/create', [NotifierController::class, 'create'])->name('notifiers.create');
        Route::post('/notifiers', [NotifierController::class, 'store'])->name('notifiers.store');
        Route::get('/notifiers/{notifier}/edit', [NotifierController::class, 'edit'])->name('notifiers.edit');
        Route::post('/notifiers/{notifier}/update', [NotifierController::class, 'update'])->name('notifiers.update');
        Route::post('/notifiers/{notifier}/delete', [NotifierController::class, 'destroy'])->name('notifiers.destroy');
    });
});
