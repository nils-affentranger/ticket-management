<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Film",
 *     required={"id", "filmtitel", "bild_url"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="filmtitel", type="string", example="Oppenheimer"),
 *     @OA\Property(property="bild_url", type="string", format="url", example="https://www.themoviedb.org/t/p/w1280/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="FilmInput",
 *     required={"filmtitel", "bild_url"},
 *     @OA\Property(property="filmtitel", type="string", example="Oppenheimer"),
 *     @OA\Property(property="bild_url", type="string", format="url", example="https://www.themoviedb.org/t/p/w1280/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg")
 * )
 */
class Film extends Model
{
    protected $table = 'filme';
    protected $fillable = ['filmtitel', 'bild_url'];

    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
