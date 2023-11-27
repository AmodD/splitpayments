<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Models\Transactionstatus;
use App\Models\Transactiondevicedetail;
use App\Models\Transactionvalidity;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Actions\GenerateJWS;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
//use Illuminate\Http\Client\Response;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Crypt;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $epayload)
    {
      // step 1 - get the order details

      if(!$epayload) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48032',
      ], Response::HTTP_BAD_REQUEST);

      //$payload = openssl_decrypt($epayload, 'AES-128-CTR', env('APP_KEY'), 0, 'SPO48'.sprintf("%011d", $order->id));
      $payload = Crypt::decryptString($epayload);

      if(!$payload) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48029',
      ], Response::HTTP_UNAUTHORIZED);

      // payload does not contain ~
      if(!Str::contains($payload, '~')) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48033',
      ], Response::HTTP_BAD_REQUEST);

      $payload_values = explode('~', $payload);

      //payload_values must contain 3 values
      if(count($payload_values) != 3) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48034',
      ], Response::HTTP_BAD_REQUEST);

      $order_id = $payload_values[0];
      $clientip = $payload_values[1];
      $otimestamp = $payload_values[2];

      // check if order id is numeric
      if(!is_numeric($order_id)) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48035',
      ], Response::HTTP_BAD_REQUEST);

      // check if client ip is valid
      if(!filter_var($clientip, FILTER_VALIDATE_IP)) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48036',
      ], Response::HTTP_BAD_REQUEST);

      $order = Order::find($order_id);

      if(!$order) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48028',
      ], Response::HTTP_NOT_FOUND);

      // if order clientipaddress does not match clientip
      if($order->clientipaddress != $clientip) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48039',
      ], Response::HTTP_BAD_REQUEST);

      // if request ip does not match order clientipaddress
      if($request->ip() != $order->clientipaddress) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48043',
      ], Response::HTTP_BAD_REQUEST);

      //if otimestamp is not numeric
      if(!is_numeric($otimestamp)) return response()->json([
        'status' => 'error',
        'data' => null,
        'message' => 'ER48041',
      ], Response::HTTP_BAD_REQUEST);

      // if timeout is greather than 10 seconds
      if((time() - $otimestamp) > env('APP_TENANT_TIMEOUT')) return response()->json([
        'status' => 'error',
        'data' => time() - $otimestamp,
        'message' => 'ER48040',
      ], Response::HTTP_BAD_REQUEST);
      

      // step 3 - generate the jws
      $jwsrequest = GenerateJWS::encryptPG($order,$request->ip(),$request->userAgent(),$request->header('accept'));

     // return $jws;


      // step 4 - call the PG create order api
      $jwsresponse = Http::withHeaders([
          //'content-type' => 'application/jose',
          'accept' => 'application/jose',
          'bd-timestamp' => time(),
          'bd-traceid' => 'SPO48'.sprintf("%07d", $order->id),
      ])->withBody(
          $jwsrequest, 'application/jose'
      )->withMiddleware(Middleware::log(with(new Logger('guzzle-log'))->pushHandler(
                new RotatingFileHandler(storage_path('logs/guzzle-log.log'))
            ), new MessageFormatter(MessageFormatter::DEBUG)))->post('https://pguat.billdesk.io/payments/ve1_2/orders/create');

      // step 5 - from response get required attributes
      $pgpayload = null;
     // dd($jwsresponse);

      if(Str::contains($jwsresponse, 'error')) return $jwsresponse;
      // check if response is 200
      //

      $jwsresponse->throwIfStatus(500)->json([
          'status' => 'error',
          'data' => null,
          'message' => 'ER48030',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);

      $jwsresponse->throwIfStatus(400)->json([
          'status' => 'error',
          'data' => null,
          'message' => 'ER48031',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);

      $pgpayload = GenerateJWS::decryptPG($jwsresponse);

      //else $pgpayload = GenerateJWS::decrypt($jwsresponse);

      return $pgpayload;

      // step 6 - redirect to order page

        return view('transactions');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
      $transaction = new Transaction();
      $transaction->consumer_id = $request->consumer_id;
      $transaction->paymentgateway_id = $request->paymentgateway_id;
      $transaction->submerchant_id = $request->submerchant_id;
      $transaction->tenant_id = $request->tenant_id;
      $transaction->amount = $request->amount;
      $transaction->orderid = $request->orderid;
      $transaction->currency = "356";
      $transaction->mid = 'UATFORTV2';
      $transaction->tid = '';
      $transaction->productid = $request->productid;
      $transaction->paymentmethod = $request->paymentmethod;
      $transaction->externalpaymentreference = $request->externalpaymentreference;
      $transaction->externaltenantreference = $request->externaltenantreference;
      $transaction->ipaddress = $request->ip();

      $transaction->save();

      $transactionstatus = new Transactionstatus();
      $transactionstatus->transaction_id = $transaction->id;
      $transactionstatus->status = "initiated"; // initiated , success , failed , pending , cancelled, abandoned, customer-timeout, pg-timeout,
      $transactionstatus->paymentgateway_id = $request->paymentgateway_id;
      $transactionstatus->submerchant_id = $request->submerchant_id;
      $transactionstatus->tenant_id = $request->tenant_id;
      $transactionstatus->externalpaymentreference = $request->externalpaymentreference;
      $transactionstatus->externaltenantreference = $request->externaltenantreference;

      $transactionstatus->save();

      $transactiondevicedetail = new Transactiondevicedetail();
      $transactiondevicedetail->transaction_id = $transaction->id;
      $transactionstatus->paymentgateway_id = $request->paymentgateway_id;
      $transactionstatus->submerchant_id = $request->submerchant_id;
      $transactionstatus->tenant_id = $request->tenant_id;
      $transactionstatus->externalpaymentreference = $request->externalpaymentreference;
      $transactionstatus->externaltenantreference = $request->externaltenantreference;

      $transactiondevicedetail->save();

      $transactionvalidity = new Transactionvalidity();
      $transactionvalidity->transaction_id = $transaction->id;
      $transactionvalidity->paymentgateway_id = $request->paymentgateway_id;
      $transactionvalidity->submerchant_id = $request->submerchant_id;
      $transactionvalidity->tenant_id = $request->tenant_id;
      $transactionvalidity->externalpaymentreference = $request->externalpaymentreference;
      $transactionvalidity->externaltenantreference = $request->externaltenantreference;
      $transactionvalidity->pgexpiryat = (new Carbon('now'))->addSeconds(240);
      $transactionvalidity->tenantexpiryat = (new Carbon('now'))->addSeconds(300);

      $transactionvalidity->save();

      return response()->json([
        'status' => 'initiated',
        'data' => $transaction
      ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
