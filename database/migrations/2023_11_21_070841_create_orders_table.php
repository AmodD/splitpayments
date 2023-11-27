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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable();
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->bigInteger('total_order_amount'); // in indian paise
            $table->bigInteger('submerchant_payout_amount'); // in indian paise
            $table->bigInteger('tenant_commission_amount'); // in indian paise
            $table->bigInteger('processing_fee_amount'); // in indian paise
            $table->ipAddress('tenantipaddress');
            $table->ipAddress('clientipaddress');
            $table->string('externalpaymentgatewayreference',256)->nullable()->unique(); // unique from PG
            $table->string('externaltenantreference',256)->unique(); // unique from tenant
            $table->string('message_on_modal')->nullable();
            $table->string('return_url')->nullable();
            $table->string('return_url_message')->nullable();
            $table->string('consumerid')->nullable();
            $table->date('tenant_order_date_time');
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
        Schema::dropIfExists('orders');
    }
};
