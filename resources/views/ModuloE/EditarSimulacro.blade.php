@extends('Plantilla.Principal')
@section('title', 'Editar Simulacro')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Simulacro
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
                            <h4 class="card-title">Editar Simulacro.</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-dismissible show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">×</button>
                                                <h6 style="font: 16px EXODO;">Por favor corrige los siguientes errores:</h6>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <strong style="font: 15px EXODO;">
                                                            <li>{{ $error }}</li>
                                                        </strong>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <p class="px-1"></p>

                                <!--begin::Form-->
                                @include('ModuloE.FormSimulacro', [
                                    'url' => '/ModuloE/ModificarSimu/' . $Simu->id,
                                    'method' => 'post',
                                ])
                                <!--end::Form-->
                                <p class="px-1"></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/ModuloE/CargAreasxSesiones', 'id' => 'formAuxiliarAresxSesiones']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/CargAreas', 'id' => 'formAuxiliarAreas']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/Cargcompetencias', 'id' => 'formAuxiliarCompe']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/CargInfAreaSesion', 'id' => 'formAuxiliarAreaSesion']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/EliminarAreaSesion', 'id' => 'formAuxiliarDelAreaSesion']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/ConsultarSesionesSimulacros', 'id' => 'formAuxiliarInfSimulacro']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/EliminarSesion', 'id' => 'formAuxiliarDelSesion']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/EliminarPregSesionArea', 'id' => 'formAuxiliarDelPregArea']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/ModificarDetaSesion', 'id' => 'formAuxiliarUpdateSesion']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/CargaPregCompexCompo', 'id' => 'formAuxiliarCompxComp']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/CargarPreguntas', 'id' => 'formAuxiliarEvalDet']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/ModuloE/PreguntasxBanco', 'id' => 'formAuxiliarPreguntasxBanco']) !!}
    {!! Form::close() !!}






@endsection
@section('scripts')
    <script>
        ///////////////////CONFIGURACION EDITOR


        $(document).ready(function() {
            $("#Men_Inicio").removeClass("active");
            $("#Men_Modulos_E").addClass("has-sub open");
            $("#Men_ModulosE_addSimulacro").addClass("active");

            $('#fecha').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });

            let conse=1;


            $.extend({
                GuardarSimulacro: function() {
                    $('#Btn_Crear').prop('disabled', true);
                    $("#Btn_Crear").html('<i class="fa fa-refresh spinner"> </i>  Guardando...');
                    var rurl = $("#Ruta").val();

                    $.ajax({
                        type: "POST",
                        url: rurl + "ModuloE/GuardarSimulacro",
                        data: new FormData($('#formSimu')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Simu").val(respuesta.Simu);
                                swal.fire({
                                    title: "Gestión de Simulacros",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });


                                $('#div-addSesion').show();
                                $('#Div_Crear').hide();
                                $('#Div_Update').show();

                            } else {
                                mensaje = "El Simulacro no pudo ser Guardado";
                                swal.fire({
                                    title: "Gestión de Simulacros",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }
                        },
                        error: function(error_messages) {
                            alert('HA OCURRIDO UN ERROR');
                        }
                    });



                },
                CargCompetenciasxComponentes: function(Area) {
                    var form = $("#formAuxiliarCompe");
                    var grado = $("#prueba").val();
                    $("#idArea").remove();
                    $("#GradoP").remove();

                    form.append("<input type='hidden' name='idArea' id='idArea' value='" + Area + "'>");
                    form.append("<input type='hidden' name='GradoP' id='GradoP' value='" + grado +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Competencia").html(respuesta.select_compe);
                            $("#Componente").html(respuesta.select_compo);
                            $.ValCampArea();
                        }

                    });
                },
                AtrasAreaSesion: function() {
                    $("#Div_Sesiones").show();
                    $("#div-addSesion").show();
                    $("#btn-volverInicio").show();
                    $("#AreasAgregadas").hide();
                },
                EditAreaSesion: function(id) {
                    var form = $("#formAuxiliarAreaSesion");
                    var grado = $("#prueba").val();
                    $("#IdSesion").val(id);
                    $("#OpcSesion").val("E");

                    $("#idAreSes").remove();
                    $("#AreasAgregadas").hide();
                    $("#DetaAreas").show();
                    $.CargAreas();

                    $("#PreguntasxAreas").html("");
                    $("#tr_competencias").html("");

                    form.append("<input type='hidden' name='idAreSes' id='idAreSes' value='" + id +
                        "'>");

                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            $('#area').val(respuesta.SesAre.area).trigger(
                                'change.select2');
                            $("#n_preguntas").val(respuesta.SesAre.npreguntas);
                            $.CargCompetenciasxComponentes(respuesta.SesAre.area);

                            /////Cargar % Preguntas por Competencia

                            var ConsComp = 1;
                            var style = 'text-transform: uppercase;background-color:white;';
                            var clase = 'text-truncate';

                            $.each(respuesta.CompAre, function(i, item) {

                                var campo = "";
                                campo += "<tr id='tr_" + ConsComp + "'>";
                                campo += "<td id='td_compe" + ConsComp +
                                    "' class='" + clase + "'>";
                                campo += item.nomcomp;
                                campo += "</td>";
                                campo += "<td id='td_compo" + ConsComp +
                                    "' class='" + clase + "'>";
                                campo += item.nomcompo;
                                campo += "</td>";
                                campo += "<td id='td_porc" + ConsComp +
                                    "' class='" + clase + "'>";
                                campo += item.porcpreg;
                                campo += "</td>";

                                campo += "<td class='" + clase + "'>";
                                campo += "<a onclick='$.EditPorc(" + ConsComp +
                                    ")' class='btn btn-info btn-sm btnEditar text-white'  title='Editar'><i class='fa fa-edit font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                                campo += "<a onclick='$.DelPorc(" + ConsComp +
                                    ")' class='btn btn-danger btn-sm btnQuitar text-white'  title='Eliminar'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                                campo += "<input type='hidden' id='txtComp" +
                                    ConsComp +
                                    "' name='txtcomp[]' style='" + style +
                                    "'  readonly value='" + item.competencia + "-" +
                                    item.componente + "'>";

                                campo += "<input type='hidden' id='txtporc" +
                                    ConsComp +
                                    "' name='txtporc[]'  style='" + style +
                                    "'  value='" + item.porcpreg + "'>";
                                campo += "</td>";
                                campo += "</tr>";
                                ConsComp++;

                                $("#tr_competencias").append(campo);
                                $("#ConsComp").val(ConsComp);

                            });

                            /////Cargar Preguntas x areas
                            $("#GenPreg").show();
                            $('#TablaPreg').show();
                            $("#PreguntasxAreas").html(respuesta.PregArea);

                            $("#EditCofCompeEdit").show();

                        }

                    });
                },
                AtrasAreas: function() {
                    $("#DetaAreas").hide();
                    $("#AreasAgregadas").show();
                },
                ParSesion: function(id) {
                    var IdSimu = $("#Id_Simu").val();
                    var IdSesi = $("#" + id).data('value');

                    $("#IdSesionGen").val(IdSesi);
                    $("#detalleSesion").show();
                    $("#btn-volverInicio").hide();

                    var form = $("#formAuxiliarAresxSesiones");
                    var url = form.attr("action");
                    $("#idSesion").remove();
                    form.append("<input type='hidden' name='idSesion' id='idSesion' value='" + IdSesi +
                        "'>");

                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#tr_areas").html(respuesta.trAreas);
                            $("#DescSesion2").val(respuesta.DetaSesion.sesion);
                            $("#TSesion2").val(respuesta.DetaSesion.tiempo_sesion);
                        }
                    });


                    $("#Tit_ProceSimu").html('<i class="ft-settings"></i> Parametrización Sesión');
                    $("#Div_Sesiones").hide();
                    $("#div-addSesion").hide();
                    $("#AreasAgregadas").show();
                },
                GuardarSesion: function() {


                    var idSesion = $("#IdSesionGen").val();
                    var desc = $("#DescSesion2").val();
                    var tiempo = $("#TSesion2").val();


                    var form = $("#formAuxiliarUpdateSesion");
                    var url = form.attr("action");
                    $("#idSesion").remove();
                    $("#descSesion").remove();
                    $("#tiempoSesion").remove();
                    form.append("<input type='hidden' name='idSesion' id='idSesion' value='" +
                        idSesion + "'>");
                    form.append("<input type='hidden' name='descSesion' id='descSesion' value='" +
                        desc + "'>");
                    form.append("<input type='hidden' name='tiempoSesion' id='tiempoSesion' value='" +
                        tiempo + "'>");

                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            swal.fire({
                                title: "Gestión de Simulacros",
                                text: "Operación Realizada Exitosamente",
                                icon: "success",
                                button: "Aceptar",
                            });
                        }
                    });


                },

                GuarDatosSesion: function() {
                    var rurl = $("#Ruta").val();
                    var sesion = '';

                    $.ajax({
                        type: "POST",
                        url: rurl + "ModuloE/GuardarDetaSesion",
                        data: new FormData($('#formSimu')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {

                                Swal.fire({
                                    title: "Gestión de Simulacros",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#ModnewSesion").modal("hide");

                                ///////////////////////////////////////////////////////
                                var conse = $("#ConsSesiones").val();


                                sesion = '<div class="col-md-12 col-sm-12"  id="sesion' +
                                    conse + '">' +
                                    '<input type="hidden" id="codSesion" name="codSesion" value="" />' +
                                    '<div class="card border-top-info box-shadow-0 border-bottom-info">' +
                                    '    <div class="card-header">' +
                                    '        <h4 class="card-title">' + respuesta.DetaSesion
                                    .sesion + '</h4>' +
                                    '  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>' +
                                    ' <div class="heading-elements">' +
                                    '   <ul class="list-inline mb-0">' +
                                    '    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>' +
                                    '    </ul>' +
                                    '     </div>' +
                                    '   </div>' +
                                    '       <div class="card-content collapse show">' +
                                    '            <div class="card-body">' +
                                    '              <div class="row" id="row_areas' +
                                    conse + '">' +
                                    '              </div>' +
                                    '               <div class="card-footer">' +
                                    '                    <div class="chart-title mb-1 text-center">' +
                                    '                        <h6>Detalles de Sesión.</h6>' +
                                    '                    </div>' +
                                    '                    <div class="chart-stats text-center">' +
                                    '                          <a href="#" class="btn btn-sm btn-primary mr-1"><i id="Tiempo_sesion_' +
                                    conse + '" class="ft-clock"> ' + respuesta
                                    .DetaSesion.tiempo_sesion + '</i></a>' +
                                    '                          <a href="#" class="btn btn-sm btn-primary mr-1"><i id="NPreguntas_' +
                                    conse +
                                    '" class="ft-hash"> Sin Asignar</i></a>' +
                                    '                       </div>' +
                                    '                    </div>' +
                                    '                   <div class="form-actions right">' +
                                    ' <button type="button" onclick="$.DelSesion(this.id);" id="DelSesion_' +
                                    conse + '" data-value="' + respuesta.DetaSesion
                                    .id + '" class="btn btn-outline-primary">' +
                                    '                               <i class="ft-trash"></i> Eliminar' +
                                    '                             </button>' +
                                    '                       <button type="button" onclick="$.ParSesion(this.id);" id="ParSesion_' +
                                    conse + '" data-value="' + respuesta.DetaSesion
                                    .id + '" class="btn btn-outline-grey-blue">' +
                                    '                               <i class="ft-settings"></i> Parametrizar' +
                                    '                             </button>' +

                                    '                         </div>' +
                                    '                  </div>' +
                                    '                    </div>' +
                                    '               </div>' +
                                    '            </div>';

                                conse++;

                                $("#ConsSesiones").val(conse);

                                $("#ParSesiones").show();
                                $("#Div_Sesiones").append(sesion);
                                $("#IdSesion").val("");

                                ////////////////////////////////////////////////
                            } else {
                                mensaje = "La Sesión no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestión de Simulacros",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }
                        },
                        error: function(error_messages) {
                            alert('HA OCURRIDO UN ERROR');
                        }
                    });
                },

                CargAreas: function() {
                    var form = $("#formAuxiliarAreas");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {

                            $("#area").html(respuesta.select_Area);
                        }

                    });
                },
                CargarInfoSim: function() {

                    $('#Div_Crear').hide();
                    $('#Div_Update').show();
                    $("#div-addSesion").show();

                    var form = $("#formAuxiliarInfSimulacro");
                    $("#IdSimu").remove();
                    form.append("<input type='hidden' name='IdSimu' id='IdSimu' value='" + $("#Id_Simu")
                        .val() + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var sesion = '';
                    var conse = $("#ConsSesiones").val();
                    var ruta = $("#rutaimagen").val();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {

                            $.each(respuesta.DetaSesion, function(i, item) {

                                sesion +=
                                    '<div class="col-md-12 col-sm-12"  id="sesion' +
                                    conse + '">' +
                                    '<input type="hidden" id="codSesion" name="codSesion" value="" />' +
                                    '<div class="card border-top-info box-shadow-0 border-bottom-info">' +
                                    '    <div class="card-header">' +
                                    '        <h4 class="card-title">' + item
                                    .sesion +
                                    '</h4>' +
                                    '  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>' +
                                    ' <div class="heading-elements">' +
                                    '   <ul class="list-inline mb-0">' +
                                    '    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>' +
                                    '    </ul>' +
                                    '     </div>' +
                                    '   </div>' +
                                    '       <div class="card-content collapse show">' +
                                    '            <div class="card-body">' +
                                    '              <div class="row pb-1" id="row_areas' +
                                    conse + '">';

                                $.each(item.DetAreasxSEsiones, function(i2, item2) {

                                    sesion +=
                                        '  <div class="col-xl-6 col-md-12 border-left-blue-grey  overflow-hidden hvr-grow-shadow">' +
                                        ' <div class="card">' +
                                        '    <div class="card-content">' +
                                        '       <div class="media align-items-stretch">' +
                                        '        <div>' +
                                        '       <img height="100" src="' +
                                        ruta + '/icon_areas_me/' + item2
                                        .icon + '" alt="avatar-area"/>' +
                                        '</div>' +
                                        ' <div class="media-body p-2">' +
                                        '     <h4>' + item2.nombre_area +
                                        ' </h4>' +
                                        ' <span class="primary">Número de Preguntas: ' +
                                        item2.npreguntas + '</span>' +
                                        '   </div>' +
                                        ' <div class="media-right p-2 media-middle">' +
                                        '    <h1 class="primary"></h1>' +
                                        '    </div>' +
                                        ' </div>' +
                                        ' </div>' +
                                        ' </div>' +
                                        '  </div>';
                                });

                                sesion += '              </div>' +
                                    '               <div class="card-footer">' +
                                    '                    <div class="chart-title mb-1 text-center">' +
                                    '                        <h6>Detalles de Sesión.</h6>' +
                                    '                    </div>' +
                                    '                    <div class="chart-stats text-center">' +
                                    '                          <a href="#" class="btn btn-sm btn-primary mr-1"><i id="Tiempo_sesion_' +
                                    conse + '" class="ft-clock"> ' + item
                                    .tiempo_sesion + '</i></a>' +
                                    '                          <a href="#" class="btn btn-sm btn-primary mr-1"><i id="NPreguntas_' +
                                    conse +
                                    '" >No. Preguntas: ' + item.num_preguntas +
                                    '</i></a>' +
                                    '                       </div>' +
                                    '                    </div>' +
                                    '                   <div class="form-actions right">' +
                                    ' <button type="button" onclick="$.DelSesion(this.id);" id="DelSesion_' +
                                    conse + '" data-value="' + item.id +
                                    '" class="btn btn-outline-primary">' +
                                    '                               <i class="ft-trash"></i> Eliminar' +
                                    '                             </button>' +
                                    '                       <button type="button" onclick="$.ParSesion(this.id);" id="ParSesion_' +
                                    conse + '" data-value="' + item.id +
                                    '" class="btn btn-outline-grey-blue">' +
                                    '                               <i class="ft-settings"></i> Parametrizar' +
                                    '                             </button>' +

                                    '                         </div>' +
                                    '                  </div>' +
                                    '                    </div>' +
                                    '               </div>' +
                                    '            </div>';
                                conse++;
                            });
                            $("#ConsSesiones").val(conse);

                            $("#Div_Sesiones").html(sesion);

                        }

                    });


                },
                AddSesion: function() {

                    $("#DescSesion").val("");
                    $("#TSesion").val("");

                    $("#ModnewSesion").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                },
                AddArea: function() {
                    $("#AreasAgregadas").hide();
                    $("#DetaAreas").show();
                    $("#GuarCofCompe").hide();
                    $("#EditCofCompeEdit").hide();
                    $("#n_preguntas").val("");
                    $("#PorcPreguntas").val("");
                    $("#tr_competencias").html("");
                    $("#GenPreg").hide();
                    $('#TablaPreg').hide();
                    $('#PreguntasxAreas').html("");
                    $("#gtotal").html("0");

                    $('#Competencia').val('').trigger('change.select2');
                    $('#Componente').val('').trigger('change.select2');
                    $('#Competencia').prop('disabled', true);
                    $('#Componente').prop('disabled', true);
                    $("#OpcSesion").val("G");


                    $.CargAreas();
                },

                AddComp: function() {

                    var comptext = $("select[name='Competencia'] option:selected").text();
                    var compid = $("#Competencia").val();
                    var compotext = $("select[name='Componente'] option:selected").text();
                    var compoid = $("#Componente").val();
                    var npreg = $("#n_preguntas").val()

                    var porc = $("#PorcPreguntas").val();
                    var style = 'text-transform: uppercase;background-color:white;';

                    var clase = 'text-truncate';
                    var ConsComp = $("#ConsComp").val();

                    if (compid === "") {
                        mensaje = "Seleccione la Competencia";
                        Swal.fire({
                            title: "Gestión de Simulacros",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (compoid === "") {
                        mensaje = "Seleccione El Componente";
                        Swal.fire({
                            title: "Gestión de Simulacros",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (porc === "") {
                        mensaje = "Ingrese la cantidad de Preguntas para esta Competencia";
                        Swal.fire({
                            title: "Gestión de Simulacros",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    //////////////////VALIDAR CANTIDAD
                    var ToporcV = 0;
                    var Toporc = 0;

                    $("input[name='txtporc[]']").each(function(indice, elemento) {
                        ToporcV = ToporcV + parseInt($(elemento).val());
                    });


                    var ToporcTV = ToporcV + parseInt(porc);

                    if (ToporcTV > npreg) {
                        mensaje = "La cantidad Total de preguntas no debe ser Mayor a " + npreg;
                        Swal.fire({
                            title: "Gestión de Simulacros",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var comxComp = compid + "-" + compoid;
                    var flag = "n";

                    $("input[name='txtcomp[]']").each(function(indice, elemento) {
                        if (comxComp === $(elemento).val()) {
                            flag = "s";
                        }
                    });

                    if (flag === "s") {
                        mensaje =
                            "La competencia y el Componente Seleccionado ya se encuentran Agregados...";
                        Swal.fire({
                            title: "Gestión de Simulacros",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    //////////////////

                    var campo = "";
                    campo += "<tr id='tr_" + ConsComp + "'>";
                    campo += "<td class='" + clase + "'>";
                    campo += comptext;
                    campo += "</td>";
                    campo += "<td class='" + clase + "'>";
                    campo += compotext;
                    campo += "</td>";
                    campo += "<td id='td_porc" + ConsComp + "' class='" + clase + "'>";
                    campo += porc;
                    campo += "</td>";

                    campo += "<td class='" + clase + "'>";
                    campo += "<a onclick='$.EditPorc(" + ConsComp +
                        ")' class='btn btn-info btn-sm btnEditar text-white'  title='Editar'><i class='fa fa-edit font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "<a onclick='$.DelPorc(" + ConsComp +
                        ")' class='btn btn-danger btn-sm btnQuitar text-white'  title='Eliminar'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";

                    campo += "<input type='hidden' data-cantidad='" + porc + "' data-nombre='" +
                        comptext + "-" + compotext + "' id='txtComp" + ConsComp +
                        "' name='txtcomp[]' style='" + style + "'  readonly value='" + compid + "-" +
                        compoid + "'>";

                    campo += "<input type='hidden' id='txtporc" + ConsComp +
                        "' name='txtporc[]'  style='" + style + "'  value='" + porc + "'>";
                    campo += "</td>";
                    campo += "</tr>";

                    $("#tr_competencias").append(campo);

                    $("input[name='txtporc[]']").each(function(indice, elemento) {
                        Toporc = Toporc + parseInt($(elemento).val());
                    });

                    $("#Tot_Porc").val(Toporc);

                    $("#gtotal").html("");
                    $("#gtotal").html(Toporc);
                    if (Toporc == npreg) {
                        $("#GenPreg").show();
                    }

                    ConsComp++;
                    $("#ConsComp").val(ConsComp);

                    $('#Competencia').val('').trigger('change.select2');
                    $('#Componente').val('').trigger('change.select2');
                    $("#PorcPreguntas").val("");


                },
                CargCompetencias: function(Asig) {
                    var form = $("#formAuxiliarCompe");
                    $("#idAsig").remove();
                    form.append("<input type='hidden' name='id' id='idAsig' value='" + Asig + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Competencia").html(respuesta.select_compe);
                            $.ValCampArea();
                        }

                    });
                },
                DelAreaSesion: function(Sesi) {

                    mensaje = "¿Desea Elimninar esta Área de la Sesión?";

                    Swal.fire({
                        title: 'Gestionar Simulacros',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $("#formAuxiliarDelAreaSesion");
                            var IdSesionGen = $("#IdSesionGen").val();
                            $("#IdSes").remove();
                            $("#IdSesGen").remove();

                            form.append("<input type='hidden' name='IdSes' id='IdSes' value='" +
                                Sesi +
                                "'>");
                            form.append(
                                "<input type='hidden' name='IdSesGen' id='IdSesGen' value='" +
                                IdSesionGen +
                                "'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                async: false,
                                dataType: "json",
                                success: function(respuesta) {

                                    if (respuesta.Resp == 'success') {
                                        $("#tr_Area" + Sesi).remove();
                                        Swal.fire({
                                            title: "Gestión de Simulacros",
                                            text: "Operación Realizada Exitosamente",
                                            icon: "success",
                                            button: "Aceptar",
                                        });
                                        $.CargarInfoSim();
                                    } else {
                                        Swal.fire({
                                            title: "Gestión de Simulacros",
                                            text: "No se pudo Eliminar",
                                            icon: "warning",
                                            button: "Aceptar",
                                        });
                                    }

                                }

                            });
                        }
                    });


                },
                DelSesion: function(id) {

                    mensaje = "¿Desea Elimninar esta Sesión?";
                    var IdSesi = $("#" + id).data('value');

                    Swal.fire({
                        title: 'Gestionar Simulacros',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $("#formAuxiliarDelSesion");
                            $("#IdSes").remove();

                            form.append("<input type='hidden' name='IdSes' id='IdSes' value='" +
                                IdSesi +
                                "'>");

                            var url = form.attr("action");
                            var datos = form.serialize();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                async: false,
                                dataType: "json",
                                success: function(respuesta) {

                                    if (respuesta.Resp == 'success') {
                                        $("#tr_Area" + IdSesi).remove();
                                        Swal.fire({
                                            title: "Gestión de Simulacros",
                                            text: "Operación Realizada Exitosamente",
                                            icon: "success",
                                            button: "Aceptar",
                                        });
                                        $.CargarInfoSim();
                                    } else {
                                        Swal.fire({
                                            title: "Gestión de Simulacros",
                                            text: "No se pudo Eliminar",
                                            icon: "warning",
                                            button: "Aceptar",
                                        });
                                    }

                                }

                            });
                        }
                    });


                },
                ValCampArea: function() {
                    if ($("#area").val() !== "" && $("#n_preguntas").val()) {
                        $('#Competencia').prop('disabled', false);
                        $('#Componente').prop('disabled', false);
                        $('#PorcPreguntas').prop('disabled', false);
                    } else {
                        $('#Competencia').prop('disabled', true);
                        $('#Componente').prop('disabled', true);
                        $('#PorcPreguntas').prop('disabled', true);
                    }
                },

                selCompxComp: function(val) {
                    var pacomp = val.split("/");
                    $("#nPregCompoxCompe").val(pacomp[1]);

                    var form = $("#formAuxiliarCompxComp");
                    $("#compexcomp").remove();
                    form.append("<input type='hidden' name='compexcomp' id='compexcomp' value='" +
                        pacomp[0] + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    let preguntas = '';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {

                            $.each(respuesta.Preguntas, function(x, items) {
                                preguntas += '   <div class="col-md-12 pb-1">' +
                                    ' <div class="bs-callout-primary callout-border-left callout-bordered callout-transparent p-1">' +
                                    '     <div class="row">' +
                                    '         <div class="col-md-10">' +
                                    '        <h4 class="primary">' + items
                                    .tipo_pregunta +
                                    ' - <strong>No. Preguntas: </strong> ' + items
                                    .npreguntas + ' </h4>' +
                                    '     <p> ' + items.descripcion + '</p>' +
                                    ' </div>' +
                                    ' <div class="col-md-2 d-flex align-items-center">' +
                                    '     <div class="btn-group mx-2" role="group" aria-label="Second Group">' +
                                    '    <button onclick="$.MostrarPreguntas(' + items.id +')" type="button" class="btn btn-icon btn-outline-success"><i class="fa fa-search"></i></button>' +
                                    ' <button onclick="$.AgregarPregunta(' + items.id +')" type="button" class="btn btn-icon btn-outline-info"><i class="fa fa-plus"></i></button>' +
                                    '  </div>' +
                                    '   </div>' +
                                    ' </div>' +
                                    '  </div>' +
                                    ' </div>';

                            });


                            $("#listPreguntas").html(preguntas);

                        }

                    });

                },
                AgregarPregunta: function (idBanco) {

                    $('#TablaPreg').show();
                    $('#GuarCofCompe').show();
                    var form = $("#formAuxiliarPreguntasxBanco");
                    $("#idBancoPreg").remove();
                    form.append("<input type='hidden' name='idBancoPreg' id='idBancoPreg' value='" +
                    idBanco + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    let preguntas = '';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {

                          let npreg = parseInt($("#nPregCompoxCompeSel").val())+parseInt(respuesta.Banco.npreguntas);
                          if(npreg<=parseInt($("#nPregCompoxCompe").val())){

                            //AGREGAR PREGUNTAS A LA  TABLA DE PREGUNTAS

                            let PreEnunciado='';
                            let Preguntas='';
                            

                            if(respuesta.Banco.tipo_pregunta=="PARTE 1"){
                                PreEnunciado="CUAL PALABRA CONCUERDA CON LA DESCRIPCIÓN DE LA FRASE?";

                                Preguntas += '<div  class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-3">'
                                    + '<h4 class="primary">' + PreEnunciado + '!</h4>'
                                    + respuesta.Parte1.pregunta
                                    + '</div>';
                
                           
                            $.each(respuesta.Preguntas, function(x, items) {
                                    Preguntas += "<div class='row'>";
                                    Preguntas += "<div class='col-12'>";
                                    Preguntas += ' <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">'
                                        + '<h4 class="success">Pregunta ' + conse + '</h4><input type="hidden" name="Preguntas[]" value="' + items.id + '" />'
                                        + '<input type="hidden" name="PregBancoId[]" value="' + respuesta.Banco.id + '" />'
                                        + '<input type="hidden" name="PregTipPreg[]" value="' + respuesta.Banco.tipo_pregunta + '" />';
                                    Preguntas += "<div class='col-6'>";
                                    Preguntas += ' <label for="input-15"><b>Pregunta:</b> ' + items.pregunta + '</label></fieldset>';
                                    Preguntas += "</div>";
                                    Preguntas += "<div class='col-6'>";
                                    Preguntas += ' <label for="input-15"><b>Respuesta:</b> ' + items.respuesta + '</label></fieldset>';
                                    Preguntas += "</div>";
                                    Preguntas += "</div>";
                                    Preguntas += "</div>";
                                    Preguntas += "</div>";
                                    conse++;
                                });

                            }else{
                                PreEnunciado="RESPONDA LA SIGUIENTE PREGUNTA SEGUN EL SIGUIENTE ENUNCIADO";
                                Preguntas += '<div  class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-3">'
                                    + '<h4 class="primary">' + PreEnunciado + '!</h4>'
                                    + respuesta.Banco.enunciado
                                    + '</div>';

                                     $.each(respuesta.PregMult, function(y, itemsP) {
                
                                Preguntas += ' <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">'
                                    + '<h4 class="success">Pregunta ' + conse + '</h4><input type="hidden" name="Preguntas[]" value="' + itemsP.id + '" />'
                                    + '<input type="hidden" name="PregBancoId[]" value="' + respuesta.Banco.id + '" />'
                                    + '<input type="hidden" name="PregTipPreg[]" value="' + respuesta.Banco.tipo_pregunta + '" />'
                
                                    + itemsP.pregunta;
                    
                                Preguntas += '  <ul class="list-group icheck-task">';
                                    $.each(respuesta.OpciMult, function(x, items) {
                                    let disable = "disabled";
                                        if(itemsP.id==items.pregunta){
                                            if (items.$Preg == "si") {
                                                disable = "disabled checked";
                                            }
                        
                                            Preguntas += '<fieldset><input type="checkbox" ' + disable + ' id="input-15" > <label for="input-15">' + items.opciones + '</label></fieldset>';
                                        }
                                    

                                });
                
                                Preguntas += ' </ul></div>';
                                conse++;
                            });

                            }

                         $("#PreguntasxAreas").append(Preguntas);

                            ////////////////////////////////

                            $("#nPregCompoxCompeSel").val(npreg);
                          }else{
                            mensaje = "El Número de preguntas no debe ser mayor a "+ $("#nPregCompoxCompe").val();
                            Swal.fire({
                                title: "Gestión de Simulacros",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                          }

                        



                        }

                    });
                },
                MostrarPreguntas: function(idBanco) {
                    $("#verPreguntas").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    $("#div-evaluaciones").html("");


                    var form = $("#formAuxiliarEvalDet");
                    $("#idbanc").remove();
                    form.append("<input type='hidden' name='idbanc' id='idbanc' value='" + idBanco +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    let enunciado = "";
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            var cons = 1;
                            $("#textCompetencia").html(respuesta.DesCompe);
                            $("#textComponente").html(respuesta.DesCompo);
                            respuesta.Banco.enunciado != null ? enunciado = respuesta.Banco
                                .enunciado : enunciado = "N/A";
                            $("#desEnunciado").html(enunciado);
                            $("#titParte").html(respuesta.Partes.parte);
                            $("#desParte").html(respuesta.Partes.descripcion);


                            if (respuesta.Banco.tipo_pregunta === "PARTE 1") {
                                var Preguntas = "";

                                var conse = 1;
                                var consp = 1;

                                Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '         <div class="col-md-4">' +
                                    '           <div class="form-group row">' +
                                    '<input type="hidden" id="id-parte1' + cons +
                                    '"  value="' + respuesta.Partes.id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
                                    '"  value="PARTE 1" />' +
                                    '            <div class="col-md-12 right">' +
                                    '            </div>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </div>' +
                                    '  <div class="col-md-12"> ' +
                                    '     <div class="form-group">' +
                                    '        <label class="form-label"><b>Ingrese las Palabras:</b></label>' +
                                    '<div id="PregOpciones' + cons + '">' +
                                    '    <select class="form-control select2" multiple="multiple" onchange="$.AddPalabra()" style="width: 100%;" data-placeholder="Ingrese las Opciones"' +
                                    '  id="cb_Opciones" name="cb_Opciones[]">' +
                                    '</select>' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '  <div class="col-md-12"> ' +
                                    '     <div class="form-group">' +
                                    '        <label class="form-label"><b>Ingrese las Preguntas:</b></label>' +
                                    '<div id="DivPreg' + cons + '">' +
                                    '<div id="RowRelPreg' + consp + '">' +
                                    '                 <div class="row top-buffer" id="RowOpcRelPreg1" style="padding-bottom: 15px;">' +
                                    '                      <div class="col-lg-6 border-top-primary pt-1">' +
                                    ' <input type="hidden" class="form-control" name="Preg[]" value="1" />' +
                                    '        <label class="form-label"><b>Pregunta 1:</b></label>' +
                                    '    <input type="text" class="form-control" name="Pregunta[]" value="" />' +
                                    '     </div>' +
                                    '                      <div class="col-lg-4 border-top-primary pt-1">' +
                                    ' <input type="hidden" class="form-control" name="Palabra[]" value="1" />' +
                                    '        <label class="form-label"><b>Respuesta:</b></label>' +
                                    '    <select class="form-control select2 SelecPalabras"  style="width: 100%;" data-placeholder="Seleccione la Respuesta"' +
                                    '  id="cb_respuesta' + consp +
                                    '" name="cb_respuesta[]">' +
                                    '</select>' +
                                    '     </div>' +
                                    '      </div>' +
                                    '   </div>' +
                                    '</div>' +

                                    '</div>' +
                                    '      </div>' +
                                    '   </div>' +
                                    '</div>';

                                $("#div-evaluaciones").append(Preguntas);

                                $("#PregOpciones" + cons).html(respuesta.Parte1
                                    .pregunta);
                                var opciones = '';
                                var preg = 1;



                                $.each(respuesta.Preguntas, function(k, item) {
                                    opciones += '<fieldset>';
                                    opciones += '<div class="row">' +
                                        '<div class="col-md-8">' +
                                        ' <label class="form-label"><b>Pregunta ' +
                                        preg + ': </b></label> ' +
                                        '<label>' + item.pregunta + '</label>' +
                                        '</div>' +
                                        '<div class="col-md-4">' +
                                        ' <label class="form-label"><b>Respuesta: </b></label> ' +
                                        '<label>' + item.respuesta + '</label>' +
                                        '</div>' +

                                        '</div>';
                                    preg++;
                                });

                                $("#DivPreg" + cons).html(opciones);

                                $("#cb_Opciones").select2({
                                    tags: true,
                                    language: {
                                        noResults: function() {
                                            return 'Debe de Ingresar las Opciones para completar el parrafo.';
                                        },
                                    }
                                });


                            } else if (respuesta.Banco.tipo_pregunta === "PARTE 2") {


                                $.each(respuesta.PregBanc, function(i, item) {


                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte2' +
                                        cons +
                                        '" name="id-parte2" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PARTE 2" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese la Pregunta:</b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese las Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '           <!--<input class="form-control" placeholder="Opción 1" aria-label="Text input with radio button" name="txtopcpreg1[]" type="text">-->' +
                                        '          </div>' +
                                        '     </div>' +
                                        '     <div class="col-lg-1">' +
                                        '         <!--<button type="button" class="btn btn-icon btn-outline-warning btn-social-icon btn-sm"><i class="fa fa-trash"></i></button>-->' +
                                        '      </div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '   <div class="row">' +
                                        '  <button id="AddOpcPre" onclick="$.AddOpcion();" type="button" class="btn mr-1 mb-1 btn-success"><i class="fa fa-plus"></i> Agregar Opcion</button> ' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {



                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-parte2" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;



                                });

                            } else if (respuesta.Banco.tipo_pregunta === "PARTE 3") {

                                $.each(respuesta.PregBanc, function(i, item) {

                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte3' +
                                        cons +
                                        '" name="id-parte3" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PARTE 3" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese la Pregunta:</b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese las Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '           <!--<input class="form-control" placeholder="Opción 1" aria-label="Text input with radio button" name="txtopcpreg1[]" type="text">-->' +
                                        '          </div>' +
                                        '     </div>' +
                                        '     <div class="col-lg-1">' +
                                        '         <!--<button type="button" class="btn btn-icon btn-outline-warning btn-social-icon btn-sm"><i class="fa fa-trash"></i></button>-->' +
                                        '      </div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '   <div class="row">' +
                                        '  <button id="AddOpcPre" onclick="$.AddOpcion();" type="button" class="btn mr-1 mb-1 btn-success"><i class="fa fa-plus"></i> Agregar Opcion</button> ' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-parte3" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;

                                });
                            } else if (respuesta.Banco.tipo_pregunta === "PARTE 4") {

                                $("#ParteSel").val(respuesta.Partes.parte);

                                $.each(respuesta.PregBanc, function(i, item) {


                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte4' +
                                        cons +
                                        '" name="id-parte4" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PARTE 4" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div  class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Completar:</b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '          </div>' +
                                        '     </div>' +
                                        '      </div>' +
                                        '   </div>' +

                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-parte4" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;


                                });


                            } else if (respuesta.Banco.tipo_pregunta === "PARTE 5") {

                                $.each(respuesta.PregBanc, function(i, item) {


                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte5' +
                                        cons +
                                        '" name="id-parte5" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PARTE 5" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Pregunta:</b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '          </div>' +
                                        '     </div>' +
                                        '      </div>' +
                                        '   </div>' +

                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-parte5" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;

                                });

                            } else if (respuesta.Banco.tipo_pregunta === "PARTE 6") {

                                $.each(respuesta.PregBanc, function(i, item) {


                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte6' +
                                        cons +
                                        '" name="id-parte6" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PARTE 6" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b> Pregunta:</b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '          </div>' +
                                        '     </div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if (cons === 1) {
                                            $('#competencia' + cons).val(
                                                    itemp.competencia)
                                                .trigger('change.select2');

                                            $('#componente' + cons).val(
                                                    itemp.componente)
                                                .trigger('change.select2');
                                        }

                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-parte6" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );

                                });

                            } else if (respuesta.Banco.tipo_pregunta === "PARTE 7") {

                                $.each(respuesta.PregBanc, function(i, item) {


                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte7' +
                                        cons +
                                        '" name="id-parte7" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PARTE 7" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Completar: </b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '          </div>' +
                                        '     </div>' +

                                        '      </div>' +
                                        '   </div>' +

                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if (cons === 1) {
                                            $('#competencia' + cons).val(
                                                    itemp.competencia)
                                                .trigger('change.select2');

                                            $('#componente' + cons).val(
                                                    itemp.componente)
                                                .trigger('change.select2');
                                        }

                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-parte7" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );

                                });

                            } else {

                                $.each(respuesta.PregBanc, function(i, item) {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-preopcmult' +
                                        cons +
                                        '" name="id-preopcmult" value="" />' +

                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese la Pregunta:</b></label>' +
                                        '<div id="PreguntaMultiple' + cons + '">' +
                                        '     <textarea cols="80" id="summernotePreg1" name="PreMulResp" rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese las Opciones:</b></label>' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="RowMulPreg1">' +
                                        '                 <div class="row top-buffer" id="RowOpcPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">' +
                                        '                             <input aria-label="Checkbox for following text input" id="checkopcpreg11"' +
                                        '                              name="RadioOpcPre[]" onclick="$.selCheck(1);" value="off"' +
                                        '                            type="radio">' +
                                        '                        <input type="hidden" id="OpcCorecta1" name="OpcCorecta[]" value="no" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '          </div>' +
                                        '     </div>' +

                                        '      </div>' +
                                        '   </div>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarEvalOpcMult(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPreguntasOpcMult(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DelPreguntasOpcMult(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {
                                        if ($.trim(item.idpreg) === $.trim(
                                                itemp.id)) {

                                            $('#Btn-guardarPreg' + cons)
                                                .prop('disabled', false);

                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-preopcmult" +
                                                cons).val(
                                                itemp.id);

                                            $("#CompeEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compe);

                                            $("#CompoEdit" +
                                                cons).val(
                                                itemp
                                                .nombre_compo);

                                            $("#PreguntaMultiple" +
                                                cons).html(
                                                itemp
                                                .pregunta);


                                            $.each(respuesta.OpciMult,
                                                function(k, itemo) {

                                                    if ($.trim(itemo
                                                            .pregunta
                                                        ) === $
                                                        .trim(item
                                                            .idpreg)) {
                                                        opciones +=
                                                            '<fieldset>';
                                                        if (itemo
                                                            .correcta ===
                                                            "si") {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15" checked>';
                                                        } else {
                                                            opciones +=
                                                                '<input type="checkbox" disabled id="input-15">';
                                                        }

                                                        opciones +=
                                                            ' <label for="input-15"> ' +
                                                            itemo
                                                            .opciones +
                                                            '</label>' +
                                                            '</fieldset>';
                                                    }

                                                });

                                            $("#DivOpcionesMultiples" +
                                                cons).html(opciones);
                                        }
                                    });

                                    cons++;
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                });

                            }


                        }
                    });

                },
                DelPorc: function(id_fila) {
                    var porcT = $("#Tot_Porc").val();
                    var porc = $("#txtporc" + id_fila).val();
                    porcT = parseInt(porcT) - parseInt(porc);

                    $("#Tot_Porc").val(porcT);
                    $("#gtotal").html("");
                    $("#gtotal").html(porcT);
                    $('#tr_' + id_fila).remove();
                    p
                },
                EditPorc: function(id) {

                    var ToporcV = 0;
                    mensaje =
                        "Al editar estos parametros, las preguntas tendran que ser generdas nuevamanete. ¿Desea continuar?";
                    Swal.fire({
                        title: 'Gestionar Simualcros',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Continuar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var ParCom = $("#txtComp" + id).val();
                            ParCom = ParCom.split("-");
                            $('#Competencia').val(ParCom[0]).trigger('change.select2');
                            $('#Componente').val(ParCom[1]).trigger('change.select2');
                            $("#PorcPreguntas").val($("#txtporc" + id).val());
                            $("#tr_" + id).remove();

                            $("input[name='txtporc[]']").each(function(indice, elemento) {
                                ToporcV = ToporcV + parseInt($(elemento).val());
                            });

                            $("#gtotal").html(ToporcV);
                            $("#Tot_Porc").val(ToporcV);


                            ////Borrar preguntas generadas
                            var form = $("#formAuxiliarDelPregArea");

                            var IdSesi = $("#IdSesionGen").val();
                            var IdArea = $("#IdSesion").val();
                            $("#IdSes").remove();
                            $("#IdSesArea").remove();


                            form.append("<input type='hidden' name='IdSes' id='IdSes' value='" +
                                IdSesi + "'>");
                            form.append(
                                "<input type='hidden' name='IdSesArea' id='IdSesArea' value='" +
                                IdArea + "'>");

                            var url = form.attr("action");
                            var datos = form.serialize();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                async: false,
                                dataType: "json",
                                success: function(respuesta) {

                                    $("#GenPreg").hide();
                                    $("#AddCompe").hide();
                                    $("#UpdCompe").show();
                                    $('#TablaPreg').hide();
                                    $('#EditCofCompeEdit').hide();
                                }

                            });



                        }
                    });




                },
                UpdatPorc: function() {

                    var Toporc = 0;
                    var ToporcT = 0;

                    $.AddComp();

                    $("#UpdCompe").hide();
                    $("#AddCompe").show();

                },
                GuarConfComp: function(Opc) {

                    var rurl = $("#Ruta").val();
                    $("#OpcGuardado").val(Opc);

                    $.ajax({
                        type: "POST",
                        url: rurl + "ModuloE/GuardarAreaSimuPreg",
                        data: new FormData($('#formSimu')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {

                                Swal.fire({
                                    title: "Gestión de Simulacros",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });


                                var campo = "";
                                var ConsArea = 1;
                                var style =
                                    'text-transform: uppercase;background-color:white;';
                                var clase = 'text-truncate';
                                $.each(respuesta.AreSim, function(i, item) {

                                    campo += "<tr id='tr_Area" + item.id + "'>";
                                    campo += "<td class='" + clase + "'>";
                                    campo += item.nombre_area;
                                    campo += "</td>";
                                    campo += "<td class='" + clase + "'>";
                                    campo += item.npreguntas;
                                    campo += "</td>";
                                    campo += "<td class='" + clase + "'>";
                                    campo += "<a onclick='$.EditAreaSesion(" + item
                                        .id +
                                        ")' class='btn btn-info btn-sm btnEditar text-white'   title='Editar'><i class='fa fa-edit font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                                    campo += "<a onclick='$.DelAreaSesion(" + item
                                        .id +
                                        ")' class='btn btn-danger btn-sm btnQuitar text-white'  title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                                    campo += "</td>";
                                    campo += "</tr>";

                                });
                                $("#tr_areas").html(campo);

                                $("#AreasAgregadas").show();
                                $("#DetaAreas").hide();
                                $("#GuarCofCompe").hide();


                            } else {
                                mensaje = "El Simulacro no pudo ser Guardado";
                                Swal.fire({
                                    title: "Gestión de Simulacros",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }

                            $.CargarInfoSim();
                        },
                        error: function(error_messages) {
                            alert('HA OCURRIDO UN ERROR');
                        }
                    });
                },
                GenPreg: function() {
                    var rurl = $("#Ruta").val();
                    var area = $("#area").val();

                    if (area == "5") {
                        $("#selPreguntas").modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        let option = "<option value='0/0'>Seleccione...</option>";
                        let totCant = 0;

                        $("input[name='txtcomp[]']").each(function(indice, elemento) {
                            option += "<option value='" + $(elemento).val() + "/" + $(elemento)
                                .data("cantidad") + "'>" + $(elemento).data("nombre") +
                                "</option>";
                        });

                        $("#compxcompo").html(option);

                    } else {
                        $.ajax({
                            type: "POST",
                            url: rurl + "ModuloE/GenerPregArea",
                            data: new FormData($('#formSimu')[0]),
                            processData: false,
                            contentType: false,
                            success: function(respuesta) {
                                if (respuesta) {
                                    Swal.fire({
                                        title: "Gestión de Simulacros",
                                        text: "Preguntas Generadas Exitosamente",
                                        icon: "success",
                                        button: "Aceptar",
                                    });

                                    $("#PreguntasxAreas").html(respuesta.Preguntas);
                                    if ($("#OpcSesion").val() === "G") {
                                        $('#GuarCofCompe').show();
                                        $('#EditCofCompeEdit').hide();
                                    } else {
                                        $('#EditCofCompeEdit').show();
                                        $('#GuarCofCompe').hide();

                                    }
                                    $('#TablaPreg').show();


                                } else {
                                    mensaje = "Las Preguntas no Lograron ser Generadas";
                                    Swal.fire({
                                        title: "Gestión de Simulacros",
                                        text: mensaje,
                                        icon: "warning",
                                        button: "Aceptar",
                                    });
                                }
                            },
                            error: function(error_messages) {
                                alert('HA OCURRIDO UN ERROR');
                            }
                        });

                    }


                },

                GuarConfCompPreg() {


                    var rurl = $("#Ruta").val();

                    $.ajax({
                        type: "POST",
                        url: rurl + "ModuloE/GuardarAreaSimuPreg",
                        data: new FormData($('#formSimu')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Simu").val(respuesta.Simu.id);
                                swal({
                                    title: "Gestión de Simulacros",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });

                            } else {
                                mensaje = "El Simulacro no pudo ser Guardado";
                                swal({
                                    title: "Gestión de Simulacros",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }
                        },
                        error: function(error_messages) {
                            alert('HA OCURRIDO UN ERROR');
                        }
                    });
                },
                FormTiempo: function() {
                    $.mask.definitions['H'] = "[0-1]";
                    $.mask.definitions['h'] = "[0-9]";
                    $.mask.definitions['M'] = "[0-5]";
                    $.mask.definitions['m'] = "[0-9]";
                    $.mask.definitions['P'] = "[AaPp]";
                    $.mask.definitions['p'] = "[Mm]";

                    $("#TSesion").mask("Hh:Mm");
                    $("#TSesion2").mask("Hh:Mm");
                },

            });

            $.CargarInfoSim();
            $.FormTiempo();
            //======================EVENTO AGREGAR PERIODOS=======================\\

        });

        function validartxtnum(e) {
            tecla = e.which || e.keyCode;
            patron = /[0-9]+$/;
            te = String.fromCharCode(tecla);
            //    if(e.which==46 || e.keyCode==46) {
            //        tecla = 44;
            //    }
            return (patron.test(te) || tecla == 9 || tecla == 8 || tecla == 37 || tecla == 39 || tecla == 44);
        }
    </script>
@endsection
