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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->String('ticket_number')->unique()->nullable();
            $table->timestamp('purchase_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            //llaves foraneas.
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('raffle_id')->constrained('raffles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
