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
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->String('prize')->nullable();
            $table->timestamp('awared_date')->nullable();
            $table->timestamps();

            //llaves foraneas
            $table->foreignId('raffle_id')->constrained('raffles');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('ticket_id')->constrained('tickets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};
