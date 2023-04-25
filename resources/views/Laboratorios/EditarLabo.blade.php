@extends('Plantilla.Principal')
@section('title', 'Editar Laboratorio')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIONAR LABORATORIOS</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Laboratorio
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
                            <h4 class="card-title">Editar Laboratorio</h4>
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
                                @include('Laboratorios.FormLaboratorio',
                                ['url'=>'/Laboratorios/ModificarLabo/'.$Laboratorio->id,
                                'method'=>'put'
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

    {!! Form::open(['url' => '/cambiar/Periodos2', 'id' => 'formAuxiliarPeri']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Unidad2', 'id' => 'formAuxiliarUnid']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Laboratorios/BuscaInfLaboratorio', 'id' => 'formAuxiliarLaboratorio']) !!}
    {!! Form::close() !!}


    {!! Form::open(['url' => '/BuscarInf/Archivos', 'id' => 'formAuxiliarArchivos']) !!}
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

            ///////////////////CONFIGURACION EDITOR


            ///////////////
            $("#Men_Inicio").removeClass("active");
            $("#Men_Laboratorios").addClass("active");
            $.extend({
                CargPeriodos: function() {

                    var form = $("#formAuxiliarPeri");
                    var id = $("#modulo").val();
                    var idPer = $("#labo_periodo").val();
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
                GuardarLabo: function() {
                    if ($("#modulo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Laboratorios",
                            text: "Debe seleccionar la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#periodo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Laboratorios",
                            text: "Debe seleccionar el Periodo.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($("#unidad").val() === "") {
                        Swal.fire({
                            title: "Gestionar Laboratorios",
                            text: "Debe seleccionar la Unidad.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }
                    if ($("#titulo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Laboratorios",
                            text: "Ingrese el Título del Laboratorio.",
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
                    $("#formLaboratorio").submit();
                },
                CargUnidades: function() {

                    var form = $("#formAuxiliarUnid");
                    var idPer = $("#periodo").val();
                    var idUnid = $("#labo_unidad").val();
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
                hab_editTeoria: function() {
                    CKEDITOR.replace('summernoteTeoria', {

                    });
                },
                hab_editMateriales: function() {
                    CKEDITOR.replace('summernoteMateriales', {

                    });
                },
                hab_editProced: function(i) {

                    CKEDITOR.replace('summernoteProce' + i, {
                        width: '100%',
                        height: 100
                    });

                },
                DelProc: function(id_fila) {
                    $('#proc' + id_fila).remove();
                    ConsAct = $('#ConsProc').val() - 1;
                    $("#ConsProc").val(ConsAct);
                },
                CambVideo: function(id) {
                    $("#id_verf" + id).hide();
                    $("#id_file" + id).show();
                },
                MostVidProc: function(id) {
                    var videoID = 'videoclip';
                    var sourceID = 'mp4video';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#dat-vid').data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    //$('#'+videoID).attr('poster', newposter); //Change video poster
                    $('#' + videoID).get(0).play();

                    $("#ModVidelo").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                SelArchivo: function(id) {
                    var archivos = document.getElementById('file' + id).files;
                    for (x = 0; x < archivos.length; x++) {
                        $("#ArchivoSel" + id).val("si/"+archivos[x].name);
                    }
                
                },
                CargInfLabo: function() {

                    var form = $("#formAuxiliarLaboratorio");
                    var IdLabo = $("#labo_id").val();
                    $("#IdLabo").remove();
                    form.append("<input type='hidden' name='IdLabo' id='IdLabo' value='" + IdLabo +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();

                    var Procesos = '';
                    $("#DivProcedimientos").html("");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            var j = 1;

                            $('#summernoteTeoria').val(respuesta.DesLabo.fund_teorico);
                            $('#summernoteMateriales').val(respuesta.DesLabo.materiales);

                            $.each(respuesta.ProcLabo, function(i, item) {

                                Procesos += '<div id="proc' + j + '">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-2">' +
                                    ' <h4 class="primary">Procedimiento ' + j +
                                    '</h4>' +
                                    ' <textarea cols="80" id="summernoteProce' + j +
                                    '" name="TextProce[]" rows="3"></textarea>';
                                if (item.vide_proced !== "") {
                                    Procesos +=
                                        '<br><div class="form-group" id="id_file' +
                                        j + '" style="display:none;">' +
                                        '  <input id="file' + j + '" onchange="$.SelArchivo(' + j +');" accept="video/*"  class="form-control" name="VideoProceso[]" type="file">' +
                                        '  </div>' +
                                        '<input type="hidden" class="form-control" name="ArchivoSel[]" id="ArchivoSel' +
                                        j + '"  value="no/'+item.vide_proced+'"/>' +
                                        ' <div cclass="form-group" id="id_verf' +
                                        j + '" >' +
                                        '<div class="btn-group" role="group" aria-label="Basic example">' +
                                        '<button type="button" onclick="$.MostVidProc(this.id);" id="' +
                                        item.id + '" data-archivo="' + item
                                        .vide_proced +
                                        '" class="btn btn-success"><i class="fa fa-search"></i> Ver Video</button>' +
                                        '            <button type="button" onclick="$.CambVideo(' +
                                        j +
                                        ');" class="btn btn-warning"><i class="fa fa-refresh"></i> Cambiar Video</button>' +
                                        '          </div>' +
                                        '          </div>';
                                } else {
                                    Procesos +=
                                        '  <input id="file' + j + '" onchange="$.SelArchivo(' + j +');"  class="form-control" name="VideoProceso[]" type="file">'+
                                        '<input type="hidden" class="form-control" name="ArchivoSel[]" id="ArchivoSel' +
                                        j + '"  value="no/"/>'+
                                        '  <input type="hidden" class="form-control" name="VideoProcmi[]"  value="' +
                                        item.vide_proced + '"/>';

                                }
                                if(j>1){
                                    Procesos +=
                                    '<br><button id="DelProc" onclick="$.DelProc(' +
                                    j +
                                    ')" type="button" class="btn mr-1 btn-warning"><i class="fa fa-trash-o"></i> Eliminar Procedimiento</button>' ;
                                }
                              


                                    Procesos+='   </div>' +
                                    '</div>';
                                j++;

                            });

                            $("#ConsProc").val(j - 1);

                            var j = 1;
                            $("#DivProcedimientos").html(Procesos);

                            $.each(respuesta.ProcLabo, function(i, item) {
                                $.hab_editProced(j);
                                $('#summernoteProce' + j).val(item.procedimiento);
                                j++;
                            });
                        }
                    });
                }
            });



            $.CargPeriodos();
            $.CargUnidades();
            $.hab_editTeoria();
            $.hab_editMateriales();
            $.CargInfLabo();

   
            $("#AddProc").on({
                click: function(e) {

                    e.preventDefault();

                    var cons = parseFloat($("#ConsProc").val()) + 1;

                    var Procesos = '<div id="proc' + cons + '">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-2">' +
                        ' <h4 class="primary">Procedimiento ' + cons + '</h4>' +
                        ' <textarea cols="80" id="summernoteProce' + cons +
                        '" name="TextProce[]" rows="3"></textarea>' +
                        '  <input id="file' + cons + '" onchange="$.SelArchivo(' + cons +');" class="form-control" value="Agregar" name="VideoProceso[]" type="file">' +
                        '<input type="hidden" class="form-control" name="ArchivoSel[]" id="ArchivoSel' +
                        cons + '"  value="no/"/>' +
                        '  <br><button id="DelProc" onclick="$.DelProc(' + cons +
                        ')" type="button"  class="btn mr-1  btn-warning"><i class="fa fa-trash-o"></i> Eliminar Procedimiento</button>' +
                        '   </div>' +
                        '</div>';

                    $("#DivProcedimientos").append(Procesos);
                    $("#ConsProc").val(cons);
                    $.hab_editProced(cons);

                }
            });
        });
    </script>
@endsection
