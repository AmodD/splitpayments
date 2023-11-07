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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('ulid',100);
            $table->string('name');
            $table->string('message_on_modal')->nullable();
            $table->string('return_url')->nullable();
            $table->string('return_url_message')->nullable();
            $table->string('request_url')->nullable();
            $table->string('secret',100);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
