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
        Schema::create('partits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')->constrained('equips')->onDelete('cascade');
            $table->foreignId('visitant_id')->constrained('equips')->onDelete('cascade');
            $table->integer('gols_local')->nullable();
            $table->integer('gols_visitant')->nullable();
            $table->dateTime('data_partit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partits');
    }
};
