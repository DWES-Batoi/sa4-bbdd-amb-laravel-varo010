<?php

namespace Tests\Unit;

use App\Models\Jugador;
use App\Services\JugadorService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
use App\Repositories\BaseRepository;

class JugadorServiceTest extends TestCase
{
    use WithFaker;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_guardar_crea_jugador_i_puja_foto_si_cal()
    {
        Storage::fake('public');

        $repo = Mockery::mock(BaseRepository::class);

        $data = ['nom' => 'Lionel', 'cognoms' => 'Messi', 'dorsal' => 10];
        $foto = UploadedFile::fake()->image('foto.png', 200, 200);

        $repo->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($payload) {
                return isset($payload['foto']) && str_starts_with($payload['foto'], 'jugadors/');
            }))
            ->andReturnUsing(function ($payload) use ($data) {
                return new Jugador(array_merge($data, ['foto' => $payload['foto']]));
            });

        $service = new JugadorService($repo);
        $jugador = $service->guardar($data, $foto);

        Storage::disk('public')->assertExists($jugador->foto);
    }

    public function test_actualitzar_sustitueix_foto_i_esborra_l_antiga()
    {
        Storage::fake('public');

        $repo = Mockery::mock(BaseRepository::class);
        $jugador = new Jugador(['id' => 1, 'nom' => 'Lionel', 'foto' => 'jugadors/vella.png']);

        Storage::disk('public')->put($jugador->foto, 'dummy');

        $repo->shouldReceive('find')->once()->with(1)->andReturn($jugador);
        $repo->shouldReceive('update')
            ->once()
            ->with(1, Mockery::on(fn($payload) => isset($payload['foto']) && str_starts_with($payload['foto'], 'jugadors/')))
            ->andReturn($jugador);

        $service = new JugadorService($repo);
        $novaFoto = UploadedFile::fake()->image('nova.png');

        $service->actualitzar(1, ['nom' => 'Leo'], $novaFoto);

        Storage::disk('public')->assertMissing('jugadors/vella.png');
    }

    public function test_eliminar_esborra_foto_si_existeix()
    {
        Storage::fake('public');

        $repo = Mockery::mock(BaseRepository::class);
        $jugador = new Jugador(['id' => 2, 'foto' => 'jugadors/perfil.png']);
        Storage::disk('public')->put($jugador->foto, 'dummy');

        $repo->shouldReceive('find')->once()->with(2)->andReturn($jugador);
        $repo->shouldReceive('delete')->once()->with(2);

        $service = new JugadorService($repo);
        $service->eliminar(2);

        Storage::disk('public')->assertMissing('jugadors/perfil.png');
    }
}
