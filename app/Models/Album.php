<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = "albumes";

    use HasFactory;

    protected $fillable = [
        'nombre', 'portada', 'fecha_lanzamiento'
    ];

    public function canciones()
    {
        return $this->hasMany(Cancion::class);
    }
}
