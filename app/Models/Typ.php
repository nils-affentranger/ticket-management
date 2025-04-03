<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Typ",
 *     required={"id", "name", "zuschlag_chf"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="VIP"),
 *     @OA\Property(property="zuschlag_chf", type="number", format="float", example=5.50),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="TypInput",
 *     required={"name", "zuschlag_chf"},
 *     @OA\Property(property="name", type="string", example="VIP"),
 *     @OA\Property(property="zuschlag_chf", type="number", format="float", example=5.50)
 * )
 */
class Typ extends Model
{
    protected $table = 'typen';
    protected $fillable = ['name', 'zuschlag_chf'];

    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
