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
        Schema::create('transactiondevicedetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->string('externalpaymentreference',256)->nullable(); // unique from PG
            $table->string('externaltenantreference',256)->nullable(); // unique from tenant
            $table->string('latitude',128)->nullable(); 
            $table->string('longitude',128)->nullable(); 
            $table->string('ipaddress')->nullable();
            $table->string('useragent')->nullable();
            $table->string('acceptheader')->nullable();
            $table->string('fingerprintid')->nullable();
            $table->string('browsertz')->nullable();
            $table->string('browsercoulurdepth')->nullable();
            $table->string('browserjavaenabled')->nullable();
            $table->string('browserscreenheight')->nullable();
            $table->string('browserscreenwidth')->nullable();
            $table->string('browserlanguage')->nullable();
            $table->string('browserjavascriptenabled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactiondevicedetails');
    }
};
