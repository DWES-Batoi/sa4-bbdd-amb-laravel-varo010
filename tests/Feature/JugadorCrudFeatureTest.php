<?php

namespace Tests\Feature;

use App\Models\Jugador;
use App\Models\Equip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JugadorCrudFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Gate::before(function () { return true; });
    }

    public function test_es_pot_llistar_jugadors()
    {
        $u = User::factory()->create();
        $this->actingAs($u);

        Jugador::factory()->create(['nom' => 'Pedri']);
        Jugador::factory()->create(['nom' => 'Gavi']);

        $resp = $this->get('/jugadors');
        $resp->assertStatus(200);
        $resp->assertSee('Pedri');
        $resp->assertSee('Gavi');
    }

    public function test_es_pot_crear_un_jugador()
    {
        $u = User::factory()->create();
        $this->actingAs($u);

        $equip = Equip::factory()->create();
        Storage::fake('public');

        $resp = $this->from(route('jugadors.create'))
            ->post('/jugadors', [
                'nom' => 'Lamine',
                'cognoms' => 'Yamal',
                'dorsal' => 19,
                'posicio' => 'Davanter',
                'data_naixement' => '2007-07-13',
                'equip_id' => $equip->id,
                'foto' => UploadedFile::fake()->image('yamal.png'),
            ]);

        $resp->assertSessionHasNoErrors();
        $resp->assertRedirect(route('jugadors.index'));

        $this->assertDatabaseHas('jugadors', [
            'nom' => 'Lamine',
            'dorsal' => 19,
        ]);
    }

    public function test_es_pot_actualitzar_un_jugador()
    {
        $u = User::factory()->create();
        $this->actingAs($u);

        $jugador = Jugador::factory()->create(['nom' => 'Frenkie', 'dorsal' => 21]);

        $resp = $this->from(route('jugadors.edit', $jugador))
            ->put("/jugadors/{$jugador->id}", [
                'nom' => 'Frenkie de Jong',
                'cognoms' => $jugador->cognoms,
                'dorsal' => 21,
                'posicio' => $jugador->posicio,
                'data_naixement' => $jugador->data_naixement,
                'equip_id' => $jugador->equip_id,
            ]);

        $resp->assertSessionHasNoErrors();
        $resp->assertRedirect(route('jugadors.index'));

        $this->assertDatabaseHas('jugadors', [
            'id' => $jugador->id,
            'nom' => 'Frenkie de Jong',
        ]);
    }

    public function test_es_pot_esborrar_un_jugador()
    {
        $u = User::factory()->create();
        $this->actingAs($u);

        $jugador = Jugador::factory()->create();

        $resp = $this->from(route('jugadors.index'))->delete("/jugadors/{$jugador->id}");

        $resp->assertSessionHasNoErrors();
        $resp->assertRedirect(route('jugadors.index'));

        $this->assertDatabaseMissing('jugadors', ['id' => $jugador->id]);
    }
}
