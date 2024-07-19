<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancion;
use App\Models\Album;
use App\Models\Artista;


class CancionesController extends Controller
{
    public function view(){
        return view('cruds.canciones');
    }

    public function index()
    {
        return response()->json(Cancion::with(['album', 'artista'])->get(), 200);
    }

    public function show($id)
    {
        return response()->json(Cancion::find($id), 200);
    }

    public function store(Request $request)
    {
        $cancion = Cancion::create($request->all());
        return response()->json($cancion, 201);
    }


    public function update(Request $request, $id)
    {
        $cancion = Cancion::findOrFail($id);
        $cancion->update($request->all());
        return response()->json($cancion, 200);
    }

    public function destroy($id)
    {
        Cancion::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function fetchAlbumes()
    {
        return response()->json(Album::all(), 200);
    }

    public function fetchArtistas()
    {
        return response()->json(Artista::all(), 200);
    }
}
