<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Models\Hotspots;
use App\Http\Controllers\UserAuthController;
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

Route::get('/', [Controller::class, 'home']);

Route::get('/get-hotspots', [Controller::class, 'hotspots']);
Route::get('/get-user', [Controller::class, 'users']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


/* API Endpoints */
//-- Auth
Route::post('/v1/helium/user-login', [UserAuthController::class, 'login']);
Route::post('/v1/helium/user-register', [UserAuthController::class, 'registerNewUser']);







//-- Data API
Route::post('/v1/helium/add-hotpsot', [Hotspots::class, 'addHotspot']);
