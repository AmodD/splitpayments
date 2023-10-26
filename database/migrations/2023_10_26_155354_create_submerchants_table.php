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
            $table->string('DBA_Name');
            $table->string('GSTN');
            $table->string('Bank_Name');
            $table->string('IFSC');
            $table->string('Account_Type');
            $table->string('Account_Number');
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
