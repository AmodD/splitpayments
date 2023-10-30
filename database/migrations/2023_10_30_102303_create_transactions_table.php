<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('consumerid');
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->bigInteger('amount'); // in indian paise
            $table->string('status');
            $table->string('orderid');
            $table->string('currency');
            $table->string('mid',9);
            $table->string('tid',14);
            $table->string('productid');
            $table->string('paymentmethod'); // credit , debit , prepaid , UPI, EMI 
            $table->string('externalpaymentreference',256); // unique from PG
            $table->string('externaltenantreference',256); // unique from tenant
            $table->string('latitude',128); 
            $table->string('longitude',128); 
            $table->string('ipaddress');
            $table->string('useragent');
            $table->string('acceptheader');
            $table->string('fingerprintid');
            $table->string('browsertz');
            $table->string('browsercoulurdepth');
            $table->string('browserjavaenabled');
            $table->string('browserscreenheight');
            $table->string('browserscreenwidth');
            $table->string('browserlanguage');
            $table->string('browserjavascriptenabled');
            $table->timestamp('payment_at');
            $table->timestamp('tenant_at');
            $table->timestamp('validity_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
