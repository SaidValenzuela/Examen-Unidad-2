@extends('templates.crud')

@section('title', 'Artistas')

@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Artistas</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#artistaModal" onclick="clearForm()">
        Agregar Artista
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apodo</th>
            <th>Fecha de Nacimiento</th>
            <th>Actions</th>
    </thead>
    <tbody id="artistasTableBody">
        
    </tbody>
</table>

<div class="modal fade" id="artistaModal" tabindex="-1" aria-labelledby="artistaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="artistaForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="artistaModalLabel">Agregar/Editar Artista</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="artistaId" name="id">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apodo">Apodo</label>
                        <input type="text" class="form-control" id="apodo" name="apodo" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        fetchArtistas();

        $('#artistaForm').on('submit', function (e) {
            e.preventDefault();

            let id = $('#artistaId').val();
            console.log(id)
            let url = id ? `/update/artista/${id}` : '/insert/artista';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $('#artistaForm').serialize(),
                success: function (response) {
                    $('#artistaModal').modal('hide');
                    fetchArtistas();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });

    function fetchArtistas() {
        $.get('/get/artistas', function (artistas) {
            let tableBody = $('#artistasTableBody');
            tableBody.empty();
            artistas.forEach(artista => {
                tableBody.append(`
                    <tr>
                        <td>${artista.nombre}</td>
                        <td>${artista.apodo}</td>
                        <td>${artista.fecha_nacimiento}</td>
                        <td>
                            <button class="btn btn-warning" onclick="editArtista(${artista.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteArtista(${artista.id})">Delete</button>
                        </td>
                    </tr>
                `);
            });
        });
    }

    function editArtista(id) {
        $.get(`/get/artista/${id}`, function (artista) {
            $('#artistaId').val(artista.id);
            $('#nombre').val(artista.nombre);
            $('#apodo').val(artista.apodo);
            $('#fecha_nacimiento').val(artista.fecha_nacimiento);
            $('#artistaModal').modal('show');
        });
    }

    function deleteArtista(id) {
        $.ajax({
            url: `/delete/artista/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'DELETE',
            success: function () {
                fetchArtistas();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function clearForm() {
        $('#artistaId').val('');
        $('#artistaForm')[0].reset();
    }
</script>
@endsection
