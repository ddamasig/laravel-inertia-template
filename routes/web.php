<?php

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

require_once __DIR__ . '/fortify.php';
require_once __DIR__ . '/management.php';

Route::get('/', function (\Illuminate\Http\Request $request) {
    return redirect('/management/users');
});

Route::group([
    'middleware' => [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ]
], function () {
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $tenant = \App\Models\Tenant::current();
    });
});

Route::get('/errors', function (\Illuminate\Http\Request $request) {
    return Inertia::render('Error', [
        'status' => $request->status
    ]);
});

