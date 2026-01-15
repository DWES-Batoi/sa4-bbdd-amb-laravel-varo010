<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipRequest;
use App\Http\Requests\UpdateEquipRequest;
use App\Models\Equip;
use App\Models\Estadi;
use App\Services\EquipService;

class EquipController extends Controller
{
    public function __construct(private EquipService $servei) {}

    // GET /equips
    public function index() {
        $equips = $this->servei->llistar();
        return view('equips.index', compact('equips'));
    }

    // GET /equips/create
    public function create() {
        $estadis = Estadi::all();
        return view('equips.create', compact('estadis'));
    }

    // POST /equips
    public function store(StoreEquipRequest $request) {
        $this->servei->guardar($request->validated(), $request->file('escut'));
        return redirect()->route('equips.index')
            ->with('success', 'Equip creat correctament!');
    }

    // GET /equips/{equip}
    public function show(Equip $equip) {
        return view('equips.show', compact('equip'));
    }

    // GET /equips/{equip}/edit
    public function edit(Equip $equip) {
        $estadis = Estadi::all();
        return view('equips.edit', compact('equip', 'estadis'));
    }

    // PUT /equips/{equip}
    public function update(UpdateEquipRequest $request, Equip $equip) {
        $this->servei->actualitzar($equip->id, $request->validated(), $request->file('escut'));
        return redirect()->route('equips.index')
            ->with('success', 'Equip actualitzat correctament!');
    }

    // DELETE /equips/{equip}
    public function destroy(Equip $equip) {
        $this->servei->eliminar($equip->id);
        return redirect()->route('equips.index');
    }
}
