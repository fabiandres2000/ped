@extends('Plantilla.Principal')
@section('title', 'Gestionar de Usuarios')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Usuarios</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Usuarios
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="number-tabs">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Gestionar Usuarios</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-8 col-lg-6">
                                                    <div class="btn-list">
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('/Usuarios/Nuevo') }}" title="Nuevo Usuario">
                                                            <i class="fa fa-user-plus"></i> Nuevo Usuario
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-6 float-md-right">
                                                    {!! Form::model(Request::all(), ['url' => '/Usuarios/Gestion', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}
                                                    <div class="input-group">
                                                        {!! Form::text('txtbusqueda', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'BUSQUEDA..']) !!}
                                                        <span class="input-group-append">
                                                            <button type="submit" class="btn btn-primary "> <i
                                                                    class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (Session::has('error'))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-icon-right alert-warning alert-dismissible mb-2"
                                                        role="alert">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                        <strong>Alerta!</strong> {!! session('error') !!}

                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                        @if (Session::has('success'))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-icon-right alert-info alert-dismissible mb-2"
                                                        role="alert">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                        <strong>{!! session('success') !!}</strong>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="recent-orders"
                                                class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Usuario</th>
                                                        <th>Perfil</th>
                                                        <th>Estado</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-transform: capitalize;">
                                                    @foreach ($Usuarios as $Usu)
                                                        <tr data-id='{{ $Usu->id }}' id='Usuario{{ $Usu->id }}'>
                                                            <td class="text-truncate">{!! $Usu->nombre_usuario !!}</td>
                                                            <td class="text-truncate">{!! $Usu->login_usuario !!}</td>
                                                            <td class="text-truncate">{!! $Usu->tipo_usuario !!}</td>
                                                            <td class="text-truncate">{!! $Usu->estado_usuario !!}</td>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Usuarios/Editar/' . $Usu->id) }}'
                                                                     title="Editar"
                                                                    class="btn btn-outline-success btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <p class="px-1"></p>
                                        @include('Usuario.Paginacion')
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Usuario/Eliminar', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Usuarios").addClass("active open");

            $(".btnEliminar").on({
                click: function(e) {
                    e.preventDefault();
                    var boton = $(this);
                    var hijo = $(this).children('i');
                    console.log(hijo.attr('id'));
                    var fila = $(this).parents('tr');
                    var id = fila.data('id');
                    var form = $("#formAuxiliar");
                    $("#idAuxiliar").remove();
                    form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var mensaje = "";

                    var cadena = fila.find("td:eq(8)").text();

                    mensaje = "¿Desea Elimninar este Alumno?";

                    swal({
                        title: mensaje,
                        text: "",
                        icon: "warning",
                        buttons: true,
                        buttons: ["Cancelar", "Aceptar"],
                        dangerMode: true,
                    }).then((result) => {
                        if (result === true) {
                            $.ajax({
                                type: "post",
                                url: url,
                                data: datos,
                                success: function(respuesta) {
                                    swal({
                                        title: "",
                                        text: respuesta.mensaje,
                                        icon: "success",
                                        button: "Aceptar",
                                    });

                                    if (respuesta.estado === "ELIMINADO") {

                                        $("#alumno" + id).hide();
                                    }

                                },
                                error: function() {

                                    mensaje = "El Alumno no pudo ser Eliminado";

                                    swal({
                                        title: "",
                                        text: mensaje,
                                        icon: "warning",
                                        button: "Aceptar",
                                    });
                                }
                            });
                        }
                    });
                }
            });

        });
    </script>
@endsection
