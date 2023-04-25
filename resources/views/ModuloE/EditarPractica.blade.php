@extends('Plantilla.Principal')
@section('title', 'Editar Practica')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIÓN DE EVALUACIONES Y ACTIVIDADES</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Evaluación / Actividad
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
                            <h4 class="card-title">Editar Evaluación</h4>
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
                                @include('ModuloE.FormPracticasTemas', [
                                    'url' => '/ModuloE/guardarEvaluacion/',
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


    {!! Form::open(['url' => '/ModuloE/CargarEvaluacion', 'id' => 'formAuxiliarEvalDet']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/consulEvalPreg', 'id' => 'formAuxiliarEval']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/ElimnarPreg', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}




@endsection
@section('scripts')
    <script>
        $(document).keydown(function() {
            if (event.keyCode == 8) {
                if (event.target.nodeName == 'BODY') {
                    event.preventDefault();
                }
            }
        });

        ///////////////////CONFIGURACION EDITOR

        CKEDITOR.editorConfig = function(config) {
            config.toolbarGroups = [{
                    name: 'document',
                    groups: ['mode', 'document', 'doctools']
                },
                {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo']
                },
                {
                    name: 'styles',
                    groups: ['styles']
                },
                {
                    name: 'editing',
                    groups: ['find', 'selection', 'spellchecker', 'editing']
                },
                {
                    name: 'forms',
                    groups: ['forms']
                },
                {
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
                },
                {
                    name: 'links',
                    groups: ['links']
                },
                {
                    name: 'insert',
                    groups: ['insert']
                },
                {
                    name: 'colors',
                    groups: ['colors']
                },
                {
                    name: 'tools',
                    groups: ['tools']
                },
                {
                    name: 'others',
                    groups: ['others']
                },
                {
                    name: 'about',
                    groups: ['about']
                }
            ];

            config.removeButtons =
                'Source,Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Replace,Find,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,SelectAll,Button,ImageButton,HiddenField,Strike,CopyFormatting,RemoveFormat,Indent,Blockquote,Outdent,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,BidiLtr,BidiRtl,Language,Link,Unlink,Anchor,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Format,BGColor,ShowBlocks,About,Underline,Italic';
        };

        $(document).ready(function() {

            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var fecact = d.getFullYear() + '/' +
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day;
            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addTem").addClass("active");
            $.extend({
                CargPeriodos: function() {

                    var form = $("#formAuxiliarPeri");
                    var id = $("#modulo").val();
                    var idPer = $("#tema_periodo").val();
                    $("#idAsig").remove();
                    form.append("<input type='hidden' name='id' id='idModd' value='" + id +
                        "'><input type='hidden' name='idPer' id='idPer' value='" + idPer + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#periodo").html(respuesta.select_Periodo);
                        }

                    });
                    $("#unidad").html("");
                },
                CargUnidades: function() {

                    var form = $("#formAuxiliarUnid");
                    var idPer = $("#periodo").val();
                    var idUnid = $("#tema_unidad").val();
                    $("#idPer").remove();
                    form.append("<input type='hidden' name='idPer' id='idPer' value='" + idPer +
                        "'><input type='hidden' name='idUnid' id='idUnid' value='" + idUnid + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#unidad").html(respuesta.select_Unidades);
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

                    $("#TEval").mask("Hh:Mm");
                },
                CargEvaluacion: function() {
                    var IdEval = $("#Id_Eval").val();
                    var form = $("#formAuxiliarEvalDet");
                    $("#idAsig").remove();
                    form.append("<input type='hidden' name='ideva' id='ideva' value='" + IdEval + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            var cons = 1;
                            $("#MensInf").hide();

                            $('#clasificacion').val(respuesta.Evaluacion.clasificacion)
                                .trigger('change.select2');
                            $('#cb_intentosPer').val(respuesta.Evaluacion.intentos_perm)
                                .trigger('change.select2');
                            $('#cb_CalUsando').val(respuesta.Evaluacion.calif_usando)
                                .trigger('change.select2');

                            $("#Punt_Max").val(respuesta.Evaluacion.punt_max);
                            $('#HabConv').val(respuesta.Evaluacion.hab_conversacion)
                                .trigger('change.select2');

                            $("#TEval").val(respuesta.Evaluacion.tiempo);
                            $("#TextHabTiempo").val(respuesta.Evaluacion.hab_tiempo);
                            $("#summernoteRelacione").val(respuesta.Evaluacion.enunciado);

                            if (respuesta.Evaluacion.hab_tiempo === "SI") {
                                $("#hab_tiempo").prop("checked", true);
                                $('#TEval').prop('readonly', false);
                            } else {
                                $("#hab_tiempo").prop("checked", false);
                                $('#TEval').prop('readonly', true);
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
                                    '    <input id="archiVideo" accept="video/*" name="archiVideo" type="file">' +
                                    '    <span class="file-custom"></span>' +
                                    ' </label>' +
                                    '         <br>' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '<div class="form-group"  style="margin-bottom: 0px;">' +
                                    '    <button type="button" onclick="$.GuardarEvalVideo();" id="Btn-guardarVideo"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-Editvideo"  style="display:none;" onclick="$.EditEvalVideo()" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-EliVideo" onclick="$.DelEvalVideo()" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                    '</div>' +
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarEvalEnsayo(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPreguntasEnsay(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DelPreguntasEnsay(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarEvalComplete(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPreguntascomplete(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DelPreguntascomplete(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarEvalVerFal(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPreguntasVerFal(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DelPreguntasVerFal(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarEvalRelacione(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPreguntasRelacione(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DelPreguntasRelacione(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
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
                                                            .id) === $
                                                        .trim(
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
                                                            .id) === $
                                                        .trim(
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
                                                            .id) === $
                                                        .trim(
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
                                                            .id) === $
                                                        .trim(
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarEvalTaller(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPreguntasTaller(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DelPreguntasTaller(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
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

                                $("#ConsPreguntas").val(cons);


                            });
                        }
                    });

                },
                hab_ediRelacione: function() {

                    CKEDITOR.replace('summernoteRelacione', {
                        width: '100%',
                        height: 100
                    });
                },
                MostArc: function(id) {
                    window.open($('#dattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
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
                //AGREGAR PREGUNTA ABIERTA
                AddPregAbierta: function() {
                    edit = "no";
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-8">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '         <div class="col-md-4">' +
                        '           <div class="form-group row">' +
                        '<input type="hidden" id="id-pregensay' + cons +
                        '"  value="" />' +
                        '<input type="hidden" id="Tipreguntas' + cons +
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
                        '         <textarea cols="80" id="summernote_pregensay' + cons +
                        '" name="summernote_pregensay" rows="3"></textarea>' +
                        '         <br>' +
                        '</div>' +
                        '</div>' +
                        '      </div>' +
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalEnsayo(' + cons +
                        ');" id="Btn-guardarPreg' + cons +
                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-EditPreg' + cons +
                        '"  style="display:none;" onclick="$.EditPreguntasEnsay(' + cons +
                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-ElimPreg' + cons +
                        '" onclick="$.DelPreguntasEnsay(' + cons +
                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#div-evaluaciones").append(Preguntas);

                    $.hab_edipre(cons);
                    cons++;
                    $("#ConsPreguntas").val(cons);
                    ///
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //AGREGAR PREGUNTA COMPLETE
                AddPregComplete: function() {
                    edit = "no";
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-8">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '         <div class="col-md-4">' +
                        '           <div class="form-group row">' +
                        '<input type="hidden" id="id-pregcomplete' + cons +
                        '"  value="" />' +
                        '<input type="hidden" id="Tipreguntas' + cons +
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
                        '         <textarea cols="80" id="summernoteCompPar' + cons +
                        '" name="summernoteCompPar" rows="3"></textarea>' +
                        '         <br>' +
                        '</div>' +
                        '</div>' +
                        '      </div>' +
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalComplete(' + cons +
                        ');" id="Btn-guardarPreg' + cons +
                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-EditPreg' + cons +
                        '"  style="display:none;" onclick="$.EditPreguntascomplete(' + cons +
                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-ElimPreg' + cons +
                        '" onclick="$.DelPreguntascomplete(' + cons +
                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#div-evaluaciones").append(Preguntas);

                    $.hab_ediContCompPar(cons);
                    cons++;
                    $("#ConsPreguntas").val(cons);
                    ///

                    $("#cb_Opciones").select2({
                        tags: true,
                        language: {
                            noResults: function() {
                                return 'Debe de Ingresar las Opciones para completar el parrafo.';
                            },
                        }
                    });
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //AGREGAR PREGUNTA OPCION MULTIPLE
                AddPregOpcMultiple: function() {
                    edit = "no";
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-8">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '         <div class="col-md-4">' +
                        '           <div class="form-group row">' +
                        '<input type="hidden" id="id-preopcmult' + cons +
                        '"  value="" />' +
                        '<input type="hidden" id="Tipreguntas' + cons +
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
                        '<div id="DivOpcionesMultiples' + cons + '">' +
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
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalOpcMult(' + cons +
                        ');" id="Btn-guardarPreg' + cons +
                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-EditPreg' + cons +
                        '"  style="display:none;" onclick="$.EditPreguntasOpcMult(' + cons +
                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-ElimPreg' + cons +
                        '" onclick="$.DelPreguntasOpcMult(' + cons +
                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#div-evaluaciones").append(Preguntas);

                    $.hab_ediPreMul("1");
                    $.hab_ediPreOpcMul("1");
                    cons++;
                    $("#ConsPreguntas").val(cons);
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //AGREGAR PREGUNTA VERDADERO Y FALSO
                AddPregVerdFalso: function() {
                    edit = "no";
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-8">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '         <div class="col-md-4">' +
                        '           <div class="form-group row">' +
                        '<input type="hidden" id="id-pregverfal' + cons +
                        '" value="" />' +
                        '<input type="hidden" id="Tipreguntas' + cons +
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
                        '         <textarea cols="80" id="summernote_pregverdFals' + cons +
                        '" name="summernote_pregverdFals" rows="3"></textarea>' +
                        '         <br>' +
                        '</div>' +
                        '<div class="col-md-4 border-bottom-cyan" id="CheckResp' + cons + '"  >' +
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
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalVerFal(' + cons +
                        ');" id="Btn-guardarPreg' + cons +
                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-EditPreg' + cons +
                        '"  style="display:none;" onclick="$.EditPreguntasVerFal(' + cons +
                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-ElimPreg' + cons +
                        '" onclick="$.DelPreguntasVerFal(' + cons +
                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#div-evaluaciones").append(Preguntas);

                    $.hab_edipreVerFal(cons);
                    cons++;
                    $("#ConsPreguntas").val(cons);
                    ///
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //AGREGAR PREGUNTA  RELACIONE
                AddPregRelacione: function() {
                    edit = "no";
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-8">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '         <div class="col-md-4">' +
                        '           <div class="form-group row">' +
                        '<input type="hidden" id="id-relacione' + cons +
                        '"  value="" />' +
                        '<input type="hidden" id="Tipreguntas' + cons +
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

                        '<div id="DivOpcionesRelacione' + cons + '">' +
                        '<input type="hidden" class="form-control" id="ConsOpcRel" value="2" />' +
                        '<div id="RowRelPreg' + cons + '">' +
                        '                 <div class="row top-buffer" id="RowOpcRelPreg1" style="padding-bottom: 15px;">' +
                        '                      <div class="col-lg-6 border-top-primary">' +
                        ' <input type="hidden" class="form-control" name="Mesnsaje[]" value="1" />' +
                        '        <label class="form-label"><b>Indicaciones:</b></label>' +
                        '                     <textarea cols="80" class="txtareaM" id="summernoteMensaje1" name="txtopcpreg[]"' +
                        '                        rows="3"></textarea>' +
                        '     </div>' +
                        '                      <div class="col-lg-6 border-top-primary">' +
                        ' <input type="hidden" class="form-control" name="respuestas[]" value="1" />' +
                        '        <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                        '                     <textarea cols="80" class="txtareaR" id="summernoteRespuesta1" name="txtopcResp[]"' +
                        '                        rows="3"></textarea>' +
                        '     </div>' +
                        '      </div>' +
                        '   </div>' +
                        '   <div class="row" id="divaddpar' + cons + '">' +
                        '  <button id="AddOpcPre" onclick="$.AddOpcionPar(' + cons +
                        ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Par</button> ' +
                        '</div>' +
                        ' <div class="row">' +
                        '  <label class="form-label pt-2"><b>Respuestas Adicionales:</b></label>' +
                        '<input type="hidden" class="form-control" id="ConsOpcRelAdd" value="1" />' +
                        '</div>' +
                        ' <div class="row" id="DivRespAdd' + cons + '"></div>' +
                        '<div class="row" id="divaddpre">' +
                        '  <button  onclick="$.AddOpcionRespAdd(' + cons +
                        ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Respuesta</button> ' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '      </div>' +
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalRelacione(' + cons +
                        ');" id="Btn-guardarPreg' + cons +
                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-EditPreg' + cons +
                        '"  style="display:none;" onclick="$.EditPreguntasRelacione(' + cons +
                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-ElimPreg' + cons +
                        '" onclick="$.DelPreguntasRelacione(' + cons +
                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#div-evaluaciones").append(Preguntas);

                    $.hab_ediEnunRel();
                    $.hab_ediMensaje("1");
                    $.hab_ediRespuesta("1");
                    cons++;
                    $("#ConsPreguntas").val(cons);
                    ///
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //AGREGAR ARCHIVO
                AddPregArchivo: function() {
                    edit = "no";
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-8">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '         <div class="col-md-4">' +
                        '           <div class="form-group row">' +
                        '<input type="hidden" id="id-taller' + cons +
                        '"  value="" />' +
                        '<input type="hidden" id="Tipreguntas' + cons +
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
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalTaller(' + cons +
                        ');" id="Btn-guardarPreg' + cons +
                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-EditPreg' + cons +
                        '"  style="display:none;" onclick="$.EditPreguntasTaller(' + cons +
                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-ElimPreg' + cons +
                        '" onclick="$.DelPreguntasTaller(' + cons +
                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#div-evaluaciones").append(Preguntas);

                    cons++;
                    $("#ConsPreguntas").val(cons);
                    ///
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //AGREGAR VIDO
                AddVideo: function() {
                    edit = "no";
                    $("#MensInf").hide();

                    var Preguntas = '<div id="Video" style="padding-bottom: 10px;">' +
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
                        '    <input id="archiVideo" accept="video/*"  name="archiVideo" type="file">' +
                        '    <span class="file-custom"></span>' +
                        ' </label>' +
                        '         <br>' +
                        '</div>' +
                        '</div>' +
                        '      </div>' +
                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                        '    <button type="button" onclick="$.GuardarEvalVideo();" id="Btn-guardarVideo"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                        '    <button type="button" id="Btn-Editvideo"  style="display:none;" onclick="$.EditEvalVideo()" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                        '    <button type="button" id="Btn-EliVideo" onclick="$.DelEvalVideo()" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                        '</div>' +
                        '   </div>' +
                        '</div>';

                    $("#vid-adjunto").append(Preguntas);

                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

                },
                //EDITAR PREGUNTA ABIERTA
                EditPreguntasEnsay: function(cons) {
                    if (edit === "si") {
                        var form = $("#formAuxiliarEval");
                        var id = $("#id-pregensay" + cons).val();

                        var preg = "";
                        var punt = "";
                        var comp = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipPregunta' id='TipPregunta' value='PREGENSAY'>"
                        );
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {

                                punt = respuesta.PregEnsayo.puntaje;
                                preg = respuesta.PregEnsayo.pregunta;
                            }
                        });

                        let puntPre = punt;
                        let puntmax = $("#Punt_Max").val();
                        let total = parseInt(puntmax) - parseInt(puntPre);
                        $("#Punt_Max").val(total);

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                            '         <div class="row">' +
                            '            <div class="col-md-8">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '         <div class="col-md-4">' +
                            '           <div class="form-group row">' +
                            '<input type="hidden" id="id-pregensay' + cons +
                            '"  value="' + id + '" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PREGENSAY" />' +
                            '            <div class="col-md-12 right">' +
                            '<div id="PuntEnsay' + cons + '">' +
                            '    <fieldset >' +
                            '        <div class="input-group">' +
                            '          <input type="text" class="form-control" id="puntaje"' +
                            '    name="puntaje" value="' + punt +
                            '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
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
                            '        <label class="form-label">Contenido de Pregunta:</label>' +
                            '<div id="PregEnsay' + cons + '">' +
                            '         <textarea cols="80" id="summernote_pregensay' + cons +
                            '" name="summernote_pregensay" rows="3"></textarea>' +
                            '         <br>' +
                            '</div>' +
                            '</div>' +
                            '      </div>' +
                            '<div class="form-group"  style="margin-bottom: 0px;">' +
                            '    <button type="button" onclick="$.GuardarEvalEnsayo(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '" onclick="$.EditPreguntasEnsay(' + cons +
                            ')" style="display:none;" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DelPreguntasEnsay(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';

                        $("#Preguntas" + cons).html(Preguntas);
                        $.hab_edipre(cons);
                        $('#summernote_pregensay' + cons).val(preg);
                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //EDITAR PREGUNTA COMPLETE
                EditPreguntascomplete: function(cons) {
                    if (edit === "si") {


                        var form = $("#formAuxiliarEval");
                        var id = $("#id-pregcomplete" + cons).val();

                        var opci = "";
                        var parr = "";
                        var punt = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipPregunta' id='TipPregunta' value='COMPLETE'>"
                        );
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {

                                opci = respuesta.PregComple.opciones;
                                parr = respuesta.PregComple.parrafo;
                                punt = respuesta.PregComple.puntaje
                            }
                        });

                        let puntPre = punt;
                        let puntmax = $("#Punt_Max").val();
                        let total = parseInt(puntmax) - parseInt(puntPre);
                        $("#Punt_Max").val(total);

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                            '         <div class="row">' +
                            '            <div class="col-md-8">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '         <div class="col-md-4">' +
                            '           <div class="form-group row">' +
                            '<input type="hidden" id="id-pregcomplete' + cons +
                            '"  value="' + id + '" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="COMPLETE" />' +
                            '            <div class="col-md-12 right">' +
                            '<div id="PuntComplete' + cons + '">' +
                            '    <fieldset >' +
                            '        <div class="input-group">' +
                            '          <input type="text" class="form-control" id="puntaje"' +
                            '    name="puntaje" value="' + punt +
                            '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
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
                            '         <textarea cols="80" id="summernoteCompPar' + cons +
                            '" name="summernoteCompPar" rows="3"></textarea>' +
                            '         <br>' +
                            '</div>' +
                            '</div>' +
                            '      </div>' +
                            '<div class="form-group"  style="margin-bottom: 0px;">' +
                            '    <button type="button" onclick="$.GuardarEvalComplete(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPreguntascomplete(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DelPreguntascomplete(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#Preguntas" + cons).html(Preguntas);
                        $("#cb_Opciones").select2({
                            tags: true,
                            language: {
                                noResults: function() {
                                    return 'Debe de Ingresar las Opciones para completar el parrafo.';
                                },
                            }
                        });
                        $.hab_ediContCompPar(cons);
                        var resp = opci.split(",");
                        var options;
                        $.each(resp, function(index, value) {
                            options += '<option value="' + value + '">' + value + '</option>';
                        });

                        $("#cb_Opciones").html(options);

                        $("#cb_Opciones").val(resp).change();
                        $('#summernoteCompPar' + cons).val(parr);
                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //EDITAR PREGUNTA COMPLETE
                EditPreguntasOpcMult: function(cons) {
                    if (edit === "si") {

                        var form = $("#formAuxiliarEval");
                        var id = $("#id-preopcmult" + cons).val();

                        var opci = "";
                        var parr = "";
                        var punt = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipPregunta' id='TipPregunta' value='OPCMULT'>"
                        );
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        var Preguntas = "";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {
                                let puntPre = respuesta.PregMult.puntuacion;
                                let puntmax = $("#Punt_Max").val();
                                let total = parseInt(puntmax) - parseInt(puntPre);
                                $("#Punt_Max").val(total);

                                Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-8">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-4">' +
                                    '           <div class="form-group row">' +
                                    '<input type="hidden" id="id-preopcmult' + cons +
                                    '"  value="' + id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
                                    '"  value="OPCMULT" />' +
                                    '            <div class="col-md-12 right">' +
                                    '<div id="PuntMultiple' + cons + '">' +
                                    '    <fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" class="form-control" id="puntaje"' +
                                    '    name="puntaje" value="' + puntPre +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
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
                                    '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                    '<div id="DivOpcionesMultiples' + cons +
                                    '"><div id="RowMulPreg1">';
                                var x = 1;
                                $.each(respuesta.OpciMult, function(k, item) {
                                    Preguntas +=
                                        '<div class="row top-buffer" id="RowOpcPreg1' +
                                        x + '" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-11">' +
                                        '                            <div class="input-group" style="padding-bottom: 10px;">' +
                                        '                            <div class="input-group-prepend" style="width: 100%;">' +
                                        '                              <div class="input-group-text">';
                                    if (item.correcta === "no") {
                                        Preguntas +=
                                            '<input aria-label="Checkbox for following text input" id="checkopcpreg1' +
                                            x + '"' +
                                            '                              name="RadioOpcPre[]" onclick="$.selCheck( ' +
                                            x + ');" value="off"' +
                                            '                            type="radio">';
                                    } else {
                                        Preguntas +=
                                            '<input aria-label="Checkbox for following text input" checked id="checkopcpreg1' +
                                            x + '"' +
                                            '                              name="RadioOpcPre[]" onclick="$.selCheck( ' +
                                            x + ');" value="off"' +
                                            '                            type="radio">';
                                    }

                                    Preguntas +=
                                        '<input type="hidden" id="OpcCorecta' + x +
                                        '" name="OpcCorecta[]" value="' +
                                        item.correcta + '" />' +
                                        '                      </div>' +
                                        '                     <textarea cols="80" id="summernoteOpcPreg' +
                                        x + '" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '                </div>' +
                                        '          </div>' +
                                        '     </div>' +
                                        '     <div class="col-lg-1">';
                                    if (x > 1) {
                                        Preguntas +=
                                            ' <button type="button" onclick="$.DelOpcPreg(' +
                                            x +
                                            ')" class="btn btn-icon btn-outline-warning btn-social-icon btn-sm"><i class="fa fa-trash"></i></button>';

                                    }
                                    Preguntas += '       </div>' +
                                        '      </div>';
                                    x++;
                                });

                                Preguntas +=
                                    '<input type="hidden" class="form-control" id="ConsOpcMu' +
                                    cons + '" value="' +
                                    x + '" /></div>  <div class="row">' +
                                    '  <button id="AddOpcPre" onclick="$.AddOpcion(1);" type="button" class="btn mr-1 mb-1 btn-success"><i class="fa fa-plus"></i> Agregar Opcion</button> ' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '<div class="form-group"  style="margin-bottom: 0px;">' +
                                    '    <button type="button" onclick="$.GuardarEvalOpcMult(' +
                                    cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPreguntasOpcMult(' +
                                    cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DelPreguntasOpcMult(' + cons +
                                    ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                    '</div>' +
                                    '   </div>' +
                                    '</div>';

                                $("#Preguntas" + cons).html(Preguntas);

                                $.hab_ediPreMul("1");

                                $('#summernotePreg1').val(respuesta.PregMult.pregunta);
                                var j = 1;
                                var y = 1;
                                $.each(respuesta.OpciMult, function(k, item) {

                                    $.hab_ediPreOpcMul(y);
                                    $('#summernoteOpcPreg' + y)
                                        .val(item.opciones);

                                    y++;

                                });
                                $("#ConsOpcMul").val(y);

                            }
                        });

                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //EDITAR PREGUNTA VERDADERO Y FALSO
                EditPreguntasVerFal: function(cons) {
                    if (edit === "si") {
                        var form = $("#formAuxiliarEval");
                        var id = $("#id-pregverfal" + cons).val();
                        var preg = "";
                        var punt = "";
                        var opci = "";
                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipPregunta' id='TipPregunta' value='VERFAL'>"
                        );
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {
                                punt = respuesta.PregVerFal.puntaje;
                                preg = respuesta.PregVerFal.pregunta;
                                resp = respuesta.PregVerFal.respuesta;
                            }
                        });

                        let puntPre = punt;
                        let puntmax = $("#Punt_Max").val();
                        let total = parseInt(puntmax) - parseInt(puntPre);
                        $("#Punt_Max").val(total);

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                            '         <div class="row">' +
                            '            <div class="col-md-8">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '         <div class="col-md-4">' +
                            '           <div class="form-group row">' +
                            '<input type="hidden" id="id-pregverfal' + cons +
                            '"  value="' + id + '" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="VERFAL" />' +
                            '            <div class="col-md-12 right">' +
                            '<div id="PuntVerFal' + cons + '">' +
                            '    <fieldset >' +
                            '        <div class="input-group">' +
                            '          <input type="text" class="form-control" id="puntaje"' +
                            '    name="puntaje" value="' + puntPre +
                            '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
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
                            '         <textarea cols="80" id="summernote_pregverdFals' + cons +
                            '" name="summernote_pregverdFals" rows="3"></textarea>' +
                            '         <br>' +
                            '</div>' +
                            '<div class="col-md-4 border-bottom-cyan" id="CheckResp' + cons + '"  >' +
                            '           <div class="form-group row">' +
                            '<div class="col-md-12">' +
                            '    <fieldset >' +
                            '        <div class="input-group">';
                        if (resp === "si") {
                            Preguntas +=
                                '          <input  name="radpregVerFal[]" checked="" value="si" type="radio">';
                        } else {
                            Preguntas +=
                                '          <input  name="radpregVerFal[]"  value="si" type="radio">';
                        }
                        Preguntas +=
                            '          <div class="input-group-append" style="margin-left:5px;">' +
                            '            <span  id="basic-addon2">Verdadero</span>' +
                            '          </div>' +
                            '        </div>' +
                            '      </fieldset>' +
                            '</div>' +
                            '<div  class="col-md-12">' +
                            '    <fieldset >' +
                            '        <div class="input-group">';
                        if (resp === "no") {
                            Preguntas +=
                                '          <input  name="radpregVerFal[]"  checked="" value="no" type="radio">';

                        } else {
                            Preguntas +=
                                '          <input  name="radpregVerFal[]"  value="no" type="radio">';

                        }

                        Preguntas +=
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
                            '<div class="form-group"  style="margin-bottom: 0px;">' +
                            '    <button type="button" onclick="$.GuardarEvalVerFal(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPreguntasVerFal(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DelPreguntasVerFal(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';

                        $("#Preguntas" + cons).html(Preguntas);

                        $.hab_edipreVerFal(cons);
                        $('#summernote_pregverdFals' + cons).val(preg);
                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //EDITAR PREGUNTA RELACIONE
                EditPreguntasRelacione: function(cons) {
                    if (edit === "si") {
                        var form = $("#formAuxiliarEval");
                        var id = $("#id-relacione" + cons).val();

                        var preg = "";
                        var punt = "";
                        var comp = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipPregunta' id='TipPregunta' value='RELACIONE'>"
                        );
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        var Preguntas = "";

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {
                                punt = respuesta.PregRelacione.puntaje;

                                let puntPre = punt;
                                let puntmax = $("#Punt_Max").val();
                                let total = parseInt(puntmax) - parseInt(puntPre);
                                $("#Punt_Max").val(total);

                                Preguntas += '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-8">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-4">' +
                                    '           <div class="form-group row">' +
                                    '<input type="hidden" id="id-relacione' + cons +
                                    '"  value="' + id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
                                    '"  value="RELACIONE" />' +
                                    '            <div class="col-md-12 right">' +
                                    '<div id="PuntRelacione' + cons + '">' +
                                    '    <fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" class="form-control" id="puntaje"' +
                                    '    name="puntaje" value="' + punt +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
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

                                    '<div id="DivOpcionesRelacione' + cons + '">' +
                                    '<input type="hidden" class="form-control" id="ConsOpcRel" value="2" />' +
                                    '<div id="RowRelPreg' + cons + '">';

                                $.each(respuesta.PregRelIndi, function(k, item) {
                                    Preguntas +=
                                        '<div class="row top-buffer" id="RowOpcRelPreg' +
                                        j + '" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-6 border-top-primary">' +
                                        ' <input type="hidden" class="form-control" name="Mesnsaje[]" value="' +
                                        j + '" />' +
                                        '        <label class="form-label"><b>Indicaciones:</b></label>' +
                                        '                     <textarea cols="80" id="summernoteMensaje' +
                                        j + '" name="txtopcpreg[]"' +
                                        '                        rows="3"></textarea>' +
                                        '     </div>' +
                                        '                      <div class="col-lg-6 border-top-primary">' +
                                        ' <input type="hidden" class="form-control" name="respuestas[]" value="' +
                                        j + '" />' +
                                        '        <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                        '                     <textarea cols="80" id="summernoteRespuesta' +
                                        j + '" name="txtopcResp[]"' +
                                        '                        rows="3"></textarea>' +
                                        '     </div>' +
                                        '     <div class="col-lg-12 pt-2">' +
                                        '<button type="button" onclick="$.DelOpcRelacione(' +
                                        j +
                                        ')" class="btn mr-1 mb-1 btn-success btn-sm float-right"><i class="fa fa-trash"></i> Eliminar Par</button>' +
                                        '     </div>' +
                                        '      </div>';
                                    j++;
                                });


                                Preguntas += '   </div>' +
                                    '   <div class="row" id="divaddpar' + cons + '">' +
                                    '  <button id="AddOpcPre" onclick="$.AddOpcionPar(' +
                                    cons +
                                    ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Par</button> ' +
                                    '</div>' +
                                    ' <div class="row">' +
                                    '  <label class="form-label pt-2"><b>Respuestas Adicionales:</b></label>' +
                                    '<input type="hidden" class="form-control" id="ConsOpcRelAdd" value="1" />' +
                                    '</div>' +
                                    ' <div class="row" id="DivRespAdd' + cons + '">';
                                j = 1;
                                $.each(respuesta.PregRelRespAdd, function(k, item) {

                                    Preguntas +=
                                        '<div class="row top-buffer" id="RowOpcRelPregAdd' +
                                        j +
                                        '" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-6 border-top-primary">' +
                                        '     </div>' +
                                        ' <div class="col-lg-6 border-top-primary">' +
                                        ' <input type="hidden" class="form-control"  name="respuestas[]" value="-" />' +
                                        '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                        '   <textarea cols="80" id="summernoteRespuestaAdd' +
                                        j +
                                        '" name="txtopcResp[]" rows="3"></textarea>' +
                                        '     </div>' +
                                        '     <div class="col-lg-12 pt-2">' +
                                        '<button type="button" onclick="$.DelOpcRelacioneAdd(' +
                                        j +
                                        ')" class="btn mr-1 mb-1 btn-success btn-sm float-right"><i class="fa fa-trash"></i> Eliminar Respuesta</button>' +
                                        "     </div>" +
                                        '      </div>';
                                    j++;
                                });

                                Preguntas += '</div>' +
                                    '<div class="row" id="divaddpre' + cons + '">' +
                                    '  <button  onclick="$.AddOpcionRespAdd(' + cons +
                                    ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Respuesta</button> ' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '<div class="form-group"  style="margin-bottom: 0px;">' +
                                    '    <button type="button" onclick="$.GuardarEvalRelacione(' +
                                    cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPreguntasRelacione(' +
                                    cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DelPreguntasRelacione(' + cons +
                                    ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                    '</div>' +
                                    '   </div>' +
                                    '</div>';

                                $("#Preguntas" + cons).html(Preguntas);
                                j = 1;

                                $.hab_ediEnunRel();
                                $("#EnuncRelacione").val(respuesta.PregRelacione.enunciado);

                                $.each(respuesta.PregRelIndi, function(k, item) {
                                    $.hab_ediMensaje(j);
                                    $('#summernoteMensaje' + j).val(item
                                        .definicion);
                                    j++;
                                    $("#ConsOpcRel").val(j);
                                });
                                j = 1;
                                $.each(respuesta.PregRelResp, function(k, item) {
                                    if (item.correcta !== "-") {
                                        $.hab_ediRespuesta(j);
                                        $('#summernoteRespuesta' + j).val(item
                                            .respuesta);
                                        j++;
                                    }

                                });
                                j = 1;
                                $.each(respuesta.PregRelRespAdd, function(k, item) {
                                    $.hab_ediRespuestaAdd(j);
                                    $('#summernoteRespuestaAdd' + j).val(item
                                        .respuesta);
                                    j++;

                                    $("#ConsOpcRelAdd").val(j);
                                });


                                cons++;
                                $("#ConsPreguntas").val(cons);


                            }
                        });
                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //EDITAR PREGUNTA ABIERTA
                EditPreguntasTaller: function(cons) {
                    if (edit === "si") {
                        var form = $("#formAuxiliarEval");
                        var id = $("#id-taller" + cons).val();

                        var preg = "";
                        var punt = "";
                        var comp = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipPregunta' id='TipPregunta' value='TALLER'>"
                        );
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {
                                punt = respuesta.PregTaller.puntaje;
                            }
                        });

                        let puntPre = punt;
                        let puntmax = $("#Punt_Max").val();
                        let total = parseInt(puntmax) - parseInt(puntPre);
                        $("#Punt_Max").val(total);

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                            '         <div class="row">' +
                            '            <div class="col-md-8">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '         <div class="col-md-4">' +
                            '           <div class="form-group row">' +
                            '<input type="hidden" id="id-taller' + cons +
                            '"  value="' + id + '" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="TALLER" />' +
                            '            <div class="col-md-12 right">' +
                            '<div id="PuntTaller' + cons + '">' +
                            '    <fieldset >' +
                            '        <div class="input-group">' +
                            '          <input type="text" class="form-control" id="puntaje"' +
                            '    name="puntaje" value="' + punt +
                            '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
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
                            '             <label>Seleccionar Archivos</label>' +
                            '<label id="projectinput7" class="file center-block"><br>' +
                            '    <input id="archiTaller"  name="archiTaller" type="file">' +
                            '    <span class="file-custom"></span>' +
                            ' </label>' +
                            '         <br>' +
                            '</div>' +
                            '</div>' +
                            '      </div>' +
                            '<div class="form-group"  style="margin-bottom: 0px;">' +
                            '    <button type="button" onclick="$.GuardarEvalTaller(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPreguntasTaller(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DelPreguntasTaller(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';

                        $("#Preguntas" + cons).html(Preguntas);
                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //EDITAR VIDEO
                EditEvalVideo: function(cons) {
                    if (edit === "si") {
                        var id = $("#id-video").val();

                        var Preguntas = '<div id="Video" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                            '         <div class="row">' +
                            '            <div class="col-md-8">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '<input type="hidden" id="id-video" name="id-video" value="' + id + '" />' +
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
                            '    <input id="archiVideo" accept="video/*"  name="archiVideo" type="file">' +
                            '    <span class="file-custom"></span>' +
                            ' </label>' +
                            '         <br>' +
                            '</div>' +
                            '</div>' +
                            '      </div>' +
                            '<div class="form-group"  style="margin-bottom: 0px;">' +
                            '    <button type="button" onclick="$.GuardarEvalVideo();" id="Btn-guardarVideo"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-Editvideo"  style="display:none;" onclick="$.EditEvalVideo()" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-EliVideo" onclick="$.DelEvalVideo()" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';

                        $("#vid-adjunto").html(Preguntas);
                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //ELIMINAR PREGUNTA ABIERTA
                DelPreguntasEnsay: function(id_fila) {
                    edit = "si";
                    if ($("#id-pregensay" + id_fila).val() !== "") {
                        var preg = $("#id-pregensay" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var IdEval = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        $("#ideval").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='" + TipPreg +
                            "'>");
                        form.append("<input type='hidden' name='ideval' id='ideval' value='" + IdEval +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        let puntPre = $("#PuntEdit" + id_fila)
                                            .val();

                                        let puntmax = $("#Punt_Max").val();
                                        let total = parseInt(puntmax) - parseInt(
                                            puntPre);
                                        $("#Punt_Max").val(total);

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                //ELIMINAR PREGUNTA COMPLETE
                DelPreguntascomplete: function(id_fila) {
                    edit = "si"
                    if ($("#id-pregcomplete" + id_fila).val() !== "") {
                        var preg = $("#id-pregcomplete" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var IdEval = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        $("#ideval").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='" + TipPreg +
                            "'>");
                        form.append("<input type='hidden' name='ideval' id='ideval' value='" + IdEval +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        let puntPre = $("#PuntEdit" + id_fila)
                                            .val();
                                        let puntmax = $("#Punt_Max").val();
                                        let total = parseInt(puntmax) - parseInt(
                                            puntPre);
                                        $("#Punt_Max").val(total);

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                //ELIMINAR PREGUNTA OPCION MULTIPLE
                DelPreguntasOpcMult: function(id_fila) {
                    edit = "si"
                    if ($("#id-preopcmult" + id_fila).val() !== "") {
                        var preg = $("#id-preopcmult" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var IdEval = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        $("#ideval").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='" + TipPreg +
                            "'>");
                        form.append("<input type='hidden' name='ideval' id='ideval' value='" + IdEval +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        let puntPre = $("#PuntEdit" + id_fila)
                                            .val();
                                        let puntmax = $("#Punt_Max").val();
                                        let total = parseInt(puntmax) - parseInt(
                                            puntPre);
                                        $("#Punt_Max").val(total);

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";
                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                //ELIMINAR PREGUNTA VERDADERO Y FALSO
                DelPreguntasVerFal: function(id_fila) {
                    edit = "si";
                    if ($("#id-pregverfal" + id_fila).val() !== "") {
                        var preg = $("#id-pregverfal" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var IdEval = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        $("#ideval").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='" + TipPreg +
                            "'>");
                        form.append("<input type='hidden' name='ideval' id='ideval' value='" + IdEval +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        let puntPre = $("#PuntEdit" + id_fila)
                                            .val();

                                        let puntmax = $("#Punt_Max").val();
                                        let total = parseInt(puntmax) - parseInt(
                                            puntPre);
                                        $("#Punt_Max").val(total);

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                //ELIMINAR PREGUNTA RELACIONE
                DelPreguntasRelacione: function(id_fila) {
                    edit = "si";
                    if ($("#id-relacione" + id_fila).val() !== "") {
                        var preg = $("#id-relacione" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var IdEval = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        $("#ideval").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='" + TipPreg +
                            "'>");
                        form.append("<input type='hidden' name='ideval' id='ideval' value='" + IdEval +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";
                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        let puntPre = $("#PuntEdit" + id_fila)
                                            .val();

                                        let puntmax = $("#Punt_Max").val();
                                        let total = parseInt(puntmax) - parseInt(
                                            puntPre);
                                        $("#Punt_Max").val(total);

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";
                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                //ELIMINAR TALLER
                DelPreguntasTaller: function(id_fila) {
                    edit = "si";
                    if ($("#id-taller" + id_fila).val() !== "") {
                        var preg = $("#id-taller" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var IdEval = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        $("#ideval").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='" + TipPreg +
                            "'>");
                        form.append("<input type='hidden' name='ideval' id='ideval' value='" + IdEval +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        let puntPre = $("#PuntEdit" + id_fila)
                                            .val();

                                        let puntmax = $("#Punt_Max").val();
                                        let total = parseInt(puntmax) - parseInt(
                                            puntPre);
                                        $("#Punt_Max").val(total);

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                //ELIMINAR VIDEO
                DelEvalVideo: function(id_fila) {
                    edit = "si";
                    if ($("#id-video").val() !== "") {
                        var preg = $("#Id_Eval").val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#idtippreg").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='tip' id='idtippreg' value='VIDEO'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar este Video?";

                        Swal.fire({
                            title: 'Gestionar Evaluaciones',
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
                                            title: "Gestionar Evaluaciones",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

                                        $('#Video').remove();
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Gestionar Evaluaciones',
                                            mensaje,
                                            'warning'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Video').remove();
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }

                },
                hab_edipreVerFal: function() {
                    CKEDITOR.replace('summernote_pregverdFals', {
                        width: '100%',
                        height: 100
                    });

                },
                hab_ediRelacione: function() {

                    CKEDITOR.replace('summernoteRelacione', {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediMensaje: function(cons) {
                    CKEDITOR.replace('summernoteMensaje' + cons, {
                        width: '100%',
                        height: 100
                    });

                },
                hab_ediEnunRel: function() {
                    CKEDITOR.replace('EnuncRelacione', {
                        width: '100%',
                        height: 50
                    });

                },
                hab_ediRespuesta: function(cons) {
                    CKEDITOR.replace('summernoteRespuesta' + cons, {
                        width: '100%',
                        height: 100
                    });

                },
                hab_ediRespuestaAdd: function(cons) {
                    CKEDITOR.replace('summernoteRespuestaAdd' + cons, {
                        width: '100%',
                        height: 100
                    });

                },
                hab_ediGruPreg: function(cons) {
                    CKEDITOR.replace('summernoteGruPreg' + cons, {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediOpc: function(cons) {
                    CKEDITOR.replace('summernoteOpc' + cons, {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediPreMul: function(cons) {
                    CKEDITOR.replace('summernotePreg' + cons, {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediPreOpcMul: function(preg) {

                    CKEDITOR.replace('summernoteOpcPreg' + preg, {
                        width: '100%',
                        height: 100
                    });

                },
                hab_ediContCompPar: function(cons) {
                    CKEDITOR.replace('summernoteCompPar' + cons, {
                        width: '100%',
                        height: 100
                    });
                },
                hab_edipre: function(id) {
                    CKEDITOR.replace('summernote_pregensay' + id, {
                        width: '100%',
                        height: 100
                    });
                },
                DelOpcRelacione: function(id) {
                    $('#RowOpcRelPreg' + id).remove();

                },

                DelOpcRelacioneAdd: function(id) {
                    $('#RowOpcRelPregAdd' + id).remove();

                },

                DelOpcPreg: function(id) {
                    $('#RowOpcPreg1' + id).remove();

                },
                AddOpcion: function(id) {
                    var cons = $("#ConsOpcMul").val();

                    var preguntas = "<div class='row top-buffer' id='RowOpcPreg1" + cons +
                        "' style='padding-bottom: 15px;'>" +
                        "    <div class='col-lg-11' >" +
                        "       <div class='input-group' style='padding-bottom: 10px;' >" +
                        "            <div class='input-group-prepend' style='width: 100%;''>" +
                        "                <div class='input-group-text'>" +
                        "                    <input aria-label='Checkbox for following text input'value='off' name='RadioOpcPre[]'  onclick='$.selCheck(" +
                        cons + ");' id='checkopcpreg1" + cons +
                        "'  type='radio'>" +
                        "                    <input type='hidden'  id='OpcCorecta" + cons +
                        "' name='OpcCorecta[]'  value='no'/>" +
                        "             </div>" +
                        "         <textarea cols='80' id='summernoteOpcPreg" + cons +
                        "' name='txtopcpreg[]' rows='3'></textarea>" +
                        "        </div>" +
                        "        </div>" +
                        "     </div>" +
                        "     <div class='col-lg-1'>" +
                        "         <button type='button' onclick='$.DelOpcPreg(" + cons +
                        ")' class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        "     </div>" +
                        " </div>";

                    $("#RowMulPreg1").append(preguntas);
                    $("#ConsOpcMul").val(parseFloat(cons) + 1);

                    $.hab_ediPreOpcMul(cons);
                },

                AddOpcionPar: function(id) {
                    var cons = $("#ConsOpcRel").val();


                    var preguntas = '<div class="row top-buffer" id="RowOpcRelPreg' + cons +
                        '" style="padding-bottom: 15px;">' +
                        '                      <div class="col-lg-6 border-top-primary">' +
                        ' <input type="hidden" class="form-control" name="Mesnsaje[]" value="' + cons +
                        '" />' +
                        '        <label class="form-label"><b>Mensaje:</b></label>' +
                        '                     <textarea cols="80" class="txtareaM" id="summernoteMensaje' +
                        cons +
                        '" name="txtopcpreg[]"' +
                        '                        rows="3"></textarea>' +
                        '     </div>' +
                        ' <div class="col-lg-6 border-top-primary">' +
                        ' <input type="hidden" class="form-control" name="respuestas[]" value="' +
                        cons + '" />' +
                        '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                        '   <textarea cols="80" class="txtareaR" id="summernoteRespuesta' + cons +
                        '" name="txtopcResp[]" rows="3"></textarea>' +
                        '     </div>' +
                        '     <div class="col-lg-12 pt-2">' +
                        '<button type="button" onclick="$.DelOpcRelacione(' + cons +
                        ')" class="btn mr-1 mb-1 btn-success btn-sm float-right"><i class="fa fa-trash"></i> Eliminar Par</button>' +
                        "     </div>" +
                        '      </div>' +
                        " </div>";

                    $("#RowRelPreg" + id).append(preguntas);
                    $("#ConsOpcRel").val(parseFloat(cons) + 1);

                    $.hab_ediMensaje(cons);
                    $.hab_ediRespuesta(cons);
                },
                AddOpcionRespAdd: function(x) {
                    var cons = $("#ConsOpcRelAdd").val();

                    var preguntas = '<div class="row top-buffer" id="RowOpcRelPregAdd' + cons +
                        '" style="padding-bottom: 15px;">' +
                        '                      <div class="col-lg-6 border-top-primary">' +
                        '     </div>' +
                        ' <div class="col-lg-6 border-top-primary">' +
                        ' <input type="hidden" class="form-control" name="respuestas[]" value="-" />' +
                        '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                        '   <textarea cols="80" id="summernoteRespuestaAdd' + cons +
                        '" name="txtopcResp[]" rows="3"></textarea>' +
                        '     </div>' +
                        '     <div class="col-lg-12 pt-2">' +
                        '<button type="button" onclick="$.DelOpcRelacioneAdd(' + cons +
                        ')" class="btn mr-1 mb-1 btn-success btn-sm float-right"><i class="fa fa-trash"></i> Eliminar Respuesta</button>' +
                        "     </div>" +
                        '      </div>' +
                        " </div>";

                    $("#DivRespAdd" + x).append(preguntas);
                    $("#ConsOpcRelAdd").val(parseFloat(cons) + 1);

                    $.hab_ediRespuestaAdd(cons);
                },
                ////////////GUARDAR PREGUNTAS TIPO ENSAYO
                GuardarEvalEnsayo: function(id) {
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    $("#PregConse").val(id);
                    $("#id-pregensay").val($("#id-pregensay" + id).val());
                    var form = $("#formAsigEval");
                    var datos = form.serialize();
                    var url = form.attr("action");
                    $.UpdPunMax();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestión de Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-pregensay" + id).val(respuesta.ContPregEnsayo.id);


                                $("#PuntEnsay" + id).html('<fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" id="PuntEdit' + id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.ContPregEnsayo.puntaje +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>');
                                $("#PregEnsay" + id).html(respuesta.ContPregEnsayo
                                    .pregunta);
                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR PREGUNTAS TIPO COMPLETE
                GuardarEvalComplete: function(id) {
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    $("#PregConse").val(id);
                    $("#id-pregcomplete").val($("#id-pregcomplete" + id).val());
                    var form = $("#formAsigEval");
                    var datos = form.serialize();
                    var url = form.attr("action");
                    $.UpdPunMax();

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestión de Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-pregcomplete" + id).val(respuesta.ContPreComplete
                                    .id);


                                $("#PuntComplete" + id).html('<fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" id="PuntEdit' + id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.ContPreComplete.puntaje +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>');
                                $("#PregOpciones" + id).html(respuesta.ContPreComplete
                                    .opciones);
                                $("#DivParrCompleta" + id).html(respuesta.ContPreComplete
                                    .parrafo);
                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR PREGUNTAS TIPO OPCION MULTIPLE
                GuardarEvalOpcMult: function(id) {
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());

                    $("#IdpreguntaMul").val($("#id-preopcmult" + id).val());
                    $("#PregConse").val(id);

                    let flag = "no";
                    $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                        if ($(elemento).val() == "si") {
                            flag = "si";
                        }
                    });
                    if (flag === "no") {
                        mensaje = "Debe de Seleccionar la Opción Correcta";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $('#Btn-guardarPreg' + id).prop('disabled', true);
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formAsigEval");
                    var datos = form.serialize();
                    var url = form.attr("action");
                    $.UpdPunMax();

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestión de Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $('#Btn-guardarPreg' + id).prop('disabled', false);


                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-preopcmult" + id).val(respuesta.PregOpcMul.id);



                                $("#PuntMultiple" + id).html('<fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" id="PuntEdit' + id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.PregOpcMul.puntuacion +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>');

                                $("#PreguntaMultiple" + id).html(respuesta.PregOpcMul
                                    .pregunta);
                                var opciones = '';

                                $.each(respuesta.OpciPregMul, function(k, item) {
                                    opciones += '<fieldset>';
                                    if (item.correcta === "si") {
                                        opciones +=
                                            '<input type="checkbox" disabled id="input-15" checked>';
                                    } else {
                                        opciones +=
                                            '<input type="checkbox" disabled id="input-15">';
                                    }

                                    opciones += ' <label for="input-15"> ' + item
                                        .opciones + '</label>' +
                                        '</fieldset>';

                                });


                                $("#DivOpcionesMultiples" + id).html(opciones);


                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR PREGUNTAS VERDADERO Y FALSO
                GuardarEvalVerFal: function(id) {
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    $("#PregConse").val(id);
                    $("#id-pregverfal").val($("#id-pregverfal" + id).val());
                    var form = $("#formAsigEval");
                    var datos = form.serialize();
                    var url = form.attr("action");
                    $.UpdPunMax();

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestión de Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-pregverfal" + id).val(respuesta.ContPregVerFal.id);

                                $("#PuntVerFal" + id).html('<fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" id="PuntEdit' + id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.ContPregVerFal.puntaje +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>');
                                $("#PregVerFal" + id).html(respuesta.ContPregVerFal
                                    .pregunta);
                                var Opc = '<div class="form-group row">' +
                                    '<div class="col-md-12">' +
                                    '    <fieldset >' +
                                    '        <div class="input-group">';
                                if (respuesta.ContPregVerFal.respuesta === "si") {
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
                                if (respuesta.ContPregVerFal.respuesta === "no") {
                                    Opc +=
                                        '<input   checked="" value="si" disabled type="radio">';

                                } else {
                                    Opc += '<input  value="si" disabled type="radio">';

                                }
                                Opc +=
                                    '<div class="input-group-append" style="margin-left:5px;">' +
                                    '            <span  id="basic-addon2">Falso</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>' +
                                    '</div>' +
                                    '            </div>';

                                $("#CheckResp" + id).html(Opc);

                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR PREGUNTAS RELACIONE
                GuardarEvalRelacione: function(id) {
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    $("#PregConse").val(id);
                    $("#id-relacione").val($("#id-relacione" + id).val());
                    var form = $("#formAsigEval");
                    var datos = form.serialize();
                    var url = form.attr("action");
                    $.UpdPunMax();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestión de Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-relacione" + id).val(respuesta.PregRel.id);


                                $("#PuntRelacione" + id).html('<fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" id="PuntEdit' + id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.PregRel.puntaje +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>');

                                $("#ConsEnunRel" + id).html(respuesta.PregRel.enunciado);

                                var cons = 1;
                                var preguntas = "";
                                $.each(respuesta.PregRelIndi, function(k, item) {
                                    preguntas +=
                                        '<div class="row top-buffer" id="RowOpcRelPreg' +
                                        id +
                                        '" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-6 border-top-primary">' +
                                        '        <label class="form-label"><b>Mensaje:</b></label>' +
                                        '<div id="mesaje' + id + cons + '"></div>' +
                                        '     </div>' +
                                        ' <div class="col-lg-6 border-top-primary">' +
                                        '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                        '<div id="respuesta' + id + cons +
                                        '"></div>' +
                                        '     </div>' +
                                        '      </div>' +
                                        ' </div>';
                                    cons++;
                                });

                                $("#RowRelPreg" + id).html(preguntas);
                                cons = 1;
                                $.each(respuesta.PregRelIndi, function(k, item) {
                                    $("#mesaje" + id + cons).html(item.definicion);
                                    cons++;
                                });

                                cons = 1;
                                $.each(respuesta.PregRelResp, function(k, item) {
                                    if (item.correcta !== "-") {
                                        $("#respuesta" + id + cons).html(item
                                            .respuesta);
                                        cons++;
                                    }
                                });
                                preguntas = "";
                                cons = 1;
                                $.each(respuesta.PregRelResp, function(k, item2) {
                                    if (item2.correcta === "-") {
                                        preguntas +=
                                            '<div class="row top-buffer" id="RowOpcRelPregAdd' +
                                            id +
                                            '" style="padding-bottom: 15px;width: 100%;">' +
                                            '                      <div class="col-lg-6 border-top-primary">' +
                                            '     </div>' +
                                            ' <div class="col-lg-6 border-top-primary" >' +
                                            '  <label class="form-label"><b>Respuesta Enviada:</b></label>' +
                                            '   <div id="respuestaadd' + id + cons +
                                            '"></div>' +
                                            '     </div>' +
                                            '      </div>' +
                                            " </div>";
                                    }
                                    cons++;
                                });

                                $("#DivRespAdd" + id).html(preguntas);

                                cons = 1;
                                $.each(respuesta.PregRelResp, function(k, item2) {
                                    if (item2.correcta === "-") {
                                        $("#respuestaadd" + cons).html(item2
                                            .respuesta);
                                    }
                                    cons++;
                                });

                                $("#divaddpar" + id).remove();
                                $("#divaddpre" + id).remove();

                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR PREGUNTAS TALLER
                GuardarEvalTaller: function(id) {
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    $("#PregConse").val(id);
                    $("#id-taller").val($("#id-taller" + id).val());
                    var form = $("#formAsigEval");
                    var url = form.attr("action");

                    if (!$('#archiTaller').val()) {
                        mensaje = "Debe Seleccionar el Archivo";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
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
                    Swal.showLoading();

                    $.UpdPunMax();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData($('#formAsigEval')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.hideLoading();
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-taller" + id).val(respuesta.ContPregTaller.id);

                                $("#PuntTaller" + id).html('<fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <input type="text" id="PuntEdit' + id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.ContPregTaller.puntaje +
                                    '" placeholder="Puntaje" aria-describedby="basic-addon2">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Puntos</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>');

                                $("#PregTaller" + id).html(
                                    '<div class="form-group" id="id_verf">' +
                                    ' <label class="form-label " for="imagen">Ver Archivo Cargado:</label>' +
                                    ' <div class="btn-group" role="group" aria-label="Basic example">' +
                                    '   <button id="idimg' + id +
                                    '" type="button" data-archivo="' +
                                    respuesta.ContPregTaller.nom_archivo +
                                    '" onclick="$.MostArc(this.id);" class="btn btn-success"><i' +
                                    '             class="fa fa-search"></i> Ver Archivo</button>' +
                                    '      </div>' +
                                    '       </div>');
                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR VIDEO
                GuardarEvalVideo: function(id) {
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formAsigEval");
                    var url = form.attr("action");

                    if (!$('#archiVideo').val()) {
                        mensaje = "Debe Seleccionar el Archivo";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var rurl = $("#RutEvalVideo").val();
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
                    Swal.showLoading();


                    $.ajax({
                        type: "POST",
                        url: rurl + "/Guardar/VideoEval",
                        data: new FormData($('#formAsigEval')[0]),
                        async: false,
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.hideLoading();
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $("#id-video").val(respuesta.EvPDidact.id);
                                $("#Btn-guardarVideo").hide();
                                $("#Btn-Editvideo").show();
                                $("#div-addpreg").show();
                                $('#Btn-guardarVideo').prop('disabled', false);
                                $("#Btn-guardarVideo").html(
                                    '<i  class="fa fa-save"></i> Guardar');

                                $("#Det_video").html(
                                    '<div class="form-group" id="id_verf">' +
                                    ' <label class="form-label " for="imagen">Ver Archivo Cargado:</label>' +
                                    ' <div class="btn-group" role="group" aria-label="Basic example">' +
                                    '   <button id="idvide" type="button" data-archivo="' +
                                    respuesta.EvPDidact.cont_didactico +
                                    '" onclick="$.Mostvideo(this.id);" class="btn btn-success"><i' +
                                    '             class="fa fa-search"></i> Ver Archivo</button>' +
                                    '      </div>' +
                                    '       </div>');
                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                ////////////GUARDAR Y CERRAR
                GuardarEval: function(id) {
                    var form = $("#formAsigEval");
                    var url = form.attr("action");

                    var rurl = $("#RutEvalVideo").val();
                    if ($("#ConsPreguntas").val() <= 1) {
                        mensaje = "No existe Ninguna Pregunta en la Evaluación";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#titulo").val() === "") {
                        mensaje = "Ingrese el Título";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#clasificacion").val() === "") {
                        mensaje = "Seleccione la Clasificación de la Evaluación";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (edit === "no") {
                        mensaje = "Existe una pregunta sin Guardar, Verifique...";
                        Swal.fire({
                            title: "Gestionar Evaluaciones",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    $.ajax({
                        type: "POST",
                        url: rurl + "ModuloE/GuardarEvalFin",
                        data: new FormData($('#formAsigEval')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#Id_Eval").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $(location).attr('href', rurl +
                                    "/ModuloE/ConsulPrePract/" + $(
                                        "#tema_id").val()
                                )
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Evaluaciones",
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
                UpdPunMax: function() {
                    let puntPre = $("#puntaje").val();
                    let puntmax = $("#Punt_Max").val();
                    let total = parseInt(puntmax) + parseInt(puntPre);
                    $("#Punt_Max").val(total);
                },
                selCheck: function(pre) {

                    $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                        $(elemento).val("no");
                    });

                    if ($('#checkopcpreg1' + pre).prop('checked')) {
                        $('#OpcCorecta' + pre).val("si");
                    } else {
                        $('#OpcCorecta' + pre).val("no");
                    }

                },
                HabTiempo: function() {
                    if ($('#hab_tiempo').prop('checked')) {
                        $('#TEval').prop('readonly', false);
                        $("#TextHabTiempo").val("SI");
                    } else {
                        $('#TEval').prop('readonly', true);
                        $("#TextHabTiempo").val("NO");

                    }
                }
            });
            $.hab_ediRelacione();

            $.CargEvaluacion()
            $.FormTiempo();
        });
    </script>
@endsection
