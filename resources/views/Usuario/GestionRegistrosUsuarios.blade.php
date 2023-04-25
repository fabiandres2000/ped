@extends('Plantilla.Principal')
@section('title', 'Registros de Usuarios')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestionar Registros de Usuarios</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Registros de Usuarios
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
                            <h4 class="card-title">Gestionar Registros de Usuarios</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                              
                                                <div class="col-md-6">
                                                    {!! Form::model(Request::all(), [
                                                        'url' => '/Usuarios/RegistrosUsuarios',
                                                        'method' => 'GET',
                                                        'autocomplete' => 'off',
                                                        'role' => 'search',
                                                        'id' => 'RegUsu'
                                                    ]) !!}
                                                    <div class="form-group">
                                                        <label class="control-label">Usuarios</label>
                                                        <select class="form-control select2" name="Usuario" id="Usuario">
                                                            {!! $select_Usu !!}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Fecha Inicio</label>
                                                    <div class="input-group">
                                                      
                                                        {!! Form::text('fechaIni', null, ['class' => 'form-control', 'placeholder' => 'Fecha Inicio...','id' => 'fechaIni']) !!}
                                                     
                                                    </div>

                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Fecha Final</label>
                                                    <div class="input-group">
                                                       
                                                        {!! Form::text('fechaFin', null, ['class' => 'form-control ', 'placeholder' => 'Fecha Final...','id' => 'fechaFin']) !!}
                                                      
                                                    </div>

                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label"></label>
                                                    <div class="input-group">
                                                  
                                                        <span class="input-group-append">
                                                            <button type="button" onclick="$.Consultar();" class="btn btn-primary "> <i
                                                                    class="fa fa-search"></i> Busqueda</button>
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
                                                        <th>Acción</th>
                                                        <th>Descripción</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha y hora</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($RegistUsu as $Reg)
                                                        <tr >
                                                            <td class="text-truncate">{!! $Reg->accion !!}</td>
                                                            <td class="text-truncate">{!! $Reg->descr  !!}</td>
                                                            <td class="text-truncate">{!! $Reg->nombre_usuario !!}</td>
                                                            <td class="text-truncate">{!! $Reg->fc !!}</td>
                                                           
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
                                @include('Usuario.paginacionRegistros')

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

          $('#fechaIni').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });

            $('#fechaFin').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });

            $.extend({
                Consultar: function(){

                    if($("#fechaIni").val() == ''){
                        Swal.fire({
                            title: "Registros de Usuarios",
                            text: "Seleccione la Fecha Inicial",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if($("#fechaFin").val() == ''){
                        Swal.fire({
                            title: "Registros de Usuarios",
                            text: "Seleccione la Fecha Final",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }
                    
                    
                    Swal.fire({
                        title: 'Espere Por Favor',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        background: '#FFFFFF',
                        showConfirmButton: false,
                        onOpen: () => {
                            Swal.showLoading();
                        }
                    })
                    Swal.showLoading()

                    $("#RegUsu").submit();

                }
            })

            $("#Men_Inicio").removeClass("active");
            $("#Men_RegUsuarios").addClass("active open");


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


                                    if (respuesta.estado === "ELIMINADO") {
                                        Swal.fire({
                                            title: "Gestionar Grados",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        $("#Asig" + id).hide();
                                    } else if (respuesta.estado === "NO ELIMINADO") {

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
