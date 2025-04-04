<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Kino",
 *     required={"id", "name", "ort"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Cinépolis"),
 *     @OA\Property(property="ort", type="string", example="Zürich"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="KinoInput",
 *     required={"name", "ort"},
 *     @OA\Property(property="name", type="string", example="Cinépolis"),
 *     @OA\Property(property="ort", type="string", example="Zürich")
 * )
 */
class Kino extends Model
{
    protected $table = 'kinos';
    protected $fillable = ['name', 'ort'];

    public function saele()
    {
        return $this->hasMany(Saal::class);
    }
}
