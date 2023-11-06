<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmerchantController;
use App\Livewire\CreateSubmerchant;

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

Route::get('/wh/transactions/status', function () {
    return "success";
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/sdk/registration', CreateSubmerchant::class);
Route::get('/sdk/thankyou', function () { return 'Successfully created merchant with status as INACTIVE . Pending Verification !';});

Route::resource('submerchants', SubmerchantController::class);

//Route::controller(SubmerchantController::class)->group(function () {
//    Route::get('/submerchants/{id}', 'show');
//    Route::post('/submerchants', 'store');
//});

