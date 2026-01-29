<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Forma “Laravel moderna” i més neta:
            $table->foreignId('equip_id')
                ->nullable()
                ->constrained('equips')
                ->nullOnDelete();
        });
    }

    public function down(): void    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('equip_id');
        });
    }
};
