<?php

namespace App\Services;

use App\Models\Equip;
use App\Repositories\BaseRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EquipService {
    public function __construct(private BaseRepository $repo) {}

    public function llistar() {
        return $this->repo->getAll();
    }

    public function trobar($id){
        return $this->repo->find($id);
    }

    public function guardar(array $data, ?UploadedFile $escut = null): Equip {
        if ($escut) {
            $data['escut'] = $escut->store('escuts', 'public');
        }
        return $this->repo->create($data);
    }

    public function actualitzar(int $id, array $data, ?UploadedFile $escut = null): Equip {
        $equip = $this->repo->find($id);

        if ($escut) {
            if ($equip->escut) {
                Storage::disk('public')->delete($equip->escut);
            }
            $data['escut'] = $escut->store('escuts', 'public');
        }

        return $this->repo->update($id, $data);
    }

    public function eliminar(int $id): void {
        $equip = $this->repo->find($id);

        if ($equip->escut) {
            Storage::disk('public')->delete($equip->escut);
        }

        $this->repo->delete($id);
    }
}
