@extends('Plantilla.Principal')
@section('title', 'Gestionar Contenido Tematico')
@section('Contenido')

    @php
    use Illuminate\Support\Facades\Input;
    @endphp
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIONAR CONTENIDO TEMATICO</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestionar Contenido Tematico
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
                            <h4 class="card-title">Gestionar Contenido Tematico</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="btn-list">
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('/Asignaturas/NuevoTema') }}" title="Nuevo Tema">
                                                            <i class="fa fa-plus"></i> Crear Tema
                                                        </a>
                                                        @if (Auth::user()->tipo_usuario == 'Administrador')
                                                        <a class="btn btn-outline-danger" href="#"
                                                        onclick="$.Reasignar();" title="Reasignar Unidades">
                                                        <i class="fa fa-exchange"></i> Reasignar Temas
                                                    </a>
                                                    @endif

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    {!! Form::model(Request::all(), ['url' => '/Asignaturas/GestionTem', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}

                                                    <div class="input-group">
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
                                        <div class="table-responsive scrollable-container">
                                            <table id="recent-orders"
                                                class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th>Opciones</th>
                                                        <th>Título del Tema</th>
                                                        <th>Asignatura</th>
                                                        <th>Unidad</th>
                                                        
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($Temas as $Tem)
                                                        <tr data-id='{{ $Tem->id }}' id='Tema{{ $Tem->id }}'>
                                                            <td class="text-truncate">
                                                                <a href='{{ url('Asignaturas/EditarTema/' . $Tem->id) }}'
                                                                    title="Editar"
                                                                    class="btn btn-outline-success  btn-sm"><i
                                                                        class="fa fa-edit"></i></a>
                                                                <a href='#' title="Eliminar"
                                                                    class="btn btn-outline-warning  btn-sm btnEliminar"
                                                                    id="btnActi{{ $Tem->id }}"><i
                                                                        class="fa fa-trash"
                                                                        id="iconBoton{{ $Tem->id }}"></i></a>
                                                                <a href='{{ url('Asignaturas/GestionAsigEvaluacion/' . $Tem->id) }}'
                                                                    class="btn btn-outline-info  btn-sm"
                                                                    title="Asignar Evaluación"><i
                                                                        class="fa fa-check-square-o"></i></a>
                                                            </td>
                                                            <td class="text-truncate" title="{!! $Tem->titu_contenido !!}" style="text-transform: capitalize;">
                                                                {!! substr($Tem->titu_contenido,0,40) !!}...</td>
                                                            <td class="text-truncate">{!! $Tem->nombre . ' - Grado ' . $Tem->grado_modulo . '°' !!}</td>
                                                            <td class="text-truncate">{!! $Tem->nom_unidad . ' - ' . $Tem->des_unidad !!}</td>
                                                        
                                                     
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
                                                    <h4 class="modal-title" id="titu_tema">Reasignar Temas creados por
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
                                                                    onchange="$.CargaTemas(this.value);"
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
                                                                    Docente a Reasginar el Tema </label>

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
                                                                onclick="$.ReasignarTemas();"
                                                                title="Buscar Estudiantes">
                                                                <i class="fa fa-check"></i> Reasignar
                                                            </a>
                                                        </diV>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <form action="{{ url('/Asignaturas/ReasignarTemas') }}"
                                                                    method="post" id="FormTemas">
                                                                    <table id="recent-orders"
                                                                        class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Tema</th>
                                                                                <th>Asignatura</th>
                                                                                <th class="text-center"><label
                                                                                        style='cursor: pointer;'><input
                                                                                            type='checkbox'
                                                                                            onclick="$.SelAllTemas();"
                                                                                            id="SelAll" value=''>
                                                                                        Seleccionar
                                                                                    </label></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="td-Temas"
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
                                @include('Asignaturas.paginacionTema')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Asignaturas/ElimnarTema', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Asignaturas/CargarTemasReasignar', 'id' => 'formAuxiliarTemas']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addTem").addClass("active");

            $('[data-toggle="tooltip"]').tooltip()


               $.extend({
                Reasignar: function() {
                    $("#ModReasignar").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                CargaTemas: function(Doce) {


                    var form = $("#formAuxiliarTemas");
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

                            if (Object.keys(respuesta.Temas).length > 0) {
                                $.each(respuesta.Temas, function(i, item) {
                                    Tabla += " <tr data-id='" + item.id +
                                        "' id='Unidad" + item.id + "'>";
                                    Tabla += "<td class='text-truncate'>" + j +
                                        "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .titu_contenido +"</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .asignatura + "</td> ";
                                    Tabla +=
                                        "<input type='hidden' name='idTema[]' value='" +
                                        item.id +
                                        "'><input type='hidden' id='TemSel" +
                                        j +
                                        "' name='TemSel[]' value='no'><td class='text-truncate text-center'><input type='checkbox' onclick='$.SelTema(" +
                                        j + ");' id='Seleccion" +
                                        j +
                                        "' style='cursor: pointer;' name='TemaSel' value=''></td> ";
                                    Tabla += " </tr>";
                                    j++;
                                });
                                $("#td-Temas").html(Tabla);
                                $("#Divdoc2").show();
                                $("#btn_reasignar").show();

                            } else {
                                $("#td-Temas").html('');
                                $("#Divdoc2").hide();
                                $("#btn_reasignar").hide();
                                swal.fire({
                                    title: "Administrar Temas",
                                    text: 'No existen Temas para ser Reasignadas',
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
                SelAllTemas: function() {
                    var j = 1;
                    if ($('#SelAll').prop('checked')) {
                        $("input[name='TemaSel']").each(function(indice, elemento) {
                            $(elemento).prop("checked", true);
                            $("#TemSel" + j).val("si");
                            j++;
                        });
                    } else {
                        $("input[name='TemaSel']").each(function(indice, elemento) {
                            $(elemento).prop("checked", false);
                            $("#TemSel" + j).val("no");
                            j++;
                        });
                    }
                },
                SelTema: function(id) {
                    if ($('#Seleccion' + id).prop('checked')) {
                        $("#TemSel" + id).val("si");
                    } else {
                        $("#TemSel" + id).val("no");

                    }
                },
                ReasignarTemas: function(){

                    var doc1=$("#docenteold").val();
                    var doc2=$("#docentenew").val();

                    var flag = "no";
                    $("input[name='TemaSel']").each(function(indice, elemento) {
                        if ($(elemento).prop('checked')) {
                            flag = "si";
                            return;
                        }
                    });

                    

                    if(doc1===doc2) {
                        $('#docentenew').val("")
                        .trigger('change.select2');
                        
                        swal.fire({
                            title: "Administrar Temas",
                            text: 'Debe Seleccionar un docente diferente al seleccionado inicialmente',
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }else if(doc2===""){
                        swal.fire({
                            title: "Administrar Temas",
                            text: 'Debe Seleccionar un docente',
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return
                    }else if(flag==="no"){
                        swal.fire({
                            title: "Administrar Temas",
                            text: 'Debe Seleccionar los Temas a Reasignar',
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    var form = $("#FormTemas");
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

                    mensaje = "¿Desea Reasignar los Temas Seleccionados?";
                    Swal.fire({
                        title: 'Administrar Temas',
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
                                            title: "Administrar Temas",
                                            text: respuesta.Mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                 
                                        $("#td-Temas").html("");
                                     
                                    }else{
                                        swal.fire({
                                            title: "Administrar Temas",
                                            text: respuesta.Mensaje,
                                            icon: "warning",
                                            button: "Aceptar",
                                        });
                                    }
                                },
                                error: function() {

                                    mensaje = "Administrar Temas";

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

                    mensaje = "¿Desea Eliminar este Tema?";

                    Swal.fire({
                        title: 'Gestionar Temas',
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
                                            title: "Gestionar Temas",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        $("#Tema" + id).hide();
                                    }else if (respuesta.estado === "SINPERMISO"){
                                        Swal.fire({
                                            title: "Gestionar Temas",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });
                                    }else if(respuesta.estado==="NO"){
                                        Swal.fire({
                                            title: "Gestionar Temas",
                                            text: respuesta.mensaje,
                                            icon: "warning",
                                            button: "Aceptar"
                                        });

                                    }

                                },
                                error: function() {

                                    mensaje = "El Tema no pudo ser Eliminado";

                                    Swal.fire(

                                        'Gestionar Temas',
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
