@extends('templates.crud')

@section('title', 'Reproductor de Canciones')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reproductor de Canciones</h1>
</div>

<div class="mb3">
    <div class="form-inline">
        <label for="sort-field" class="mr-2">Ordenar por:</label>
        <select id="sort-field" class="form-control mr-2">
            <option value="artista-asc">Artista Ascendente</option>
            <option value="artista-desc">Artista Descendente</option>
            <option value="album-asc">Album Ascendente</option>
            <option value="album-desc">Album Descendente</option>
            <option value="nombre-asc">Nombre Ascendente</option>
            <option value="nombre-desc">Nombre Descendente</option>
            <option value="duracion-asc">Duración Ascendente</option>
            <option value="duracion-desc">Duración Descendente</option>
        </select>
        <button id="sort-btn" class="btn btn-secondary ml-2">Ordenar</button>
    </div>
</div>
<br>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Duración</th>
            <th>Album</th>
            <th>Artista</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="cancionesTableBody">
       
    </tbody>
</table>

<div class="overlay">
    <div class="music-player-container card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <img src="{{ asset('img/img.jpg') }}" alt="Song Image" class="img-fluid song-image">
                </div>
                <div class="col-md-8">
                    <h4 class="card-title song-title">Humble.</h4>
                    <p class="card-text song-author">Kendrick Lamar</p>
                    <p class="card-text song-album">ABC</p>
                </div>
            </div>
            <div class="progress-song-container mb-3">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="time-left">0:57</span>
                    <span class="time-right">3:03</span>
                </div>
            </div>
            <div class="main-song-controls d-flex justify-content-center">
                <button class="btn btn-outline-secondary btn-sm mx-2 btn-backward">
                    <i class="fa-solid fa-backward"></i>
                </button>
                <button class="btn btn-outline-secondary btn-sm mx-2 btn-play">
                    <i class="fa-solid fa-play"></i>
                </button>
                <button class="btn btn-outline-secondary btn-sm mx-2 btn-forward">
                    <i class="fa-solid fa-forward"></i>
                </button>
                <button class="btn btn-outline-secondary btn-sm mx-2 btn-random">
                    <i class="fa-solid fa-shuffle"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
   $(document).ready(function () {
        fetchCanciones();
        fetchAlbumes();
        fetchArtistas();

        let canciones = [];
        let currentIndex = 0;

        function loadSong(index) {
            const cancion = canciones[index];
            $('.song-image').attr('src', `/storage/${cancion.album.portada}`);
            $('.song-title').text(cancion.nombre);
            $('.song-author').text(cancion.artista.nombre);
            $('.song-album').text(cancion.album.nombre);
        }

        $('#cancionForm').on('submit', function (e) {
            e.preventDefault();

            let id = $('#cancionId').val();
            let url = id ? `/update/cancion/${id}` : '/insert/cancion';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $('#cancionForm').serialize(),
                success: function (response) {
                    $('#cancionModal').modal('hide');
                    fetchCanciones();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        function fetchCanciones() {
            $.get('/get/canciones', function (data) {
                canciones = data;
                renderCancionesTable();
                if (canciones.length > 0) {
                    loadSong(currentIndex);
                }
            });
        }

        function renderCancionesTable() {
            let tableBody = $('#cancionesTableBody');
            tableBody.empty();
            canciones.forEach(cancion => {
                tableBody.append(`
                    <tr>
                        <td>${cancion.nombre}</td>
                        <td>${cancion.duracion}</td>
                        <td>${cancion.album ? cancion.album.nombre : 'N/A'}</td>
                        <td>${cancion.artista ? cancion.artista.nombre : 'N/A'}</td>
                        <td>
                            <button class="btn btn-warning" onclick="editCancion(${cancion.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteCancion(${cancion.id})">Delete</button>
                        </td>
                    </tr>
                `);
            });
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }

        $('.btn-backward').on('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
                loadSong(currentIndex);
            } else {
                alert('No hay más canciones anteriores.');
            }
        });

        $('.btn-forward').on('click', function () {
            if (currentIndex < canciones.length - 1) {
                currentIndex++;
                loadSong(currentIndex);
            } else {
                alert('No hay más canciones siguientes.');
            }
        });

        $('.btn-random').on('click', function () {
            shuffleArray(canciones);
            renderCancionesTable();
        });

        function fetchAlbumes() {
            $.get('/get/albumes', function (albumes) {
                let albumSelect = $('#album_id');
                albumSelect.empty();
                albumSelect.append('<option value="" selected disabled>Seleccione un Album</option>');
                albumes.forEach(album => {
                    albumSelect.append(`<option value="${album.id}">${album.nombre}</option>`);
                });
            });
        }

        function fetchArtistas() {
            $.get('/get/artistas', function (artistas) {
                let artistaSelect = $('#artista_id');
                artistaSelect.empty();
                artistaSelect.append('<option value="" selected disabled>Seleccione un Artista</option>');
                artistas.forEach(artista => {
                    artistaSelect.append(`<option value="${artista.id}">${artista.nombre}</option>`);
                });
            });
        }

        window.editCancion = function editCancion(id) {
            $.get(`/get/cancion/${id}`, function (cancion) {
                $('#cancionId').val(cancion.id);
                $('#nombre').val(cancion.nombre);
                $('#duracion').val(cancion.duracion);
                $('#album_id').val(cancion.album_id);
                $('#artista_id').val(cancion.artista_id);
                $('#cancionModal').modal('show');
            });
        }

        window.deleteCancion = function deleteCancion(id) {
            $.ajax({
                url: `/delete/cancion/${id}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'DELETE',
                success: function () {
                    fetchCanciones();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        $('#sort-btn').on('click', function () {
            sortCanciones();
        });

        function sortCanciones() {
            const sortBy = $('#sort-field').val();
            if (sortBy.includes('artista')) {
                canciones.sort((a, b) => {
                    if (a.artista.nombre < b.artista.nombre) return sortBy.includes('asc') ? -1 : 1;
                    if (a.artista.nombre > b.artista.nombre) return sortBy.includes('asc') ? 1 : -1;
                    return 0;
                });
            } else if (sortBy.includes('album')) {
                canciones.sort((a, b) => {
                    if (a.album.nombre < b.album.nombre) return sortBy.includes('asc') ? -1 : 1;
                    if (a.album.nombre > b.album.nombre) return sortBy.includes('asc') ? 1 : -1;
                    return 0;
                });
            } else if (sortBy.includes('nombre')) {
                canciones.sort((a, b) => {
                    if (a.nombre < b.nombre) return sortBy.includes('asc') ? -1 : 1;
                    if (a.nombre > b.nombre) return sortBy.includes('asc') ? 1 : -1;
                    return 0;
                });
            } else if (sortBy.includes('duracion')) {
                canciones.sort((a, b) => sortBy.includes('asc') ? a.duracion - b.duracion : b.duracion - a.duracion);
            }
            renderCancionesTable();
        }
    });
</script>
@endsection
