<?php

namespace Database\Seeders;

use App\Models\Equip;
use App\Models\Estadi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EquipsSeeder extends Seeder
{
    public function run(): void
    {
        $estadi = Estadi::where('nom', 'Camp Nou')->first();
        if ($estadi) {
            $estadi->equips()->updateOrCreate(
                ['nom' => 'Barça Femení'],
                ['titols' => 30]
            );
        }

        $estadi = Estadi::where('nom', 'Wanda Metropolitano')->first();
        if ($estadi) {
            $estadi->equips()->updateOrCreate(
                ['nom' => 'Atlètic de Madrid'],
                ['titols' => 10]
            );
        }

        $estadi = Estadi::where('nom', 'Santiago Bernabéu')->first();
        if ($estadi) {
            $estadi->equips()->updateOrCreate(
                ['nom' => 'Real Madrid Femení'],
                ['titols' => 5]
            );
        }

        // Si tens UNIQUE a equips.nom i la factory pot repetir noms, posa unique() a la factory
        Equip::factory()->count(10)->create();

        foreach (Equip::all() as $equip) {
            User::updateOrCreate(
                ['email' => $equip->id . '@manager.com'],
                [
                    'name' => 'Manager ' . $equip->nom,
                    'password' => Hash::make('1234'),
                    'role' => 'manager',
                    'equip_id' => $equip->id,
                ]
            );
        }
    }
}
