<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JugadorController extends Controller
{
    public function index()
    {
        $jugadors = \App\Models\Jugador::with('equip')->get();
        return view('jugadores.index', compact('jugadors'));
    }

    public function create()
    {
        $equips = \App\Models\Equip::all();
        return view('jugadores.create', compact('equips'));
    }

    public function store(\App\Http\Requests\StoreJugadorRequest $request)
    {
        \App\Models\Jugador::create($request->validated());
        return redirect()->route('jugadors.index')->with('success', 'Jugador creat correctament!');
    }

    public function show(\App\Models\Jugador $jugador)
    {
        return view('jugadores.show', compact('jugador'));
    }

    public function edit(\App\Models\Jugador $jugador)
    {
        $equips = \App\Models\Equip::all();
        return view('jugadores.edit', compact('jugador', 'equips'));
    }

    public function update(\App\Http\Requests\StoreJugadorRequest $request, \App\Models\Jugador $jugador)
    {
        $jugador->update($request->validated());
        return redirect()->route('jugadors.index')->with('success', 'Jugador actualitzat correctament!');
    }

    public function destroy(\App\Models\Jugador $jugador)
    {
        $jugador->delete();
        return redirect()->route('jugadors.index')->with('success', 'Jugador eliminat correctament!');
    }
}
