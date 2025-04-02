<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprache extends Model
{
    protected $table = 'sprachen';
    protected $fillable = ['name'];

    public function besuche()
    {
        return $this->hasMany(Besuch::class);
    }
}
