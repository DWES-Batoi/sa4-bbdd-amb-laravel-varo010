<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JugadorRequest;
use App\Http\Resources\JugadorResource;
use App\Models\Jugador;
use Illuminate\Http\Request;

class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JugadorResource::collection(Jugador::query()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JugadorRequest $request)
    {
        $jugador = Jugador::create($request->validated());
        return response()->json(new JugadorResource($jugador), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jugador $jugador)
    {
        return new JugadorResource($jugador);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JugadorRequest $request, Jugador $jugador)
    {
        $jugador->update($request->validated());
        return new JugadorResource($jugador);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jugador $jugador)
    {
        $jugador->delete();
        return response()->noContent();
    }
}
