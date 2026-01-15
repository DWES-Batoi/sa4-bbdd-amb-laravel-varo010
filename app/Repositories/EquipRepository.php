<?php

namespace App\Repositories;

use App\Models\Equip;

class EquipRepository implements BaseRepository {
    public function getAll() {
        return Equip::all();
    }

    public function find($id) {
        return Equip::findOrFail($id);
    }

    public function create(array $data) {
        return Equip::create($data);
    }

    public function update($id, array $data) {
        $equip = Equip::findOrFail($id);
        $equip->update($data);
        return $equip;
    }

    public function delete($id) {
        return Equip::destroy($id);
    }
}
