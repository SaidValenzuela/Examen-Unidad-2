<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artista extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apodo', 'fecha_nacimiento'
    ];

    public function canciones()
    {
        return $this->hasMany(Cancion::class);
    }
}
