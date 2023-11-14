<?php

use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\TenantController;
use App\Http\Controllers\Security\UserController;
use Illuminate\Foundation\Application;
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

Route::get('/', function (\Illuminate\Http\Request $request) {
    return redirect('/login');
});

Route::get('/errors', function (\Illuminate\Http\Request $request) {
    return Inertia::render('Error', [
        'status' => $request->status
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        return Inertia::render('Dashboard', [
        ]);
    })->name('dashboard');

    Route::resource('users', UserController::class)->except(['update']);
    Route::post('users/{user}', [UserController::class, 'update']);
    Route::resource('tenants', TenantController::class)->except(['update']);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::middleware('tenant')->group(function() {
        Route::get('/tenant-page', function () {
            return 123;
        });
    });
});
