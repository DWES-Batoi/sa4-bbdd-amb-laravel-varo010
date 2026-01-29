<?php

namespace Tests\Unit;

use App\Models\Equip;
use App\Models\Estadi;
use App\Repositories\EquipRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected EquipRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new EquipRepository();
    }

    public function test_create_i_find()
    {
        $estadi = Estadi::factory()->create();

        $equip = $this->repo->create([
            'nom' => 'Valencia CF',
            'titols' => 6,
            'estadi_id' => $estadi->id,
        ]);

        $this->assertDatabaseHas('equips', ['nom' => 'Valencia CF']);
        $this->assertEquals('Valencia CF', $this->repo->find($equip->id)->nom);
    }

    public function test_update_modifica_un_equip()
    {
        $equip = Equip::factory()->create(['nom' => 'Antic']);

        $this->repo->update($equip->id, ['nom' => 'Nou']);

        $this->assertDatabaseHas('equips', ['id' => $equip->id, 'nom' => 'Nou']);
    }

    public function test_delete_esborra_un_equip()
    {
        $equip = Equip::factory()->create();

        $this->repo->delete($equip->id);

        $this->assertDatabaseMissing('equips', ['id' => $equip->id]);
    }

    public function test_getAll_retorna_tots()
    {
        Equip::factory()->count(3)->create();

        $equips = $this->repo->getAll();

        $this->assertCount(3, $equips);
    }
}
