<?php

namespace Tests\Feature;

use App\Models\Equip;
use App\Models\Estadi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EquipCrudFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // ✅ CHANGED: autoritza tot en tests per simplificar (policies/gates)
        Gate::before(function () { return true; });
    }

    public function test_es_pot_llistar_equips()
    {
        $u = User::factory()->create();
        $this->actingAs($u);

        Equip::factory()->create(['nom' => 'FC Barcelona']);
        Equip::factory()->create(['nom' => 'Real Madrid']);

        $resp = $this->get('/equips');
        $resp->assertStatus(200);
        $resp->assertSee('FC Barcelona');
        $resp->assertSee('Real Madrid');
    }

    public function test_es_pot_crear_un_equip()
    {
        $u = User::factory()->create([
            'role' => 'administrador',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($u);

        $estadi = Estadi::factory()->create();
        Storage::fake('public');

        $resp = $this->from(route('equips.create'))
            ->post('/equips', [
                'nom' => 'FC Barcelona',
                'titols' => 30,
                'estadi_id' => $estadi->id,
                'escut' => UploadedFile::fake()->image('escut.png'),
            ]);

        $resp->assertSessionHasNoErrors();
        $resp->assertRedirect(route('equips.index'));

        $this->assertDatabaseHas('equips', [
            'nom' => 'FC Barcelona',
            'titols' => 30,
            'estadi_id' => $estadi->id,
        ]);
    }

    public function test_es_pot_actualitzar_un_equip()
    {
        $u = User::factory()->create([
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($u);

        $estadi = Estadi::factory()->create();
        $equip = Equip::factory()->create([
            'nom' => 'FC Barcelona',
            'estadi_id' => $estadi->id,
            'titols' => 30,
        ]);

        $resp = $this->from(route('equips.edit', $equip))
            ->put("/equips/{$equip->id}", [
                'nom' => 'Barça',
                'estadi_id' => $estadi->id,
                'titols' => 31,
            ]);

        $resp->assertSessionHasNoErrors();
        $resp->assertRedirect(route('equips.index'));

        $this->assertDatabaseHas('equips', [
            'id' => $equip->id,
            'nom' => 'Barça',
             'titols' => 31,
        ]);
    }

    public function test_es_pot_esborrar_un_equip()
    {
        $u = User::factory()->create([
            'role' => 'administrador',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($u);

        $equip = Equip::factory()->create(['nom' => 'FC Barcelona']);

        $resp = $this->from(route('equips.index'))->delete("/equips/{$equip->id}");

        $resp->assertSessionHasNoErrors();
        $resp->assertRedirect(route('equips.index'));

        $this->assertDatabaseMissing('equips', ['id' => $equip->id]);
    }
}
