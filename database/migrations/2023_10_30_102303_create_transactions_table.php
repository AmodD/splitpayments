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
            $table->string('consumerid')->nullable();
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->bigInteger('amount'); // in indian paise
            $table->string('orderid')->nullable();
            $table->string('currency');
            $table->string('mid',9)->nullable(); 
            $table->string('tid',14)->nullable();
            $table->string('productid')->nullable();
            $table->string('paymentmethod')->nullable(); // credit , debit , prepaid , UPI, EMI 
            $table->string('externalpaymentgatewayreference',256)->nullable()->unique(); // unique from PG
            $table->string('externaltenantreference',256)->nullable()->unique(); // unique from tenant
            $table->timestamps();

//            $table->primary(['tenant_id', 'paymentgateway_id']);
//            $table->primary(['tenant_id', 'submerchant_id']);
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
