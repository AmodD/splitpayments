<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\Submerchant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Actions\GenerateJWS;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
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
    public function create(Request $request)
    {
      $loggingChannel = Log::build(['driver' => 'single','path' => storage_path('logs/tenants/unkown/orders.log')]);

        if (!$request->accepts(['application/json'])) {
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48001',
          ], Response::HTTP_NOT_ACCEPTABLE);
        }

      if ($request->hasHeader('SP-Tenant-ID')) {
        $value = $request->header('SP-Tenant-ID');     
        $tenant = Tenant::where('uuid', $value)->first();

        if($tenant) $loggingChannel = Log::build(['driver' => 'single','path' => storage_path('logs/tenants/'.$tenant->code.'/orders.log')]);
        else {
          Log::stack([$loggingChannel])->error('ER48046');
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48046',
          ], Response::HTTP_BAD_REQUEST);
        }

      } 
      else {
          Log::stack([$loggingChannel])->error('ER48045');
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48045',
          ], Response::HTTP_BAD_REQUEST);
      }

      if($tenant->status != 'active') {
        Log::stack([$loggingChannel])->alert('ER48050');
        Log::stack([$loggingChannel])->alert('AL48050');
        return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48050',
          ], Response::HTTP_CONFLICT);
      }

      Log::stack([$loggingChannel])->info('IN48047');


      // Front end validations
      $validator = Validator::make($request->all(), [
//        'tenant_id' => 'required|uuid|unique:App\Models\Tenants,uuid',
        'submerchant_reference_number' => 'required|string,',
        'order_reference_number' => 'required|string|unique:App\Models\Order,externaltenantreference',
        'total_order_amount' => 'required|integer|numeric',
        'submerchant_payout_amount' => 'required|integer|numeric|lt:total_order_amount',
        'tenant_commission_amount' => 'required|integer|numeric|lt:total_order_amount',
        'processing_fee_amount' => 'required|integer|numeric|lt:total_order_amount',
        'clientipaddress' => 'required|ip',
        'tenant_order_date_time' => 'required|date_format:Y-m-d H:i:s',
        'message_on_modal' => 'required|string',
        'return_url' => 'required|url',
        'return_url_message' => 'required|string',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {    
      Log::stack([$loggingChannel])->error($validator->messages()->first());
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => $validator->messages()->first(),
          ], Response::HTTP_BAD_REQUEST);
      }

      $loggingChannel = Log::build(['driver' => 'single','path' => storage_path('logs/tenants/'.$tenant->code.'/unkown/orders.log')]);
      $submerchant = Submerchant::where('externaltenantreference', $request->submerchant_reference_number)->first();
      
      if($submerchant) {
        $loggingChannel = Log::build(['driver' => 'single','path' => storage_path('logs/tenants/'.$tenant->code.'/'.$submerchant->code.'/orders.log')]);
      }
      else {
         Log::stack([$loggingChannel])->error('ER48049');
         return response()->json([
            'status' => 'error',
            'data' => null,
            'message' => 'ER48049',
         ], Response::HTTP_BAD_REQUEST);
      }

      if($submerchant->status != 'active') {
        Log::stack([$loggingChannel])->alert('ER48051');
        Log::stack([$loggingChannel])->alert('AL48051');
        return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48051',
          ], Response::HTTP_CONFLICT);
      }

      Log::stack([$loggingChannel])->info('IN48048');
      
      if ($request->total_order_amount != ($request->submerchant_payout_amount + $request->tenant_commission_amount + $request->processing_fee_amount)) { 
        Log::stack([$loggingChannel])->alert('ER48023');
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => "ER48023",
          ], Response::HTTP_BAD_REQUEST);
      }

      // step 2 - store order in db
      return $this->store($request,$tenant,$submerchant);
    }

    /**
     * Store a newly created resource in storage.
     */
    //public function store(StoreOrderRequest $request): RedirectResponse
    public function store(Request $request,Tenant $tenant,Submerchant $submerchant)
    {
      $order = new Order();

      $order->paymentgateway_id = 1;
      $order->submerchant_id = $submerchant->id;
      $order->tenant_id = $tenant->id;

      $order->total_order_amount = $request->total_order_amount;
      $order->submerchant_payout_amount = $request->submerchant_payout_amount;
      $order->tenant_commission_amount = $request->tenant_commission_amount;
      $order->processing_fee_amount = $request->processing_fee_amount;

      $order->tenantipaddress = $request->ip();
      $order->clientipaddress = $request->clientipaddress;

      $order->tenant_order_date_time = $request->tenant_order_date_time;
      $order->message_on_modal = $request->message_on_modal;
      $order->return_url = $request->return_url;
      $order->return_url_message = $request->return_url_message;

      $order->externaltenantreference = $request->order_reference_number;

      $order->save();

      // step 3 - generate JWT
      $payload = $order->id.'~'.$order->clientipaddress.'~'.time();
      //$encrypted = openssl_encrypt($payload, 'AES-128-CTR', env('APP_KEY'), 0, 'SPO48'.sprintf("%011d", $order->id));
      $encrypted = Crypt::encryptString($payload);

      return response()->json([
              'status' => 'success',
              'data' => route('transactions.create', ['epayload' => $encrypted]),
              'message' => 'SPO48'.sprintf("%07d", $order->id),
          ], Response::HTTP_OK);


    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
      
      
      
    }
}
