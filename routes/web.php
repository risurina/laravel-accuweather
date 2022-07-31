<?php

use App\Http\Controllers\Location\AdministrativeAreaController;
use App\Http\Controllers\Location\CityController;
use App\Http\Controllers\Location\CountryController;
use App\Http\Controllers\Location\ForecastController;
use App\Http\Controllers\Location\RegionController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Index');
    })->name('dashboard');
});



/**
 * Create api base data list
 * In this way, I can reuse routes for api
 */
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->prefix('api')
    ->name('api.')
    ->group(function () {
        Route::prefix('/location')
            ->name('location.')
            ->group(function () {
                Route::get('/regions', RegionController::class)->name('regions-list');
                Route::get('/countries', CountryController::class)->name('countries-list');
                Route::get('/admin-area', AdministrativeAreaController::class)->name('admin-area-list');
                Route::get('/cities', CityController::class)->name('cities-list');
            });

        Route::get('/forecast/{locationKey}', ForecastController::class)->name('forecast-get');
    });
