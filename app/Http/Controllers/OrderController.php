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
      // step 1 - validate request
        //$request->host();
        //$request->httpHost();
        //$request->schemeAndHttpHost();
        //
        //$token = $request->bearerToken();


        if (!$request->accepts(['application/json'])) {
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48001',
          ], Response::HTTP_NOT_ACCEPTABLE);
        }

      // Front end validations
      $validator = Validator::make($request->all(), [
        'tenant_id' => 'required|uuid',
        'submerchant_reference_number' => 'required|string',
        'order_reference_number' => 'required|string',
        'total_order_amount' => 'required|integer|numeric',
        'submerchant_payout_amount' => 'required|integer|numeric|lt:total_order_amount',
        'tenant_commission_amount' => 'required|integer|numeric|lt:total_order_amount',
        'processing_fee_amount' => 'required|integer|numeric|lt:total_order_amount',
        'tenant_order_date_time' => 'required|date_format:Y-m-d H:i:s',
        'message_on_modal' => 'required|string',
        'return_url' => 'required|url',
        'return_url_message' => 'required|string',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {    
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => $validator->messages()->first(),
          ], Response::HTTP_BAD_REQUEST);
      }
      
      if ($request->total_order_amount != ($request->submerchant_payout_amount + $request->tenant_commission_amount + $request->processing_fee_amount)) { 
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => "ER48023",
          ], Response::HTTP_BAD_REQUEST);
      }

     // Back end validations 
      $tenant = Tenant::with('submerchants')->where('uuid', $request->tenant_id)->first();

      if(!$tenant) {
        return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48004',
          ], Response::HTTP_NOT_FOUND);
      }

      if($tenant->status != 'active') {
        return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48005',
          ], Response::HTTP_CONFLICT);
      }

      $submerchant = $tenant->submerchants()->where('externaltenantreference', $request->submerchant_reference_number)->first();
      
      if(!$submerchant) {
        return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48008',
          ], Response::HTTP_NOT_FOUND);
      }

      if($submerchant->status != 'active') {
        return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'ER48009',
          ], Response::HTTP_CONFLICT);
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
      
      $order->tenant_order_date_time = $request->tenant_order_date_time;
      $order->message_on_modal = $request->message_on_modal;
      $order->return_url = $request->return_url;
      $order->return_url_message = $request->return_url_message;

      $order->externaltenantreference = $request->order_reference_number;

      $order->save();

      return response()->json([
              'status' => 'success',
              'data' => 'SPO48'.sprintf("%07d", $order->id),
              'message' => 'SplitPayments Order Reference Number',
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
        //
    }
}
