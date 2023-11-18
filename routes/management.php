<?php

use App\Http\Controllers\Management\Security\PermissionController;
use App\Http\Controllers\Management\Security\RoleController;
use App\Http\Controllers\Management\Security\TenantController;
use App\Http\Controllers\Management\Security\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => 'management',
    'middleware' => [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
        'management'
    ]
], function () {
    Route::resource('users', UserController::class)->except(['update']);
    Route::post('users/{user}', [UserController::class, 'update']);
    Route::resource('tenants', TenantController::class)->except(['update']);
    Route::post('tenants/{tenant}', [TenantController::class, 'update']);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
