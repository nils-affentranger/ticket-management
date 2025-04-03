<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Saal",
 *     required={"id", "name", "kino_id"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Saal 1"),
 *     @OA\Property(property="kino_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="SaalInput",
 *     required={"name", "kino_id"},
 *     @OA\Property(property="name", type="string", example="Saal 1"),
 *     @OA\Property(property="kino_id", type="integer", format="int64", example=1)
 * )
 */
class Saal extends Model
{
    protected $table = 'saele';
    protected $fillable = ['name', 'kino_id'];

    public function kino()
    {
        return $this->belongsTo(Kino::class);
    }
    
    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
