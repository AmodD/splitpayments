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
        Schema::create('gsitems', function (Blueprint $table) {
          $table->id();
          $table->string('name');
            $table->bigInteger('amount'); // in indian paise
            $table->foreignId('paymentgateway_id');
            $table->foreignId('submerchant_id');
            $table->foreignId('tenant_id');
            $table->foreignId('order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gsitems');
    }
};
