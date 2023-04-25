@extends('Plantilla.Principal')
@section('title', 'Gestión de Calificaciones')
@section('Contenido')

    <input type="hidden" data-id='id-dat' id="dattaller"
        data-ruta="{{ asset('/app-assets/Archivos_EvaluacionTaller') }}" />
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Calificar Alumnos
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success white">
                    <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">Contenido Didactico
                        Cargado</h4>
                </div>
                <div class="modal-body">
                    <div id='ListEval' style="height: 400px; overflow: auto;text-align: center;">
                        <video width="640" height="360" id="datruta" controls
                            data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}">
                        </video>
                    </div>

                    <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                            class="ft-corner-up-left position-right"></i> Salir</button>
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
                            <h4 class="card-title">Gestión de Calificaciones</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body" id="Div_Search">
                                <div class="row">

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label" for="unidad">Evaluaciones:</label>
                                                <select class="form-control form-control" onchange="$.CambEval(this.value);" id="CalEval" name="CalEval">
                                                    <option value="T">TEMAS</option>
                                                    <option value="L">LABORATORIOS</option>
                                                  </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label" for="unidad">Periodo:</label>
                                                <select class="form-control select2" onchange='$.CargUnidades(this.value)'
                                                    name="Periodos" id="Periodos">
                                                    {!! $Select_Peri !!}
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="unidad">Unidad:</label>
                                                <select class="form-control select2" onchange='$.CargTemas(this.value)'
                                                    name="Unidad" id="Unidad">

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" id="Tit-Tem" for="unidad">Tema:</label>
                                                <select class="form-control select2" onchange="$.BuscEval(this.value);"
                                                    name="Temas" id="Temas">

                                                </select>
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
                                <div class="row" id="Div_ListEval">
                                    <div class="col-12">
                                        <div class="table-responsive"> <input type='hidden' id='Id_Eval' name='Id_Eval'
                                                value='' />
                                            <table id="recent-orders"
                                                class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Evaluación</th>
                                                        <th>Clasificación</th>
                                                        <th>Tema</th>
                                                        <th>Revisar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id='tr_eval'>
                                                    @foreach ($Eval as $Ev)
                                                        @php
                                                            $clasif = $Ev->clasificacion;
                                                            if ($clasif === 'ACTINI') {
                                                                $clasif = 'ACTIVIDAD DE INICIO';
                                                            } else {
                                                                $clasif = 'PRODUCCIÓN';
                                                            }
                                                        @endphp
                                                        <tr data-id='{{ $Ev->id }}' id='alumno{{ $Ev->id }}'>
                                                            <td class="text-truncate" style="text-transform: capitalize;">
                                                                {!! mb_strtolower($Ev->titulo) !!}</td>

                                                            <td class="text-truncate">{!! $clasif !!}</td>
                                                            <td class="text-truncate">{!! $Ev->titu_contenido !!}</td>
                                                            <td class="text-truncate">

                                                                <button type="button" data-toggle="tooltip"
                                                                    data-original-title="Revisar Evaluación"
                                                                    data-animation="false"
                                                                    onclick="$.VerEval({{ $Ev->id }});"
                                                                    class="btn btn-icon btn-pure primary"><i
                                                                        style="font-size: 30px;"
                                                                        class="ft-eye"></i></button>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="px-1"></p>
                                        <div id='pagEva'>
                                            @include('Calificaciones.paginacion')
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row" id="Div_DetEval" style="display:none">
                        <div class="col-xl-8 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="Titulos_Eval"></h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="card-content">
                                        <div id="Div_Enunciado"></div>
                                        <div style="width: 100%;" id='div-evaluaciones'>

                                        </div>
                                        <div id="vid-adjunto"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-12">
                            <div class="card" style="height: 460px;">
                                <div class="card-header">
                                    <h4 class="card-title">Parametros</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <span id="span_clasificacion"
                                                class="badge badge-default badge-pill bg-primary float-right"></span>
                                            Clasificación
                                        </li>
                                        <li class="list-group-item">
                                            <span id="span_intentos"
                                                class="badge badge-default badge-pill bg-primary float-right"></span>
                                            Intentos Permitidos
                                        </li>
                                        <li class="list-group-item">
                                            <span id="span_cal_usando"
                                                class="badge badge-default badge-pill bg-info float-right"></span>
                                            Calificar Usando
                                        </li>
                                        <li class="list-group-item">
                                            <span id="span_puntma"
                                                class="badge badge-default badge-pill bg-danger float-right"></span>
                                            Puntuación Maxima
                                        </li>
                                        <li class="list-group-item">
                                            <span id="span_tiempo"
                                                class="badge badge-default badge-pill bg-success float-right"></span>
                                            Tiempo de Evaluación
                                        </li>

                                    </ul>

                                    <div class="card-body" style="text-align: right; ">
                                        <button type="button" class="btn mr-1 mb-1 btn-outline-danger"
                                            onclick="$.MostrarEvaluaciones();"><i class="fa fa-reply-all"></i>
                                            Regresar</button>
                                        <button type="button" data-ruta="{{ asset('/Calificaciones/EvaluarAlumnos/') }}"
                                            id="btn_cal" class="btn mr-1 mb-1 btn-outline-success"
                                            onclick="$.CalAlumnos(this.id);"><i class="fa fa-check-circle"></i>
                                            Calificar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel15" aria-hidden="true">
                        <div class="modal-dialog  modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success white">
                                    <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">
                                        Contenido Didactico
                                        Cargado</h4>
                                </div>
                                <div class="modal-body">
                                    <div id='ListEval' style="height: 400px; overflow: auto;text-align: center;">
                                        <video width="640" height="360" id="datruta" controls
                                            data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}">
                                        </video>
                                    </div>

                                    <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                                        data-dismiss="modal"><i class="ft-corner-up-left position-right"></i>
                                        Salir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {!! Form::open(['url' => '/Alumnos/Eliminar', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Calificaciones/VerEvaluacion', 'id' => 'formEvaluacion']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Unidad', 'id' => 'formAuxiliarUnid']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Temas', 'id' => 'formAuxiliarTema']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Evalulacione', 'id' => 'formAuxiliarEval']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Presentacion").removeClass("active");
            $("#Men_Calificaciones").addClass("has-sub open");
            $("#Men_Calificiones_CalAlumnos").addClass("active");
            $.extend({
                VerEval: function(id) {
                    var form = $("#formEvaluacion");
                    $("#div-evaluaciones").html("");
                    $("#idEval").remove();
                    form.append("<input type='hidden' name='idEval' id='idEval' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $("#Div_DetEval").show();
                    $("#Div_Search").hide();
                    $("#Div_ListEval").hide();
                    $("#Id_Eval").val(id)
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            var cons = 1;
                            $("#Div_Enunciado").html(respuesta.Evaluacion.enunciado);
                            if(respuesta.Evaluacion.clasificacion==="ACTINI"){
                                $("#span_clasificacion").html("Actividad de Inicio");
                            }else{
                                $("#span_clasificacion").html("Actividad de Producción");
                            }
                           
                            $("#span_intentos").html(respuesta.Evaluacion.intentos_perm);
                            $("#span_cal_usando").html(respuesta.Evaluacion.calif_usando);
                            $("#span_puntma").html(respuesta.Evaluacion.punt_max);
                            

                            if (respuesta.Evaluacion.hab_tiempo === "SI") {
                                $("#span_tiempo").html(respuesta.Evaluacion.tiempo);
                            } else {
                                $("#span_tiempo").html('Sin Límite de Tiempo');
                            }



                            if (respuesta.VideoEval !== "no") {
                                var Preguntas =
                                    '<div id="Video" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-8">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '<input type="hidden" id="id-video" name="id-video" value="" />' +
                                    '     <h4 class="primary">Video Adjunto</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '      </div>' +
                                    '  <div class="col-md-12"> ' +
                                    '     <div class="form-group">' +
                                    '<div id="Det_video">' +
                                    '             <label>Seleccionar Archivo:  </label>' +
                                    '<label id="projectinput7" class="file center-block"><br>' +
                                    '    <input id="archiVideo"  name="archiVideo" type="file">' +
                                    '    <span class="file-custom"></span>' +
                                    ' </label>' +
                                    '         <br>' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '   </div>' +
                                    '</div>';

                                $("#vid-adjunto").append(Preguntas);

                                $("#id-video").val(respuesta.idvideo);
                                $("#Btn-guardarVideo").hide();
                                $("#Btn-Editvideo").show();
                                $("#div-addpreg").show();
                                $('#Btn-guardarVideo').prop('disabled', false);


                                $("#Det_video").html(
                                    '<div class="form-group" id="id_verf">' +
                                    ' <label class="form-label " for="imagen">Ver Archivo Cargado:</label>' +
                                    ' <div class="btn-group" role="group" aria-label="Basic example">' +
                                    '   <button id="idvide" type="button" data-archivo="' +
                                    respuesta.VideoEval +
                                    '" onclick="$.Mostvideo(this.id);" class="btn btn-success"><i' +
                                    '             class="fa fa-search"></i> Ver Archivo</button>' +
                                    '      </div>' +
                                    '       </div>');


                            }


                            $.each(respuesta.PregEval, function(i, item) {
                                if (item.tipo === "PREGENSAY") {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px; ">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-8">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-4">' +
                                        '           <div class="form-group row">' +
                                        '<input type="hidden" id="id-pregensay' +
                                        cons + '"  value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="PREGENSAY" />' +
                                        '            <div class="col-md-12 right">' +
                                        '<div id="PuntEnsay' + cons + '">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input type="text" class="form-control" id="puntaje"' +
                                        '    name="puntaje" value="10" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '            </div>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Contenido de Pregunta:</b></label>' +
                                        '<div id="PregEnsay' + cons + '">' +
                                        '         <textarea cols="80" id="summernote_pregensay' +
                                        cons +
                                        '" name="summernote_pregensay" rows="3"></textarea>' +
                                        '         <br>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    $.each(respuesta.PregEnsayo, function(x,
                                        item1) {
                                        if ($.trim(item.idpreg) === $.trim(
                                                item1.id)) {
                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();
                                            $("#id-pregensay" + cons).val(
                                                item1.id);
                                            $("#PuntEnsay" + cons).html(
                                                '<fieldset >' +
                                                '        <div class="input-group">' +
                                                '          <input type="text" id="PuntEdit' +
                                                cons +
                                                '" class="form-control"' +
                                                '     value="' + item1
                                                .puntaje +
                                                '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                                '          <div class="input-group-append">' +
                                                '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>');
                                            $("#PregEnsay" + cons).html(
                                                item1.pregunta);
                                            edit = "si";
                                            cons++;
                                        }

                                    });

                                } else if (item.tipo === "COMPLETE") {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-8">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-4">' +
                                        '           <div class="form-group row">' +
                                        '<input type="hidden" id="id-pregcomplete' +
                                        cons +
                                        '"  value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="COMPLETE" />' +
                                        '            <div class="col-md-12 right">' +
                                        '<div id="PuntComplete' + cons + '">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input type="text" class="form-control" id="puntaje"' +
                                        '    name="puntaje" value="10" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '            </div>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese las Opciones:</b></label>' +
                                        '<div id="PregOpciones' + cons + '">' +
                                        '    <select class="form-control select2" multiple="multiple" style="width: 100%;" data-placeholder="Ingrese las Opciones"' +
                                        '  id="cb_Opciones" name="cb_Opciones[]">' +
                                        '</select>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Ingrese el parrafo a completar:</b></label>' +
                                        '<div id="DivParrCompleta' + cons + '">' +
                                        '         <textarea cols="80" id="summernoteCompPar' +
                                        cons +
                                        '" name="summernoteCompPar" rows="3"></textarea>' +
                                        '         <br>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    $.each(respuesta.PregComple, function(x,
                                        item1) {
                                        if ($.trim(item.idpreg) === $.trim(
                                                item1.id)) {
                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();
                                            $("#id-pregcomplete" + cons)
                                                .val(item1.id);
                                            $("#PuntComplete" + cons).html(
                                                '<fieldset >' +
                                                '        <div class="input-group">' +
                                                '          <input type="text" id="PuntEdit' +
                                                cons +
                                                '" class="form-control"' +
                                                '     value="' + item1
                                                .puntaje +
                                                '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                                '          <div class="input-group-append">' +
                                                '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>');
                                            $("#PregOpciones" + cons).html(
                                                item1.opciones);
                                            $("#DivParrCompleta" + cons)
                                                .html(item1.parrafo);
                                            edit = "si";
                                            cons++;
                                        }

                                    });
                                } else if (item.tipo === "OPCMULT") {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-8">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-4">' +
                                        '           <div class="form-group row">' +
                                        '<input type="hidden" id="id-preopcmult' +
                                        cons +
                                        '" name="id-preopcmult" value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="OPCMULT" />' +
                                        '            <div class="col-md-12 right">' +
                                        '<div id="PuntMultiple' + cons + '">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input type="text" class="form-control" id="puntaje"' +
                                        '    name="puntaje" value="10" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '            </div>' +
                                        '          </div>' +
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

                                            $("#id-preopcmult" +
                                                cons).val(
                                                itemp.id);
                                            $("#PuntMultiple" +
                                                cons).html(
                                                '<fieldset >' +
                                                '        <div class="input-group">' +
                                                '          <input type="text" id="PuntEdit' +
                                                cons +
                                                '" class="form-control"' +
                                                '     value="' +
                                                itemp
                                                .puntuacion +
                                                '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                                '          <div class="input-group-append">' +
                                                '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>'
                                            );

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

                                } else if (item.tipo === "VERFAL") {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-8">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-4">' +
                                        '           <div class="form-group row">' +
                                        '<input type="hidden" id="id-pregverfal' +
                                        cons +
                                        '"  value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="VERFAL" />' +
                                        '            <div class="col-md-12 right">' +
                                        '<div id="PuntVerFal' + cons + '">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input type="text" class="form-control" id="puntaje"' +
                                        '    name="puntaje" value="10" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '            </div>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group" >' +
                                        '        <label class="form-label"><b>Contenido de Pregunta:</b></label>' +
                                        '<div id="PregVerFal' + cons + '">' +
                                        '         <textarea cols="80" id="summernote_pregverdFals' +
                                        cons +
                                        '" name="summernote_pregverdFals" rows="3"></textarea>' +
                                        '         <br>' +
                                        '</div>' +
                                        '<div class="col-md-4 border-bottom-cyan" id="CheckResp' +
                                        cons + '"  >' +
                                        '           <div class="form-group row">' +
                                        '<div class="col-md-12">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input  name="radpregVerFal[]" checked="" value="si" type="radio">' +
                                        '          <div class="input-group-append" style="margin-left:5px;">' +
                                        '            <span  id="basic-addon2">Verdadero</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '<div  class="col-md-12">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input  name="radpregVerFal[]"  value="no" type="radio">' +
                                        '          <div class="input-group-append" style="margin-left:5px;">' +
                                        '            <span  id="basic-addon2">Falso</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '            </div>' +
                                        '          </div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';

                                    $("#div-evaluaciones").append(Preguntas);
                                    $.each(respuesta.PregVerFal, function(x,
                                        item1) {
                                        if ($.trim(item.idpreg) === $.trim(
                                                item1.id)) {
                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-pregverfal" + cons).val(
                                                item1.id);

                                            $("#PuntVerFal" + cons).html(
                                                '<fieldset >' +
                                                '        <div class="input-group">' +
                                                '          <input type="text" id="PuntEdit' +
                                                cons +
                                                '" class="form-control"' +
                                                '     value="' + item1
                                                .puntaje +
                                                '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                                '          <div class="input-group-append">' +
                                                '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>');
                                            $("#PregVerFal" + cons).html(
                                                item1.pregunta);
                                            var Opc =
                                                '<div class="form-group row">' +
                                                '<div class="col-md-12">' +
                                                '    <fieldset >' +
                                                '        <div class="input-group">';
                                            if (item1.respuesta === "si") {
                                                Opc +=
                                                    '<input   checked="" value="si" disabled type="radio">';

                                            } else {
                                                Opc +=
                                                    ' <input   value="si" disabled type="radio">';

                                            }

                                            Opc +=
                                                ' <div class="input-group-append" style="margin-left:5px;">' +
                                                '            <span  id="basic-addon2">Verdadero</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>' +
                                                '</div>' +
                                                '<div  class="col-md-12">' +
                                                '    <fieldset >' +
                                                '        <div class="input-group">';
                                            if (item1.respuesta === "no") {
                                                Opc +=
                                                    '<input   checked="" value="si" disabled type="radio">';

                                            } else {
                                                Opc +=
                                                    '<input  value="si" disabled type="radio">';

                                            }
                                            Opc +=
                                                '<div class="input-group-append" style="margin-left:5px;">' +
                                                '            <span  id="basic-addon2">Falso</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>' +
                                                '</div>' +
                                                '            </div>';

                                            $("#CheckResp" + cons).html(
                                                Opc);

                                            edit = "si";
                                            cons++;
                                        }

                                    });
                                } else if (item.tipo === "RELACIONE") {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-8">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-4">' +
                                        '           <div class="form-group row">' +
                                        '<input type="hidden" id="id-relacione' +
                                        cons +
                                        '"  value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="RELACIONE" />' +
                                        '            <div class="col-md-12 right">' +
                                        '<div id="PuntRelacione' + cons + '">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input type="text" class="form-control" id="puntaje"' +
                                        '    name="puntaje" value="10" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '</div>' +
                                        '            </div>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12 pb-1"> ' +
                                        '<div id="ConsEnunRel' + cons + '">' +
                                        '                     <textarea cols="80" class="txtareaR" id="EnuncRelacione" name="EnuncRelacione"' +
                                        '                        rows="3"></textarea>' +
                                        '</div>' +
                                        '</div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +

                                        '<div id="DivOpcionesRelacione' + cons +
                                        '">' +
                                        '<input type="hidden" class="form-control" id="ConsOpcRel" value="2" />' +
                                        '<div id="RowRelPreg' + cons + '">' +
                                        '                 <div class="row top-buffer" id="RowOpcRelPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-6 border-top-primary">' +
                                        ' <input type="hidden" class="form-control" name="Mesnsaje[]" value="1" />' +
                                        '        <label class="form-label"><b>Indicaciones:</b></label>' +
                                        '                     <textarea cols="80" id="summernoteMensaje1" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '     </div>' +
                                        '                      <div class="col-lg-6 border-top-primary">' +
                                        ' <input type="hidden" class="form-control" name="respuestas[]" value="1" />' +
                                        '        <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                        '                     <textarea cols="80" id="summernoteRespuesta1" name="txtopcResp[]"' +
                                        '                        rows="3"></textarea>' +
                                        '     </div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '   <div class="row" id="divaddpar' + cons +
                                        '">' +
                                        '  <button id="AddOpcPre" onclick="$.AddOpcionPar();" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Par</button> ' +
                                        '</div>' +
                                        ' <div class="row">' +
                                        '  <label class="form-label pt-2"><b>Respuestas Adicionales:</b></label>' +
                                        '<input type="hidden" class="form-control" id="ConsOpcRelAdd" value="1" />' +
                                        '</div>' +
                                        ' <div class="row" id="DivRespAdd' + cons +
                                        '"></div>' +
                                        '<div class="row" id="divaddpre' + cons +
                                        '">' +
                                        '  <button  onclick="$.AddOpcionRespAdd();" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Respuesta</button> ' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);

                                    $.each(respuesta.PregRelacione, function(x,
                                        item1) {

                                        if ($.trim(item.idpreg) === $.trim(
                                                item1.id)) {
                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();
                                            $("#id-relacione" + cons).val(
                                                item1.id);

                                            $("#PuntRelacione" + cons).html(
                                                '<fieldset >' +
                                                '        <div class="input-group">' +
                                                '          <input type="text" id="PuntEdit' +
                                                cons +
                                                '" class="form-control"' +
                                                '     value="' + item1
                                                .puntaje +
                                                '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                                '          <div class="input-group-append">' +
                                                '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                                '          </div>' +
                                                '        </div>' +
                                                '      </fieldset>');
                                            var y = 1;
                                            var preguntas = "";

                                            $("#ConsEnunRel" + cons).html(
                                                item1.enunciado);

                                            $.each(respuesta.PregRelIndi,
                                                function(k, item2) {
                                                    if ($.trim(item1
                                                        .id) === $.trim(
                                                            item2
                                                            .pregunta)
                                                        ) {
                                                        preguntas +=
                                                            '<div class="row top-buffer" id="RowOpcRelPreg' +
                                                            y +
                                                            '" style="padding-bottom: 15px;">' +
                                                            '                      <div class="col-lg-6 border-top-primary">' +
                                                            '        <label class="form-label"><b>Mensaje:</b></label>' +
                                                            '<div id="mesaje' +
                                                            cons + y +
                                                            '"></div>' +
                                                            '     </div>' +
                                                            ' <div class="col-lg-6 border-top-primary">' +
                                                            '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                                            '<div id="respuesta' +
                                                            cons + y +
                                                            '"></div>' +
                                                            '     </div>' +
                                                            '      </div>' +
                                                            ' </div>';
                                                        y++;
                                                    }

                                                });

                                            $("#RowRelPreg" + cons).html(
                                                preguntas);

                                            y = 1;

                                            $.each(respuesta.PregRelIndi,
                                                function(k, item2) {
                                                    if ($.trim(item1
                                                        .id) === $.trim(
                                                            item2
                                                            .pregunta)
                                                        ) {
                                                        $("#mesaje" +
                                                                cons + y
                                                                )
                                                            .html(item2
                                                                .definicion
                                                            );
                                                        y++;
                                                    }

                                                });

                                            y = 1;
                                            $.each(respuesta.PregRelResp,
                                                function(k, item3) {
                                                    if ($.trim(item1
                                                        .id) === $.trim(
                                                            item3
                                                            .pregunta)
                                                        ) {
                                                        if (item3
                                                            .correcta !==
                                                            "-") {
                                                            $("#respuesta" +
                                                                cons +
                                                                y
                                                            ).html(
                                                                item3
                                                                .respuesta
                                                            );
                                                            y++;
                                                        }
                                                    }
                                                });

                                            preguntas = "";
                                            y = 1;
                                            $.each(respuesta.PregRelResp,
                                                function(k, item4) {
                                                    if (item4
                                                        .correcta ===
                                                        "-") {
                                                        preguntas +=
                                                            '<div class="row top-buffer" id="RowOpcRelPregAdd' +
                                                            y +
                                                            '" style="padding-bottom: 15px;width: 100%;">' +
                                                            '                      <div class="col-lg-6 border-top-primary">' +
                                                            '     </div>' +
                                                            ' <div class="col-lg-6 border-top-primary" >' +
                                                            '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                                            '   <div id="respuestaadd' +
                                                            cons + y +
                                                            '"></div>' +
                                                            '     </div>' +
                                                            '      </div>' +
                                                            " </div>";
                                                    }
                                                    y++;
                                                });

                                            $("#DivRespAdd" + cons).html(
                                                preguntas);

                                            y = 1;
                                            $.each(respuesta.PregRelResp,
                                                function(k, item4) {
                                                    if ($.trim(item1
                                                        .id) === $.trim(
                                                            item4
                                                            .pregunta)
                                                        ) {
                                                        if (item4
                                                            .correcta ===
                                                            "-") {
                                                            $("#respuestaadd" +
                                                                cons +
                                                                y
                                                            ).html(
                                                                item4
                                                                .respuesta
                                                            );
                                                        }
                                                        y++;
                                                    }
                                                });

                                            $("#divaddpar" + cons).remove();
                                            $("#divaddpre" + cons).remove();

                                            edit = "si";
                                            cons++;
                                        }
                                    });

                                } else if (item.tipo === "TALLER") {
                                    var Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-8">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' +
                                        cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-4">' +
                                        '           <div class="form-group row">' +
                                        '<input type="hidden" id="id-taller' +
                                        cons +
                                        '"  value="" />' +
                                        '<input type="hidden" id="Tipreguntas' +
                                        cons +
                                        '"  value="TALLER" />' +
                                        '            <div class="col-md-12 right">' +
                                        '<div id="PuntTaller' + cons + '">' +
                                        '    <fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <input type="text" class="form-control" id="puntaje"' +
                                        '    name="puntaje" value="10" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </fieldset>' +
                                        '      </div>' +
                                        '            </div>' +
                                        '          </div>' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '<div id="PregTaller' + cons + '">' +
                                        '             <label>Seleccionar Archivo: </label>' +
                                        '<label id="projectinput7" class="file center-block"><br>' +
                                        '    <input id="archiTaller"  name="archiTaller" type="file">' +
                                        '    <span class="file-custom"></span>' +
                                        ' </label>' +
                                        '         <br>' +
                                        '</div>' +
                                        '</div>' +
                                        '      </div>' +
                                        '   </div>' +
                                        '</div>';

                                    $("#div-evaluaciones").append(Preguntas);
                                    $.each(respuesta.PregTaller, function(x,
                                        item5) {
                                        if ($.trim(item.idpreg) === $.trim(
                                                item5.id)) {
                                            $("#Btn-guardarPreg" + cons)
                                                .hide();
                                            $("#Btn-EditPreg" + cons)
                                                .show();
                                            $("#div-addpreg").show();

                                            $("#id-taller" + cons).val(item5
                                                .id);

                                            $("#PuntTaller" + cons).html(
                                                '<fieldset >' +
                                                '        <div class="input-group">' +
                                                '          <input type="text" id="PuntEdit' +
                                                cons +
                                                '" class="form-control"' +
                                                '     value="' + item5
                                                .puntaje +
                                                '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                                '          <div class="input-group-append">' +
                                                '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                                '            </div>' +
                                                '        </div>' +
                                                '      </fieldset>');
                                            $("#PregTaller" + cons).html(
                                                '<div class="form-group" id="id_verf">' +
                                                ' <label class="form-label " for="imagen">Ver Archivo Cargado:</label>' +
                                                ' <div class="btn-group" role="group" aria-label="Basic example">' +
                                                '   <button id="idimg' +
                                                cons +
                                                '" type="button" data-archivo="' +
                                                item5.nom_archivo +
                                                '" onclick="$.MostArc(this.id);" class="btn btn-success"><i' +
                                                '             class="fa fa-search"></i> Ver Archivo</button>' +
                                                '      </div>' +
                                                '       </div>');
                                            edit = "si";
                                            cons++;
                                        }
                                    });
                                }
                            });

                        }

                    });
                },
                Mostvideo: function(id) {

                    $("#ModVidelo").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    $("#datruta").html(
                        '<source src="" id="sour_video" type="video/mp4">'
                    );
                    jQuery('#sour_video').attr('src', $('#datruta').data(
                        "ruta") + "/" + $('#' + id).data("archivo"));
                    $('#' + id).data("archivo");
                    $('#datruta').get(0).load();

                },
                MostArc: function(id) {
                    window.open($('#dattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
                },
                MostrarEvaluaciones: function() {
                    $("#Div_DetEval").hide();
                    $("#Div_Search").show();
                    $("#Div_ListEval").show();
                },
                hab_ediContDidac: function() {
                    $("#cont_sumerdidactico").html(
                        '<textarea name="summernoteContTema"  id="summernoteContTema" class="summernote"></textarea>'
                    );
                    $('#summernoteContTema').summernote({
                        focus: true,
                        height: 150, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
                    });
                },
                CambEval: function(eval) {
                    if(eval==="L"){
                        $("#Tit-Tem").html("Laboratorio");
                    }else{
                        $("#Tit-Tem").html("Tema");
                    }
                    $('#Periodos').val('').trigger('change');
                    $('#Unidad').val('').trigger('change');
                    $('#Temas').val('').trigger('change');
                },
             
                hab_ediContCompOpc: function() {
                    $("#cont_sumercompopci").html(
                        '<textarea name="summernoteCompOpc"  id="summernoteCompOpc" class="summernote"></textarea>'
                    );
                    $('#summernoteCompOpc').summernote({
                        focus: true,
                        height: 150, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
                    });
                },
                hab_ediContCompPar: function() {
                    $("#cont_sumercompparraf").html(
                        '<textarea name="summernoteCompPar"  id="summernoteCompPar" class="summernote"></textarea>'
                    );
                    $('#summernoteCompPar').summernote({
                        focus: true,
                        height: 150, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
                    });
                },
                CargUnidades: function(id) {

                    var form = $("#formAuxiliarUnid");
                    $("#idPer").remove();
                    form.append("<input type='hidden' name='id' id='idPer' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Unidad").html(respuesta.select_Unidades);
                        }

                    });
                },
                CargTemas: function(id) {

                    var form = $("#formAuxiliarTema");
                    var eval=$("#CalEval").val();
                    $("#idUni").remove();
                    $("#evalCal").remove();
                    form.append("<input type='hidden' name='idUni' id='idUni' value='" + id + "'>");
                    form.append("<input type='hidden' name='evalCal' id='evalCal' value='" + eval + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Temas").html(respuesta.Select_Temas);
                        }

                    });
                },
                BuscEval: function(id) {
                    var form = $("#formAuxiliarEval");
                    var eval=$("#CalEval").val();
                    $("#pagEva").hide();
                    $("#idUni").remove();
                    $("#evalCal").remove();
                    form.append("<input type='hidden' name='idTem' id='idTem' value='" + id + "'>");
                    form.append("<input type='hidden' name='evalCal' id='evalCal' value='" + eval + "'>");

                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#tr_eval").html(respuesta.tr_Eval);
                        }

                    });
                },
                MostVid: function() {
                    $("#ModVidelo").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },

                CalAlumnos: function(id) {
                    var url = $('#' + id).data("ruta");
                    location.href = url + '/' + $("#Id_Eval").val();
                }
            });
        });
    </script>
@endsection
