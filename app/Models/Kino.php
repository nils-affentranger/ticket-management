<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kino extends Model
{
    protected $table = 'kinos';
    protected $fillable = ['ort', 'name'];

    public function saele()
    {
        return $this->hasMany(Saal::class);
    }
}
