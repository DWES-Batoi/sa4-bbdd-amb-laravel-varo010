<?php

namespace Database\Seeders;

use App\Models\Estadi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estadi::create(['nom' => 'Camp Nou',            'capacitat' => 99000]);
        Estadi::create(['nom' => 'Wanda Metropolitano', 'capacitat' => 68000]);
        Estadi::create(['nom' => 'Santiago Bernabéu',   'capacitat' => 81000]);

        // Opcional: per comprovar que s'han creat
        dump('EstadisSeeder - després de crear:', Estadi::count());
    }
}
