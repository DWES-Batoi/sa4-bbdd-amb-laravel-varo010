<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'cognoms', 'dorsal', 'equip_id', 'foto', 'posicio', 'data_naixement'];

    public function equip()
    {
        return $this->belongsTo(Equip::class);
    }
}
