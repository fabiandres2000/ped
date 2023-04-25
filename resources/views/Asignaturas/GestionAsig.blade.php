@extends('Plantilla.Principal')
@section('title', 'Gestionar Grados')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Grados</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Grados
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
                            <h4 class="card-title">Gestionar Grados</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('/Asignaturas/NuevoModulo') }}"
                                                            title="Nuevo Grado">
                                                            <i class="fa fa-plus"></i> Crear Grado
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    {!! Form::model(Request::all(), [
                                                        'url' => '/Asignaturas/GestionGrado',
                                                        'method' => 'GET',
                                                        'autocomplete' => 'off',
                                                        'role' => 'search',
                                                    ]) !!}
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="nombre" id="nombre">
                                                            {!! $select_Asig !!}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        {!! Form::text('txtbusqueda', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'BUSQUEDA..']) !!}
                                                        <span class="input-group-append">
                                                            <button type="submit" class="btn btn-primary "> <i
                                                                    class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>

                                                </div>
                                                {!! Form::close() !!}
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
                                                        <th>Asignatura</th>
                                                        <th>Grado</th>
                                                        <th>Estado</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($Asignatura as $Asig)
                                                        <tr data-id='{{ $Asig->id }}' id='Asig{{ $Asig->id }}'>
                                                            <td class="text-truncate">{!! $Asig->nombre !!}</td>
                                                            <td class="text-truncate">{!! 'Grado ' . $Asig->grado_modulo . '°' !!}</td>
                                                            <!--                                                    <td class="text-center font-small-2">{!! $Asig->avance_modulo !!}%
                                                                            <div class="progress progress-sm mt-1 mb-0">
                                                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $Asig->avance_modulo }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                                            </div>
                                                                        </td>-->
                                                            <td class="text-truncate">{!! $Asig->estado_modulo !!}</td>
                                                            <td class="text-truncate">
                                                                <!--<a href='{{ url('Asignaturas/Consultar/' . $Asig->id) }}' data-toggle="tooltip" title="Ver" class="btn btn-icon btn-outline-info btn-social-icon btn-sm"><i class="fa fa-search"></i></a>-->
                                                                <a href='{{ url('Asignaturas/EditarAsig/' . $Asig->id) }}'
                                                                    title="Editar"
                                                                    class="btn  btn-outline-success  btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href='#' title="Eliminar"
                                                                    class="btn btn-outline-warning btn-sm btnEliminar"
                                                                    id="btnActi{{ $Asig->id }}"><i class="fa fa-trash"
                                                                        id="iconBoton{{ $Asig->id }}"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
                                @include('Asignaturas.paginacion')

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Asignaturas/Eliminar', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addAdig").addClass("active");


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

                    mensaje = "¿Desea Elimninar este Grado?";

                    Swal.fire({
                        title: 'Gestionar Grados',
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
                                    if (respuesta.estado == "ELIMINADO") {
                                        Swal.fire({
                                            title: "Gestionar Grados",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        $("#Asig" + id).hide();
                                    }else if (respuesta.estado == "SINPERMISO"){    
                                        Swal.fire({
                                            title: "Gestionar Grados",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });
                                    } else if (respuesta.estado == "NO ELIMINADO") {

                                        Swal.fire({
                                            title: "Gestionar Grados",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });
                                    }

                                },
                                error: function() {

                                    mensaje = "El Grado no pudo ser Eliminado";
                                    Swal.fire(

                                        'Gestionar Grados',
                                        mensaje,
                                        'warning'
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
