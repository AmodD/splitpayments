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
        Schema::create('transactionvalidities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->string('externalpaymentreference',256)->nullable()->unique(); // unique from PG
            $table->string('externaltenantreference',256)->nullable()->unique(); // unique from tenant
            $table->timestamp('pg_expiry_at')->nullable();
            $table->timestamp('tenant_expity_at')->nullable();
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
        Schema::dropIfExists('transactionvalidities');
    }
};
