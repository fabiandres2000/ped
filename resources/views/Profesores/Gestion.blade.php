@extends('Plantilla.Principal')
@section('title', 'Gestionar Docentes')
@section('Contenido')
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Docentes</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Docentes
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
                            <h4 class="card-title">Gestionar Docentes</h4>
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
                                                            href="{{ url('/Profesores/Nuevo') }}" title="Nuevo Docente">
                                                            <i class="fa fa-user-plus"></i> Crear Docente
                                                        </a>
                                                        @if (Auth::user()->tipo_usuario == 'Administrador')
                                                        <a class="btn btn-outline-blue-grey"
                                                        href="#" onclick="$.MostrarCarga();" title="Carga Academica">
                                                        <i class="fa fa-address-card-o"></i> Consultar Carga Academica
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-6 float-md-right">
                                                    {!! Form::model(Request::all(), ['url' => '/Profesores/Gestion', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}
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
                                                        <th>Jornada</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($Profesores as $Prof)
                                                        <tr data-id='{{ $Prof->id }}' id='profesor{{ $Prof->id }}'
                                                            style="text-transform: capitalize;">
                                                            <td class="text-truncate">{!! $Prof->identificacion !!}</td>
                                                            <td class="text-truncate">{!! $Prof->nombre !!}</td>
                                                            <td class="text-truncate">{!! $Prof->apellido !!}</td>

                                                            <td class="text-truncate">{!! $Prof->Jorna !!}</td>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Profesores/Consultar/' . $Prof->id) }}'
                                                                    title="Ver" class="btn  btn-outline-info  btn-sm"><i
                                                                        class="fa fa-search"></i></a>
                                                                <a href='{{ url('Profesores/Editar/' . $Prof->id) }}'
                                                                    title="Editar"
                                                                    class="btn  btn-outline-success  btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href="#" title="Eliminar"
                                                                    class="btn  btn-outline-warning  btn-sm btn-sm btnEliminar"
                                                                    id="btnActi{{ $Prof->id }}"><i
                                                                        class="fa fa-trash"
                                                                        id="iconBoton{{ $Prof->id }}"></i></a>

                                                                <button type="button"
                                                                    class="btn btn-outline-blue  btn-sm btn-sm"
                                                                    title="Asignar Asignatura y Modulos"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"><i class="fa fa-list"
                                                                        id="iconBoton{{ $Prof->id }}"></i></button>
                                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                                    style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <a class="dropdown-item"
                                                                        href='{{ url('Profesores/AddAsignatura/' . $Prof->usuario_profesor) }}'>
                                                                        Asignar Asignatura</a>
                                                                        @if (Session::get('PerModu') == 'si')    
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item"
                                                                        href='{{ url('Profesores/AddModulos/' . $Prof->usuario_profesor) }}'>asignar
                                                                        Módulo</a>
                                                                     @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="px-1"></p>
                                        @include('Profesores.paginacion')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    <div class="modal fade text-left" id="ModAsigna" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel15" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-body">
                <div class="modal-header bg-blue white">
                    <h4 class="modal-title" id="titu_tema">Carga Academica de Docentes</h4>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5> </h5>
                    <div class="row pt-1">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="form-label" for="">Seleccione el
                                    Docente</label>
                                <select class="form-control select2" style="width: 100%;"
                                    onchange="$.MostrarCarga();"
                                    id="docenteold">
                                    {!! $select_docente !!}

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 text-right" 
                            id="btn_reasignar">
                            <label class="form-label" for=""></label>
                            <a class="btn btn-outline-success"
                                onclick="$.ImprimirAsig();"
                                title="Buscar Estudiantes">
                                <i class="fa fa-print"></i> Imprimir
                            </a>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12" id="CargaAcademica" style="overflow: scroll; height:300px;" >

                        </div>

                    
                    </div>

                </div>



            </div>
        </div>
    </div>
</div>

    {!! Form::open(['url' => '/Profesores/Eliminar', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/Profesores/CargaAcademica', 'id' => 'formAuxiliarCarga']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Profesores").addClass("active");
            $('[data-toggle="tooltip"]').tooltip();


            $.extend({
                MostrarCarga: function() {
                    $("#ModAsigna").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var Doce =$("#docenteold").val();
                    var form = $("#formAuxiliarCarga");
                    var token = $("#token").val();

                    $("#_token").remove();
                    $("#docente").remove();
                    form.append("<input type='hidden' name='doce' id='docente' value='" + Doce + "'>");
                    form.append("<input type='hidden' name='_token'  id='_token' value='" + token +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var Tabla = "";
                    var j = 1;

                    $.ajax({
                        type: "post",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {

                            if (respuesta) {
                                $("#CargaAcademica").html(respuesta.tableAsig);
                              
                            } else {
                                $("#CargaAcademica").html('');
                                swal.fire({
                                    title: "Administrar Temas",
                                    text: 'No se ha realizado la carga Academica',
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }

                        },
                        error: function() {

                            mensaje = "No se pudo Cargar los Temas";

                            swal.fire({
                                title: "",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    });
                },
                ImprimirAsig: function(){
                    $("#CargaAcademica").css({'height':''});
                    $("#CargaAcademica").printThis({header: "<h1>CARGA ACADEMICA DE DOCENTES</h1>"});
                }

            });

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

                    mensaje = "¿Desea Eliminar este Docente?";

                    Swal.fire({
                        title: 'Gestionar Docentes',
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
                                        $("#profesor" + id).hide();
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
                    });
                }
            });
        });
    </script>
@endsection
