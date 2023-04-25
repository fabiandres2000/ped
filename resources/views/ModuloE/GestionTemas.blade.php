@extends('Plantilla.Principal')
@section('title', 'Gestionar Temas Módulo E')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Temas Módulo E</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Asignaturas Módulo E
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div <div class="content-body">
    <section id="number-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestionar Temas</h4>
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
                                                        href="{{ url('/ModuloE/NuevoTema') }}" title="Nueva Tema">
                                                        <i class="fa fa-plus"></i> Crear Tema
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                {!! Form::model(Request::all(), ['url' => '/ModuloE/GestionTema', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}
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
                                                    <th>#</th>
                                                    <th>Título</th>
                                                    <th>Componente</th>
                                                    <th>Asignatura</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                @endphp
                                                @foreach ($Temas as $Tem)
                                                    <tr data-id='{{ $Tem->id }}' id='Tem{{ $Tem->id }}'>
                                                        <td class="text-truncate">{!! $cont !!}</td>
                                                        <td class="text-truncate">{!! $Tem->titulo !!}</td>
                                                        <td class="text-truncate">{!! $Tem->ncom !!}</td>
                                                        <td class="text-truncate">{!! $Tem->nombre . ' - Grado ' . $Tem->grado . '°' !!}</td>
                                                        <td class="text-truncate">
                                                            <a href='{{ url('ModuloE/EditarTema/' . $Tem->id) }}'
                                                                title="Editar" class="btn btn-outline-success btn-sm"><i
                                                                    class="fa fa-edit"></i> </a>
                                                            <a href='#' title="Eliminar"
                                                                class="btn btn-outline-warning btn-sm btnEliminar"
                                                                id="btnActi{{ $Tem->id }}"><i class="fa fa-trash"
                                                                    id="iconBoton{{ $Tem->id }}"> </i></a>
                                                            <a href='{{ url('ModuloE/ConsulPrePract/' . $Tem->id) }}'
                                                                title="Asignar Practica"
                                                                class="btn btn-outline-primary btn-sm"
                                                                id="btnActi{{ $Tem->id }}"><i
                                                                    class="fa fa-check-square-o"
                                                                    id="iconBoton{{ $Tem->id }}"></i> </a>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <p class="px-1"></p>
                            @include('ModuloE.PaginacionTemas')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    {!! Form::open(['url' => '/ModuloE/EliminarTema', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Modulos_E").addClass("has-sub open");
            $("#Men_ModulosE_addTem").addClass("active");


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

                    mensaje = "¿Desea Elimninar este Tema?";

                    Swal.fire({
                        title: 'Gestionar Módulo E',
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
                                    Swal.fire({
                                        title: "",
                                        text: respuesta.mensaje,
                                        icon: "success",
                                        button: "Aceptar"
                                    });

                                    if (respuesta.estado === "ELIMINADO") {
                                        Swal.fire({
                                            title: "",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });
                                        $("#Tem" + id).hide();
                                    }

                                },
                                error: function() {

                                    mensaje = "El Tema no pudo ser Eliminado";

                                    Swal.fire(
                              
                                        'Eliminado!',
                                        mensaje,
                                        'success'
                                      )
                                }
                            });


                         
                        }
                      })
                  
                }
            });
        });
    </script>
@endsection
