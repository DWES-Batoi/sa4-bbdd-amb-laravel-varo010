<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JugadorResource extends JsonResource
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
             'cognoms' => $this->cognoms,
             'dorsal' => $this->dorsal,
             'equip_id' => $this->equip_id,
             'posicio' => $this->posicio,
             'data_naixement' => $this->data_naixement,
             'foto' => $this->foto,
             // Relació opcional
             'equip' => $this->whenLoaded('equip', function() {
                 return $this->equip->nom;
             }),
        ];
    }
}
