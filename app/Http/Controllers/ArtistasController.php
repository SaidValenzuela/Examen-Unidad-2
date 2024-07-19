<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artista;

class ArtistasController extends Controller
{
    public function view(){
        return view('cruds.artistas');
    }

    public function index()
    {
        return response()->json(Artista::all(), 200);
    }

    public function show($id)
    {
        return response()->json(Artista::find($id), 200);
    }

    public function store(Request $request)
    {
        $artista = Artista::create($request->all());
        return response()->json($artista, 201);
    }

    public function update(Request $request, $id)
    {
        $artista = Artista::findOrFail($id);
        $artista->update($request->all());
        return response()->json($artista, 200);
    }

    public function destroy($id)
    {
        Artista::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
