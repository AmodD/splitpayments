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
        Schema::create('submerchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id');
            $table->foreignId('paymentgateway_id');
            $table->string('mid',9);
            $table->string('tid',14);
            $table->string('dba_name');
            $table->string('gstn');
            $table->string('bank_name');
            $table->string('ifsc');
            $table->string('account_type');
            $table->string('account_number');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submerchants');
    }
};
