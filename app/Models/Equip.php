<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estadi;
use App\Models\User;
use Database\Factories\EquipFactory;

class Equip extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'estadi_id', 'titols', 'escut'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estadi()
    {
        return $this->belongsTo(Estadi::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manager()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Enllacem explícitament amb la factory
     */
    protected static function newFactory()
    {
        return EquipFactory::new();
    }
}
