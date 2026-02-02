<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Des d'ací cridem la resta de seeders
        $this->call([
            EstadisSeeder::class,
            EquipsSeeder::class,
            JugadorSeeder::class,
            PartitSeeder::class,
            UserSeeder::class,
        ]);

        // Opcional: per veure que acaba
        dump('DatabaseSeeder: FIN');
    }
}
