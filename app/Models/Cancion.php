<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    protected $table = "canciones";
    use HasFactory;

    protected $fillable = [
        'nombre', 'duracion', 'album_id', 'artista_id'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function artista()
    {
        return $this->belongsTo(Artista::class);
    }
}
