@extends('templates.crud')

@section('title', 'Albumes')

@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Albumes</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#albumModal" onclick="clearForm()">
        Agregar Album
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Portada</th>
            <th>Fecha de Lanzamiento</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="albumesTableBody">
        
    </tbody>
</table>

<div class="modal fade" id="albumModal" tabindex="-1" aria-labelledby="albumModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="albumForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="albumModalLabel">Agregar/Editar Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="albumId" name="id">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="portada">Portada</label>
                        <input type="file" class="form-control" id="portada" name="portada" accept="image/*" onchange="previewImage(event)">
                        <img id="portadaPreview" src="" alt="Previsualización de Portada" style="display:none; margin-top:10px; max-height:200px;">
                    </div>
                    <div class="form-group">
                        <label for="fecha_lanzamiento">Fecha de Lanzamiento</label>
                        <input type="date" class="form-control" id="fecha_lanzamiento" name="fecha_lanzamiento" required>
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
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('portadaPreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $(document).ready(function () {
        fetchAlbumes();

        $('#albumForm').on('submit', function (e) {
            e.preventDefault();

            let id = $('#albumId').val();
            let url = id ? `/update/album/${id}` : '/insert/album';
            let method = id ? 'PUT' : 'POST';

            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#albumModal').modal('hide');
                    fetchAlbumes();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });

    function fetchAlbumes() {
        $.get('/get/albumes', function (albumes) {
            let tableBody = $('#albumesTableBody');
            tableBody.empty();
            albumes.forEach(album => {
                tableBody.append(`
                    <tr>
                        <td>${album.nombre}</td>
                        <td><img src="/storage/${album.portada}" alt="${album.nombre}" style="max-height: 100px;"></td>
                        <td>${album.fecha_lanzamiento}</td>
                        <td>
                            <button class="btn btn-warning" onclick="editAlbum(${album.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteAlbum(${album.id})">Delete</button>
                        </td>
                    </tr>
                `);
            });
        });
    }

    function editAlbum(id) {
        $.get(`/get/album/${id}`, function (album) {
            $('#albumId').val(album.id);
            $('#nombre').val(album.nombre);
            $('#portadaPreview').attr('src', `/storage/${album.portada}`).show();
            $('#fecha_lanzamiento').val(album.fecha_lanzamiento);
            $('#albumModal').modal('show');
        });
    }

    function deleteAlbum(id) {
        $.ajax({
            url: `/delete/album/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'DELETE',
            success: function () {
                fetchAlbumes();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function clearForm() {
        $('#albumId').val('');
        $('#albumForm')[0].reset();
        $('#portadaPreview').hide();
    }
</script>
@endsection
