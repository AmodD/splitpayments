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
            $table->foreignId('transaction_id');
            $table->string('consumerid')->nullable();
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->string('externalpaymentgatewayreference',256)->nullable()->unique(); // unique from PG
            $table->string('externaltenantreference',256)->unique(); // unique from tenant
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
