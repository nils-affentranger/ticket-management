<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typ extends Model
{
    protected $table = 'typen';
    protected $fillable = ['name', 'zuschlag_chf'];

    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
