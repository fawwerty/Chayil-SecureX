<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AdminIncidentController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientIncidentController;
use App\Http\Controllers\ClientReportController;
use App\Http\Controllers\ClientComplianceController;
use App\Http\Controllers\ClientTrainingController;
use App\Http\Controllers\ClientMessageController;
use App\Http\Controllers\ClientAccountController;
use App\Http\Controllers\ClientBillingController;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('verify-2fa', [AuthController::class, 'verify2fa']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// Admin routes
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('summary', [AdminDashboardController::class, 'summary']);
    Route::get('clients', [AdminClientController::class, 'index']);
    Route::post('clients', [AdminClientController::class, 'store']);
    Route::patch('clients/{clientId}', [AdminClientController::class, 'update']);
    Route::delete('clients/{clientId}', [AdminClientController::class, 'destroy']);
    Route::get('incidents', [AdminIncidentController::class, 'index']);
    Route::post('incidents', [AdminIncidentController::class, 'store']);
    Route::patch('incidents/{incidentId}', [AdminIncidentController::class, 'update']);
    Route::get('reports', [AdminReportController::class, 'index']);
    Route::post('reports/export', [AdminReportController::class, 'export']);
    Route::get('users', [AdminUserController::class, 'index']);
    Route::patch('roles/{userId}', [AdminUserController::class, 'updateRole']);
    Route::get('settings', [AdminSettingsController::class, 'index']);
    Route::patch('settings', [AdminSettingsController::class, 'update']);
});

// Client routes
Route::middleware(['auth:api', 'role:client'])->prefix('client')->group(function () {
    Route::get('dashboard', [ClientDashboardController::class, 'index']);
    Route::get('incidents', [ClientIncidentController::class, 'index']);
    Route::post('incidents/report', [ClientIncidentController::class, 'report']);
    Route::get('reports', [ClientReportController::class, 'index']);
    Route::get('compliance', [ClientComplianceController::class, 'index']);
    Route::patch('compliance', [ClientComplianceController::class, 'update']);
    Route::get('training/modules', [ClientTrainingController::class, 'modules']);
    Route::get('training/progress', [ClientTrainingController::class, 'progress']);
    Route::post('training/progress', [ClientTrainingController::class, 'storeProgress']);
    Route::get('messages', [ClientMessageController::class, 'index']);
    Route::post('messages', [ClientMessageController::class, 'store']);
    Route::get('billing', [ClientBillingController::class, 'index']);
    Route::get('account', [ClientAccountController::class, 'index']);
    Route::patch('account', [ClientAccountController::class, 'update']);
});


Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');
