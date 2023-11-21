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
    public function create()
    {
        //
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
