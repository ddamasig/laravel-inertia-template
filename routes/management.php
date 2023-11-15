<?php

use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\TenantController;
use App\Http\Controllers\Security\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    return Inertia::render('Dashboard', [
    ]);
})->name('dashboard');

Route::resource('users', UserController::class)->except(['update']);
Route::post('users/{user}', [UserController::class, 'update']);
Route::resource('tenants', TenantController::class)->except(['update']);
Route::post('tenants/{tenant}', [TenantController::class, 'update']);
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
