<?php

namespace Tests\Unit;

use App\Models\Jugador;
use App\Models\Equip;
use App\Repositories\JugadorRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JugadorRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected JugadorRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new JugadorRepository();
    }

    public function test_create_i_find()
    {
        $equip = Equip::factory()->create();

        $jugador = $this->repo->create([
            'nom' => 'Pedri',
            'cognoms' => 'González',
            'dorsal' => 8,
            'equip_id' => $equip->id,
            'data_naixement' => '2002-11-25',
            'posicio' => 'Migcampista'
        ]);

        $this->assertDatabaseHas('jugadors', ['nom' => 'Pedri']);
        $this->assertEquals('Pedri', $this->repo->find($jugador->id)->nom);
    }

    public function test_update_modifica_un_jugador()
    {
        $jugador = Jugador::factory()->create(['nom' => 'Antic']);

        $this->repo->update($jugador->id, ['nom' => 'Nou']);

        $this->assertDatabaseHas('jugadors', ['id' => $jugador->id, 'nom' => 'Nou']);
    }

    public function test_delete_esborra_un_jugador()
    {
        $jugador = Jugador::factory()->create();

        $this->repo->delete($jugador->id);

        $this->assertDatabaseMissing('jugadors', ['id' => $jugador->id]);
    }

    public function test_getAll_retorna_tots()
    {
        Jugador::factory()->count(3)->create();

        $jugadors = $this->repo->getAll();

        $this->assertCount(3, $jugadors);
    }
}
