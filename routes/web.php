<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminEmployeeLogsController;
use App\Http\Controllers\AdminEmployeeManagementController;
use App\Http\Controllers\DriverLogbookController;
use App\Http\Controllers\DriverReminderController;
use App\Http\Controllers\OptimalPathController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortestPathController;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.suspension'])->name('dashboard');

Route::middleware(['auth', 'verified', 'check.suspension'])->group(function () {
    // ==========================================
    // Optimal Path Routes
    // ==========================================
    Route::get('/optimal-path', [OptimalPathController::class, 'index'])->name('optimal-path');
    Route::post('/deriveTSP', [ShortestPathController::class, 'deriveTSP'])->name('deriveTSP');
    Route::post('/save-route', [OptimalPathController::class, 'saveRoute'])->name('saveRoute');
    Route::get('/user-routes', [OptimalPathController::class, 'getUserRoutes'])->name('getUserRoutes');
    Route::get('/route/{id}', [OptimalPathController::class, 'show'])->name('route.show');
    Route::delete('/route/{id}', [OptimalPathController::class, 'delete'])->name('route.delete');

    // ==========================================
    // Driver Logbook Routes
    // ==========================================
    Route::get('/driver-logbook', [DriverLogbookController::class, 'index'])->name('driver-logbook');
    Route::post('/save-driver-checklist', [DriverLogbookController::class, 'saveChecklist'])->name('saveDriverChecklist');
    Route::post('/save-odometer-reading', [DriverLogbookController::class, 'saveOdometer'])->name('saveOdometerReading');
    Route::get('/driver-logs', [DriverLogbookController::class, 'getLogs'])->name('getDriverLogs');
    Route::delete('/driver-log/{id}', [DriverLogbookController::class, 'deleteLog'])->name('deleteDriverLog');

    // ==========================================
    // Driver Reminders Routes
    // ==========================================
    Route::get('/driver-reminders/today', [DriverReminderController::class, 'getTodayReminders'])->name('getTodayReminders');
    Route::get('/driver-reminders/pending', [DriverReminderController::class, 'getPendingReminders'])->name('getPendingReminders');
    Route::get('/driver-reminders/statistics', [DriverReminderController::class, 'getStatistics'])->name('getReminderStatistics');
    Route::post('/driver-reminders/{id}/complete', [DriverReminderController::class, 'markAsCompleted'])->name('completeReminder');
    Route::post('/driver-reminders/end-of-day/complete', [DriverReminderController::class, 'markEndOfDayComplete'])->name('completeEndOfDay');

    // ==========================================
    // Employee Reminders from Admin
    // ==========================================
    Route::get('/reminders/received', [AdminEmployeeLogsController::class, 'getReceivedReminders'])->name('reminders.received');
    Route::post('/reminders/{id}/acknowledge', [AdminEmployeeLogsController::class, 'acknowledgeReminder'])->name('reminders.acknowledge');

    // ==========================================
    // User Settings Routes
    // ==========================================
    Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/get', [UserSettingsController::class, 'getSettings'])->name('settings.get');
    Route::post('/settings/notifications', [UserSettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('/settings/display', [UserSettingsController::class, 'updateDisplay'])->name('settings.display');
    Route::post('/settings/privacy', [UserSettingsController::class, 'updatePrivacy'])->name('settings.privacy');
    Route::post('/settings/preferences', [UserSettingsController::class, 'updatePreferences'])->name('settings.preferences');
    Route::post('/settings/vehicle', [UserSettingsController::class, 'updateVehicle'])->name('settings.vehicle');
    Route::post('/settings/reset', [UserSettingsController::class, 'resetSettings'])->name('settings.reset');
    Route::get('/settings/timezones', [UserSettingsController::class, 'getTimezones'])->name('settings.timezones');

    // ==========================================
    // Profile Routes
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'check.suspension', 'admin'])->group(function () {
    // ==========================================
    // Admin Dashboard & Views (PAGES)
    // ==========================================
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/employees', [AdminController::class, 'employeeManagement'])->name('admin.employees');
    Route::get('/admin/employee-logs', [AdminController::class, 'employeeLogs'])->name('admin.employee-logs');
    Route::get('/admin/employees/{employee}/routes-view', [AdminController::class, 'employeeRoutes'])->name('admin.employee.routes.view');

    // ==========================================
    // Employee Management API
    // ==========================================
    Route::get('/admin/api/employees', [AdminEmployeeManagementController::class, 'getAllEmployees'])->name('admin.api.employees');
    Route::post('/admin/employees/suspend', [AdminEmployeeManagementController::class, 'suspendEmployee'])->name('admin.suspend-employee');
    Route::post('/admin/employees/unsuspend', [AdminEmployeeManagementController::class, 'unsuspendEmployee'])->name('admin.unsuspend-employee');
    Route::get('/admin/employees/{id}/suspension-history', [AdminEmployeeManagementController::class, 'getEmployeeSuspensionHistory'])->name('admin.suspension-history');
    Route::get('/admin/suspension/statistics', [AdminEmployeeManagementController::class, 'getSuspensionStatistics'])->name('admin.suspension-statistics');

    // ==========================================
    // Admin Employee Logs Routes (API)
    // ==========================================
    Route::get('/admin/employee-logs/status', [AdminEmployeeLogsController::class, 'getEmployeeLogsStatus'])->name('admin.employee-logs.status');
    Route::get('/admin/employee-logs/{employee}/detailed', [AdminEmployeeLogsController::class, 'getEmployeeDetailedLogs'])->name('admin.employee-logs.detailed');
    Route::post('/admin/employee-logs/send-reminder', [AdminEmployeeLogsController::class, 'sendEmployeeReminder'])->name('admin.send-reminder');
    Route::get('/admin/reminders', [AdminEmployeeLogsController::class, 'getAdminReminders'])->name('admin.reminders');
    Route::get('/admin/reminders/statistics', [AdminEmployeeLogsController::class, 'getReminderStatistics'])->name('admin.reminder-statistics');
    Route::get('/admin/missing-logs', [AdminEmployeeLogsController::class, 'getEmployeesWithMissingLogs'])->name('admin.missing-logs');
    Route::post('/admin/reminders/{id}/acknowledge', [AdminEmployeeLogsController::class, 'acknowledgeReminder'])->name('admin.acknowledge-reminder');

    // ==========================================
    // Employee Routes Management (API)
    // ==========================================
    Route::get('/admin/employees/{employee}/routes', [AdminEmployeeLogsController::class, 'getEmployeeRoutes'])->name('admin.employee.routes');
    Route::get('/admin/employees/{employee}/routes/statistics', [AdminEmployeeLogsController::class, 'getEmployeeRouteStatistics'])->name('admin.employee.routes.statistics');
    Route::get('/admin/routes/{route}/details', [AdminEmployeeLogsController::class, 'getRouteDetails'])->name('admin.route.details');
});

require __DIR__ . '/auth.php';