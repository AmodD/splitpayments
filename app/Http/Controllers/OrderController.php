<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
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
        $ipAddress = $request->ip();
           return response()->json([
              'ipaddress' => $ipAddress,
          ]);

        if (!$request->accepts(['text/html', 'application/json'])) {
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'Unacceptable header accept type. Only text/html and application/json are supported',
          ]);
        }
      //
      // step 2 - store order in db
      $this->store($request);
      // step 3 - generate the jws
      //
      // step 4 - call the create order api
      //
      // step 5 - from response get required attributes
      //
      // step 6 - redirect to order page

      return view('orders');
    }

    /**
     * Store a newly created resource in storage.
     */
    //public function store(StoreOrderRequest $request): RedirectResponse
    public function store(Request $request)
    {
        if (!$request->accepts(['application/json'])) {
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'Unacceptable Header Accept Key - Value. Only application/json is supported',
          ]);
        }

      $ipAddress = $request->ip();

//      $validated = $request->validate([
//        'body' => 'required',
//    ]);
      $validator = Validator::make($request->all(), [
//        'body' => 'required',
        'tenantid' => 'required|ulid',
      ]);


      if ($validator->fails()) {    
//        return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => $validator->messages()->first(),
          ], Response::HTTP_BAD_REQUEST);
      }

        return response()->json([
              'ipaddress' => $ipAddress,
          ]);

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
