@extends('Plantilla.Principal')
@section('title', 'Gestionar de Evaluaciones Laboratorio')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIONAR EVALUACIONES / ACTIVIDADES</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Evaluaciones / Actividades
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="number-tabs">

            <p class="px-1"></p>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Gestionar Evaluaciones / Actividades</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
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
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-10 ">
                                                    <div class="btn-list">
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('/Laboratorios/AsigEvaluacion/' . $id) }}"
                                                            title="Nueva Evaluación">
                                                            <i class="fa fa-plus"></i> Crear Evaluación
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 ml-3">
                                                    <a class="btn btn-outline-dark pr-1" href="javascript:history.go(-1)"
                                                        title="Volver">
                                                        <i class="fa fa-angle-double-left"></i> Volver
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive scrollable-container">
                                        <table id="recent-orders"
                                            class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Título</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp

                                                @foreach ($Evaluaciones as $Eva)
                                                    <tr data-id='{{ $Eva->id }}' id='eval{{ $Eva->id }}'>
                                                        <td style="text-transform: uppercase;" class="text-truncate">
                                                            {!! $i !!}</td>
                                                        @php
                                                            $i++;
                                                            $tipo_ev = $Eva->tip_evaluacion;
                                                            $nom_eva = '';
                                                        @endphp

                                                        <td class="text-truncate" style="text-transform:uppercase;">
                                                            {!! $Eva->titulo !!}</td>
                                                        <td class="text-truncate">
                                                            <a href='{{ url('Laboratorios/EditarEvaluacion/' . $Eva->id) }}'
                                                                title="Editar" class="btn  btn-outline-success btn-sm"><i
                                                                    class="fa fa-edit"></i></a>
                                                            <a href='#' title="Eliminar"
                                                                class="btn  btn-outline-warning  btn-sm btnEliminar"
                                                                id="btnActi{{ $Eva->id }}"><i class="fa fa-trash"
                                                                    id="iconBoton{{ $Eva->id }}"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <p class="px-1"></p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

    {!! Form::open(['url' => '/Laboratorios/EliminarEval', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Laboratorios").addClass("active");

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

                    mensaje = "¿Desea Elimninar esta Evaluación?";

                    Swal.fire({
                        title: 'Gestionar Laboratorios',
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
                                        icon: respuesta.icon,
                                        button: "Aceptar"
                                    });

                                    if (respuesta.estado === "ELIMINADO") {
                                        $("#eval" + id).hide();
                                    }

                                },
                                error: function() {

                                    mensaje = "La Evaluacio NO pudo ser Eliminada";

                                    Swal.fire(
                                        'Gestionar Laboratorios',
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
