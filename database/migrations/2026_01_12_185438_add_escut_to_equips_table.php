<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equips', function (Blueprint $table) {
            $table->string('escut')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('equips', function (Blueprint $table) {
            $table->dropColumn('escut');
        });
    }
};
