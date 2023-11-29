<?php

namespace App\Actions\Billdesk;
 
interface BillDeskClient {
    public function createOrder($request, $headers = array());
    public function createTransaction($request, $headers = array());
    public function refundTransaction($request, $headers = array());
} 
