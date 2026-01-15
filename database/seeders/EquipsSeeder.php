<?php

namespace Database\Seeders;

use App\Models\Equip;
use App\Models\Estadi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opcional: missatge de depuració
        dump('EquipsSeeder: abans de crear', [
            'estadis' => Estadi::count(),
            'equips'  => Equip::count(),
        ]);

        // Assegurem que els 3 estadis principals existeixen
        $campNou = Estadi::firstOrCreate(
            ['nom' => 'Camp Nou'],
            ['capacitat' => 99000]
        );

        $wanda = Estadi::firstOrCreate(
            ['nom' => 'Wanda Metropolitano'],
            ['capacitat' => 68000]
        );

        $bernabeu = Estadi::firstOrCreate(
            ['nom' => 'Santiago Bernabéu'],
            ['capacitat' => 81000]
        );

        // Crear equips “a mà” amb relació
        $campNou->equips()->firstOrCreate(
            ['nom' => 'Barça Femení'],
            ['titols' => 30]
        );

        $wanda->equips()->firstOrCreate(
            ['nom' => 'Atlètic de Madrid'],
            ['titols' => 10]
        );

        $bernabeu->equips()->firstOrCreate(
            ['nom' => 'Real Madrid Femení'],
            ['titols' => 5]
        );

        // Crear 10 equips més usant la factory
        Equip::factory()->count(10)->create();

        // Opcional: comprovar resultats
        dump('EquipsSeeder: després de crear', [
            'estadis' => Estadi::count(),
            'equips'  => Equip::count(),
        ]);
    }
}
