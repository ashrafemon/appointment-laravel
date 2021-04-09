<?php

use Illuminate\Support\Facades\Artisan;
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

// Route::get('{any}', function () {
//     return view('app');
// })->where('any', '.*');

Route::get('/', function () {
    return view('install');
})->name('install');

Route::get('/migrate/fresh', function () {
    Artisan::call('migrate:fresh');

    return redirect()->route('install')->with('message', 'Fresh Migrate Complete');
})->name('migrate.fresh');

Route::get('/migrate/new', function () {
    Artisan::call('migrate');

    return redirect()->route('install')->with('message', 'New Migrate Complete');
})->name('migrate.new');

Route::get('/migrate/seed', function () {
    Artisan::call('db:seed');

    return redirect()->route('install')->with('message', 'Seed Complete');
})->name('migrate.seed');

Route::get('/cache/clear', function () {
    Artisan::call('optimize:clear');

    return redirect()->route('install')->with('message', 'All cache clear complete');
})->name('cache.clear');
