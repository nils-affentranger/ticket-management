<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'filme';
    protected $fillable = ['filmtitel', 'bild'];

    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
