<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CancionesReproductorController extends Controller
{
    //
    public function view(){
        return view('cruds.canciones_reproductor');
    }
}
