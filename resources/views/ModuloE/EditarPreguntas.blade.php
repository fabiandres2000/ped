@extends('Plantilla.Principal')
@section('title', 'Editar Preguntas')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIÓN DE BANCO DE PREGUNTAS MÓDULO E</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Preguntas
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
                            <h4 class="card-title">Editar Pregunta</h4>
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
                                @include('ModuloE.FormBancoPregunta', [
                                    'url' => '/ModuloE/GuardarPreguntas/',
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

    {!! Form::open(['url' => '/ModuloE/CargTemas', 'id' => 'formAuxiliarTema']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/CargPartes', 'id' => 'formAuxiliarPartes']) !!}
    {!! Form::close() !!}


    {!! Form::open(['url' => '/ModuloE/Cargcompe_compo', 'id' => 'formAuxiliarCompe']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/CargarPreguntas', 'id' => 'formAuxiliarEvalDet']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/ConsulPregME', 'id' => 'formAuxiliarEval']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/ElimnarPregBancoPreg', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}




@endsection
@section('scripts')
    <script>
        var edit = "no";
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

            $("#Men_Inicio").removeClass("active");
            $("#Men_Modulos_E").addClass("has-sub open");
            $("#Men_ModulosE_addPre").addClass("active");

            var ConPart1 = 1;
            var selectPalabras = [];

            $.extend({

                HabEditEnunciado: function() {
                    CKEDITOR.replace('enunciado', {
                        width: '100%',
                        height: 120
                    });
                },
          

                CargCompe_Compo: function(id) {
                    var form = $("#formAuxiliarCompe");
                    var Asig = $("#asignatura").val();
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
                            $("#competencia" + id).html(respuesta.select_compe);
                            $("#componente" + id).html(respuesta.select_compo);
                        }

                    });
                },
                AddPregOpcMultiple: function() {
                    var cons = parseFloat($("#ConsPreguntas").val());
                    $("#MensInf").hide();

                    if ($("#asignatura").val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Debe seleccionar la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    if ($("#tema").val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Debe seleccionar el Tema.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    var Preguntas = '<div id="Preguntas' + cons + '" style="padding-bottom: 10px;">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                        '         <div class="row">' +
                        '            <div class="col-md-7">' +
                        '             <div class="form-group row">' +
                        '             <div class="col-md-12">' +
                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                        '            </div>' +
                        '           </div>' +
                        '         </div>' +
                        '            <div class="col-md-5">' +
                        '<input type="hidden" id="id-preopcmult' + cons +
                        '"  value="" />' +

                        '         </div>' +

                        '      </div>' +
                        '<div id="DivCompetencia' + cons + '">' +
                        '<div class="row pb-2">' +

                        '<div class="col-md-6">' +
                        '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                        cons + '">' +

                        '    </select>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                        cons + '">' +

                        '    </select>' +
                        '</div>' +
                        '         </div>' +
                        '</div>' +
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
                    $.CargCompe_Compo(cons);
                    cons++;
                    $("#ConsPreguntas").val(cons);
                    $("#div-addpreg").hide();
                    $("#btns_guardar").show();

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

                    if ($("#competencia" + id).val() === "") {
                        mensaje = "Seleccione la Competencia";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#componente" + id).val() === "") {
                        mensaje = "Seleccione el Componente";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (flag === "no") {
                        mensaje = "Debe de Seleccionar la Opción Correcta";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $('#Btn-guardarPreg' + id).prop('disabled', true);
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formBanco");
                    var datos = form.serialize();
                    var url = form.attr("action");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#preg_id").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Módulo E",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $('#Btn-guardarPreg' + id).prop('disabled', false);


                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-preopcmult" + id).val(respuesta.PregOpcMul.id);

                                $("#DivCompetencia" + id).html(
                                    '<div class="row pb-2"><div class="col-md-6"><fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Competencia</span>' +
                                    '          </div>' +
                                    '          <input type="text" disabled id="CompeEdit' +
                                    id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.PregOpcMul.DesCompe +
                                    '" placeholder="Competencia" aria-describedby="basic-addon2">' +

                                    '        </div>' +
                                    '      </fieldset></div>' +
                                    '<div class="col-md-6"><fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Componente</span>' +
                                    '          </div>' +
                                    '          <input type="text" disabled id="CompoEdit' +
                                    id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.PregOpcMul.DesCompo +
                                    '" placeholder="Competencia" aria-describedby="basic-addon2">' +
                                    '        </div>' +
                                    '      </fieldset></div></div>');

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
                                    title: "Gestionar Módulo E",
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

                                Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-7">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-5">' +
                                    '<input type="hidden" id="id-preopcmult' + cons +
                                    '"  value="' + id + '" />' +


                                    '        </div>' +
                                    '      </div>' +
                                    '<div id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +

                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +

                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +

                                    '    </select>' +
                                    '</div>' +
                                    '         </div>' +
                                    '</div>' +
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
                                        '<div class="row top-buffer" id="RowOpcPreg' +
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

                                $.CargCompe_Compo(cons);

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

                                $('#competencia' + cons).val(respuesta.PregMult.competencia)
                                    .trigger('change.select2');

                                $('#componente' + cons).val(respuesta.PregMult.componente)
                                    .trigger('change.select2');
                                $("#ConsOpcMul").val(y);

                            }
                        });

                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //ELIMINAR PREGUNTA OPCION MULTIPLE
                DelPreguntasOpcMult: function(id_fila) {

                    if ($("#id-preopcmult" + id_fila).val() !== "") {
                        var preg = $("#id-preopcmult" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#TipPregu").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                            form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='" + TipPreg +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";


                        Swal.fire({
                            title: 'Gestionar Módulo E',
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
                                            title: "Gestionar Módulo E",
                                            text: respuesta.mensaje,
                                            icon: "success",
                                            button: "Aceptar"
                                        });

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
                                            'Eliminado!',
                                            mensaje,
                                            'success'
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

                    if ($("#ConsPreguntas").val() < 2) {
                        edit = "no"
                    } else {
                        edit = "si"
                    }

                },
                GuardarPregParte1: function(id) {
                    edit = "si";

                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());

                    $("#IdpreguntaPart1").val($("#id-parte1" + id).val());
                    $("#PregConse").val(id);



                    if ($("#competencia" + id).val() === "") {
                        mensaje = "Seleccione la Competencia";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#componente" + id).val() === "") {
                        mensaje = "Seleccione el Componente";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }



                    $('#Btn-guardarPreg' + id).prop('disabled', true);
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formBanco");
                    var datos = form.serialize();
                    var url = form.attr("action");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#preg_id").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Módulo E",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $('#Btn-guardarPreg' + id).prop('disabled', false);


                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();

                                $("#id-parte1" + id).val(respuesta.PregOpcMul.id);

                                $("#DivCompetencia" + id).html(
                                    '<div class="row pb-2"><div class="col-md-6"><fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Competencia</span>' +
                                    '          </div>' +
                                    '          <input type="text" disabled id="CompeEdit' +
                                    id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.DesCompe +
                                    '" placeholder="Competencia" aria-describedby="basic-addon2">' +
                                    '        </div>' +
                                    '      </fieldset></div>' +
                                    '<div class="col-md-6"><fieldset >' +
                                    '        <div class="input-group">' +
                                    '          <div class="input-group-append">' +
                                    '            <span class="input-group-text" id="basic-addon2">Componente</span>' +
                                    '          </div>' +
                                    '          <input type="text" disabled id="CompoEdit' +
                                    id +
                                    '" class="form-control"' +
                                    '     value="' + respuesta.DesCompo +
                                    '" placeholder="Competencia" aria-describedby="basic-addon2">' +
                                    '        </div>' +
                                    '      </fieldset></div></div>');

                                $("#PregOpciones" + id).html(respuesta.PregOpcMul
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

                                $("#DivPreg" + id).html(opciones);
                                $("#divaddpre" + id).hide();


                                edit = "si";
                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Módulo E",
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
                EditPregParte1: function(cons) {
                    if (edit === "si") {

                        var form = $("#formAuxiliarEval");
                        var id = $("#id-parte1" + cons).val();

                        var tipPreg = $("#Tipreguntas" + cons).val();

                        var opci = "";
                        var parr = "";
                        var punt = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
                        form.append(
                            "<input type='hidden' name='TipoPregunta' id='TipPregunta' value='" +
                            tipPreg + "'>");
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

                                var Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte +
                                    '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-4">' +
                                    '           <div class="form-group row">' +
                                    '<input type="hidden" id="id-parte1' + cons +
                                    '"  value="' + respuesta.Parte1.id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
                                    '"  value="PARTE 1" />' +
                                    '            <div class="col-md-12 right">' +
                                    '            </div>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </div>' +
                                    '<div id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +

                                    '<div class="col-md-6">' +
                                    '        <label class="form-label"><b>Competencia:</b></label>' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +

                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '        <label class="form-label"><b>Componente:</b></label>' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +

                                    '    </select>' +
                                    '</div>' +
                                    '         </div>' +
                                    '</div>' +
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
                                    '<div id="DivPreg' + cons + '">';

                                var x = 1;
                                $.each(respuesta.Preguntas, function(k, item) {
                                    Preguntas += '<div id="RowRelPreg' + x + '">' +
                                        '                 <div class="row top-buffer" id="RowOpcRelPreg1" style="padding-bottom: 15px;">' +
                                        '                      <div class="col-lg-6 border-top-primary pt-1">' +
                                        ' <input type="hidden" class="form-control" id="Preg' +
                                        x + '" name="Preg[]" value="' + item
                                        .pregunta + '" />' +
                                        '        <label class="form-label"><b>Pregunta 1:</b></label>' +
                                        '    <input type="text" class="form-control" name="Pregunta[]" value="' +
                                        item.pregunta + '" />' +
                                        '     </div>' +
                                        '                      <div class="col-lg-4 border-top-primary  pt-1">' +
                                        ' <input type="hidden" class="form-control"  name="Palabra[]" value="' +
                                        item.respueesta + '" />' +
                                        '        <label class="form-label"><b>Respuesta:</b></label>' +
                                        '    <select class="form-control select2 SelecPalabras"  style="width: 100%;" data-placeholder="Seleccione la Respuesta"' +
                                        '  id="cb_respuesta' + x +
                                        '" name="cb_respuesta[]">' +
                                        '</select>' +
                                        '     </div>' +
                                        '     <div class="col-lg-2">' +
                                        '<button type="button" onclick="$.DelPregunta(' +
                                        x +
                                        ')" class="btn mr-1 mb-3 btn-success btn-sm float-right"><i class="fa fa-trash"></i> Eliminar</button>' +
                                        "     </div>" +
                                        '      </div>' +
                                        '   </div>';
                                    x++;

                                });

                                Preguntas += '</div>' +
                                    '<div class="row" id="divaddpre' + cons + '">' +
                                    '  <button  onclick="$.AddOpcionRespAdd(' + cons +
                                    ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Pregunta</button> ' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '<div class="form-group"  style="margin-bottom: 0px;">' +
                                    '    <button type="button" onclick="$.GuardarPregParte1(' +
                                    cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPregParte1(' +
                                    cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DelPregParte1(' + cons +
                                    ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                    '</div>' +
                                    '   </div>' +
                                    '</div>';

                                $("#Preguntas" + cons).html(Preguntas);

                                $.CargCompe_Compo(cons);

                                $.LlenarAllSelect("All");

                                var j = 1;
                                var y = 1;
                                $.each(respuesta.Preguntas, function(k, item) {
                                    $('#competencia' + y).val(item.respuesta)
                                        .trigger('change.select2');
                                    y++;

                                });

                                $("#cb_Opciones").select2({
                                    tags: true,
                                    language: {
                                        noResults: function() {
                                            return 'Debe de Ingresar las Opciones para completar el parrafo.';
                                        },
                                    }
                                });

                                var resp = respuesta.Parte1.pregunta.split(",");
                                var options;
                                $.each(resp, function(index, value) {
                                    options += '<option value="' + value + '">' +
                                        value + '</option>';
                                });

                                $("#cb_Opciones").html(options);


                                $("#cb_Opciones").val(resp).change();

                                var y = 1;
                                $.each(respuesta.Preguntas, function(k, item) {

                                    $('#cb_respuesta' + y).val(item.respuesta)
                                        .trigger('change.select2');
                                    y++;
                                });

                                $('#competencia' + cons).val(respuesta.Parte1.competencia)
                                    .trigger('change.select2');
                                $('#componente' + cons).val(respuesta.Parte1.componente)
                                    .trigger('change.select2');



                            }
                        });

                        edit = "no"
                    } else {
                        mensaje = "Debe Guardar la Pregunta antes de editar otra.";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                DelPregParte1: function(id_fila) {
                    edit = "si";
                    if ($("#id-parte1" + id_fila).val() !== "") {
                        var preg = $("#id-parte1" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();


                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        $("#TipPregu").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                        form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE1'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Módulo E',
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

                                        $('#Preguntas' + id_fila).remove();
                                      
                                        $("#ConsPreguntas").val("1");
                                        $("#ConsOpcRel").val("1");

                                        $("#div-addpreg").show();
                                        $("#MensInf").show();
                                        $("#btns_guardar").hide();


                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Eliminado!',
                                            mensaje,
                                            'success'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        $("#ConsPreguntas").val("1");
                        $("#ConsOpcRel").val("1");
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }


                    if ($("#ConsPreguntas").val() < 2) {
                        edit = "no"
                    } else {
                        edit = "si"
                    }
                },

                      ////////////GUARDAR PREGUNTAS PARTE 2
                      GuardarPregParte2: function(id) {
                        edit = "si";
                        for (var instanceName in CKEDITOR.instances) {
                            CKEDITOR.instances[instanceName].updateElement();
                        }
                        $("#Tipreguntas").val($("#Tipreguntas" + id).val());
    
                        $("#IdpreguntaPart2").val($("#id-parte2" + id).val());
                        $("#PregConse").val(id);
    
                        let flag = "no";
                        $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                            if ($(elemento).val() == "si") {
                                flag = "si";
                            }
                        });
    
                        if ($("#competencia" + id).val() === "") {
                            mensaje = "Seleccione la Competencia";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        if ($("#componente" + id).val() === "") {
                            mensaje = "Seleccione el Componente";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        if (flag === "no") {
                            mensaje = "Debe de Seleccionar la Opción Correcta";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        $('#Btn-guardarPreg' + id).prop('disabled', true);
                        $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                        var form = $("#formBanco");
                        var datos = form.serialize();
                        var url = form.attr("action");
    
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            success: function(respuesta) {
                                if (respuesta) {
    
                                    $("#preg_id").val(respuesta.idEval);
                                    Swal.fire({
                                        title: "Gestionar Módulo E",
                                        text: "Operación Realizada Exitosamente",
                                        icon: "success",
                                        button: "Aceptar",
                                    });
                                    $('#Btn-guardarPreg' + id).prop('disabled', false);
    
    
                                    $("#Btn-guardarPreg" + id).hide();
                                    $("#Btn-EditPreg" + id).show();
                                    $("#div-addpreg").show();
    
                                    $("#id-parte2" + id).val(respuesta.PregOpcMul.id);
    
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
    
                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );
    
                                } else {
                                    mensaje = "La Evaluación no pudo ser Guardada";
                                    Swal.fire({
                                        title: "Gestionar Módulo E",
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
                    //EDITAR PREGUNTA PARTE 2
                    EditPregParte2: function(cons) {
                        if (edit === "si") {
    
                            var form = $("#formAuxiliarEval");
                            var id = $("#id-parte2" + cons).val();
    
                            var opci = "";
                            var parr = "";
                            var punt = "";
    
                            $("#Pregunta").remove();
                            $("#TipPregunta").remove();
                            form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                                id + "'>");
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
    
                                    Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte2' + cons +
                                        '"  value="' + id + '" />' +
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                        '<div id="DivOpcionesMultiples' + cons +
                                        '"><div id="RowMulPreg1">';
                                    var x = 1;
                                    $.each(respuesta.OpciMult, function(k, item) {
                                        Preguntas +=
                                            '<div class="row top-buffer" id="RowOpcPreg' +
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
                                        '    <button type="button" onclick="$.GuardarPregParte2(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' + cons +
                                        '"  style="display:none;" onclick="$.EditPregParte2(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' + cons +
                                        '" onclick="$.DePregParte2(' + cons +
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
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    },
                    //ELIMINAR PREGUNTA PARTE2
                    DePregParte2: function(id_fila) {
                        edit = "si";
                    
                        if ($("#id-parte2" + id_fila).val() !== "") {
                            var preg = $("#id-parte2" + id_fila).val();
                            var TipPreg = $("#Tipreguntas" + id_fila).val();
                            var form = $("#formAuxiliar");
                            $("#idAuxiliar").remove();
                            form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                                "'>");
                                form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE2'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            var mensaje = "";
                            mensaje = "¿Desea Eliminar esta Pregunta?";
    
                            Swal.fire({
                                title: 'Gestionar Módulo E',
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
    
                                            $('#Preguntas' + id_fila).remove();
                                            ConsPreg = $('#ConsPreguntas').val() - 1;
                                            $("#ConsPreguntas").val(ConsPreg);
                                            $("#div-addpreg").show();
                                            $("#btns_guardar").hide();
                                            if(ConsPreg===1){
                                                $("#InfPreg").remove();
                                                $("#MensInf").show();
                                                $.CargPartes($("#asignatura").val());
                                            }
    
                                        },
                                        error: function() {
    
                                            mensaje =
                                                "La Pregunta no pudo ser Eliminada";
    
                                            Swal.fire(
                                                'Eliminado!',
                                                mensaje,
                                                'success'
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
    
                      
    
                        if ($("#ConsPreguntas").val() < 2) {
                            edit = "no"
                        } else {
                            edit = "si"
                        }
    
                    },
                           //GUARDAR PREGUNTA PARTE 3
                GuardarPregParte3: function(id) {
                    edit = "si";
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());

                    $("#IdpreguntaPart3").val($("#id-parte3" + id).val());
                    $("#PregConse").val(id);

                    let flag = "no";
                    $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                        if ($(elemento).val() == "si") {
                            flag = "si";
                        }
                    });

                    if ($("#competencia" + id).val() === "") {
                        mensaje = "Seleccione la Competencia";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#componente" + id).val() === "") {
                        mensaje = "Seleccione el Componente";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (flag === "no") {
                        mensaje = "Debe de Seleccionar la Opción Correcta";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $('#Btn-guardarPreg' + id).prop('disabled', true);
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formBanco");
                    var datos = form.serialize();
                    var url = form.attr("action");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {

                                $("#preg_id").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Módulo E",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $('#Btn-guardarPreg' + id).prop('disabled', false);


                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-parte3" + id).val(respuesta.PregOpcMul.id);

                               
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

                                $("#Bts_Preg").html(
                                    '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                );

                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Módulo E",
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
                //EDITAR PREGUNTA PARTE 3
                EditPregParte3: function(cons) {
                    if (edit === "si") {

                        var form = $("#formAuxiliarEval");
                        var id = $("#id-parte3" + cons).val();

                        var opci = "";
                        var parr = "";
                        var punt = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");

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

                                Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-7">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-5">' +
                                    '<input type="hidden" id="id-parte3' + cons +
                                    '"  value="' + id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
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
                                    '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                    '<div id="DivOpcionesMultiples' + cons +
                                    '"><div id="RowMulPreg1">';


                                var x = 1;
                                $.each(respuesta.OpciMult, function(k, item) {
                                    Preguntas +=
                                        '<div class="row top-buffer" id="RowOpcPreg' +
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
                                    '    <button type="button" onclick="$.GuardarPregParte3(' +
                                    cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPregParte3(' +
                                    cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DePregParte2(' + cons +
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
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //ELIMINAR PREGUNTA PARTE 3
                DePregParte3: function(id_fila) {
                    edit = "si";
                    if ($("#id-parte3" + id_fila).val() !== "") {
                        var preg = $("#id-parte3" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                          form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE3'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Módulo E',
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

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();
                                        if(ConsPreg===1){
                                            $("#InfPreg").remove();
                                            $("#MensInf").show();
                                            $.CargPartes($("#asignatura").val());
                                        }

                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Eliminado!',
                                            mensaje,
                                            'success'
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

                  

                    if ($("#ConsPreguntas").val() < 2) {
                        edit = "no"
                    } else {
                        edit = "si"
                    }

                },
                       //GUARDAR PREGUNTA PARTE 4
                       GuardarPregParte4: function(id) {
                        edit = "si";
                        for (var instanceName in CKEDITOR.instances) {
                            CKEDITOR.instances[instanceName].updateElement();
                        }
                        $("#Tipreguntas").val($("#Tipreguntas" + id).val());
    
                        $("#IdpreguntaPart4").val($("#id-parte4" + id).val());
                        $("#PregConse").val(id);
    
                        let flag = "no";
                        $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                            if ($(elemento).val() == "si") {
                                flag = "si";
                            }
                        });
    
                        if ($("#competencia" + id).val() === "") {
                            mensaje = "Seleccione la Competencia";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        if ($("#componente" + id).val() === "") {
                            mensaje = "Seleccione el Componente";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        if (flag === "no") {
                            mensaje = "Debe de Seleccionar la Opción Correcta";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        $('#Btn-guardarPreg' + id).prop('disabled', true);
                        $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                        var form = $("#formBanco");
                        var datos = form.serialize();
                        var url = form.attr("action");
    
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            success: function(respuesta) {
                                if (respuesta) {
    
                                    $("#preg_id").val(respuesta.idEval);
                                    Swal.fire({
                                        title: "Gestionar Módulo E",
                                        text: "Operación Realizada Exitosamente",
                                        icon: "success",
                                        button: "Aceptar",
                                    });
                                    $('#Btn-guardarPreg' + id).prop('disabled', false);
    
    
                                    $("#Btn-guardarPreg" + id).hide();
                                    $("#Btn-EditPreg" + id).show();
                                    $("#div-addpreg").show();
    
                                    $("#id-parte4" + id).val(respuesta.PregOpcMul.id);
    
                                   
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
    
                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );
    
                                } else {
                                    mensaje = "La Evaluación no pudo ser Guardada";
                                    Swal.fire({
                                        title: "Gestionar Módulo E",
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
                    //EDITAR PREGUNTA PARTE 4
                    EditPregParte4: function(cons) {
                        if (edit === "si") {
    
                            var form = $("#formAuxiliarEval");
                            var id = $("#id-parte4" + cons).val();
                           
    
                            var opci = "";
                            var parr = "";
                            var punt = "";
    
                            $("#Pregunta").remove();
                            $("#TipPregunta").remove();
                            form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                                id + "'>");
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
    
                                    Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte4' + cons +
                                        '"  value="' + id + '" />' +
                                        '<input type="hidden" id="Tipreguntas' + cons +
                                        '"  value="PARTE 4" />' +
                                        '        </div>' +
                                        '      </div>' +
                                        '  <div class="col-md-12"> ' +
                                        '     <div class="form-group">' +
                                        '        <label class="form-label"><b>Completar:</b></label>' +
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
                                            '<div class="row top-buffer" id="RowOpcPreg' +
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
                                        '    <button type="button" onclick="$.GuardarPregParte4(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' + cons +
                                        '"  style="display:none;" onclick="$.EditPregParte4(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' + cons +
                                        '" onclick="$.DePregParte4(' + cons +
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
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    },
                    //ELIMINAR PREGUNTA PARTE 4
                    DePregParte4: function(id_fila) {
                        edit = "si";
                        if ($("#id-parte4" + id_fila).val() !== "") {
                            var preg = $("#id-parte4" + id_fila).val();
                            var TipPreg = $("#Tipreguntas" + id_fila).val();
                            var form = $("#formAuxiliar");
                            $("#idAuxiliar").remove();
                            form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                                "'>");
                                form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE4'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            var mensaje = "";
                            mensaje = "¿Desea Eliminar esta Pregunta?";
    
                            Swal.fire({
                                title: 'Gestionar Módulo E',
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
    
                                            $('#Preguntas' + id_fila).remove();
                                            ConsPreg = $('#ConsPreguntas').val() - 1;
                                            $("#ConsPreguntas").val(ConsPreg);
                                            $("#div-addpreg").show();
                                            $("#btns_guardar").hide();
                                            if(ConsPreg===1){
                                                $("#InfPreg").remove();
                                                $("#MensInf").show();
                                                $.CargPartes($("#asignatura").val());
                                            }
    
                                        },
                                        error: function() {
    
                                            mensaje =
                                                "La Pregunta no pudo ser Eliminada";
    
                                            Swal.fire(
                                                'Eliminado!',
                                                mensaje,
                                                'success'
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
    
    
                        if ($("#ConsPreguntas").val() < 2) {
                            edit = "no"
                        } else {
                            edit = "si"
                        }
    
                    },
                         //GUARDAR PREGUNTA PARTE 5
                  GuardarPregParte5: function(id) {
                    edit = "si";
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());

                    $("#IdpreguntaPart5").val($("#id-parte5" + id).val());
                    $("#PregConse").val(id);

                    let flag = "no";
                    $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                        if ($(elemento).val() == "si") {
                            flag = "si";
                        }
                    });

                    if ($("#competencia" + id).val() === "") {
                        mensaje = "Seleccione la Competencia";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#componente" + id).val() === "") {
                        mensaje = "Seleccione el Componente";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (flag === "no") {
                        mensaje = "Debe de Seleccionar la Opción Correcta";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $('#Btn-guardarPreg' + id).prop('disabled', true);
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formBanco");
                    var datos = form.serialize();
                    var url = form.attr("action");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {

                                $("#preg_id").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Módulo E",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $('#Btn-guardarPreg' + id).prop('disabled', false);


                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-parte5" + id).val(respuesta.PregOpcMul.id);

                               
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

                                $("#Bts_Preg").html(
                                    '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                );

                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Módulo E",
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
                //EDITAR PREGUNTA PARTE 5
                EditPregParte5: function(cons) {
                    if (edit === "si") {

                        var form = $("#formAuxiliarEval");
                        var id = $("#id-parte5" + cons).val();
                       

                        var opci = "";
                        var parr = "";
                        var punt = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
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

                                Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-7">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-5">' +
                                    '<input type="hidden" id="id-parte5' + cons +
                                    '"  value="' + id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
                                    '"  value="PARTE 5" />' +
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
                                        '<div class="row top-buffer" id="RowOpcPreg' +
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
                                    '    <button type="button" onclick="$.GuardarPregParte5(' +
                                    cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPregParte5(' +
                                    cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DePregParte5(' + cons +
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
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //ELIMINAR PREGUNTA PARTE 5
                DePregParte5: function(id_fila) {
                    edit = "si";
                    if ($("#id-parte5" + id_fila).val() !== "") {
                        var preg = $("#id-parte5" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                            form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE5'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Módulo E',
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

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();
                                        if(ConsPreg===1){
                                            $("#InfPreg").remove();
                                            $("#MensInf").show();
                                            $.CargPartes($("#asignatura").val());
                                        }

                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Eliminado!',
                                            mensaje,
                                            'success'
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

                  

                    if ($("#ConsPreguntas").val() < 2) {
                        edit = "no"
                    } else {
                        edit = "si"
                    }

                },
                      //GUARDAR PREGUNTA PARTE 6
                      GuardarPregParte6: function(id) {
                        edit = "si";
                        for (var instanceName in CKEDITOR.instances) {
                            CKEDITOR.instances[instanceName].updateElement();
                        }
                        $("#Tipreguntas").val($("#Tipreguntas" + id).val());
    
                        $("#IdpreguntaPart6").val($("#id-parte6" + id).val());
                        $("#PregConse").val(id);
    
                        let flag = "no";
                        $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                            if ($(elemento).val() == "si") {
                                flag = "si";
                            }
                        });
    
                        if ($("#competencia" + id).val() === "") {
                            mensaje = "Seleccione la Competencia";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        if ($("#componente" + id).val() === "") {
                            mensaje = "Seleccione el Componente";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        if (flag === "no") {
                            mensaje = "Debe de Seleccionar la Opción Correcta";
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
    
                        $('#Btn-guardarPreg' + id).prop('disabled', true);
                        $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                        var form = $("#formBanco");
                        var datos = form.serialize();
                        var url = form.attr("action");
    
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            success: function(respuesta) {
                                if (respuesta) {
    
                                    $("#preg_id").val(respuesta.idEval);
                                    Swal.fire({
                                        title: "Gestionar Módulo E",
                                        text: "Operación Realizada Exitosamente",
                                        icon: "success",
                                        button: "Aceptar",
                                    });
                                    $('#Btn-guardarPreg' + id).prop('disabled', false);
    
    
                                    $("#Btn-guardarPreg" + id).hide();
                                    $("#Btn-EditPreg" + id).show();
                                    $("#div-addpreg").show();
    
                                    $("#id-parte6" + id).val(respuesta.PregOpcMul.id);
    
                                   
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
    
                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );
    
                                } else {
                                    mensaje = "La Evaluación no pudo ser Guardada";
                                    Swal.fire({
                                        title: "Gestionar Módulo E",
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
                    //EDITAR PREGUNTA PARTE 6
                    EditPregParte6: function(cons) {
                        if (edit === "si") {
    
                            var form = $("#formAuxiliarEval");
                            var id = $("#id-parte6" + cons).val();
                           
    
                            var opci = "";
                            var parr = "";
                            var punt = "";
    
                            $("#Pregunta").remove();
                            $("#TipPregunta").remove();
                            form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                                id + "'>");
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
    
                                    Preguntas = '<div id="Preguntas' + cons +
                                        '" style="padding-bottom: 10px;">' +
                                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                        '         <div class="row">' +
                                        '            <div class="col-md-7">' +
                                        '             <div class="form-group row">' +
                                        '             <div class="col-md-12">' +
                                        '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                        '            </div>' +
                                        '           </div>' +
                                        '         </div>' +
                                        '         <div class="col-md-5">' +
                                        '<input type="hidden" id="id-parte6' + cons +
                                        '"  value="' + id + '" />' +
                                        '<input type="hidden" id="Tipreguntas' + cons +
                                        '"  value="PARTE 6" />' +
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
                                            '<div class="row top-buffer" id="RowOpcPreg' +
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
                                        '    <button type="button" onclick="$.GuardarPregParte6(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' + cons +
                                        '"  style="display:none;" onclick="$.EditPregParte6(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' + cons +
                                        '" onclick="$.DePregParte6(' + cons +
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
                                title: "Gestionar Módulo E",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }
                    },
                    //ELIMINAR PREGUNTA PARTE 6
                    DePregParte6: function(id_fila) {
                        edit = "si";
                        if ($("#id-parte6" + id_fila).val() !== "") {
                            var preg = $("#id-parte6" + id_fila).val();
                            var TipPreg = $("#Tipreguntas" + id_fila).val();
                            var form = $("#formAuxiliar");
                            $("#idAuxiliar").remove();
                            form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                                "'>");
                                form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE6'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            var mensaje = "";
                            mensaje = "¿Desea Eliminar esta Pregunta?";
    
                            Swal.fire({
                                title: 'Gestionar Módulo E',
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
    
                                            $('#Preguntas' + id_fila).remove();
                                            ConsPreg = $('#ConsPreguntas').val() - 1;
                                            $("#ConsPreguntas").val(ConsPreg);
                                            $("#div-addpreg").show();
                                            $("#btns_guardar").hide();
                                            if(ConsPreg===1){
                                                $("#InfPreg").remove();
                                                $("#MensInf").show();
                                                $.CargPartes($("#asignatura").val());
                                            }
    
                                        },
                                        error: function() {
    
                                            mensaje =
                                                "La Pregunta no pudo ser Eliminada";
    
                                            Swal.fire(
                                                'Eliminado!',
                                                mensaje,
                                                'success'
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
    
                      
    
                        if ($("#ConsPreguntas").val() < 2) {
                            edit = "no"
                        } else {
                            edit = "si"
                        }
    
                    },
                         //GUARDAR PREGUNTA PARTE 7
                GuardarPregParte7: function(id) {
                    edit = "si";
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());

                    $("#IdpreguntaPart7").val($("#id-parte7" + id).val());
                    $("#PregConse").val(id);

                    let flag = "no";
                    $("input[name='OpcCorecta[]']").each(function(indice, elemento) {
                        if ($(elemento).val() == "si") {
                            flag = "si";
                        }
                    });

                    if ($("#competencia" + id).val() === "") {
                        mensaje = "Seleccione la Competencia";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#componente" + id).val() === "") {
                        mensaje = "Seleccione el Componente";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (flag === "no") {
                        mensaje = "Debe de Seleccionar la Opción Correcta";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $('#Btn-guardarPreg' + id).prop('disabled', true);
                    $("#Tipreguntas").val($("#Tipreguntas" + id).val());
                    var form = $("#formBanco");
                    var datos = form.serialize();
                    var url = form.attr("action");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            if (respuesta) {

                                $("#preg_id").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Módulo E",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $('#Btn-guardarPreg' + id).prop('disabled', false);


                                $("#Btn-guardarPreg" + id).hide();
                                $("#Btn-EditPreg" + id).show();
                                $("#div-addpreg").show();

                                $("#id-parte7" + id).val(respuesta.PregOpcMul.id);


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

                                $("#Bts_Preg").html(
                                    '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                );

                            } else {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "Gestionar Módulo E",
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
                //EDITAR PREGUNTA PARTE 7
                EditPregParte7: function(cons) {
                    if (edit === "si") {

                        var form = $("#formAuxiliarEval");
                        var id = $("#id-parte7" + cons).val();


                        var opci = "";
                        var parr = "";
                        var punt = "";

                        $("#Pregunta").remove();
                        $("#TipPregunta").remove();
                        form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                            id + "'>");
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

                                Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-7">' +
                                    '             <div class="form-group row">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '         <div class="col-md-5">' +
                                    '<input type="hidden" id="id-parte7' + cons +
                                    '"  value="' + id + '" />' +
                                    '<input type="hidden" id="Tipreguntas' + cons +
                                    '"  value="PARTE 7" />' +
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
                                    '        <label class="form-label"><b>Ingrese las Opciones:</b></label>' +
                                    '<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />' +
                                    '<div id="DivOpcionesMultiples' + cons +
                                    '"><div id="RowMulPreg1">';
                                var x = 1;
                                $.each(respuesta.OpciMult, function(k, item) {
                                    Preguntas +=
                                        '<div class="row top-buffer" id="RowOpcPreg' +
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
                                    '    <button type="button" onclick="$.GuardarPregParte7(' +
                                    cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPregParte7(' +
                                    cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DePregParte7(' + cons +
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
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                },
                //ELIMINAR PREGUNTA PARTE 7
                DePregParte7: function(id_fila) {
                    edit = "si";
                    if ($("#id-parte7" + id_fila).val() !== "") {
                        var preg = $("#id-parte7" + id_fila).val();
                        var TipPreg = $("#Tipreguntas" + id_fila).val();
                        var form = $("#formAuxiliar");
                        $("#idAuxiliar").remove();
                        form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + preg +
                            "'>");
                            form.append("<input type='hidden' name='TipPregu' id='TipPregu' value='PARTE7'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var mensaje = "";
                        mensaje = "¿Desea Eliminar esta Pregunta?";

                        Swal.fire({
                            title: 'Gestionar Módulo E',
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

                                        $('#Preguntas' + id_fila).remove();
                                        ConsPreg = $('#ConsPreguntas').val() - 1;
                                        $("#ConsPreguntas").val(ConsPreg);
                                        $("#div-addpreg").show();
                                        $("#btns_guardar").hide();
                                        if (ConsPreg === 1) {
                                            $("#InfPreg").remove();
                                            $("#MensInf").show();
                                            $.CargPartes($("#asignatura").val());
                                        }

                                    },
                                    error: function() {

                                        mensaje =
                                            "La Pregunta no pudo ser Eliminada";

                                        Swal.fire(
                                            'Eliminado!',
                                            mensaje,
                                            'success'
                                        )
                                    }
                                });

                            }
                        });

                    } else {
                        $('#Preguntas' + id_fila).remove();
                        ConsPreg = $('#ConsPreguntas').val() - 1;
                        if (ConsPreg == 1) {
                            $("#InfPreg").remove();
                            $("#MensInf").show();
                            $.CargPartes($("#asignatura").val());
                        }
                        $("#ConsPreguntas").val(ConsPreg);
                        $("#div-addpreg").show();
                        $("#btns_guardar").hide();
                    }



                    if ($("#ConsPreguntas").val() < 2) {
                        edit = "no"
                    } else {
                        edit = "si"
                    }

                },
    
                AddOpcion: function(id) {
                    var cons = $("#ConsOpcMul").val();

                    var preguntas = "<div class='row top-buffer' id='RowOpcPreg" + cons +
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
                AddOpcionRespAdd: function(id) {
                    var cons = $("#ConsOpcRel").val();

                    var Preguntas = '<div id="RowRelPreg' + cons + '">' +
                        '                 <div class="row top-buffer" id="RowOpcRelPreg1" style="padding-bottom: 15px;">' +
                        '                      <div class="col-lg-6 border-top-primary pt-1">' +
                        ' <input type="hidden" class="form-control" name="Preg[]" value="1" />' +
                        '        <label class="form-label"><b>Pregunta ' + cons + ':</b></label>' +
                        '    <input type="text" class="form-control" name="Pregunta[]" value="" />' +
                        '     </div>' +
                        '                      <div class="col-lg-4 border-top-primary pt-1">' +
                        ' <input type="hidden" class="form-control" name="Palabra[]" value="1" />' +
                        '        <label class="form-label"><b>Respuesta:</b></label>' +
                        '    <select class="form-control select2 SelecPalabras"  style="width: 100%;" data-placeholder="Seleccione la Respuesta"' +
                        '  id="cb_respuesta' + cons + '" name="cb_respuesta[]">' +
                        '</select>' +
                        '     </div>' +
                        '     <div class="col-lg-2">' +
                        '<button type="button" onclick="$.DelPregunta(' + cons +
                        ')" class="btn mr-1 mb-3 btn-success btn-sm float-right"><i class="fa fa-trash"></i> Eliminar</button>' +
                        "     </div>" +
                        '      </div>' +
                        '   </div>';

                  $("#ConsPreguntas").val()
                    $("#DivPreg" + id).append(Preguntas);
                    $.LlenarAllSelect(cons);
                    cons++;
                    $("#ConsOpcRel").val(cons);

                    $("#ConsPreguntas").val(cons);

                },
                DelOpcPreg: function(id) {
                    $('#RowOpcPreg' + id).remove();
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
                Hab_EnunPreg: function(cons) {
                    CKEDITOR.replace('EnunPreg', {
                        width: '100%',
                        height: 150
                    });
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
                ////////////GUARDAR Y CERRAR
                GuardarPregunta: function(id) {
                    var form = $("#formBanco");
                    var url = form.attr("action");

                    var rurl = $("#Ruta").val();

                    if ($("#ConsPreguntas").val() <= 1) {
                        mensaje = "No existe Ninguna Pregunta en la Evaluación";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }



                    if (edit === "no") {
                        mensaje = "Falta una Pregunta por Guardar, Verifique...";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    $("#npreguntas").val($("#ConsPreguntas").val() - 1);
                    $.ajax({
                        type: "POST",
                        url: rurl + "ModuloE/GuardarBancoPregFin",
                        data: new FormData($('#formBanco')[0]),
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                            if (respuesta) {
                                $("#preg_id").val(respuesta.idEval);
                                Swal.fire({
                                    title: "Gestionar Módulo E",
                                    text: "Operación Realizada Exitosamente",
                                    icon: "success",
                                    button: "Aceptar",
                                });
                                $(location).attr('href', rurl +
                                    "/ModuloE/GestionBancoPreguntas")
                            } else {
                                mensaje = "Las Pregunta no pudo ser Guardada";
                                Swal.fire({
                                    title: "",
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
                AddPalabra: function() {

                    let $select = $('#cb_Opciones');

                    // Buscamos los option seleccionados
                    selectPalabras = [];
                    $select.children(':selected').each((idx, el) => {
                        // Obtenemos los atributos que necesitamos
                        selectPalabras.push({
                            id: el.id,
                            value: el.value
                        });
                    });

                    //
                    $.LlenarAllSelect("All");

                },

                LlenarAllSelect: function(id) {
                    var opction = "";
                    for (i = 0; i < selectPalabras.length; i++) {
                        opction += "<option value='" + selectPalabras[i].value + "'>" + selectPalabras[
                            i].value + "</option>";

                    }

                    if (id === "All") {
                        jQuery('.SelecPalabras').html(opction);
                    } else {

                        jQuery('#cb_respuesta' + id).html(opction);
                    }
                },
                DelPregunta: function(id) {
                    $('#RowRelPreg' + id).remove();
                    let conse = $("#ConsOpcRel").val();
                    $("#ConsOpcRel").val(conse - 1)
                    $("#ConsPreguntas").val(conse-1);
                },
                AddPregParte: function() {
                    let part = $("#ParteSel").val();
                    $.AddParte(part);
                },
                CargPartes: function(id) {
                    var Partes = "";
                  
                    var form = $("#formAuxiliarPartes");
                    $("#idArea").remove();
                    form.append("<input type='hidden' name='idArea' id='idArea' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                          
                          
                            if (respuesta.Flag === "si") {

                                $.each(respuesta.Partes, function(k, item) {
                                    Partes += '<a class="dropdown-item" id="Par' +
                                        item.id + '" data-ruta="' + item.parte +
                                        '" data-archivo="' + item.descripcion +
                                        '" title="' + item.descripcion +
                                        '" onclick="$.AddParte(this.id);">' + item
                                        .parte + '</a>';
                                });
                               

                                $("#Bts_Preg").html(Partes);
                            } else {
                                $("#Bts_Preg").html(
                                    '<a class="dropdown-item" onclick="$.AddPregOpcMultiple();">Agregar Pregunta Opción Multiple</a>'
                                );
                            }

                        }

                    });
                },
                AddParte: function(id) {

                    var cons = parseFloat($("#ConsPreguntas").val());
                    var consp = $("#ConsOpcRel").val();


                    if (cons === 1) {
                        parte = $("#" + id).data("ruta");
                        $("#ParteSel").val(parte);
                    } else {
                        parte = $("#ParteSel").val();
                    }


                    if (parte === "PARTE 1") {

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                            '         <div class="row">' +
                            '            <div class="col-md-12">' +
                            '             <div class="form-group row pt-0">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">' + parte + '</h4>' +
                            '<p>' + $("#" + id).data("archivo") + '</p>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '         <div class="col-md-4">' +
                            '           <div class="form-group row">' +
                            '<input type="hidden" id="id-parte1' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 1" />' +
                            '            <div class="col-md-12 right">' +
                            '            </div>' +
                            '          </div>' +
                            '        </div>' +
                            '      </div>' +
                            '<div id="DivCompetencia' + cons + '">' +
                            '<div class="row pb-2">' +

                            '<div class="col-md-6">' +
                            '        <label class="form-label"><b>Competencia:</b></label>' +
                            '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                            cons + '">' +

                            '    </select>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                            '        <label class="form-label"><b>Componente:</b></label>' +
                            '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                            cons + '">' +

                            '    </select>' +
                            '</div>' +
                            '         </div>' +
                            '</div>' +
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
                            '                      <div class="col-lg-6 border-top-primary  pt-1">' +
                            ' <input type="hidden" class="form-control" name="Preg[]" value="1" />' +
                            '        <label class="form-label"><b>Pregunta 1:</b></label>' +
                            '    <input type="text" class="form-control" name="Pregunta[]" value="" />' +
                            '     </div>' +
                            '                      <div class="col-lg-4 border-top-primary  pt-1">' +
                            ' <input type="hidden" class="form-control" name="Palabra[]" value="1" />' +
                            '        <label class="form-label"><b>Respuesta:</b></label>' +
                            '    <select class="form-control select2 SelecPalabras"  style="width: 100%;" data-placeholder="Seleccione la Respuesta"' +
                            '  id="cb_respuesta' + consp + '" name="cb_respuesta[]">' +
                            '</select>' +
                            '     </div>' +
                            '      </div>' +
                            '   </div>' +
                            '</div>' +
                            '<div class="row" id="divaddpre' + cons + '">' +
                            '  <button  onclick="$.AddOpcionRespAdd(' + cons +
                            ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Pregunta</button> ' +
                            '</div>' +
                            '</div>' +
                            '      </div>' +
                            '<div class="form-group"  style="margin-bottom: 0px;">' +
                            '    <button type="button" onclick="$.GuardarPregParte1(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte1(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DelPregParte1(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);
                        $.CargCompe_Compo(cons);
                        cons++;
                        consp++;
                        $("#ConsPreguntas").val(cons);
                        $("#ConsOpcRel").val(consp)
                        $("#MensInf").hide();
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();


                        $("#cb_Opciones").select2({
                            tags: true,
                            language: {
                                noResults: function() {
                                    return 'Debe de Ingresar las Opciones para completar el parrafo.';
                                },
                            }
                        });

                    } else if (parte === "PARTE 2") {


                        var cons = parseFloat($("#ConsPreguntas").val());
                        $("#MensInf").hide();

                        if ($("#asignatura").val() === "") {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Debe seleccionar la Asignatura.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }




                        if (cons === 1) {
                            var InfPreg = '<div id="InfPreg">' +
                                '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                '         <div class="row">' +
                                '            <div class="col-md-12">' +
                                '             <div class="form-group row pt-0">' +
                                '             <div class="col-md-12">' +
                                '     <h4 class="primary">' + parte + '</h4>' +
                                '<p>' + $("#" + id).data("archivo") + '</p>' +
                                '            </div>' +
                                '           </div>' +
                                '         </div>' +
                                '</div>' +
                                '<div  id="DivCompetencia' + cons + '">' +
                                '<div class="row pb-2">' +

                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '         </div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#div-evaluaciones").append(InfPreg);
                        }

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 pt-2">' +
                            '         <div class="row">' +
                            '            <div class="col-md-7">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '            <div class="col-md-5">' +
                            '<input type="hidden" id="id-parte2' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 2" />' +
                            '         </div>' +

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
                            '    <button type="button" onclick="$.GuardarPregParte2(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte2(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DePregParte2(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);

                        $.hab_ediPreMul("1");
                        $.hab_ediPreOpcMul("1");
                        if (cons === 1) {
                            $.CargCompe_Compo(cons);
                        }

                        cons++;
                        $("#ConsPreguntas").val(cons);
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();
                        edit = "no";

                    } else if (parte === "PARTE 3") {

                        var cons = parseFloat($("#ConsPreguntas").val());
                        $("#MensInf").hide();

                        if ($("#asignatura").val() === "") {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Debe seleccionar la Asignatura.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }


                        if (cons === 1) {
                            var InfPreg = '<div id="InfPreg">' +
                                '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                '         <div class="row">' +
                                '            <div class="col-md-12">' +
                                '             <div class="form-group row pt-0">' +
                                '             <div class="col-md-12">' +
                                '     <h4 class="primary">' + parte + '</h4>' +
                                '<p>' + $("#" + id).data("archivo") + '</p>' +
                                '            </div>' +
                                '           </div>' +
                                '         </div>' +
                                '</div>' +
                                '<div  id="DivCompetencia' + cons + '">' +
                                '<div class="row pb-2">' +

                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-12 pt-1">' +
                                '        <label class="form-label"><b>Ingrese la Conversación:</b></label>' +
                                '<div id="DivEnunPreg">' +
                                '     <textarea cols="80" id="EnunPreg" name="EnunPreg" rows="3"></textarea>' +
                                '</div>' +
                                '</div>' +
                                '         </div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#div-evaluaciones").append(InfPreg);
                            $.Hab_EnunPreg();

                        }

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 pt-2">' +
                            '         <div class="row">' +
                            '            <div class="col-md-7">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '            <div class="col-md-5">' +
                            '<input type="hidden" id="id-parte3' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 3" />' +
                            '         </div>' +

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
                            '    <button type="button" onclick="$.GuardarPregParte3(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte3(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DePregParte3(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);

                        $.hab_ediPreMul("1");
                        $.hab_ediPreOpcMul("1");
                        if (cons === 1) {
                            $.CargCompe_Compo(cons);
                        }

                        cons++;
                        $("#ConsPreguntas").val(cons);
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();
                        edit = "no";

                    } else if (parte === "PARTE 4") {


                        var cons = parseFloat($("#ConsPreguntas").val());
                        $("#MensInf").hide();

                        if ($("#asignatura").val() === "") {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Debe seleccionar la Asignatura.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }



                        if (cons === 1) {
                            var InfPreg = '<div id="InfPreg">' +
                                '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                '         <div class="row">' +
                                '            <div class="col-md-12">' +
                                '             <div class="form-group row pt-0">' +
                                '             <div class="col-md-12">' +
                                '     <h4 class="primary">' + parte + '</h4>' +
                                '<p>' + $("#" + id).data("archivo") + '</p>' +
                                '            </div>' +
                                '           </div>' +
                                '         </div>' +
                                '</div>' +
                                '<div  id="DivCompetencia' + cons + '">' +
                                '<div class="row pb-2">' +

                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-12 pt-1">' +
                                '        <label class="form-label"><b>Texto a Completar:</b></label>' +
                                '<div id="DivEnunPreg">' +
                                '     <textarea cols="80" id="TextComp" name="EnunPreg" rows="3"></textarea>' +
                                '</div>' +
                                '</div>' +
                                '         </div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#div-evaluaciones").append(InfPreg);
                            $.Hab_TextComp();

                        }

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 pt-2">' +
                            '         <div class="row">' +
                            '            <div class="col-md-7">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '            <div class="col-md-5">' +
                            '<input type="hidden" id="id-parte4' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 4" />' +
                            '         </div>' +

                            '      </div>' +


                            '  <div  class="col-md-12"> ' +
                            '     <div class="form-group">' +
                            '        <label class="form-label"><b>Completar:</b></label>' +
                            '<div  id="PreguntaMultiple' + cons + '">' +
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
                            '    <button type="button" onclick="$.GuardarPregParte4(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte4(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DePregParte4(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);

                        $.hab_ediPreMul("1");
                        $.hab_ediPreOpcMul("1");
                        if (cons === 1) {
                            $.CargCompe_Compo(cons);
                        }

                        cons++;
                        $("#ConsPreguntas").val(cons);
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();
                        edit = "no";


                    } else if (parte === "PARTE 5") {

                        var cons = parseFloat($("#ConsPreguntas").val());
                        $("#MensInf").hide();

                        if ($("#asignatura").val() === "") {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Debe seleccionar la Asignatura.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }




                        if (cons === 1) {
                            var InfPreg = '<div id="InfPreg">' +
                                '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                '         <div class="row">' +
                                '            <div class="col-md-12">' +
                                '             <div class="form-group row pt-0">' +
                                '             <div class="col-md-12">' +
                                '     <h4 class="primary">' + parte + '</h4>' +
                                '<p>' + $("#" + id).data("archivo") + '</p>' +
                                '            </div>' +
                                '           </div>' +
                                '         </div>' +
                                '</div>' +
                                '<div  id="DivCompetencia' + cons + '">' +
                                '<div class="row pb-2">' +

                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-12 pt-1">' +
                                '        <label class="form-label"><b>Ingrese el Texto:</b></label>' +
                                '<div id="DivEnunPreg">' +
                                '     <textarea cols="80" id="EnunPreg" name="EnunPreg" rows="3"></textarea>' +
                                '</div>' +
                                '</div>' +
                                '         </div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#div-evaluaciones").append(InfPreg);
                            $.Hab_EnunPreg();

                        }

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 pt-2">' +
                            '         <div class="row">' +
                            '            <div class="col-md-7">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '            <div class="col-md-5">' +
                            '<input type="hidden" id="id-parte5' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 5" />' +
                            '         </div>' +

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
                            '    <button type="button" onclick="$.GuardarPregParte5(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte5(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DePregParte5(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);

                        $.hab_ediPreMul("1");
                        $.hab_ediPreOpcMul("1");
                        if (cons === 1) {
                            $.CargCompe_Compo(cons);
                        }

                        cons++;
                        $("#ConsPreguntas").val(cons);
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();
                        edit = "no";
                    } else if (parte === "PARTE 6") {

                        var cons = parseFloat($("#ConsPreguntas").val());
                        $("#MensInf").hide();

                        if ($("#asignatura").val() === "") {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Debe seleccionar la Asignatura.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }



                        if (cons === 1) {
                            var InfPreg = '<div id="InfPreg">' +
                                '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                '         <div class="row">' +
                                '            <div class="col-md-12">' +
                                '             <div class="form-group row pt-0">' +
                                '             <div class="col-md-12">' +
                                '     <h4 class="primary">' + parte + '</h4>' +
                                '<p>' + $("#" + id).data("archivo") + '</p>' +
                                '            </div>' +
                                '           </div>' +
                                '         </div>' +
                                '</div>' +
                                '<div  id="DivCompetencia' + cons + '">' +
                                '<div class="row pb-2">' +

                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-12 pt-1">' +
                                '        <label class="form-label"><b>Ingrese el Texto:</b></label>' +
                                '<div id="DivEnunPreg">' +
                                '     <textarea cols="80" id="EnunPreg" name="EnunPreg" rows="3"></textarea>' +
                                '</div>' +
                                '</div>' +
                                '         </div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#div-evaluaciones").append(InfPreg);
                            $.Hab_EnunPreg();

                        }

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 pt-2">' +
                            '         <div class="row">' +
                            '            <div class="col-md-7">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '            <div class="col-md-5">' +
                            '<input type="hidden" id="id-parte6' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 6" />' +
                            '         </div>' +

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
                            '    <button type="button" onclick="$.GuardarPregParte6(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte6(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DePregParte6(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);

                        $.hab_ediPreMul("1");
                        $.hab_ediPreOpcMul("1");
                        if (cons === 1) {
                            $.CargCompe_Compo(cons);
                        }

                        cons++;
                        $("#ConsPreguntas").val(cons);
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();
                        edit = "no";

                    } else if (parte === "PARTE 7") {


                        var cons = parseFloat($("#ConsPreguntas").val());
                        $("#MensInf").hide();

                        if ($("#asignatura").val() === "") {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Debe seleccionar la Asignatura.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }



                        if (cons === 1) {
                            var InfPreg = '<div id="InfPreg">' +
                                '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                '         <div class="row">' +
                                '            <div class="col-md-12">' +
                                '             <div class="form-group row pt-0">' +
                                '             <div class="col-md-12">' +
                                '     <h4 class="primary">' + parte + '</h4>' +
                                '<p>' + $("#" + id).data("archivo") + '</p>' +
                                '            </div>' +
                                '           </div>' +
                                '         </div>' +
                                '</div>' +
                                '<div  id="DivCompetencia' + cons + '">' +
                                '<div class="row pb-2">' +

                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                cons + '">' +

                                '    </select>' +
                                '</div>' +
                                '<div class="col-md-12 pt-1">' +
                                '        <label class="form-label"><b>Texto a Completar:</b></label>' +
                                '<div id="DivEnunPreg">' +
                                '     <textarea cols="80" id="TextComp" name="EnunPreg" rows="3"></textarea>' +
                                '</div>' +
                                '</div>' +
                                '         </div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#div-evaluaciones").append(InfPreg);
                            $.Hab_TextComp();

                        }

                        var Preguntas = '<div id="Preguntas' + cons +
                            '" style="padding-bottom: 10px;">' +
                            ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 pt-2">' +
                            '         <div class="row">' +
                            '            <div class="col-md-7">' +
                            '             <div class="form-group row">' +
                            '             <div class="col-md-12">' +
                            '     <h4 class="primary">Pregunta  ' + cons + '</h4>' +
                            '            </div>' +
                            '           </div>' +
                            '         </div>' +
                            '            <div class="col-md-5">' +
                            '<input type="hidden" id="id-parte7' + cons +
                            '"  value="" />' +
                            '<input type="hidden" id="Tipreguntas' + cons +
                            '"  value="PARTE 7" />' +
                            '         </div>' +

                            '      </div>' +


                            '  <div  class="col-md-12"> ' +
                            '     <div class="form-group">' +
                            '        <label class="form-label"><b>Completar: </b></label>' +
                            '<div  id="PreguntaMultiple' + cons + '">' +
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
                            '    <button type="button" onclick="$.GuardarPregParte7(' + cons +
                            ');" id="Btn-guardarPreg' + cons +
                            '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                            '    <button type="button" id="Btn-EditPreg' + cons +
                            '"  style="display:none;" onclick="$.EditPregParte7(' + cons +
                            ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                            '    <button type="button" id="Btn-ElimPreg' + cons +
                            '" onclick="$.DePregParte7(' + cons +
                            ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                            '</div>' +
                            '   </div>' +
                            '</div>';


                        $("#div-evaluaciones").append(Preguntas);

                        $.hab_ediPreMul("1");
                        $.hab_ediPreOpcMul("1");
                        if (cons === 1) {
                            $.CargCompe_Compo(cons);
                        }

                        cons++;
                        $("#ConsPreguntas").val(cons);
                        $("#div-addpreg").hide();
                        $("#btns_guardar").show();
                        edit = "no";

                    }





                },
                CagarPreguntas: function() {

                    var IdBanco = $("#preg_id").val();
                    var form = $("#formAuxiliarEvalDet");
                    $("#idbanc").remove();
                    form.append("<input type='hidden' name='idbanc' id='idbanc' value='" + IdBanco +
                        "'>");
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
                            $.HabEditEnunciado();

                            $("#enunciado").val(respuesta.Banco.enunciado);
                            $.CargPartes($("#asignatura").val());
                        
                            if (respuesta.Banco.tipo_pregunta === "PARTE 1") {
                                var Preguntas="";

                                var conse=1;
                                var consp=1;
                                if(respuesta.Parte1!==undefined){
                                    Preguntas = '<div id="Preguntas' + cons +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
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
                                    '<div id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
        
                                    '<div class="col-md-6">' +
                                    '        <label class="form-label"><b>Competencia:</b></label>' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
        
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '        <label class="form-label"><b>Componente:</b></label>' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
        
                                    '    </select>' +
                                    '</div>' +
                                    '         </div>' +
                                    '</div>' +
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
                                    '  id="cb_respuesta' + consp + '" name="cb_respuesta[]">' +
                                    '</select>' +
                                    '     </div>' +
                                    '      </div>' +
                                    '   </div>' +
                                    '</div>' +
                                    '<div class="row" id="divaddpre' + cons + '">' +
                                    '  <button  onclick="$.AddOpcionRespAdd(' + cons +
                                    ');" type="button" class="btn-sm  btn-success"><i class="fa fa-plus"></i> Agregar Pregunta</button> ' +
                                    '</div>' +
                                    '</div>' +
                                    '      </div>' +
                                    '<div class="form-group"  style="margin-bottom: 0px;">' +
                                    '    <button type="button" onclick="$.GuardarPregParte1(' + cons +
                                    ');" id="Btn-guardarPreg' + cons +
                                    '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                    '    <button type="button" id="Btn-EditPreg' + cons +
                                    '"  style="display:none;" onclick="$.EditPregParte1(' + cons +
                                    ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                    '    <button type="button" id="Btn-ElimPreg' + cons +
                                    '" onclick="$.DelPregParte1(' + cons +
                                    ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                    '</div>' +
                                    '   </div>' +
                                    '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
    
                                    $('#Btn-guardarPreg' + cons).prop('disabled', false);
    
    
                                    $("#Btn-guardarPreg" + cons).hide();
                                    $("#Btn-EditPreg" + cons).show();
    
                                    $("#id-parte1" + cons).val(respuesta.Parte1.id);
    
                                    $("#DivCompetencia" + cons).html(
                                        '<div class="row pb-2"><div class="col-md-6"><fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Competencia</span>' +
                                        '          </div>' +
                                        '          <input type="text" disabled id="CompeEdit' +
                                        cons +
                                        '" class="form-control"' +
                                        '     value="' + respuesta.DesCompe +
                                        '" placeholder="Competencia" aria-describedby="basic-addon2">' +
                                        '        </div>' +
                                        '      </fieldset></div>' +
                                        '<div class="col-md-6"><fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Componente</span>' +
                                        '          </div>' +
                                        '          <input type="text" disabled id="CompoEdit' +
                                        cons +
                                        '" class="form-control"' +
                                        '     value="' + respuesta.DesCompo +
                                        '" placeholder="Competencia" aria-describedby="basic-addon2">' +
                                        '        </div>' +
                                        '      </fieldset></div></div>');
    
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
                                    $("#ConsPreguntas").val(preg);
                                    $("#ConsOpcRel").val(preg);
    
                                    $("#DivPreg" + cons).html(opciones);
                                    $("#divaddpre" + cons).hide();
                                    $("#div-addpreg").hide();
                                    
                                    $("#cb_Opciones").select2({
                                        tags: true,
                                        language: {
                                            noResults: function() {
                                                return 'Debe de Ingresar las Opciones para completar el parrafo.';
                                            },
                                        }
                                    });
                                    edit = "si";
                                }else{
                                    $.CargPartes($("#asignatura").val());
                                }

                            } else if (respuesta.Banco.tipo_pregunta  === "PARTE 2"){

                                $("#ParteSel").val(respuesta.Partes.parte);
                                var InfPreg = '<div id="InfPreg">' +
                                    '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '<div  id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
    
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
    
                                $("#div-evaluaciones").append(InfPreg);

                                $.CargCompe_Compo(cons);

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
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarPregParte2(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPregParte2(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DePregParte2(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if(cons===1){
                                            $('#competencia' + cons).val(itemp.competencia)
                                            .trigger('change.select2');
        
                                        $('#componente' + cons).val(itemp.componente)
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
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );

                                });

                            } else if (respuesta.Banco.tipo_pregunta  === "PARTE 3"){

                                $("#ParteSel").val(respuesta.Partes.parte);

                                var InfPreg = '<div id="InfPreg">' +
                                    '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '<div  id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
    
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-12 pt-1">'+
                                        '        <label class="form-label"><b>Ingrese la Conversación:</b></label>' +
                                                '<div id="DivEnunPreg">' +
                                                    '     <textarea cols="80" id="EnunPreg" name="EnunPreg" rows="3"></textarea>' +
                                                    '</div>' +    
                                            '</div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
    
                                $("#div-evaluaciones").append(InfPreg);
                                $.Hab_EnunPreg();
                         
                                $("#EnunPreg").val(respuesta.Banco.enunc_preg);

                                $.CargCompe_Compo(cons);

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
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '<div class="form-group"  style="margin-bottom: 0px;">' +
                                        '    <button type="button" onclick="$.GuardarPregParte3(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPregParte3(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DePregParte3(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if(cons===1){
                                            $('#competencia' + cons).val(itemp.competencia)
                                            .trigger('change.select2');
        
                                        $('#componente' + cons).val(itemp.componente)
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
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );

                                });
                            } else if (respuesta.Banco.tipo_pregunta  === "PARTE 4"){

                                $("#ParteSel").val(respuesta.Partes.parte);

                                var InfPreg = '<div id="InfPreg">' +
                                    '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '<div  id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
    
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-12 pt-1">'+
                                        '        <label class="form-label"><b>Texto a Completar:</b></label>' +
                                                '<div id="DivEnunPreg">' +
                                                    '     <textarea cols="80" id="TextComp" name="EnunPreg" rows="3"></textarea>' +
                                                    '</div>' +    
                                            '</div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
    
                                $("#div-evaluaciones").append(InfPreg);
                                $.Hab_EnunPreg();
                         
                                $("#TextComp").val(respuesta.Banco.enunc_preg);

                                $.CargCompe_Compo(cons);

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
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '    <button type="button" onclick="$.GuardarPregParte4(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPregParte4(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DePregParte4(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if(cons===1){
                                            $('#competencia' + cons).val(itemp.competencia)
                                            .trigger('change.select2');
        
                                        $('#componente' + cons).val(itemp.componente)
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
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );

                                });


                            } else if (respuesta.Banco.tipo_pregunta  === "PARTE 5"){

                                $("#ParteSel").val(respuesta.Partes.parte);

                                var InfPreg = '<div id="InfPreg">' +
                                    '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '<div  id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
    
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-12 pt-1">'+
                                        '        <label class="form-label"><b>Ingrese el texto:</b></label>' +
                                                '<div id="DivEnunPreg">' +
                                                    '     <textarea cols="80" id="EnunPreg" name="EnunPreg" rows="3"></textarea>' +
                                                    '</div>' +    
                                            '</div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
    
                                $("#div-evaluaciones").append(InfPreg);
                                $.Hab_EnunPreg();
                         
                                $("#EnunPreg").val(respuesta.Banco.enunc_preg);

                                $.CargCompe_Compo(cons);

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
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '    <button type="button" onclick="$.GuardarPregParte5(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPregParte5(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DePregParte5(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if(cons===1){
                                            $('#competencia' + cons).val(itemp.competencia)
                                            .trigger('change.select2');
        
                                        $('#componente' + cons).val(itemp.componente)
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
                                    edit = "si";
                                    $("#ConsPreguntas").val(cons);

                                    $("#Bts_Preg").html(
                                        '<a class="dropdown-item" onclick="$.AddPregParte();">Agregar Pregunta</a>'
                                    );

                                });

                            } else if (respuesta.Banco.tipo_pregunta  === "PARTE 6"){

                                $("#ParteSel").val(respuesta.Partes.parte);

                                var InfPreg = '<div id="InfPreg">' +
                                    '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '<div  id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
    
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-12 pt-1">'+
                                        '        <label class="form-label"><b>Ingrese el texto:</b></label>' +
                                                '<div id="DivEnunPreg">' +
                                                    '     <textarea cols="80" id="EnunPreg" name="EnunPreg" rows="3"></textarea>' +
                                                    '</div>' +    
                                            '</div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
    
                                $("#div-evaluaciones").append(InfPreg);
                                $.Hab_EnunPreg();
                         
                                $("#EnunPreg").val(respuesta.Banco.enunc_preg);

                                $.CargCompe_Compo(cons);

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
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '    <button type="button" onclick="$.GuardarPregParte6(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPregParte6(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DePregParte6(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if(cons===1){
                                            $('#competencia' + cons).val(itemp.competencia)
                                            .trigger('change.select2');
        
                                        $('#componente' + cons).val(itemp.componente)
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

                            } else if (respuesta.Banco.tipo_pregunta  === "PARTE 7"){

                                $("#ParteSel").val(respuesta.Partes.parte);

                                var InfPreg = '<div id="InfPreg">' +
                                    '<div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mb-1">' +
                                    '         <div class="row">' +
                                    '            <div class="col-md-12">' +
                                    '             <div class="form-group row pt-0">' +
                                    '             <div class="col-md-12">' +
                                    '     <h4 class="primary">' + respuesta.Partes.parte + '</h4>' +
                                    '<p>' + respuesta.Partes.descripcion + '</p>' +
                                    '            </div>' +
                                    '           </div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '<div  id="DivCompetencia' + cons + '">' +
                                    '<div class="row pb-2">' +
    
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Competencia" name="competencia" id="competencia' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                    '    <select class="form-control select2" data-placeholder="Componente" name="componente" id="componente' +
                                    cons + '">' +
    
                                    '    </select>' +
                                    '</div>' +
                                    '<div class="col-md-12 pt-1">'+
                                        '        <label class="form-label"><b>Texto a Completar:</b></label>' +
                                                '<div id="DivEnunPreg">' +
                                                    '     <textarea cols="80" id="TextComp" name="EnunPreg" rows="3"></textarea>' +
                                                    '</div>' +    
                                            '</div>' +
                                    '         </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
    
                                $("#div-evaluaciones").append(InfPreg);
                                $.Hab_EnunPreg();
                         
                                $("#TextComp").val(respuesta.Banco.enunc_preg);

                                $.CargCompe_Compo(cons);

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
                                        '<input type="hidden" id="Tipreguntas' + cons +
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
                                        '    <button type="button" onclick="$.GuardarPregParte7(' +
                                        cons +
                                        ');" id="Btn-guardarPreg' + cons +
                                        '"   class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>' +
                                        '    <button type="button" id="Btn-EditPreg' +
                                        cons +
                                        '"  style="display:none;" onclick="$.EditPregParte7(' +
                                        cons +
                                        ')" class="btn mr-1 mb-1 btn-primary"><i class="fa fa-edit"></i> Editar</button>' +
                                        '    <button type="button" id="Btn-ElimPreg' +
                                        cons +
                                        '" onclick="$.DePregParte7(' + cons +
                                        ')" class="btn mr-1 mb-1 btn-danger"><i class="fa fa-trash-o"></i> Eliminar</button>' +
                                        '</div>' +
                                        '   </div>' +
                                        '</div>';
                                    $("#div-evaluaciones").append(Preguntas);
                                    var opciones = '';
                                    $.each(respuesta.PregMult, function(x, itemp) {

                                        if(cons===1){
                                            $('#competencia' + cons).val(itemp.competencia)
                                            .trigger('change.select2');
        
                                        $('#componente' + cons).val(itemp.componente)
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
                                        '<div class="row pb-2"><div class="col-md-6"><fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Competencia</span>' +
                                        '          </div>' +
                                        '          <input type="text" disabled id="CompeEdit' +
                                        cons + '" class="form-control"' +
                                        '     value="" placeholder="Competencia" aria-describedby="basic-addon2">' +
                                        '        </div>' +
                                        '      </fieldset></div>' +
                                        '<div class="col-md-6"><fieldset >' +
                                        '        <div class="input-group">' +
                                        '          <div class="input-group-append">' +
                                        '            <span class="input-group-text" id="basic-addon2">Componente</span>' +
                                        '          </div>' +
                                        '          <input type="text" disabled id="CompoEdit' +
                                        cons +
                                        '" class="form-control"' +
                                        '     value="" placeholder="Componente" aria-describedby="basic-addon2">' +
                                        '        </div>' +
                                        '      </fieldset></div></div>' +
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
                }

            });

            $.CagarPreguntas();

        });
    </script>
@endsection
