@extends('Plantilla.Principal')
@section('title', 'Gestionar Contenido Tematico')
@section('Contenido')

    @php
    use Illuminate\Support\Facades\Input;
    @endphp
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIONAR TEMATICA ZONA LIBRE</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Contenido Zona Libre
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
                            <h4 class="card-title">Gestionar Contenido Zona Libre</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="btn-list">
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('/Asignaturas/NuevaZona') }}" title="Nuevo Tema">
                                                            <i class="fa fa-plus"> Nuevo Contenido</i>
                                                        </a>


                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    {!! Form::model(Request::all(), ['url' => '/Asignaturas/ZonaLibre', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}

                                                    <div class="input-group">
                                                        <select class="form-control select2" name="grado" id="grado">
                                                            {!! $select_grado !!}
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
                                                <thead>
                                                    <tr>

                                                        <th>Grado</th>
                                                        <th>Fecha</th>
                                                        <th>Tipo de Contenido</th>
                                                        <th>Título del Tema</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($Temas as $Tem)
                                                        <tr data-id='{{ $Tem->id }}' id='Tema{{ $Tem->id }}'>
                                                            <td class="text-truncate">{!! 'Grado ' . $Tem->grado . '°' !!}</td>
                                                            <td class="text-truncate">{!! $Tem->fecha !!}</td>
                                                            <td class="text-truncate">{!! $Tem->tip_contenido !!}</td>
                                                            <td class="text-truncate" style="text-transform: capitalize;">
                                                                {!! $Tem->titu_contenido !!}</td>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Asignaturas/EditarTemaLibre/' . $Tem->id) }}'
                                                                    title="Editar"
                                                                    class="btn btn-outline-success  btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href='#' title="Eliminar"
                                                                    class="btn btn-outline-warning  btn-sm btnEliminar"
                                                                    id="btnActi{{ $Tem->id }}"><i
                                                                        class="fa fa-trash"
                                                                        id="iconBoton{{ $Tem->id }}"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
                                @include('ZonaLibre.paginacionZonaLibre')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Asignaturas/ElimnarTemaZona', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Zona").addClass("active");



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

                    mensaje = "¿Desea Eliminar este Tema?";

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
                                        icon: "success",
                                        button: "Aceptar"
                                    });

                                    if (respuesta.estado === "ELIMINADO") {

                                        $("#Tema" + id).hide();
                                    }

                                },
                                error: function() {

                                    mensaje = "El Contenido no pudo ser Eliminado";

                                    Swal.fire(
                                        'Gestionar Laboratorios',
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
