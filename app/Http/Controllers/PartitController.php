<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartitController extends Controller
{
    public function index()
    {
        $partits = \App\Models\Partit::with(['local', 'visitant'])->get();
        return view('partits.index', compact('partits'));
    }

    public function create()
    {
        $equips = \App\Models\Equip::all();
        return view('partits.create', compact('equips'));
    }

    public function store(\App\Http\Requests\StorePartitRequest $request)
    {
        \App\Models\Partit::create($request->validated());
        return redirect()->route('partits.index')->with('success', 'Partit creat correctament!');
    }

    public function show(\App\Models\Partit $partit)
    {
        return view('partits.show', compact('partit'));
    }

    public function edit(\App\Models\Partit $partit)
    {
        $equips = \App\Models\Equip::all();
        return view('partits.edit', compact('partit', 'equips'));
    }

    public function update(\App\Http\Requests\StorePartitRequest $request, \App\Models\Partit $partit)
    {
        $partit->update($request->validated());
        return redirect()->route('partits.index')->with('success', 'Partit actualitzat correctament!');
    }

    public function destroy(\App\Models\Partit $partit)
    {
        $partit->delete();
        return redirect()->route('partits.index')->with('success', 'Partit eliminat correctament!');
    }
}
