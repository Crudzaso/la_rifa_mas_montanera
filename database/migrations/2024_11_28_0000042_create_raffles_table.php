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
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('url_image')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('prize');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('price_tickets', ['2000', '5000', '10000', '20000', '50000'])->default('2000');
            $table->integer('total_tickets');
            $table->integer('tickets_sold')->default(0);
            $table->enum('status', ['pending', 'ongoing', 'finished'])->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
