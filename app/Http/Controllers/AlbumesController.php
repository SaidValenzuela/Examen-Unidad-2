<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class AlbumesController extends Controller
{
    public function view()
    {
        return view('cruds.albumes');
    }

    public function index()
    {
        return response()->json(Album::all(), 200);
    }

    public function show($id)
    {
        return response()->json(Album::find($id), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'portada' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_lanzamiento' => 'required|date',
        ]);

        $portadaPath = $request->file('portada')->store('portadas', 'public');

        $album = Album::create([
            'nombre' => $request->nombre,
            'portada' => $portadaPath,
            'fecha_lanzamiento' => $request->fecha_lanzamiento,
        ]);

        return response()->json($album, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_lanzamiento' => 'required|date',
        ]);

        $album = Album::findOrFail($id);

        $album->nombre = $request->nombre;

        if ($request->hasFile('portada')) {
            Storage::disk('public')->delete($album->portada);
            $portadaPath = $request->file('portada')->store('portadas', 'public');
            $album->portada = $portadaPath;
        }

        $album->fecha_lanzamiento = $request->fecha_lanzamiento;
        $album->save();

        return response()->json($album, 200);
    }

    public function destroy($id)
    {
        $album = Album::findOrFail($id);
        Storage::disk('public')->delete($album->portada); 
        $album->delete();
        return response()->json(null, 204);
    }
}
