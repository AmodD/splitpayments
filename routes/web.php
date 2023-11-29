<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmerchantController;
use App\Http\Controllers\OrderController;
use App\Livewire\CreateSubmerchant;
use App\Livewire\IndexSubmerchant;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use App\Actions\GenerateJWS;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
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


Route::get('/sdk/v2/transactions/create/{epayload}',[TransactionController::class,'create'])->name('transactions.create');

Route::get('/jws', function (Request $request) { 

  if(!$request->has('order_id')) {
    return response()->json([
      'status' => 'error',
      'data' => null,
      'message' => 'order_id is required'
    ], 400);
  }

  $orderid = $request->input('order_id');

  // if orderid is integer then it is orderid else it is transactionid
  if(is_numeric($orderid)) {
    $order = \App\Models\Order::find($orderid);
  } else {
    $order = \App\Models\Order::find((int) Str::substr($orderid, 5, 12));
  }

  if(!$order) {
    return response()->json([
      'status' => 'error',
      'data' => null,
      'message' => 'order not found'
    ], 404);
  }

    return response()->json([
      'status' => 'success',
      'data' => GenerateJWS::encryptPG($order),
      'message' => 'JWS generated successfully'
    ], 200);


});




Route::get('/decrypt', function (Request $request) { 

  $jws = $request->input('jws');

  return GenerateJWS::decryptPG($jws);

});

Route::get('test', function () {

  return route('transactions.create', ['epayload' => '123~127.6.6.6~1707377822']);
//$encryption = openssl_encrypt($simple_string, $ciphering,$encryption_key, $options, $encryption_iv);
  $encryption = openssl_encrypt('123~127.0.0.1~1707377822', 'AES-128-CTR', env('APP_KEY'), 0, '1234567890123456');

  return $encryption .' \n '. openssl_decrypt($encryption, 'AES-128-CTR', env('APP_KEY'), 0, '1234567890123456');

return "/tender/update/".Crypt::encryptString('SP480000048');
  //  return view('test');
});

Route::get('/test2', function () {
    return response('Hello World', 200)
                  ->header('Content-Type', 'text/html');
});

//Route::resource('submerchants', SubmerchantController::class)->name('submerchants', 'submerchants');

//Route::controller(SubmerchantController::class)->group(function () {
//    Route::get('/submerchants/{id}', 'show');
//    Route::post('/submerchants', 'store');
//});

