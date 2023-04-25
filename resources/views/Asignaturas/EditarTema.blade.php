@extends('Plantilla.Principal')
@section('title', 'Editar Tema')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 ">
            <h3 class="content-header-title mb-0">GESTIÓN DE TEMAS</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Tema
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
                            <h4 class="card-title">Editar Tema</h4>
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
                                @include('Asignaturas.FormTemas', [
                                    'url' => '/Asignaturas/ModificarTema/' . $Tema->id,
                                    'method' => 'put',
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

    {!! Form::open(['url' => '/BuscarInf/Documentos', 'id' => 'formAuxiliarDocumento']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/DocumentosDida', 'id' => 'formAuxiliarDocumentoDida']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/Archivos', 'id' => 'formAuxiliarArchivos']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/DelArcTema/DelArchivos', 'id' => 'formAuxiliarDelArchivos']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/DelAnimTema/DelAnimacion', 'id' => 'formDelAnimacion']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/DelAnimTema/DelVideo', 'id' => 'formAuxiliarDelArchivosvideo']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/Links', 'id' => 'formAuxiliarLinks']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/Eval', 'id' => 'formAuxiliarPreg']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/docentesEdit', 'id' => 'formAuxiliarCargDocentes']) !!}
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
        $(document).ready(function() {

            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var fecact = d.getFullYear() + '/' +
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day;

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
                        name: 'editing',
                        groups: ['find', 'selection', 'spellchecker', 'editing']
                    },
                    {
                        name: 'forms',
                        groups: ['forms']
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
                    '/',
                    '/',
                    {
                        name: 'styles',
                        groups: ['styles']
                    },
                    {
                        name: 'basicstyles',
                        groups: ['basicstyles', 'cleanup']
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
                    'Source,Save,NewPage,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,Smiley,PageBreak,Iframe,ShowBlocks,About';
            };

            ///////////////
            $("#Men_Inicio").removeClass("active");
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
                TipDoc: function(tipdoc) {
                    if (tipdoc === "DOCUMENTO") {
                        $("#tit_cont").val("Nuevo Contenido " + fecact);
                        $("#Cont_documento").show();
                        $.hab_edi();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        $("#ArchivoVideo").hide();
                    } else if (tipdoc === "ARCHIVO") {
                        $("#tit_cont").val("Nuevo Archivo " + fecact);
                        $("#Archivo").show();
                        $("#Cont_documento").hide();
                        $("#TipUrl").hide();
                        $("#ArchivoVideo").hide();
                    } else if (tipdoc === "LINK") {
                        $("#tit_cont").val("Nuevo Link " + fecact);
                        $("#TipDoc").hide();
                        $("#TipUrl").show();
                        $("#Archivo").hide();
                        $("#ArchivoVideo").hide();
                    } else if (tipdoc === "CONTENIDO DIDACTICO") {
                        $("#titu_contenido").val("Nuevo Video " + fecact);
                        $("#ArchivoVideo").show();
                        $.hab_ediContDidac();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        $("#Cont_documento").hide();

                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");

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
                Visualizar: function() {
                    $("#Visualizar").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#prueba").html($("#summernoteCont").val());
                },
                hab_edi: function() {

                    CKEDITOR.replace('summernoteCont', {

                    });
                    //                $("#cont_sumer").html('<textarea name="summernoteCont"  id="summernoteCont" class="summernote"></textarea>');
                    //                $('#summernoteCont').summernote({
                    //                    focus: true,
                    //                    height: 300, //set editable area's height
                    //                    codemirror: {// codemirror options
                    //                        theme: 'monokai'
                    //
                    //                    }});
                },
                hab_edipre: function() {
                    $("#preg_sumer").html(
                        '<textarea name="summernote_pregensay" id="summernote_pregensay" rows="6" class="summernote"></textarea>'
                        );
                    $('#summernote_pregensay').summernote({
                        focus: true,
                        height: 200, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
                    });
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
                CargTipCont: function() {
                    var tipcont = $("#tip_contenido").val();
                    var habAni = $("#hab_cont_didact").val();
                    if (tipcont == "DOCUMENTO") {
                        $("#Cont_documento").show();
                        $("#HabConDidact").show();
                        if (habAni === "SI") {
                            $("#Div_ContDidactico").show();
                            $("#ListAnima").show();
                        }
                        var form = $("#formAuxiliarDocumento");
                        var IdTema = $("#tema_id").val();
                        $.hab_edi();

                        //                    $("#cont_sumer").html('<textarea name="summernoteCont"  id="summernoteCont" class="summernote"></textarea>');
                        $("#IdTema").remove();
                        form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var TrAnimaciones = "";
                        var j = 1;
                        $.ajax({
                            type: "POST",
                            url: url,
                            async: false,
                            data: datos,
                            dataType: "json",
                            success: function(respuesta) {
                                $('#summernoteCont').val(respuesta.content);
                                $('#HabConv').val(respuesta.DesTema.hab_conversacion)
                                    .trigger('change.select2');

                                $.each(respuesta.Animaciones, function(i, item) {
                                    TrAnimaciones += '<tr id="trAn_' + item.id +
                                        '">' +
                                        '<td class="text-truncate">' + j + '</td>' +
                                        '<td class="text-truncate">' + item.titulo +
                                        '</td>' +
                                        '<td class="text-truncate">' +
                                        '<a onclick="$.MostAnim(this.id)" id="' +
                                        item.id + '"  data-archivo="' + item
                                        .cont_didactico +
                                        '" data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}"  class="btn btn-primary btn-sm btnVer text-white"  title="Ver Animación"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '<a onclick="$.DelAnim(' + item.id +
                                        ')" class="btn btn-danger btn-sm btnQuitar text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;

                                });
                                $("#tr_animaciones").html(TrAnimaciones);


                            }
                        });
                    } else if (tipcont == "CONTENIDO DIDACTICO") {
                        $("#ArchivoVideo").show();
                        var form = $("#formAuxiliarDocumentoDida");
                        var HabConv = "";
                        var IdTema = $("#tema_id").val();
                        $("#IdTema").remove();
                        form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        var TrArc = '';
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            dataType: "json",
                            success: function(respuesta) {
                                $.each(respuesta.DesTema, function(i, item) {

                                    TrArc += '<tr id="tr_' + item.id + '">' +
                                        '<td class="text-truncate">' + j + '</td>' +
                                        '<td class="text-truncate">' + item.titulo +
                                        '</td>' +
                                        '<td class="text-truncate">' +
                                        '<a onclick="$.MostArcVideo(this.id)" id="' +
                                        item.id + '"  data-archivo="' + item
                                        .cont_didactico +
                                        '" data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}"  class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '<a onclick="$.DelArcVideo(' + item.id +
                                        ')" class="btn btn-danger btn-sm btnQuitarVideo text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;

                                });
                               
                                $("#tr_archivos_videos").html(TrArc);
                            }
                        });
                    } else if (tipcont == "ARCHIVO") {
                        $("#Archivo").show();
                        var form = $("#formAuxiliarArchivos");
                        var HabConv = "";
                        var IdTema = $("#tema_id").val();
                        $("#IdTema").remove();
                        form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        var TrArc = '';
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            dataType: "json",
                            success: function(respuesta) {
                                $.each(respuesta.Archivos, function(i, item) {
                                   
                                    TrArc += '<tr id="tr_' + item.id + '">' +
                                        '<td class="text-truncate">' + j + '</td>' +
                                        '<td class="text-truncate">' + item
                                        .nom_arch + '</td>' +
                                        '<td class="text-truncate">' +
                                        '<a onclick="$.MostArc(this.id)" id="' +
                                        item.id + '"  data-archivo="' + item
                                        .nom_arch +
                                        '" data-ruta="{{ asset('/app-assets/Archivos_Contenidos') }}"  class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '<a onclick="$.DelArc(' + item.id +
                                        ')" class="btn btn-danger btn-sm btnQuitar text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;
                                  
                                });
                                $("#tr_archivos").html(TrArc);
                              
                            }
                        });
                    } else if (tipcont == "LINK") {
                        $("#TipUrl").show();
                        var form = $("#formAuxiliarLinks");
                        var HabConv = "";
                        var IdTema = $("#tema_id").val();
                        $("#IdTema").remove();
                        form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        var TrUrl = '';
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            dataType: "json",
                            success: function(respuesta) {

                                $.each(respuesta.Links, function(i, item) {
                                    $("#titu_tema").html(item.titulo);
                                    TrUrl += '<tr id="tr_' + j + '">' +
                                        '<td class="text-truncate">' + j + '</td>' +
                                        '<td class="text-truncate">' + item.url +
                                        '</td>' +
                                        '<td class="text-truncate"><input type="hidden" id="txturl" name="txturl[]" value="' +
                                        item.url + '">' +
                                        '<a onclick="$.DelLink(' + j +
                                        ')" class="btn btn-danger btn-sm btnQuitar text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;
                                 
                                });
                                $("#Conslink").val(j);
                                $("#tr_urls").html(TrUrl);
                                $('#HabConv').val(HabConv).trigger('change.select2');
                            }

                        });
                    }
                },
                MostArc: function(id) {
                    var nomarchi = $('#' + id).data("archivo");
                    var ext = nomarchi.substring(nomarchi.lastIndexOf("."));
                    if (ext != ".jpg" && ext != ".png" && ext != ".gif" && ext != ".jpeg" && ext !=
                        ".pdf") {
                        window.open($('#' + id).data("ruta") + "/" + nomarchi, '_blank');
                    } else {
                        $("#large").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $("#div_arc").html(
                            '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                            );
                        jQuery('#embed_arch').attr('src', $('#' + id).data("ruta") + "/" + nomarchi);
                    }
                },

                MostArcVideo: function(id) {
                    $("#largeVideo").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                   
                    var videoID = 'videoclipAnima';
                    var sourceID = 'mp4videoAnima';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#' + id).data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    $('#' + videoID).get(0).play();
                },
                HabiContDid: function(val) {
                    if (val === "SI") {
                        $("#Div_ContDidactico").show();

                    } else {
                        $("#Div_ContDidactico").hide();

                    }
                },
                DelArc: function(id) {
                    var form = $("#formAuxiliarDelArchivos");
                    $("#id").remove();
                    form.append("<input type='hidden' name='id' id='id' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            swal.fire({
                                title: "",
                                text: respuesta.mensaje,
                                icon: "success",
                                button: "Aceptar",
                            });
                            if (respuesta.estado === "ok") {
                                $("#tr_" + id).hide();
                            }
                        }

                    });
                },
                DelArcVideo: function(id) {
                    var form = $("#formAuxiliarDelArchivosvideo");
                    $("#id").remove();
                    form.append("<input type='hidden' name='id' id='id' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            swal.fire({
                                title: "",
                                text: respuesta.mensaje,
                                icon: "success",
                                button: "Aceptar",
                            });
                            if (respuesta.estado === "ok") {
                                $("#tr_" + id).hide();
                            }
                        }

                    });
                },
                DelAnim: function(id) {
                    mensaje = "¿Desea Eliminar esta Animación?";

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
                            var form = $("#formDelAnimacion");
                            $("#id").remove();
                            form.append("<input type='hidden' name='id' id='id' value='" + id +
                                "'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                dataType: "json",
                                success: function(respuesta) {
                                    Swal.fire({
                                        title: "Gestionar Temas",
                                        text: respuesta.mensaje,
                                        icon: "success",
                                        button: "Aceptar"
                                    });
                                    if (respuesta.estado === "ok") {

                                        $("#trAn_" + id).remove();
                                    }
                                }

                            });
                        }
                    });
                },
                DelLink: function(id_fila) {
                    $('#tr_' + id_fila).remove();
                    $.reordenarLink();
                    ConsAct = $('#Conslink').val() - 1;
                    $("#Conslink").val(ConsAct);
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
                ReordenarPreg: function() {
                    var num = 1;
                    $('#RowGrup div').each(function() {
                        $(this).find('label').eq(0).text('Pregunta ' + num);
                        num++;
                    });
                },
                GuardarTema: function() {
                    if ($('#modulo').val() === "") {
                        Swal.fire({
                            title: "Administrar Temas",
                            text: "Seleccione la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#periodo').val() === "") {
                        Swal.fire({
                            title: "Administrar Grados",
                            text: "Seleccione el Periodo.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#unidad').val() === "") {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Seleccione la Unidad.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#tip_contenido').val() === "") {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Seleccione el Tipo de Contenido.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#titu_contenido').val() === "") {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Ingrese el Título.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#objetivo_general').val() === "") {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Ingrese el Objetivo General.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var flag = "ok";
                    $('.Selanima').each(function() {
                        if ($(this).val() === "") {
                            flag = "no";
                        }
                    });

                    var totvid = $(".btnQuitar").length;

                    if ($('#tip_contenido').val() === "DOCUMENTO" && $('#hab_cont_didact').val() ===
                        "SI" && flag === "no" && totvid < 1) {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Seleccione el Video a Cargar",
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

                    $("#formTema").submit();
                },
                UpdPunMax: function(val) {

                    $("#Punt_Max").val(val);
                },
                DelVFPreg: function(id_fila) {
                    $('#RowGruPregVerFal' + id_fila).remove();
                    ConsAct = $('#ConsVerFal').val() - 1;
                    $("#ConsVerFal").val(ConsAct);
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
                DelOpcPreg: function(id_fila) {
                    $('#RowOpcPreg' + id_fila).remove();
                    ConsAct = $('#ConsGrupPreg').val() - 1;
                    $("#ConsOpcMul").val(ConsAct);
                    //                $.ReordenarPreg();
                },
                CambVid: function() {
                    $("#Div_ArcDidac").show();
                    $("#Div_VerImg").hide();
                },
                DelDid: function(id_fila) {
                    $('#but_' + id_fila).remove();
                    $('#imp_' + id_fila).remove();
                    ConsAnim = $('#ConsAnima').val() - 1;
                    $("#ConsAnima").val(ConsAnim);

                },
                MostAnim: function(id) {
                    var videoID = 'videoclip';
                    var sourceID = 'mp4video';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#' + id).data("ruta") + "/" + nomarchi;
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
                cargarDocentes: function() {

                    $("#ModCompartir").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    var Tabla="";
                    var j=1;

                    var modu_id= $("#tema_modulo").val();
                    var tema_id= $("#tema_id").val();
                    var form = $("#formAuxiliarCargDocentes");
                    $("#idTema").remove();
                    $("#idMod").remove();
                    form.append("<input type='hidden' name='idMod' id='idMod' value='" + modu_id + "'>");
                    form.append("<input type='hidden' name='idTem' id='idTema' value='" + tema_id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            if (Object.keys(respuesta.Docentes).length > 0) {
                                $.each(respuesta.Docentes, function(i, item) {
                                    Tabla += " <tr data-id='" + item.id +
                                        "' id='Alumno" + item.id + "'>";
                                    Tabla += "<td class='text-truncate'>" + j +
                                        "</td> ";
                                    Tabla += "<td class='text-truncate'>" + item.ndocente+ "</td> ";
                                    Tabla +=
                                        "<input type='hidden' name='idDocente[]' value='" + item.usuario_profesor + "'>"+
                                        "<input type='hidden' name='grupo[]' value='" + item.grupo + "'>"+
                                        "<input type='hidden' name='jornada[]' value='" + item.jornada + "'>";

                                        if($("#id_usuario").val()==item.usuario_profesor){
                                            Tabla +=   "<input type='hidden' id='DoceSel" + j +"' name='DoceSel[]' value='si'>"+
                                            "<td class='text-truncate text-center'>"+
                                            "<input type='checkbox' onclick='$.SelDocente(" + j + ");' id='CheckSeleccion" + j +"' style='cursor: pointer;' disabled checked name='checkDocenteSel' value=''>";
                                        }else{
                                            if(item.idtemdoc!==null){
                                                Tabla += "<input type='hidden' id='DoceSel" + j +"' name='DoceSel[]' value='si'>"+
                                                "<td class='text-truncate text-center'>"+
                                                "<input type='checkbox' onclick='$.SelDocente(" + j + ");' id='CheckSeleccion" + j +"' style='cursor: pointer;' checked name='checkDocenteSel' value=''>";
                                            }else{
                                                Tabla += "<input type='hidden' id='DoceSel" + j +"' name='DoceSel[]' value='no'>"+
                                                "<td class='text-truncate text-center'>"+
                                                "<input type='checkbox' onclick='$.SelDocente(" + j + ");' id='CheckSeleccion" + j +"' style='cursor: pointer;' name='checkDocenteSel' value=''>";
                                            }
                                        }

                                        Tabla += "</td> ";
                                    Tabla += " </tr>";
                                    j++;
                                });
                                $("#td-alumnos").html(Tabla);
                            } else {
                                $("#td-alumnos").html('');
                                $("#btn-acciones").hide();
                                swal.fire({
                                    title: "Administrar Temas",
                                    text: 'No existen docentes con los que puedas compartir este tema a crear',
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }

                            $("#tdcompartir").html(Tabla);
                        }

                    });

                },
                SelDocente: function(id) {
                    if ($('#CheckSeleccion' + id).prop('checked')) {
                        $("#DoceSel" + id).val("si");
                    } else {
                        $("#DoceSel" + id).val("no");

                    }
                },

            });

            //======================AGREGAR ANIMACIONES=======================\\
            $("#AddAnimaciones").on({
                click: function(e) {
                    var cons = $("#ConsAnima").val();

                    e.preventDefault();
                    var Animaciones = "<div id='imp_" + cons + "' class='col-md-11'>" +
                        "<input id='file' class='form-control' name='archididatico[]' type='file'>" +
                        "</div>";
                    Animaciones += "<div id='but_" + cons + "' class='col-md-01'>" +
                        "<button type='button' onclick='$.DelDid(" + cons +
                        ")'  class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                        "</div><br>";

                    $("#Arch_Didact").append(Animaciones);
                }
            });



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
                    var clase = 'form-control form-control-sm';
                    var campo = "";
                    campo += "<tr id='tr_" + cons + "'>";
                    campo += "<td>";
                    campo += cons;
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<div class='controls'>";
                    campo += "<input type='hidden' id='txturl' name='txturl[]' class='" + clase +
                        "' readonly style='" + style + "' value='" + url + "'>";
                    campo += url;
                    campo += "</div>";
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<div class='controls'>";
                    campo +=
                        "<a class='btn btn-danger btn-sm btnQuitar text-white'  onclick='$.DelLink(" +
                        cons +
                        ")'  title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "</div>";
                    campo += "</td>";
                    campo += "</tr>";
                    $("#tr_urls").append(campo);
                    $("#Conslink").val(parseFloat(cons) + 1);
                    $("#url_tema").val("");
                    //                }

                }
            });
            //======================EVENTO AGREGAR PERIODOS=======================\\

            $.CargPeriodos();
            $.CargUnidades();
            $.CargTipCont();
        });
    </script>
@endsection
