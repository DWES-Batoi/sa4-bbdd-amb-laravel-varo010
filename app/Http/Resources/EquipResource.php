<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'estadi_id' => $this->estadi_id,
            'titols' => $this->titols,
            'escut' => $this->escut,
            'estadi' => $this->whenLoaded('estadi', function () {
                return $this->estadi->nom;
            }),
        ];
    }
}
