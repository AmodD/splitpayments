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
            $table->string('mid',9)->nullable();
            $table->string('tid',14)->nullable();
            $table->string('externalpaymentreference',256)->nullable(); // unique from PG
            $table->string('externaltenantreference',256)->nullable(); // unique from tenant
            $table->string('dba_name');
            $table->string('gstn')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_number')->nullable();
            $table->string('status');
            $table->timestamps();
            
            //$table->unique(['tenant_id', 'tenantsubmerchantid']);
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
