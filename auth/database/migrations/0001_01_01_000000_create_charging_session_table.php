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
        Schema::create('charging_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('station_id');
            $table->string('driver_token', length: 80);
            $table->string('callback_url', length: 255);
            $table->enum('decision', ['allowed', 'not_allowed', 'unknown', 'invalid']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('idempotency_key', length: 255)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charging_sessions');
    }
};
