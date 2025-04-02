<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saal extends Model
{
    protected $table = 'saele';
    protected $fillable = ['name', 'kino_id'];

    public function kino()
    {
        return $this->belongsTo(Kino::class);
    }
}
