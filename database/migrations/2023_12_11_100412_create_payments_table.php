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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('transaction_id')->nullable();
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->string('productid')->nullable();
            $table->string('paymentmethod')->nullable(); // credit , debit , prepaid , UPI, EMI 
            $table->string('consumerid')->nullable();
            $table->string('externalpaymentgatewayreference',256)->nullable()->unique(); // unique from PG
            $table->string('externaltenantreference',256)->nullable()->unique(); // unique from tenant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
