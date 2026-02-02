<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstadiRequest;
use App\Http\Resources\EstadiResource;
use App\Models\Estadi;
use Illuminate\Http\Request;

class EstadiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EstadiResource::collection(Estadi::query()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstadiRequest $request)
    {
        $estadi = Estadi::create($request->validated());
        return response()->json(new EstadiResource($estadi), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Estadi $estadi)
    {
        return new EstadiResource($estadi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstadiRequest $request, Estadi $estadi)
    {
        $estadi->update($request->validated());
        return new EstadiResource($estadi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estadi $estadi)
    {
        $estadi->delete();
        return response()->noContent();
    }
}
