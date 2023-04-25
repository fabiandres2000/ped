@extends('Plantilla.Principal')
@section('title', 'Gestionar de Estudiantes')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Estudiantes</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Estudiantes
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
                            <h4 class="card-title">Gestionar Estudiantes</h4>
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
                                                            href="{{ url('/Alumnos/Nuevo') }}" title="Nuevo Estudiante">
                                                            <i class="fa fa-user-plus"></i> Crear Estudiante
                                                        </a>
                                                        @if (Auth::user()->tipo_usuario == 'Administrador')
                                                            <a class="btn btn-outline-danger"
                                                                href="{{ url('/Alumnos/Administrar') }}"
                                                                title="Administrar Estudiante">
                                                                <i class="fa fa-address-card"></i> Administrar Estudiantes
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-6 float-md-right">
                                                    {!! Form::model(Request::all(), ['url' => '/Alumnos/Gestion', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}
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
                                                        <th>Identificación</th>
                                                        <th>Nombre</th>
                                                        <th>Apellido</th>
                                                        <th>Grado</th>
                                                        <th>Jornada</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-transform: capitalize;">
                                                    @foreach ($Alumnos as $Alu)
                                                        <tr data-id='{{ $Alu->id }}' id='alumno{{ $Alu->id }}'>
                                                            <td class="text-truncate">{!! $Alu->ident_alumno !!}</td>
                                                            <td class="text-truncate">{!! $Alu->nombre_alumno !!}</td>
                                                            <td class="text-truncate">{!! $Alu->apellido_alumno !!}</td>
                                                            <td class="text-truncate">{!! 'Grado ' . $Alu->grado_alumno . '° - ' . $Alu->descripcion !!}</td>
                                                            <td class="text-truncate">{!! $Alu->Jorna !!}</td>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Alumnos/Consultar/' . $Alu->id) }}'
                                                                    title="Ver" class="btn  btn-outline-info  btn-sm"><i
                                                                        class="fa fa-search"></i></a>
                                                                <a href='{{ url('Alumnos/Editar/' . $Alu->id) }}'
                                                                    title="Editar"
                                                                    class="btn  btn-outline-success  btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href='#' title="Eliminar"
                                                                    class="btn  btn-outline-warning  btn-sm btnEliminar"
                                                                    id="btnActi{{ $Alu->id }}"><i
                                                                        class="fa fa-trash"
                                                                        id="iconBoton{{ $Alu->id }}"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <p class="px-1"></p>
                                        @include('Alumnos.paginacion')
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Alumnos/Eliminar', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Estudiantes").addClass("active open");

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

                    mensaje = "¿Desea Elimninar este Estudiante?";

                    Swal.fire({
                        title: 'GESTIONAR ESTUDIANTES',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "post",
                                url: url,
                                data: datos,
                                success: function(respuesta) {
                                    if (respuesta.estado === "ELIMINADO") {
                                        Swal.fire({
                                            title: "",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });
                                        $("#alumno" + id).hide();
                                    }
                                },
                                error: function() {
                                    mensaje = "El Estudiante no pudo ser Eliminado";
                                    Swal.fire(
                                        'Eliminado!',
                                        mensaje,
                                        'success'
                                    )
                                }
                            });
                        }
                    });
                }
            });

        });
    </script>
@endsection
