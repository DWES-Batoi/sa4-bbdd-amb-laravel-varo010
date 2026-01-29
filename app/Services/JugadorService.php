<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class JugadorService {
    protected $repo;

    public function __construct(BaseRepository $repo) {
        $this->repo = $repo;
    }

    public function guardar(array $data, ?UploadedFile $foto) {
        if ($foto) {
            $path = $foto->store('jugadors', 'public');
            $data['foto'] = $path;
        }
        return $this->repo->create($data);
    }

    public function actualitzar($id, array $data, ?UploadedFile $novaFoto) {
        $jugador = $this->repo->find($id);

        if ($novaFoto) {
            if ($jugador->foto) {
                Storage::disk('public')->delete($jugador->foto);
            }
            $data['foto'] = $novaFoto->store('jugadors', 'public');
        }

        return $this->repo->update($id, $data);
    }

    public function eliminar($id) {
        $jugador = $this->repo->find($id);
        if ($jugador->foto) {
            Storage::disk('public')->delete($jugador->foto);
        }
        return $this->repo->delete($id);
    }
}
