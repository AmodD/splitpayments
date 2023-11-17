<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmerchantRequest;
use App\Http\Requests\UpdateSubmerchantRequest;
use App\Models\Submerchant;
use App\Livewire\IndexSubmerchant;

class SubmerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function store(StoreSubmerchantRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Submerchant $submerchant)
    {
      return view('submerchant', compact('submerchant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Submerchant $submerchant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmerchantRequest $request, Submerchant $submerchant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submerchant $submerchant)
    {
        $submerchant->delete();
    }
}
