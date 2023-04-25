@extends('Plantilla.Principal')
@section('title', 'Gestionar Unidades')
@section('Contenido')
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIONAR UNIDADES</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Unidades
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
                            <h4 class="card-title">Gestionar Unidades</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('/Asignaturas/NuevaUnidad') }}"
                                                            title="Nueva Unidad">
                                                            <i class="fa fa-plus"></i> Crear Unidad
                                                        </a>
                                                        @if (Auth::user()->tipo_usuario == 'Administrador')
                                                            <a class="btn btn-outline-danger" href="#"
                                                                onclick="$.Reasignar();" title="Reasignar Unidades">
                                                                <i class="fa fa-exchange"></i> Reasignar Unidades
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    {!! Form::model(Request::all(), [
                                                        'url' => '/Asignaturas/GestionUnid',
                                                        'method' => 'GET',
                                                        'autocomplete' => 'off',
                                                        'role' => 'search',
                                                        'class' => '',
                                                    ]) !!}
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="nombre" id="nombre">
                                                            {!! $select_Asig !!}
                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="col-md-3">
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
                                                        <th>Opciones</th>
                                                        <th>Nombre</th>
                                                        <th>Descripción</th>
                                                        <th>Asignatura</th>
                                                        <th>Periodo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($Unidades as $Unid)
                                                        <tr data-id='{{ $Unid->id }}' id='unidad{{ $Unid->id }}'>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Asignaturas/ConsultarUnidad/' . $Unid->id) }}'
                                                                    title="Ver" class="btn  btn-outline-info  btn-sm"><i
                                                                        class="fa fa-search"></i></a>
                                                                <a href='{{ url('Asignaturas/EditarUnidad/' . $Unid->id) }}'
                                                                    title="Editar"
                                                                    class="btn  btn-outline-success  btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href='#' title="Eliminar"
                                                                    class="btn  btn-outline-warning  btn-sm btnEliminar"
                                                                    id="btnActi{{ $Unid->id }}"><i class="fa fa-trash"
                                                                        id="iconBoton{{ $Unid->id }}"></i></a>
                                                            </td>
                                                            <td class="text-truncate">{!! $Unid->nom_unidad !!}</td>
                                                            <td class="text-truncate">{!! strtoupper($Unid->des_unidad) !!}</td>
                                                            <td class="text-truncate">{!! $Unid->nombre . ' - Grado ' . $Unid->grado_modulo . '°' !!}</td>
                                                            <td class="text-truncate">{!! $Unid->des_periodo !!}</td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade text-left" id="ModReasignar" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel15" aria-hidden="true">
                                    <div class="modal-dialog  modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-body">
                                                <div class="modal-header bg-blue white">
                                                    <h4 class="modal-title" id="titu_tema">Reasignar Unidades creadas por
                                                        Docentes</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <h5> </h5>
                                                    <div class="row pt-1">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="form-label" for="">Seleccione el
                                                                    Docente</label>
                                                                <select class="form-control select2" style="width: 100%;"
                                                                    onchange="$.CargaUnidades(this.value);"
                                                                    data-placeholder="Seleccione el Docente"
                                                                    id="docenteold">
                                                                    {!! $select_docente !!}

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5" id="Divdoc2"
                                                            style="width: 100%; display: none;">
                                                            <div class="form-group">
                                                                <label class="form-label" for="">Seleccione el
                                                                    Docente a Reasginar Unidades </label>

                                                                <select class="form-control select2" style="width: 100%;"
                                                                    data-placeholder="Seleccione el docente"
                                                                    id="docentenew">
                                                                    {!! $select_docente !!}

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <diV class="col-md-2 text-right" style="display: none;"
                                                            id="btn_reasignar">
                                                            <label class="form-label" for=""></label>
                                                            <a class="btn btn-outline-success"
                                                                onclick="$.ReasignarUnidades();"
                                                                title="Buscar Estudiantes">
                                                                <i class="fa fa-check"></i> Reasignar
                                                            </a>
                                                        </diV>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <form action="{{ url('/Asignaturas/ReasignarUnidades') }}"
                                                                    method="post" id="FormUnidades">
                                                                    <table id="recent-orders"
                                                                        class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Unidad</th>
                                                                                <th>Asignatura</th>
                                                                                <th class="text-center"><label
                                                                                        style='cursor: pointer;'><input
                                                                                            type='checkbox'
                                                                                            onclick="$.SelAllUnid();"
                                                                                            id="SelAll" value=''>
                                                                                        Seleccionar
                                                                                    </label></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="td-unidades"
                                                                            style="text-transform: capitalize;">

                                                                        </tbody>
                                                                    </table>
                                                                </form>
                                                            </div>
                                                            <p class="px-1"></p>

                                                        </div>
                                                    </div>

                                                    <div id="ResulProv" class="row"></div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
                                @include('Asignaturas.paginacionUnidad')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Asignaturas/EliminarUnidad', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Asignaturas/CargarUnidadesReasignar', 'id' => 'formAuxiliarUniades']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addUnid").addClass("active");

            $.extend({
                Reasignar: function() {
                    $("#ModReasignar").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                CargaUnidades: function(Doce) {


                    var form = $("#formAuxiliarUniades");
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

                            if (Object.keys(respuesta.Unidades).length > 0) {
                                $.each(respuesta.Unidades, function(i, item) {
                                    Tabla += " <tr data-id='" + item.id +
                                        "' id='Unidad" + item.id + "'>";
                                    Tabla += "<td class='text-truncate'>" + j +
                                        "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .nom_unidad + " - " + item.des_unidad +
                                        "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .asignatura + "</td> ";
                                    Tabla +=
                                        "<input type='hidden' name='idUnid[]' value='" +
                                        item.id +
                                        "'><input type='hidden' id='UnidSel" +
                                        j +
                                        "' name='UnidSel[]' value='no'><td class='text-truncate text-center'><input type='checkbox' onclick='$.SelUnidad(" +
                                        j + ");' id='Seleccion" +
                                        j +
                                        "' style='cursor: pointer;' name='UnidadSel' value=''></td> ";
                                    Tabla += " </tr>";
                                    j++;
                                });
                                $("#td-unidades").html(Tabla);
                                $("#Divdoc2").show();
                                $("#btn_reasignar").show();

                            } else {
                                $("#td-unidades").html('');
                                $("#Divdoc2").hide();
                                $("#btn_reasignar").hide();
                                swal.fire({
                                    title: "Gestionar Unidades",
                                    text: 'No existen Uniades para ser Reasignadas',
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }

                        },
                        error: function() {

                            mensaje = "No se pudo Cargar las Unidades";

                            swal.fire({
                                title: "",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    });
                },
                SelAllUnid: function() {
                    var j = 1;
                    if ($('#SelAll').prop('checked')) {
                        $("input[name='UnidadSel']").each(function(indice, elemento) {
                            $(elemento).prop("checked", true);
                            $("#UnidSel" + j).val("si");
                            j++;
                        });
                    } else {
                        $("input[name='UnidadSel']").each(function(indice, elemento) {
                            $(elemento).prop("checked", false);
                            $("#UnidSel" + j).val("no");
                            j++;
                        });
                    }
                },
                SelUnidad: function(id) {
                    if ($('#Seleccion' + id).prop('checked')) {
                        $("#UnidSel" + id).val("si");
                    } else {
                        $("#UnidSel" + id).val("no");

                    }
                },
                ReasignarUnidades: function() {

                    var doc1 = $("#docenteold").val();
                    var doc2 = $("#docentenew").val();

                    var flag = "no";
                    $("input[name='UnidadSel']").each(function(indice, elemento) {
                        if ($(elemento).prop('checked')) {
                            flag = "si";
                            return;
                        }
                    });

                    if (doc1 === doc2) {
                        $('#docentenew').val("")
                            .trigger('change.select2');

                        swal.fire({
                            title: "Gestionar Unidades",
                            text: 'Debe Seleccionar un docente diferente al seleccionado inicialmente',
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    } else if (doc2 === "") {
                        swal.fire({
                            title: "Gestionar Unidades",
                            text: 'Debe Seleccionar un docente',
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return
                    } else if (flag === "no") {
                        swal.fire({
                            title: "Gestionar Unidades",
                            text: 'Debe Seleccionar las unidad(es) a Reasignar',
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    var form = $("#FormUnidades");
                    var token = $("#token").val();

                    $("#doc2").remove();
                    $("#doc1").remove();
                    $("#_token").remove();

                    form.append("<input type='hidden' name='docenteReasig' id='doc2' value='" + doc2 +
                        "'>");
                    form.append("<input type='hidden' name='docenteOld' id='doc1' value='" + doc1 +
                        "'>");
                    form.append("<input type='hidden' name='_token'  id='_token' value='" + token +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var mensaje = "Las Unidades Fueron reasignadas correctamente";


                    mensaje = "¿Desea Reasignar las Unidades Seleccionadas?";
                    Swal.fire({
                        title: 'Gestionar Unidades',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Reasignar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "post",
                                url: url,
                                data: datos,
                                dataType: "json",
                                success: function(respuesta) {
                                    if (respuesta.Estado === "SI") {
                                        swal.fire({
                                            title: "Gestionar Unidades",
                                            text: respuesta.Mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });


                                        $("#td-unidades").html("");

                                    } else {
                                        swal.fire({
                                            title: "Gestionar Unidades",
                                            text: respuesta.Mensaje,
                                            icon: "warning",
                                            button: "Aceptar",
                                        });
                                    }
                                },
                                error: function() {

                                    mensaje = "Gestionar Unidades";

                                    swal.fire({
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

                    mensaje = "¿Desea Elimninar esta Unidad?";

                    Swal.fire({
                        title: 'Gestionar Unidades',
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
                                    if (respuesta.opc === "NT") {
                                        Swal.fire({
                                            title: "",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        if (respuesta.estado === "ELIMINADO") {
                                            $("#unidad" + id).hide();
                                        }
                                    } else if (respuesta.opc === "NO") {
                                        Swal.fire({
                                            title: "Gestionar Unidades",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });
                                    } else if (respuesta.opc === "VU") {
                                        Swal.fire({
                                            title: "Gestionar Unidades",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "Gestionar Unidades",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });
                                    }


                                },
                                error: function() {

                                    mensaje = "La Unidad no pudo ser Eliminada";

                                    Swal.fire(

                                        'Gestionar Unidad',
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
