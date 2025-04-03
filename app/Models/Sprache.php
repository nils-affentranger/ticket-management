<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Sprache",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Deutsch"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="SpracheInput",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Deutsch")
 * )
 */
class Sprache extends Model
{
    protected $table = 'sprachen';
    protected $fillable = ['name'];

    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
