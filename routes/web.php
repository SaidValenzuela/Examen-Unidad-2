<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\http\Controllers\ArtistasController;
use App\http\Controllers\AlbumesController;
use App\Http\Controllers\CancionesController;
use App\Http\Controllers\CancionesReproductorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('Login');
});

Route::get('/inicio',[ViewsController::class,'inicioView']);
Route::get('/contacto',[ViewsController::class,'contactView']);

Route::get('/dashboard',[ViewsController::class,'dashboard'])->name('dashboard');

Route::get('/view/artistas',[ArtistasController::class, "view"])->name('artistas');
Route::get('/get/artistas',[ArtistasController::class, "index"]);
Route::get('/get/artista/{id}',[ArtistasController::class, "show"]);
Route::post('/insert/artista',[ArtistasController::class, "store"]);
Route::put('/update/artista/{id}',[ArtistasController::class, "update"]);
Route::delete('/delete/artista/{id}',[ArtistasController::class, "destroy"]);

Route::get('/view/albumes',[AlbumesController::class, "view"])->name('albumes');
Route::get('/get/albumes',[AlbumesController::class, "index"]);
Route::get('/get/album/{id}',[AlbumesController::class, "show"]);
Route::post('/insert/album',[AlbumesController::class, "store"]);
Route::put('/update/album/{id}',[AlbumesController::class, "update"]);
Route::delete('/delete/album/{id}',[AlbumesController::class, "destroy"]);

Route::get('/view/canciones',[CancionesController::class, "view"])->name('canciones');
Route::get('/get/albumes', [CancionesController::class, 'fetchAlbumes']);
Route::get('/get/artistas', [CancionesController::class, 'fetchArtistas']);
Route::get('/get/canciones',[CancionesController::class, "index"]);
Route::get('/get/cancion/{id}',[CancionesController::class, "show"]);
Route::post('/insert/cancion',[CancionesController::class, "store"]);
Route::put('/update/cancion/{id}',[CancionesController::class, "update"]);
Route::delete('/delete/cancion/{id}',[CancionesController::class, "destroy"]);

Route::get('/view/canciones-reproductor',[CancionesReproductorController::class, "view"])->name('canciones_reproductor');