@extends('templates.crud')

@section('title', 'Canciones')

@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Canciones</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cancionModal" onclick="clearForm()">
        Agregar Canción
    </button>
</div>

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

<div class="modal fade" id="cancionModal" tabindex="-1" aria-labelledby="cancionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cancionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cancionModalLabel">Agregar/Editar Canciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="cancionId" name="id">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="duracion">Duración</label>
                        <input type="number" step="0.01" class="form-control" id="duracion" name="duracion" required>
                    </div>
                    <div class="form-group">
                        <label for="album_id">Album</label>
                        <select class="form-control" id="album_id" name="album_id">
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="artista_id">Artista</label>
                        <select class="form-control" id="artista_id" name="artista_id" required>
                            
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
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
    });

    function fetchCanciones() {
        $.get('/get/canciones', function (canciones) {
            let tableBody = $('#cancionesTableBody');
            tableBody.empty();
            canciones.forEach(cancion => {
                tableBody.append(`
                    <tr>
                        <td>${cancion.nombre}</td>
                        <td>${cancion.duracion}</td>
                        <td>${cancion.album ? cancion.album.nombre : 'Not'}</td>
                        <td>${cancion.artista ? cancion.artista.nombre : 'Not'}</td>
                        <td>
                            <button class="btn btn-warning" onclick="editCancion(${cancion.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteCancion(${cancion.id})">Delete</button>
                        </td>
                    </tr>
                `);
            });
        });
    }

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
    function editCancion(id) {
        $.get(`/get/cancion/${id}`, function (cancion) {
            $('#cancionId').val(cancion.id);
            $('#nombre').val(cancion.nombre);
            $('#duracion').val(cancion.duracion);
            $('#album_id').val(cancion.album_id);
            $('#artista_id').val(cancion.artista_id);
            $('#cancionModal').modal('show');
        });
    }

    function deleteCancion(id) {
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

    function clearForm() {
        $('#cancionId').val('');
        $('#cancionForm')[0].reset();
    }
</script>
@endsection
