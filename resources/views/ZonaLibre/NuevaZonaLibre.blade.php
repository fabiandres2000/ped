@extends('Plantilla.Principal')
@section('title', 'Crear Tema')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIÓN DE ZONA LIBRE</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Crear Contenido Zona Libre
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
                            <h4 class="card-title">Crear Contenido Zona Libre</h4>
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
                                @include('ZonaLibre.FormZonaLibre', [
                                    'url' => '/Asignaturas/guardarTemasZonaLibre',
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

    {!! Form::open(['url' => '/cambiar/Periodos', 'id' => 'formAuxiliarPeri']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Unidad', 'id' => 'formAuxiliarUnid']) !!}
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
                    name: 'styles',
                    groups: ['styles']
                },
                {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo']
                },
                {
                    name: 'editing',
                    groups: ['selection', 'find', 'spellchecker', 'editing']
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
                    name: 'document',
                    groups: ['doctools', 'mode', 'document']
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
                'Source,Save,NewPage,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,HorizontalRule,Smiley,PageBreak,Iframe,ShowBlocks,About,Styles,Format';
        };


        $(document).ready(function() {

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var fecact = d.getFullYear() + '/' +
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day;



            $("#Men_Inicio").removeClass("active");
            $("#Men_Zona").addClass("active open");


            $('#fecha').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });

            $.extend({
                CargPeriodos: function(id) {

                    var form = $("#formAuxiliarPeri");
                    $("#idAsig").remove();
                    form.append("<input type='hidden' name='id' id='idAsig' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#periodo").html(respuesta.select_Periodo);
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
                            $("#unidad").html(respuesta.select_Unidades);
                        }

                    });
                },
                hab_edi: function() {
                    CKEDITOR.replace('summernoteCont', {});
                },
                hab_ediComent: function() {
                    CKEDITOR.replace('summernoteComent', {});

                },

                TipDoc: function(tipdoc) {
                    if (tipdoc === "DOCUMENTO") {
                        $("#titu_contenido").val("Nuevo Contenido " + fecact);
                        $("#Cont_documento").show();
                        $("#HabConDidact").show();
                        $.hab_edi();
                        $("#Archivo").hide();
                        $("#TipVideo").hide();
                        $("#Cont_Comentario").hide();
                        $("#TipUrl").hide();
                        $("#TipEva").hide();
                        $("#Cont_foro").hide();
                        $("#Cont_didactico").hide();
                        $("#TipGruPreg").hide();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#rowcofev").hide();
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                    } else if (tipdoc === "ARCHIVO") {
                        $("#titu_contenido").val("Nuevo Archivo " + fecact);
                        $("#Archivo").show();
                        $("#HabConDidact").hide();
                        $("#Cont_Comentario").hide();
                        $("#Cont_documento").hide();
                        $("#TipUrl").hide();
                        $("#TipVideo").hide();
                        $("#TipEva").hide();
                        $("#Cont_foro").hide();
                        $("#Cont_didactico").hide();
                        $("#TipGruPreg").hide();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#rowcofev").hide();
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                    } else if (tipdoc === "ANUNCIO") {
                        $("#titu_contenido").val("Nuevo Anuncio " + fecact);
                        $("#Cont_Comentario").show();
                        $("#Archivo").hide();
                        $("#HabConDidact").hide();
                        $("#TipVideo").hide();
                        $("#Cont_documento").hide();
                        $.hab_ediComent();
                        $("#TipUrl").hide();
                        $("#TipEva").hide();
                        $("#Cont_foro").hide();
                        $("#Cont_didactico").hide();
                        $("#TipGruPreg").hide();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#rowcofev").hide();
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                    } else if (tipdoc === "LINK") {
                        $("#titu_contenido").val("Nuevo Link " + fecact);
                        $("#TipDoc").hide();
                        $("#HabConDidact").hide();
                        $("#TipVideo").hide();
                        $("#TipUrl").show();
                        $("#TipEva").hide();
                        $("#Cont_Comentario").hide();
                        $("#Cont_documento").hide();
                        $("#Cont_didactico").hide();
                        $("#Cont_foro").hide();
                        $("#TipGruPreg").hide();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#Archivo").hide();
                        $("#rowcofev").hide();
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                    } else if (tipdoc === "EVALUACION") {
                        $("#rowtit").removeClass("col-md-9");
                        $("#rowtit").addClass("col-md-7");
                        $("#titu_contenido").val("Nueva Evaluación " + fecact);
                        $("#Archivo").hide();
                        $("#HabConDidact").hide();


                        $("#Cont_Comentario").hide();
                        $("#Cont_documento").hide();
                        $("#TipUrl").hide();
                        $("#TipEva").hide();
                        $("#Cont_foro").hide();
                        $("#TipGruPreg").hide();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#rowcofev").hide();

                        $("#rowcofev").show();
                        $("#TipEva").show();
                        $("#TipVideo").hide();
                    } else if (tipdoc === "VIDEOS") {
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                        $("#titu_contenido").val("Nuevo Video " + fecact);
                        $("#Archivo").hide();
                        $("#HabConDidact").hide();
                        $("#Cont_Comentario").hide();

                        $("#Cont_documento").hide();
                        $("#TipUrl").hide();
                        $("#TipEva").hide();
                        $("#Cont_foro").hide();
                        $("#TipGruPreg").hide();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#rowcofev").hide();

                        $("#TipVideo").show();
                        $("#TipEva").hide();
                    }
                },
                CamContEva: function(tipeva) {

                    if (tipeva === "GRUPREGUNTA") {
                        $("#TipGruPreg").show();
                        $("#TipMulPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#TipPregVerFal").hide();
                        $("#Cont_Comentario").hide();

                        $("#Cont_documento").hide();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        //                    $("#TipEva").hide();
                        $("#Cont_foro").hide();
                    } else if (tipeva === "OPCMULT") {
                        $("#TipMulPreg").show();
                        $("#TipGruPreg").hide();
                        $("#TipPregEnsay").hide();
                        $("#Cont_Comentario").hide();
                        $("#TipPregVerFal").hide();
                        $("#Cont_documento").hide();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        //                    $("#TipEva").hide();
                        $("#Cont_foro").hide();
                    } else if (tipeva === "PREGENSAY") {
                        $.hab_edipre();
                        $("#TipPregEnsay").show();
                        $("#TipMulPreg").hide();
                        $("#TipGruPreg").hide();
                        $("#Cont_Comentario").hide();
                        $("#TipPregVerFal").hide();
                        $("#Cont_documento").hide();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        //                    $("#TipEva").hide();
                        $("#Cont_foro").hide();
                    } else if (tipeva === "VERFAL") {
                        $("#TipPregVerFal").show();
                        $("#TipPregEnsay").hide();
                        $("#TipMulPreg").hide();
                        $("#TipGruPreg").hide();
                        $("#Cont_Comentario").hide();
                        $("#Cont_documento").hide();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        //                    $("#TipEva").hide();
                        $("#Cont_foro").hide();
                    }
                },
                CamContVideo: function(tipVid) {
                    $("#Cont_didactico").show();

                    if (tipVid === "LINK") {
                        $("#Div_link").show();
                        $("#Div_ContAnimaciones").hide();
                    } else {
                        $("#Div_ContAnimaciones").show();
                        $("#Div_link").hide();

                    }
                },
                selCheck: function(id) {
                    var nid = id.substr(-2);
                    if ($('#' + id).prop('checked')) {
                        $('#OpcCorecta' + nid).val("si");
                    } else {
                        $('#OpcCorecta' + nid).val("no");
                    }

                },
                DelLink: function(id_fila) {
                    $('#tr_' + id_fila).remove();
                    $.reordenarLink();
                    ConsAct = $('#Conslink').val() - 1;
                    $("#Conslink").val(ConsAct);
                },
                HabiContDid: function(val) {
                    if (val === "SI") {
                        $("#Div_ContDidactico").show();

                    } else {
                        $("#Div_ContDidactico").hide();

                    }
                },
                reordenarLink: function() {

                    var num = 1;
                    $('#tr_urls tr').each(function() {
                        $(this).find('td').eq(0).text(num);
                        num++;
                    });
                },
                DelPreg: function(id_fila) {
                    $('#RowGruPreg' + id_fila).remove();
                    ConsAct = $('#ConsGrupPreg').val() - 1;
                    $("#ConsGrupPreg").val(ConsAct);
                    //                $.ReordenarPreg();
                },
                DelVFPreg: function(id_fila) {
                    $('#RowGruPregVerFal' + id_fila).remove();
                    ConsAct = $('#ConsVerFal').val() - 1;
                    $("#ConsVerFal").val(ConsAct);

                },
                DelDid: function(id_fila) {
                    $('#but_' + id_fila).remove();
                    $('#imp_' + id_fila).remove();
                    ConsAnim = $('#ConsAnima').val() - 1;
                    $("#ConsAnima").val(ConsAnim);

                },
                DelLinkZon: function(id_fila) {
                    $('#dbut_' + id_fila).remove();
                    $('#dtext_' + id_fila).remove();
                    ConsAnim = $('#ConsLinkAnim').val() - 1;
                    $("#ConsLinkAnim").val(ConsAnim);
                },
                DelOpcPreg: function(id_fila) {
                    $('#RowOpcPreg' + id_fila).remove();
                    ConsAct = $('#ConsGrupPreg').val() - 1;
                    $("#ConsOpcMul").val(ConsAct);
                    //                $.ReordenarPreg();
                },
                UpdPunMax: function(val) {

                    $("#Punt_Max").val(val);

                },
                UpdPunMaxGP: function() {
                    var Topunt = 0;
                    $("input[name='txtpunt[]']").each(function(indice, elemento) {
                        Topunt = Topunt + parseInt($(elemento).val());
                    });
                    $("#Punt_Max").val(Topunt);

                },
                UpdPunMaxMP: function() {
                    var Topunt = 0;
                    $("input[name='PreMulPunt[]']").each(function(indice, elemento) {
                        Topunt = Topunt + parseInt($(elemento).val());
                    });
                    $("#Punt_Max").val(Topunt);

                },
                UpdPunMaxVF: function() {
                    var Topunt = 0;
                    $("input[name='txtpuntVerFal[]']").each(function(indice, elemento) {
                        Topunt = Topunt + parseInt($(elemento).val());
                    });
                    $("#Punt_Max").val(Topunt);

                },
                AbrirConfEval: function() {
                    $("#ModConfEval").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                Guardar: function() {

                    if ($("#grado").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Debe seleccionar el Grado.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    } else if ($("#grupo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Debe seleccionar el Grupo.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    } else if ($("#jornada").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Debe seleccionar la Jornada.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    } else if ($("#tip_contenido").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Debe seleccionar el tipo de contenido.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    } else if ($("#fecha").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Debe seleccionar una fecha.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    } else if ($("#titu_contenido").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Ingrese el Título de Contenido.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#tip_contenido").val() === "VIDEOS" && $("#tip_video").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Seleccione el tipo de Video a Subir.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    } else {
                        if ($("#tip_video").val() === "ARCHIVO" && $("#file").val() === "") {
                            Swal.fire({
                                title: "Gestionar Zona Libre",
                                text: "Seleccione el Video a Cargar.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }

                        var flag = "ok";
                        $('.LinkVideo').each(function() {
                            if ($(this).val() === "") {
                                flag = "no";
                            }
                        });



                        if ($("#tip_video").val() === "LINK" && flag === "no") {
                            Swal.fire({
                                title: "Gestionar Zona Libre",
                                text: "Ingrese el Link a Compartir.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }

                    }




                    if ($("#tip_contenido").val() === "ARCHIVO" && $("#fileArchivo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Seleccione el Archivo a Cargar.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }



                    var totlink = $(".btnQuitarLink").length;

                    if ($("#tip_contenido").val() === "LINK" && totlink < 1) {
                        Swal.fire({
                            title: "Gestionar Zona Libre",
                            text: "Ingrese el Link a Compartir.",
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
                    $("#formZona").submit();
                },

            });
            //======================AGREGAR ANIMACIONES=======================\\
            $("#AddAnimaciones").on({
                click: function(e) {
                    var cons = $("#ConsAnima").val();

                    e.preventDefault();
                    var Animaciones = "<div id='imp_" + cons +
                        "' style='padding-top: 10px;' class='col-md-11'>" +
                        "<input id='file' class='form-control' name='archididatico[]' type='file'>" +
                        "</div>";
                    Animaciones += "<div id='but_" + cons +
                        "' style='padding-top: 10px;' class='col-md-01'>" +
                        "<button type='button' onclick='$.DelDid(" + cons +
                        ")'  class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        "</div>";

                    $("#Arch_Didact").append(Animaciones);
                }
            });
            //======================AGREGAR LINK VIDEOS=======================\\

            $("#AddLinkVideo").on({
                click: function(e) {
                    var cons = $("#ConsLinkAnim").val();

                    e.preventDefault();
                    var Link = "<div id='dtext_" + cons +
                        "' style='padding-top: 10px;' class='col-md-11'>" +
                        "<input id='Text_Link' class='form-control LinkVideo' name='linkVideo[]' type='text'>" +
                        "</div><br>" +
                        "<div id='dbut_" + cons + "' style='padding-top: 10px;' class='col-md-01'>" +
                        "<button type='button' onclick='$.DelLinkZon(" + cons +
                        ")'  class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        "</div><br>";

                    $("#Arch_Link").append(Link);
                }
            });

            //======================EVENTO AGREGAR GRUPO DE PREGUNTA=======================\\
            $("#AddGruPregunta").on({
                click: function(e) {
                    e.preventDefault();
                    var cons = $("#ConsGrupPreg").val();
                    var preguntas = "<div class='row' id='RowGruPreg" + cons +
                        "'><div class='col-lg-9'>" +
                        "<label id='LabelGruPreg" + cons + "' class='form-label' >Pregunta " + cons +
                        ":</label>" +
                        "<input type='text' class='form-control' name='txtpreg[]' id='preg' placeholder='Pregunta' value=''/> " +
                        "</div>" +
                        "<div class='col-lg-2'>" +
                        "<label class='form-label' >Puntaje:</label>" +
                        "<input type='text' class='form-control' name='txtpunt[]' onchange='$.UpdPunMaxGP()' id='' onkeypress='return validartxtnum(event)' placeholder='Puntaje' value=''/>" +
                        "</div>" +
                        "<div class='col-lg-1'>" +
                        "<label class='form-label' >&nbsp;</label><br>" +
                        "<button type='button' onclick='$.DelPreg(" + cons +
                        ")'  class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        "</div></div>"

                    $("#RowGrup").append(preguntas);
                    $("#ConsGrupPreg").val(parseFloat(cons) + 1);
                }
            });
            //=============================================\\

            //======================EVENTO AGREGAR PREGUNTA OPCION MULTIPLE=======================\\

            $("#AddPre").on({
                click: function(e) {

                    e.preventDefault();

                    var cons = parseFloat($("#ConsPregMul").val()) + 1;
                    var consopc = parseFloat($("#ConsOpcMulPreg").val()) + 1;


                    var preguntas = "  <div class='row'>" +
                        "<div class='col-lg-10'>" +
                        "    <div class='form-group'>" +
                        "        <label class='form-label' for='porc_modulo'>Pregunta " + cons +
                        ":</label>" +
                        "        <input type='text' class='form-control' name='PreMulResp[]' id='preg' placeholder='Pregunta' value=''/> " +
                        "    </div>" +
                        "    </div>" +
                        "    <div class='col-lg-2'>" +
                        "            <div class='form-group'>" +
                        "                <label class='form-label' for='porc_modulo'>Puntaje:</label>" +
                        "                <input type='text' class='form-control' onchange='$.UpdPunMaxMP()' name='PreMulPunt[]' id='preg' placeholder='Puntaje' value=''/>" +
                        "           </div>" +
                        "       </div>" +
                        "   </div>" +
                        "  <div id='RowMulPreg" + cons + "'>" +
                        "    <div class='row top-buffer' id='RowOpcPreg1' style='padding-bottom: 15px;'>" +
                        "    <div class='col-lg-11' >" +
                        "        <div class='input-group' style='padding-bottom: 10px;' >" +
                        "            <div class='input-group-prepend'>" +
                        "                <div class='input-group-text'>" +
                        "                    <input aria-label='Checkbox for following text input' id='checkopcpreg" +
                        cons + "1'  onclick='$.selCheck(this.id);' value='off'  type='checkbox'>" +
                        "                    <input type='hidden' id='OpcCorecta" + cons +
                        "1'  name='OpcCorecta" + cons + "[]' value='no'/>" +
                        "                </div>" +
                        "            </div>" +
                        "            <input class='form-control' placeholder='Opción 1' aria-label='Text input with radio button' name='txtopcpreg" +
                        cons + "[]' type='text'>" +
                        "        </div>" +
                        "    </div>" +
                        "    <div class='col-lg-1'>" +
                        "    </div>" +
                        "  </div>" +
                        "   </div>";

                    $("#rowpreg").append(preguntas);
                    $("#ConsOpcMul").val('2');
                    $("#ConsPregMul").val(cons);
                    $("#ConsOpcMulPreg").val(consopc);


                }
            });
            //=============================================\\   

            //======================EVENTO AGREGAR OPCION MULTIPLE=======================\\

            $("#AddOpcPre").on({
                click: function(e) {

                    e.preventDefault();

                    var consPre = $("#ConsPregMul").val();
                    var consOpc = $("#ConsOpcMulPreg").val();
                    var cons = $("#ConsOpcMul").val();
                    var preguntas = "<div class='row top-buffer' id='RowOpcPreg" + consOpc +
                        "' style='padding-bottom: 15px;'>" +
                        "    <div class='col-lg-11' >" +
                        "       <div class='input-group' style='padding-bottom: 10px;' >" +
                        "            <div class='input-group-prepend'>" +
                        "                <div class='input-group-text'>" +
                        "                    <input aria-label='Checkbox for following text input'value='off'  onclick='$.selCheck(this.id);' id='checkopcpreg" +
                        consPre + cons + "'  type='checkbox'>" +
                        "                    <input type='hidden'  id='OpcCorecta" + consPre + cons +
                        "' n0me='OpcCorecta" + consPre + "[]'  value='no'/>" +
                        "             </div>" +
                        "            </div>" +
                        "            <input class='form-control' placeholder='Opción " + cons +
                        "' aria-label='Text input with radio button' name='txtopcpreg" + consPre +
                        "[]' value=''  type='text'>" +
                        "        </div>" +
                        "     </div>" +
                        "     <div class='col-lg-1'>" +
                        "         <button type='button' onclick='$.DelOpcPreg(" + consOpc +
                        ")' class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        "     </div>" +
                        " </div>";

                    $("#RowMulPreg" + consPre).append(preguntas);
                    $("#ConsOpcMul").val(parseFloat(cons) + 1);


                }
            });
            //=============================================\\   

            //======================EVENTO AGREGAR PREGUNTA OPCION MULTIPLE=======================\\

            $("#AddVerFal").on({
                click: function(e) {

                    e.preventDefault();
                    var cons = $("#ConsVerFal").val();
                    var preguntas = "<div class='row' id='RowGruPregVerFal" + cons +
                        "' style='padding-bottom: 15px;'>" +
                        " <div class='col-lg-7'>" +
                        "<label class='form-label' for='porc_modulo'>Pregunta " + cons + ":</label>" +
                        "<input type='text' class='form-control' name='txtpregVerFal[]' id='preg' placeholder='Pregunta' value=''/>" +
                        "</div>" +
                        " <div class='col-lg-1 ' style='margin-top: 15px;text-align: center;'>" +
                        "     <label class='form-label' for='porc_modulo'>Verdadero:</label><br>" +
                        " <input  name='radpregVerFal" + cons + "[]' value='si' type='radio'>" +
                        " </div>" +
                        " <div class='col-lg-1 ' style='margin-top: 15px;text-align: center;'>" +
                        "    <label class='form-label' for='porc_modulo'>Falso:</label><br>" +
                        "     <input name='radpregVerFal" + cons + "[]' value='no' type='radio'>" +
                        " </div>" +
                        " <div class='col-lg-2 '>Puntaje:</label><br>" +
                        " <input type='text' class='form-control' id='' name='txtpuntVerFal[]' onchange='$.UpdPunMaxVF()' placeholder='Puntaje' value=''/> " +
                        " </div>" +
                        "<div class='col-lg-1'><br>" +
                        "  <button type='button' onclick='$.DelVFPreg(" + cons +
                        ")' class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        " </div>" +
                        "     </div>";

                    $("#RowGruPregVerFal").append(preguntas);
                    $("#ConsVerFal").val(parseFloat(cons) + 1);


                }
            });
            //=============================================\\   

            //======================EVENTO AGREGAR LINK=======================\\
            $("#AddLink").on({
                click: function(e) {

                    e.preventDefault();
                    var url = $("#url_tema").val();
                    if (url === "") {
                        Swal.fire('Error!', 'Ingrese una Url...', 'warning');
                        return false;
                    }
                    var cons = $("#Conslink").val();
                    var style = 'text-transform: uppercase;background-color:white;';
                    var clase = 'form-control form-control-sm LinkVideo2';
                    var campo = "";
                    campo += "<tr id='tr_" + cons + "'>";
                    campo += "<td>";
                    campo += cons;
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<input type='hidden' id='txturl' name='txturl[]' class='" + clase +
                        "' readonly style='" + style + "' value='" + url + "'>";
                    campo += url;
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<a onclick='$.DelLink(" + cons +
                        ")' class='btn btn-danger btn-sm btnQuitarLink text-white' title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "</td>";
                    campo += "</tr>";
                    $("#tr_urls").append(campo);

                    $("#Conslink").val(parseFloat(cons) + 1);
                    $("#url_tema").val("");
                }
            });
            //======================EVENTO AGREGAR PERIODOS=======================\\

        });

        $('#summernote').focus();

        function validartxtnum(e) {
            tecla = e.which || e.keyCode;
            patron = /[0-9]+$/;
            te = String.fromCharCode(tecla);
            return (patron.test(te) || tecla == 9 || tecla == 8 || tecla == 37 || tecla == 39 || tecla == 44);
        }

        function validartxt(e) {
            tecla = e.which || e.keyCode;
            patron = /[a-zA-Z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF\s]+$/;
            te = String.fromCharCode(tecla);
            return (patron.test(te) || tecla == 9 || tecla == 8 || tecla == 37 || tecla == 39 || tecla == 46);
        }
    </script>
@endsection
