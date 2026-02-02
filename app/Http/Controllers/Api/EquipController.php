<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipRequest;
use App\Http\Resources\EquipResource;
use App\Models\Equip;
use Illuminate\Http\Request;

class EquipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EquipResource::collection(Equip::query()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipRequest $request)
    {
        $equip = Equip::create($request->validated());
        return response()->json(new EquipResource($equip), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Equip $equip)
    {
        return new EquipResource($equip);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipRequest $request, Equip $equip)
    {
        $equip->update($request->validated());
        return new EquipResource($equip);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equip $equip)
    {
        $equip->delete();
        return response()->noContent();
    }
}
