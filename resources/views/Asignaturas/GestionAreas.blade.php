@extends('Plantilla.Principal')
@section('title', 'Gestionar Áreas')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Áreas</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Áreas
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
                            <h4 class="card-title">Gestionar Áreas</h4>
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
                                                            href="{{ url('/Asignaturas/NuevaArea') }}" title="Nueva Área">
                                                            <i class="fa fa-plus"></i> Nueva Área
                                                        </a>
                                                    </div>
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
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($Areas as $Ar)
                                                        <tr data-id='{{ $Ar->id }}' id='Asig{{ $Ar->id }}'>
                                                            <td class="text-truncate">{!! $Ar->nombre_area !!}</td>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Asignaturas/EditarArea/' . $Ar->id) }}'
                                                                    title="Editar" class="btn btn-outline-success btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href='#' title="Eliminar"
                                                                    class="btn btn-outline-warning  btn-sm btnEliminar"
                                                                    id="btnActi{{ $Ar->id }}"><i class="fa fa-trash"
                                                                        id="iconBoton{{ $Ar->id }}"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Asignaturas/EliminarArea', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addAreas").addClass("active");


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

                    mensaje = "¿Desea Elimninar esta Área?";

             
                    Swal.fire({
                        title: 'Gestionar Área',
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


                                   if(respuesta.estado === "ELIMINADO"){

                                    Swal.fire({
                                        title: "Gestionar Área",
                                        text: respuesta.mensaje,
                                        icon: "success",
                                        button: "Aceptar"
                                    });

                                    if (respuesta.estado === "ELIMINADO") {

                                        $("#Asig" + id).hide();
                                    }

                                }else{

                                    Swal.fire({
                                        title: "Gestionar Área",
                                        text: respuesta.mensaje,
                                        icon: "warning",
                                        button: "Aceptar"
                                    });
                                }

                                },
                                error: function() {

                              
                                    mensaje =
                                        "El Área no pudo ser Eliminada!";

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
