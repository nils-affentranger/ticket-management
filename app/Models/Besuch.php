<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Besuch",
 *     required={"id", "anfang", "ende", "reihe", "platz", "untertitel", "film_id", "typ_id", "sprache_id", "saal_id"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="anfang", type="string", format="date-time", example="2025-04-15T18:30:00+00:00"),
 *     @OA\Property(property="ende", type="string", format="date-time", example="2025-04-15T21:00:00+00:00"),
 *     @OA\Property(property="reihe", type="string", example="A"),
 *     @OA\Property(property="platz", type="integer", example=12),
 *     @OA\Property(property="untertitel", type="boolean", example=true),
 *     @OA\Property(property="snackzuschlag_chf", type="number", format="float", example=3.50, nullable=true),
 *     @OA\Property(property="film_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="typ_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="sprache_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="saal_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="BesuchInput",
 *     required={"anfang", "ende", "reihe", "platz", "untertitel", "film_id", "typ_id", "sprache_id", "saal_id"},
 *     @OA\Property(property="anfang", type="string", format="date-time", example="2025-04-15T18:30:00+00:00"),
 *     @OA\Property(property="ende", type="string", format="date-time", example="2025-04-15T21:00:00+00:00"),
 *     @OA\Property(property="reihe", type="string", example="A"),
 *     @OA\Property(property="platz", type="integer", example=12),
 *     @OA\Property(property="untertitel", type="boolean", example=true),
 *     @OA\Property(property="snackzuschlag_chf", type="number", format="float", example=3.50, nullable=true),
 *     @OA\Property(property="film_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="typ_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="sprache_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="saal_id", type="integer", format="int64", example=1)
 * )
 */
class Besuch extends Model
{
    protected $table = 'besuche';
    protected $fillable = ['anfang', 'ende', 'reihe', 'platz', 'untertitel', 'snackzuschlag_chf', 'film_id', 'typ_id', 'sprache_id', 'saal_id'];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function typ()
    {
        return $this->belongsTo(Typ::class);
    }

    public function sprache()
    {
        return $this->belongsTo(Sprache::class);
    }

    public function saal()
    {
        return $this->belongsTo(Saal::class);
    }
}
