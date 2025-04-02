<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
