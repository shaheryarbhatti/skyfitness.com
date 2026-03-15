<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SidebarManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/language/{locale}', function ($locale) {
    $allowed = ['en', 'id', 'ur'];

    if (in_array($locale, $allowed)) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('language.switch');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/userlogout', [App\Http\Controllers\HomeController::class, 'logout'])->name('userlogout');

Route::middleware(['auth'])->prefix('members')->group(function () {

    Route::get('/', [MemberController::class, 'index'])->name('members.manage');
    Route::get('/create', [MemberController::class, 'create'])->name('members.add');
    Route::post('/', [MemberController::class, 'store'])->name('members.store');
    Route::get('/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

});

// Permission Management Routes
Route::middleware(['auth'])->prefix('permissions')->group(function () {

    Route::get('/', [PermissionController::class, 'index'])->name('permissions.manage');
    Route::get('/create', [PermissionController::class, 'create'])->name('permissions.add');
    Route::post('/', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

});

// Role Management Routes
Route::middleware(['auth'])->prefix('roles')->group(function () {

    Route::get('/', [RoleController::class, 'index'])->name('roles.manage');
    Route::get('/create', [RoleController::class, 'create'])->name('roles.add');
    Route::post('/', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

});

// User Management Routes
Route::middleware(['auth'])->prefix('users')->group(function () {

    Route::get('/', [UserController::class, 'index'])->name('users.manage');
    Route::get('/create', [UserController::class, 'create'])->name('users.add');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');

});

// Currency Management Routes
Route::middleware(['auth'])->prefix('currencies')->group(function () {

    Route::get('/', [CurrencyController::class, 'index'])->name('currencies.manage');
    Route::get('/create', [CurrencyController::class, 'create'])->name('currencies.add');
    Route::post('/', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::get('/{currency}', [CurrencyController::class, 'show'])->name('currencies.show');
    Route::get('/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::put('/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
    Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');

});

// Invoice Management Routes
Route::middleware(['auth'])->prefix('invoices')->group(function () {

    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.manage');
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.add');
    Route::post('/', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

});

Route::middleware(['auth'])->prefix('sidebar-management')->group(function () {

    Route::get('/', [SidebarManagementController::class, 'index'])->name('sidebar.manage');
    Route::get('/create', [SidebarManagementController::class, 'create'])->name('sidebar.module.create');
    Route::post('/module', [SidebarManagementController::class, 'storeModule'])->name('sidebar.module.store');
    Route::post('/option', [SidebarManagementController::class, 'storeOption'])->name('sidebar.option.store');
    Route::get('/module/{module}/edit', [SidebarManagementController::class, 'editModule'])->name('sidebar.module.edit');
    Route::put('/module/{module}', [SidebarManagementController::class, 'updateModule'])->name('sidebar.module.update');
    Route::delete('/module/{module}', [SidebarManagementController::class, 'destroyModule'])->name('sidebar.module.destroy');
    Route::delete('/option/{option}', [SidebarManagementController::class, 'destroyOption'])->name('sidebar.option.destroy');

});

// Trainer Management Routes
Route::middleware(['auth'])->prefix('trainers')->group(function () {
    Route::get('/', [TrainerController::class, 'index'])->name('trainers.manage');
    Route::get('/create', [TrainerController::class, 'create'])->name('trainers.add');
    Route::post('/', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/{trainer}', [TrainerController::class, 'show'])->name('trainers.show');
    Route::get('/{trainer}/edit', [TrainerController::class, 'edit'])->name('trainers.edit');
    Route::put('/{trainer}', [TrainerController::class, 'update'])->name('trainers.update');
    Route::delete('/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
});

// Setting Management Routes
Route::middleware(['auth'])->prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/update', [SettingController::class, 'update'])->name('settings.update');
});

// Attendance Management Routes
Route::middleware(['auth'])->prefix('attendance')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/create', [AttendanceController::class, 'create'])->name('attendance.create');

    // This is the missing route:
    Route::post('/store', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
});
