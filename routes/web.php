<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmerchantController;
use App\Http\Controllers\OrderController;
use App\Livewire\CreateSubmerchant;
use App\Livewire\IndexSubmerchant;
use App\Http\Controllers\TransactionController;

use App\Actions\GenerateJWS;
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

Route::middleware('throttle:2,1')->get('/test', function () {
        return "testing";
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wh/transactions/status', function () {
  return response()->json([
    'status' => 'success',
]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/submerchants', IndexSubmerchant::class)->name('submerchants.index');
    Route::get('/submerchants/{submerchant}', [SubmerchantController::class,'show'])->name('submerchants.show');
    Route::get('/transactions', function () { return view('transactions'); })->name('transactions');  
    Route::get('/payouts', function () { return view('payouts'); })->name('payouts');  
});


Route::middleware('throttle:20,1')->get('/sdk/registration', CreateSubmerchant::class)->name('sdk');
Route::get('/sdk/thankyou', function () { return 'Successfully created merchant with status as INACTIVE . Pending Verification !';})->name('sdk');
Route::get('/sdk/v0/orders/create', function () { return 'Successfully created order in splitpayments and initiated a transaction in payment gateway  !';})->name('sdk');


Route::get('/sdk/v1/transactions/create',[TransactionController::class,'create']);

Route::get('/decrypt', function () { 

  return GenerateJWS::verifyAndDecryptJWSWithHMAC('eyJhbGciOiJIUzI1NiIsImNsaWVudGlkIjoidWF0Zm9ydHYyIiwia2lkIjoiSE1BQyJ9.eyJzdGF0dXMiOjQwOSwiZXJyb3JfdHlwZSI6ImR1cGxpY2F0ZV9yZXF1ZXN0X2Vycm9yIiwiZXJyb3JfY29kZSI6IkdORFJFMDAwMSIsIm1lc3NhZ2UiOiJEdXBsaWNhdGUgcmVxdWVzdCBlcnJvciJ9.ihXE6sf_r3JfOEBLdOkzscjTSjhXdcquYZb10Oj7peg');


});

//Route::resource('submerchants', SubmerchantController::class)->name('submerchants', 'submerchants');

//Route::controller(SubmerchantController::class)->group(function () {
//    Route::get('/submerchants/{id}', 'show');
//    Route::post('/submerchants', 'store');
//});

