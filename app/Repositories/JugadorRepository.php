<?php

namespace App\Repositories;

use App\Models\Jugador;

class JugadorRepository implements BaseRepository {
    public function getAll() {
        return Jugador::all();
    }

    public function find($id) {
        return Jugador::findOrFail($id);
    }

    public function create(array $data) {
        return Jugador::create($data);
    }

    public function update($id, array $data) {
        $jugador = Jugador::findOrFail($id);
        $jugador->update($data);
        return $jugador;
    }

    public function delete($id) {
        return Jugador::destroy($id);
    }
}
