@extends('Plantilla.Principal')
@section('title', 'Administrar Estudiantes')
@section('Contenido')
    @php
        use Illuminate\Support\Facades\Input;
    @endphp
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="form-control" id="RutArchivo" value="{{ url('/') }}/" />
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Administrar Estudiantes</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Administrar Estudiantes
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
                            <h4 class="card-title">Administrar Estudiantes</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <select class="form-control select2"
                                                            data-placeholder="Seleccione el Grado" id="grado_alumno">
                                                            <option value="">Seleccione el Grado</option>
                                                            @for ($i = 1; $i <= 11; $i++)
                                                                <option value="{{ $i }}"
                                                                    {{ Input::old('grado_alumno') == $i ? 'selected' : '' }}>
                                                                    {{ 'GRADO ' . $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">

                                                        <select class="form-control select2"
                                                            data-placeholder="Seleccione el Grupo" id="grupo_alumno">
                                                            <option value="">Seleccione el Grupo</option>
                                                            {!! $SelGrupos !!}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">

                                                        <select id="jornada_alumno" data-placeholder="Seleccione La Jornada"
                                                            class="form-control select2">
                                                            <option value="">Seleccione la Jornada</option>
                                                            <option value="JM">Jornada Mañana</option>
                                                            <option value="JT">Jornada Tarte</option>
                                                            <option value="JN">Jornada Nocturna</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <diV class="col-md-3 ">
                                                    <div class="btn-list align-right">
                                                        <button onclick="$.BuscarAlumnos();" type="button"
                                                            class="btn btn-primary "> <i class="fa fa-search"></i>
                                                            Busq.</button>
                                                        <button onclick="$.ImportarAlumnos();" type="button"
                                                            class="btn btn-warning "> <i class="fa fa-upload"></i>
                                                            Importar</button>
                                                    </div>
                                                </diV>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
                                <div class="row">
                                    <div class="col-md-12">


                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <form action="{{ url('/Alumnos/AdministrarAlumnos') }}" method="post"
                                                id="FormEstudiantes">
                                                <table id="recent-orders"
                                                    class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Identificación</th>
                                                            <th>Nombre</th>
                                                            <th>Apellido</th>
                                                            <th class="text-center"><label style='cursor: pointer;'><input
                                                                        type='checkbox' onclick="$.SelAllEst();"
                                                                        id="SelAll" value=''>
                                                                    Seleccionar
                                                                </label></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="td-alumnos" style="text-transform: capitalize;">

                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="modal-footer" style="display: none;" id="btn-acciones">
                                            <button type="button" onclick="$.Promover();" class="btn btn-outline-cyan"><i
                                                    class="fa fa-sort-numeric-asc"></i>
                                                Promover</button>
                                            <button type="button" class="btn btn-primary btn-min-width dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                    class="fa fa-toggle-off"></i> Desvincular</button>
                                            <div class="dropdown-menu" x-placement="bottom-start"
                                                style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item" onclick="$.Desvincular('Egresado');"
                                                    href="#">Como
                                                    Egresados</a>
                                                <a class="dropdown-item" onclick="$.Desvincular('Desertado');"
                                                    href="#">Como Desertados</a>
                                            </div>
                                        </div>

                                        <p class="px-1"></p>

                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    <div class="modal fade text-left" id="ModPromover" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-body">
                    <div class="modal-header bg-blue white">
                        <h4 class="modal-title" id="titu_tema">Promover Alumnos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <h5>Seleccione el Grado, Grupo y Jornada al que seran promovidos los estudiantes seleccionados.</h5>
                        <div class="row pt-1">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control select2" style="width: 100%;"
                                        data-placeholder="Seleccione el Grado" id="grado_promov">
                                        <option value="">Seleccione el Grado</option>
                                        @for ($i = 1; $i <= 11; $i++)
                                            <option value="{{ $i }}"
                                                {{ Input::old('grado_alumno') == $i ? 'selected' : '' }}>
                                                {{ 'GRADO ' . $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="width: 100%;">
                                <div class="form-group">
                                    <select class="form-control select2" style="width: 100%;"
                                        data-placeholder="Seleccione el Grupo" id="grupo_promov">
                                        <option value="">Seleccione el Grupo</option>
                                        {!! $SelGrupos !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="width: 100%;">
                                <div class="form-group">
                                    <select id="jornada_promov" style="width: 100%;"
                                        data-placeholder="Seleccione La Jornada" class="form-control select2">
                                        <option value="">Seleccione la Jornada</option>
                                        <option value="JM">Jornada Mañana</option>
                                        <option value="JT">Jornada Tarte</option>
                                        <option value="JN">Jornada Nocturna</option>
                                    </select>
                                </div>
                            </div>
                            <diV class="col-md-3 text-right">
                                <a class="btn btn-outline-success" onclick="$.CambiarGradoAlumnos();"
                                    title="Buscar Estudiantes">
                                    <i class="fa fa-check"></i> Alplicar
                                </a>
                            </diV>

                        </div>

                        <div id="ResulProv" class="row"></div>
                    </div>



                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="ModImportar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-body">
                    <div class="modal-header bg-blue white">
                        <h4 class="modal-title" id="titu_tema">Importar Estudiantes</h4>

                    </div>

                    <div id="importar" class="modal-body">
                        <h5>Este proceso solo permite archivos Excel, este archivo debe contener los estudiantes de
                            determinado grado con la información que contiene el siguiente archivo.</h5>
                        <div class="row">
                            <form action="{{ url('/Alumnos/ImportarAlumnos') }}" method="post"
                                id="FormImporEstudiantes">
                                <div class="col-md-12 text-center pb-5">
                                    <button onclick="$.DescargarFormato(this.id);" id="DescArchivo"
                                        data-archivo="Formato_estudiantes.xlsx"
                                        data-ruta="{{ asset('/app-assets/ArchivosEstudiantes') }}" type="button"
                                        class="btn btn-warning "> <i class="fa fa-download"></i>
                                        Descargar Formato</button>
                                </div>
                                <div class="col-md-12">
                                    <h3>Cargar Información.</h3>
                                    <div class="modal-body">
                                        <h5>Seleccione el Grado, Grupo y Jornada al que seran promovidos los estudiantes
                                            seleccionados.</h5>
                                        <div class="row pt-1">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control select2" style="width: 100%;"
                                                        data-placeholder="Seleccione el Grado" name="grado_import"
                                                        id="grado_import">
                                                        <option value="">Seleccione el Grado</option>
                                                        @for ($i = 1; $i <= 11; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ Input::old('grado_alumno') == $i ? 'selected' : '' }}>
                                                                {{ 'GRADO ' . $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="width: 100%;">
                                                <div class="form-group">
                                                    <select class="form-control select2" style="width: 100%;"
                                                        data-placeholder="Seleccione el Grupo" name="grupo_import"
                                                        id="grupo_import">
                                                        <option value="">Seleccione el Grupo</option>
                                                        {!! $SelGrupos !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="width: 100%;">
                                                <div class="form-group">
                                                    <select id="jornada_import" name="jornada_import"
                                                        style="width: 100%;" data-placeholder="Seleccione La Jornada"
                                                        class="form-control select2">
                                                        <option value="">Seleccione la Jornada</option>
                                                        <option value="JM">Jornada Mañana</option>
                                                        <option value="JT">Jornada Tarte</option>
                                                        <option value="JN">Jornada Nocturna</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="ResulProv" class="row"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9" style="width: 100%;">
                                            <div class="form-group">
                                                <label class="form-label " for="imagen">Cargar Formato:</label>
                                                <input id="FormartoAlumnos" name="FormartoAlumnos" type="file"
                                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="width: 100%;">
                                            <div class="form-group">
                                                <button onclick="$.ImportarFormato();" id="btn_importar" type="button"
                                                    class="btn btn-success "> <i class="fa fa-upload"></i>
                                                    Importar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive" style="height:250px;">
                                        <table id="recent-orders"
                                            class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Identificación</th>
                                                    <th>Nombre</th>
                                                    <th>Apellido</th>
                                                </tr>
                                            </thead>
                                            <tbody id="Td-AlumnosImpo" style="text-transform: capitalize; ">

                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="form-actions">
                                        <div class="row  text-right">
                                            <div class="col-md-12 col-lg-12 ">
                                                <div class="btn-list">
                                                    <button type="button" id="btn_newimp"
                                                        class="btn grey btn-outline-secondary" onclick="$.ImporNew();"><i
                                                            class="fa fa-plus"></i> Importar Otros Estudiantes</button>
                                                    <button type="button" id="btn_salir"
                                                        class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                                                            class="ft-corner-up-left position-right"></i> Salir</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-body" id="OpcGrado" style="display:none;">
                        <h5>Seleccione el Grado, Grupo y Jornada al que seran promovidos los estudiantes seleccionados.</h5>
                        <div class="row pt-1">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control select2" style="width: 100%;"
                                        data-placeholder="Seleccione el Grado" id="grado_promov">
                                        <option value="">Seleccione el Grado</option>
                                        @for ($i = 1; $i <= 11; $i++)
                                            <option value="{{ $i }}"
                                                {{ Input::old('grado_alumno') == $i ? 'selected' : '' }}>
                                                {{ 'GRADO ' . $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="width: 100%;">
                                <div class="form-group">
                                    <select class="form-control select2" style="width: 100%;"
                                        data-placeholder="Seleccione el Grupo" id="grupo_promov">
                                        <option value="">Seleccione el Grupo</option>
                                        {!! $SelGrupos !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="width: 100%;">
                                <div class="form-group">
                                    <select id="jornada_promov" style="width: 100%;"
                                        data-placeholder="Seleccione La Jornada" class="form-control select2">
                                        <option value="">Seleccione la Jornada</option>
                                        <option value="JM">Jornada Mañana</option>
                                        <option value="JT">Jornada Tarte</option>
                                        <option value="JN">Jornada Nocturna</option>
                                    </select>
                                </div>
                            </div>
                            <diV class="col-md-3 text-right">
                                <a class="btn btn-outline-success" onclick="$.CambiarGradoAlumnos();"
                                    title="Buscar Estudiantes">
                                    <i class="fa fa-check"></i> Alplicar
                                </a>
                            </diV>

                        </div>

                        <div id="ResulProv" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::open(['url' => '/Alumnos/BuscarAlumnos', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Estudiantes").addClass("active open");

            $.extend({
                BuscarAlumnos: function() {
                    var Grado = $("#grado_alumno").val();
                    var Grupo = $("#grupo_alumno").val();
                    var Jornada = $("#jornada_alumno").val();

                    var form = $("#formAuxiliar");
                    $("#Grado").remove();
                    $("#Grupo").remove();
                    $("#Jornada").remove();
                    form.append("<input type='hidden' name='Grado' id='Grado' value='" + Grado + "'>");
                    form.append("<input type='hidden' name='Grupo' id='Grupo' value='" + Grupo + "'>");
                    form.append("<input type='hidden' name='Jornada' id='Jornada' value='" + Jornada +
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

                            if (Object.keys(respuesta.Alumnos).length > 0) {
                                $.each(respuesta.Alumnos, function(i, item) {
                                    Tabla += " <tr data-id='" + item.id +
                                        "' id='Alumno" + item.id + "'>";
                                    Tabla += "<td class='text-truncate'>" + j +
                                        "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item.ident_alumno + "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item.nombre_alumno + "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item.apellido_alumno + "</td> ";
                                    Tabla +=
                                        "<input type='hidden' name='idestu[]' value='" + item.id + "'>"+
                                        "<input type='hidden' name='usuEstu[]' value='" + item.usuario_alumno + "'>"+
                                        "<input type='hidden' id='EstSel" +
                                        j +
                                        "' name='EstSel[]' value='no'><td class='text-truncate text-center'><input type='checkbox' onclick='$.SelAlumno(" +
                                        j + ");' id='Seleccion" +
                                        j +
                                        "' style='cursor: pointer;' name='AlumnoSel' value=''></td> ";
                                    Tabla += " </tr>";
                                    j++;
                                });
                                $("#td-alumnos").html(Tabla);
                                $("#btn-acciones").show();
                            } else {
                                $("#td-alumnos").html('');
                                $("#btn-acciones").hide();
                                swal.fire({
                                    title: "Administrar Estudiantes",
                                    text: 'No existen Estudiantes registrados con estos parametros',
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }

                        },
                        error: function() {
                            mensaje = "No se pudo realizar la Consulta";
                            swal.fire({
                                title: "Administrar Estudiantes",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    });
                },
                SelAllEst: function() {
                    var j = 1;
                    if ($('#SelAll').prop('checked')) {
                        $("input[name='AlumnoSel']").each(function(indice, elemento) {
                            $(elemento).prop("checked", true);
                            $("#EstSel" + j).val("si");
                            j++;
                        });
                    } else {
                        $("input[name='AlumnoSel']").each(function(indice, elemento) {
                            $(elemento).prop("checked", false);
                            $("#EstSel" + j).val("no");
                            j++;
                        });
                    }
                },
                SelAlumno: function(id) {
                    if ($('#Seleccion' + id).prop('checked')) {
                        $("#EstSel" + id).val("si");
                    } else {
                        $("#EstSel" + id).val("no");

                    }
                },
                Desvincular: function(DesEst) {
                  
                    var flag = "no";
                    $("input[name='AlumnoSel']").each(function(indice, elemento) {
                        if ($(elemento).prop('checked')) {
                            flag = "si";
                            return;
                        }
                    });

                    if (flag === "si") {
                        var form = $("#FormEstudiantes");
                        var token = $("#token").val();
                        $("#Opc").remove();
                        $("#_token").remove();
                        $("#DesEst").remove();
                        form.append("<input type='hidden' name='Opc' id='Opc' value='Desvincular'>");
                        form.append("<input type='hidden' name='DesEst' id='DesEst' value='" + DesEst + "'>");
                        form.append("<input type='hidden' name='_token'  id='_token' value='" + token + "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";

                        mensaje = "¿Desea Desvincular los Estudiantes Seleccionados?";

                        Swal.fire({
                            title: 'Administrar Estudiantes',
                            text: mensaje,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Desvincular'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: datos,
                                    dataType: "json",
                                    success: function(respuesta) {
                                        if (respuesta.Mensaje === "OK") {
                                            swal.fire({
                                                title: "Administrar Estudiantes",
                                                text: 'La Operación fue Realizada Exitosamente.',
                                                icon: "success",
                                                button: "Aceptar"
                                            });
                                            $.BuscarAlumnos();
                                            $("#SelAll").prop("checked", false);
                                        }
                                    },
                                    error: function() {

                                        mensaje =
                                            "No se pudo Desvincular los Alumnos";

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
                    } else {
                        swal.fire({
                            title: "Adminitrar Estudiantes",
                            text: "Debe Seleccionar los Estudiantes a Promover",
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }



                },
                Promover: function() {
                    var flag = "no";
                    $("input[name='AlumnoSel']").each(function(indice, elemento) {
                        if ($(elemento).prop('checked')) {
                            flag = "si";
                            return;
                        }
                    });

                    if (flag === "si") {
                        $("#ModPromover").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $("#ResulProv").html('');
                        $('#grado_promov').val('').trigger('change.select2');
                        $('#grupo_promov').val('').trigger('change.select2');
                        $('#jornada_promov').val('').trigger('change.select2');



                    } else {
                        swal.fire({
                            title: "Adminitrar Estudiantes",
                            text: "Debe Seleccionar los Estudiantes a Promover",
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }

                },
                CambiarGradoAlumnos: function() {

                    var form = $("#FormEstudiantes");
                    var token = $("#token").val();

                    var Grado = $("#grado_promov").val();
                    var Grupo = $("#grupo_promov").val();
                    var Jorna = $("#jornada_promov").val();

                    $("#Opc").remove();
                    $("#GradProv").remove();
                    $("#GrupProv").remove();
                    $("#JornProv").remove();

                    $("#_token").remove();
                    form.append("<input type='hidden' name='GradProv' id='GradProv' value='" + Grado +
                        "'>");
                    form.append("<input type='hidden' name='GrupProv' id='GrupProv' value='" + Grupo +
                        "'>");
                    form.append("<input type='hidden' name='JornProv' id='JornProv' value='" + Jorna +
                        "'>");
                    form.append("<input type='hidden' name='Opc' id='Opc' value='Promover'>");
                    form.append("<input type='hidden' name='_token'  id='_token' value='" + token +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var mensaje = "";

                    mensaje = "¿Desea Promover a los Estudiantes Seleccionados?";

                    Swal.fire({
                        title: 'Administrar Estudiantes',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Promover'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "post",
                                url: url,
                                data: datos,
                                dataType: "json",
                                success: function(respuesta) {
                                    var Tabla = '';
                                    var j = 1;
                                    if (respuesta.Mensaje === "Exiten") {
                                        Tabla += '<table id="recent-orders"' +
                                            ' class="table table-hover mb-0 ps-container ps-theme-default table-sm">' +
                                            '  <thead>' +
                                            '    <tr>' +
                                            '         <th>#</th>' +
                                            '         <th>Identificación</th>' +
                                            '           <th>Nombre</th>' +
                                            '           <th>Apellido</th>' +
                                            '        </tr>' +
                                            '        </thead>' +
                                            '       <tbody style="text-transform: capitalize;">';
                                        $.each(respuesta.Alumnos, function(i,
                                            item) {
                                            Tabla += "<tr data-id='" + item
                                                .id +
                                                "' id='Alumno" + item.id +
                                                "'>";
                                            Tabla +=
                                                "<td class='text-truncate'>" +
                                                j +
                                                "</td> ";
                                            Tabla +=
                                                "<td class='text-truncate'>" +
                                                item
                                                .ident_alumno + "</td> ";
                                            Tabla +=
                                                "<td class='text-truncate'>" +
                                                item
                                                .nombre_alumno + "</td> ";
                                            Tabla +=
                                                "<td class='text-truncate'>" +
                                                item
                                                .apellido_alumno + "</td> ";
                                            Tabla += " </tr>";
                                            j++;
                                        });
                                        Tabla += '</tbody>' +
                                            '  </table>';

                                        Tabla +=
                                            '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2 pt-2" role="alert">' +
                                            '  <button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                            '  <span aria-hidden="true">×</span>' +
                                            ' </button>' +
                                            ' <strong>Alerta!</strong> Estos Estudiantes estan registrados en el grado que Desea promover los Estudiantes Seleccionados!' +
                                            '    </div>';

                                        Tabla +=
                                            '<div class="modal-footer" style="width: 100%;">' +
                                            '<label>Desea Promover los Estudiantes de todas forma? </label>' +
                                            ' <button type="button" onclick="$.AplicarCambio();" class="btn grey btn-outline-secondary" data-dismiss="modal">Aceptar</button>' +
                                            ' <button type="button" onclick="$.CalcelarPromover();" class="btn btn-outline-primary">Cancelar</button>' +
                                            ' </div>';
                                        $("#ResulProv").html(Tabla);

                                    } else {
                                        swal.fire({
                                            title: "Administrar Estudiantes",
                                            text: 'La Operación fue Realizada Exitosamente.',
                                            icon: "success",
                                            button: "Aceptar"
                                        });
                                        $('#ModPromover').modal('toggle');
                                        $("#td-alumnos").html("");
                                        $("#btn-acciones").hiden();
                                        $.BuscarAlumnos();

                                    }

                                },
                                error: function() {

                                    mensaje = "No se pudo Promover los Alumnos";

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
                },
                AplicarCambio: function() {


                    var form = $("#FormEstudiantes");
                    var token = $("#token").val();

                    var Grado = $("#grado_promov").val();
                    var Grupo = $("#grupo_promov").val();
                    var Jorna = $("#jornada_promov").val();

                    $("#Opc").remove();
                    $("#GradProv").remove();
                    $("#GrupProv").remove();
                    $("#JornProv").remove();
                    $("#_token").remove();

                    form.append("<input type='hidden' name='GradProv' id='GradProv' value='" + Grado +
                        "'>");
                    form.append("<input type='hidden' name='GrupProv' id='GrupProv' value='" + Grupo +
                        "'>");
                    form.append("<input type='hidden' name='JornProv' id='JornProv' value='" + Jorna +
                        "'>");
                    form.append("<input type='hidden' name='Opc' id='Opc' value='ApliPromover'>");
                    form.append("<input type='hidden' name='_token'  id='_token' value='" + token +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var mensaje = "";

                    mensaje = "¿Desea Promover a los Estudiantes de todas formas?";
                    Swal.fire({
                        title: 'Administrar Estudiantes',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Promover'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "post",
                                url: url,
                                data: datos,
                                dataType: "json",
                                success: function(respuesta) {
                                    if (respuesta.Mensaje === "OK") {
                                        swal.fire({
                                            title: "ADMINISTRAR ALUMNOS",
                                            text: 'La Operación fue Realizada Exitosamente.',
                                            icon: "success",
                                            button: "Aceptar"
                                        });
                                        $.BuscarAlumnos();
                                    }


                                },
                                error: function() {

                                    mensaje = "No se pudo Promover a los Alumnos";

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
                },
                ImportarAlumnos: function() {
                    $("#ModImportar").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                ImporNew: function() {
                    $("#Td-AlumnosImpo").html('');
                    $('#grado_import').val('').trigger('change.select2');
                    $('#grupo_import').val('').trigger('change.select2');
                    $('#jornada_import').val('').trigger('change.select2');
                    $("#FormartoAlumnos").val("");
                },
                ImportarFormato: function() {
                    if ($("#FormartoAlumnos").val() === "") {
                        mensaje = "No se ha Seleccionado Ningun Archivo";
                        swal.fire({
                            title: "",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#grado_import").val() === "") {
                        mensaje = "No se ha Seleccionado Ningun Grado";
                        swal.fire({
                            title: "",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#grupo_import").val() === "") {
                        mensaje = "No se ha Seleccionado Ningun Grupo";
                        swal.fire({
                            title: "Administrar Estudiantes",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#jornada_import").val() === "") {
                        mensaje = "No se ha Seleccionado Ninguna Jornada";
                        swal.fire({
                            title: "Administrar Estudiantes",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $('#btn_importar').prop('disabled', true);
                    $('#btn_salir').prop('disabled', true);
                    $('#btn_newimp').prop('disabled', true);

                    $("#btn_importar").html('<i class="fa fa-refresh spinner"> </i>  Importando...');

                    var form = $("#FormImporEstudiantes");
                    var url = form.attr("action");
                    var token = $("#token").val();

                    form.append("<input type='hidden' id='idtoken' name='_token'  value='" + token +
                        "'>");

                    var Tabla = "";
                    var j = 1;

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData($('#FormImporEstudiantes')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {

                            if(respuesta.Mensaje=="validado"){
                                let validados='';

                                respuesta.validado.forEach(function(elemento) {
                                    validados+='<div class="alert alert-danger mb-1" role="alert">'
                                        +'<strong></strong>'+elemento
                                        +'</div>';
                                });

                                swal.fire({
                                    title: "Administrar Estudiantes",
                                    html: validados,
                                    icon: "warning",
                                    button: "Aceptar"
                                });
                                $('#btn_importar').prop('disabled', false);
                                $("#btn_importar").html(
                                    '<i class="fa fa-upload"> </i>  Importar');

                            }else{
                                $.each(respuesta.Alumnos, function(i, item) {
                                    Tabla += " <tr data-id='" + item.id +
                                        "' id='Alumno" + item.id + "'>";
                                    Tabla += "<td class='text-truncate'>" + j +
                                        "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .ident_alumno + "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .nombre_alumno + "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item
                                        .apellido_alumno + "</td> ";
                                    Tabla += " </tr>";
                                    j++;
                                });
    
                                $("#Td-AlumnosImpo").html(Tabla);
                                $('#btn_importar').prop('disabled', false);
                                $('#btn_salir').prop('disabled', false);
                                $('#btn_newimp').prop('disabled', false);
                                $("#btn_importar").html(
                                    '<i class="fa fa-upload"> </i>  Importar');
    
                                swal.fire({
                                    title: "Administrar Estudiantes",
                                    text: 'La Operación fue Realizada Exitosamente.',
                                    icon: "success",
                                    button: "Aceptar"
                                });
                            }
                         

                        },
                        error: function() {
                            mensaje = "Los Estudiantes no pueden ser Mostrados";
                            swal.fire({
                                title: "Administrar Estudiantes",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    });
                },
                DescargarFormato: function(id) {
                    window.open($('#' + id).data("ruta") + "/" + $('#' + id).data("archivo"), '_blank');
                }
            });
        });
    </script>
@endsection
