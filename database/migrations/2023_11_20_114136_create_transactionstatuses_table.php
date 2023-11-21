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
        Schema::create('transactionstatuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->string('status');
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->string('externalpaymentreference',256)->nullable(); // unique from PG
            $table->string('externaltenantreference',256)->nullable(); // unique from tenant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactionstatuses');
    }
};
