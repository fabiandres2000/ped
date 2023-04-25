@extends('Plantilla.Principal')
@section('title', 'Editar Contenido Zona Libre')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIÓN CONTENIDO ZONA LIBRE</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Contenido Zona Libre
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
                            <h4 class="card-title">Editar Contenido Zona Libre</h4>
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
                                    'url' => '/Asignaturas/ModificarZonaLibre/' . $Tema->id,
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

    {!! Form::open(['url' => '/BuscarInf/DocumentosZonaLibre', 'id' => 'formAuxiliarDocumento']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/ComentariosZonaLibre', 'id' => 'formAuxiliarComentario']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/Archivos', 'id' => 'formAuxiliarArchivos']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/VideosZonaLibre', 'id' => 'formAuxiliarVideo']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ZonaLibre/DelArchivosVideo', 'id' => 'formAuxiliarDelArchivos']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/DelAnimTema/DelAnimacion', 'id' => 'formDelAnimacion']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/LinksZonaLibre', 'id' => 'formAuxiliarLinks']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/BuscarInf/Eval', 'id' => 'formAuxiliarPreg']) !!}
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

            $("#Men_Inicio").removeClass("active");
            $("#Men_Zona").addClass("active open");

            $('#fecha').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
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

            $("#Men_Inicio").removeClass("active");
            $("#Men_Zona").addClass("active open");

            $.extend({

                TipDoc: function(tipdoc) {
                    if (tipdoc === "DOCUMENTO") {
                        $("#titu_contenido").val("Nuevo Contenido " + fecact);
                        $("#Cont_documento").show();
                        $("#HabConDidact").show();
                        $("#Btn_Visualizacion").show();
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
                        $("#Btn_Visualizacion").hide();
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
                        $("#Btn_Visualizacion").hide();
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
                        $("#Btn_Visualizacion").hide();
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
                    } else if (tipdoc === "VIDEOS") {
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                        $("#titu_contenido").val("Nuevo Video " + fecact);
                        $("#Archivo").hide();
                        $("#HabConDidact").hide();
                        $("#Cont_Comentario").hide();
                        $("#Btn_Visualizacion").hide();

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
                        $("#Cont_documento").hide();
                        $("#Archivo").hide();
                        $("#TipUrl").hide();
                        //                    $("#TipEva").hide();
                        $("#Cont_foro").hide();
                    } else if (tipeva === "OPCMULT") {
                        $("#TipMulPreg").show();
                        $("#TipGruPreg").hide();
                        $("#TipPregEnsay").hide();
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

                hab_edi: function() {
                    CKEDITOR.replace('summernoteCont', {});
                },
                hab_ediComent: function() {
                    CKEDITOR.replace('summernoteComent', {});

                },
                CargTipCont: function() {
                    var tipcont = $("#tip_contenido").val();
                    var habAni = $("#hab_cont_didact").val();
                    if (tipcont === "DOCUMENTO") {
                        $("#Cont_documento").show();
                        $("#HabConDidact").show();
                        if (habAni === "SI") {
                            $("#Div_ContDidactico").show();
                            $("#ListAnima").show();
                        }
                        var form = $("#formAuxiliarDocumento");
                        var IdTema = $("#tema_id").val();
                        $.hab_edi();
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
                            }
                        });
                    } else if (tipcont === "ANUNCIO") {

                        $("#Cont_Comentario").show();
                        var form = $("#formAuxiliarComentario");
                        var IdTema = $("#tema_id").val();
                        $("#IdTema").remove();
                        $.hab_ediComent();
                        form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            dataType: "json",
                            success: function(respuesta) {
                                $('#summernoteComent').val(respuesta.content);
                            }
                        });
                    } else if (tipcont === "ARCHIVO") {
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
                                    $("#titu_tema").html(item.titulo);
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
                                        ')" class="btn btn-danger btn-sm btnQuitarArchi text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;
                                    HabConv = item.hab_conversacion;
                                });
                                $("#tr_archivos").html(TrArc);
                                $('#HabConv').val(HabConv).trigger('change.select2');
                            }
                        });
                    } else if (tipcont === "VIDEOS") {
                        $("#TipVideo").show();
                        var form = $("#formAuxiliarVideo");
                        var HabConv = "";
                        var IdTema = $("#tema_id").val();
                        $("#IdTema").remove();
                        form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                            "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var j = 1;
                        var TrArc = '';


                        if ($("#tip_video").val() === "LINK") {
                            $("#Div_link").show();
                            $("#ListLinkVideo").show();
                            $("#Div_ContAnimaciones").hide();
                        } else {
                            $("#Div_ContAnimaciones").show();
                            $("#ListAnima").show();
                            $("#Div_link").hide();
                        }

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            dataType: "json",
                            success: function(respuesta) {
                                $.each(respuesta.TemaVideo, function(i, item) {
                                    $("#titu_tema").html(item.titulo);
                                    if ($("#tip_video").val() === "LINK") {
                                        TrArc += '<tr id="tr_' + item.id + '">' +
                                            '<td class="text-truncate">' + j +
                                            '</td>' +
                                            '<td class="text-truncate">' + item
                                            .url + '</td>' +
                                            '<td class="text-truncate">' +
                                            '<a onclick="$.MostVideoLink(this.id)" id="' +
                                            item.id + '"  data-archivo="' + item
                                            .url +
                                            '"   class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                            '<a onclick="$.DellinkVideo(' + item
                                            .id +
                                            ')" class="btn btn-danger btn-sm btnQuitarLinkVideo text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                            '</td>' +
                                            '</tr>';
                                        j++;
                                    } else {
                                        TrArc += '<tr id="tr_' + item.id + '">' +
                                            '<td class="text-truncate">' + j +
                                            '</td>' +
                                            '<td class="text-truncate">' + item
                                            .titulo.slice(0, -4) + '</td>' +
                                            '<td class="text-truncate">' +
                                            '<a onclick="$.MostAnim(this.id)" id="' +
                                            item.id + '"  data-archivo="' + item
                                            .cont_didactico +
                                            '" data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}"  class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                            '<a onclick="$.DelArc(' + item.id +
                                            ')" class="btn btn-danger btn-sm btnQuitarVidArch text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                            '</td>' +
                                            '</tr>';
                                        j++;

                                    }

                                });
                                if ($("#tip_video").val() === "LINK") {
                                    $("#tr_linkVideos").html(TrArc);
                                } else {
                                    $("#tr_animaciones").html(TrArc);
                                }


                            }
                        });
                    } else if (tipcont === "LINK") {
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
                                        '<td class="text-truncate"><input type="hidden" class="form-control form-control-sm" id="txturl" name="txturl[]" value="' +
                                        item.url + '">' +
                                        '<a onclick="$.DelLink(' + j +
                                        ')" class="btn btn-danger btn-sm btnQuitarLink text-white"  title="Remover"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;
                                    HabConv = item.hab_conversacion;
                                });
                                $("#Conslink").val(j);
                                $("#tr_urls").html(TrUrl);
                                $('#HabConv').val(HabConv).trigger('change.select2');
                            }

                        });
                    }
                },
                MostAnim: function(id) {
                    $("#ModAnima").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#DetAnimaciones").show();
                    var videoID = 'videoclipAnima';
                    var sourceID = 'mp4videoAnima';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#' + id).data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    $('#' + videoID).get(0).play();
                },
                MostVideoLink: function(id) {
                    $("#ModAnima").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#DetLinkVideo").show();
                    var nomarchi = $('#' + id).data("archivo");
                    if (nomarchi.includes('embed/')) {
                        nomarchi = nomarchi.replace("watch?v", "embed/");
                    }
                    $('#LinkVideo').attr('src', nomarchi);
                },
                CloseModAnimaciones: function() {
                    $('#ModAnima').modal('toggle');
                },
                MostArc: function(id) {
                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#div_arc").html(
                        '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                    );
                    jQuery('#embed_arch').attr('src', $('#' + id).data("ruta") + "/" + $('#' + id).data(
                        "archivo"));
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
                       
                            if (respuesta.estado === "ok") {

                                Swal.fire({
                                    title: "",
                                    text: respuesta.mensaje,
                                    icon: "success",
                                    button: "Aceptar"
                                });
    
                                $("#tr_" + id).remove();
                            }
                        }

                    });
                },
                DelAnim: function(id) {
                    mensaje = "¿Desea Eliminar esta Animación?";

                    Swal.fire({
                        title: mensaje,
                        text: "",
                        icon: "warning",
                        buttons: true,
                        buttons: ["Cancelar", "Aceptar"],
                        dangerMode: true,
                    }).then((result) => {
                        if (result === true) {
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
                                        title: "",
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
                ReordenarPreg: function() {
                    var num = 1;
                    $('#RowGrup div').each(function() {
                        $(this).find('label').eq(0).text('Pregunta ' + num);
                        num++;
                    });
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
                DellinkVideo: function(id_fila) {
                    $('#tr_' + id_fila).remove();
                    $.reordenarLink();
                    ConsAct = $('#Conslink').val() - 1;
                    $("#Conslink").val(ConsAct);
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
                        var ContArch = $(".btnQuitarVidArch").length;
                        if ($("#tip_video").val() === "ARCHIVO" && $("#file").val() === "" && ContArch <
                            1) {
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

                        var ContLinkVideo = $(".btnQuitarLinkVideo").length;

                        if ($("#tip_video").val() === "LINK" && flag === "no" && ContLinkVideo < 1) {
                            Swal.fire({
                                title: "Gestionar Zona Libre",
                                text: "Ingrese el Link a Compartir.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }

                    }



                    var ContArch = $(".btnQuitarArchi").length;

                    if ($("#tip_contenido").val() === "ARCHIVO" && $("#fileArchivo").val() === "" &&
                        ContArch < 1) {
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
                    campo += "<div class='controls'>";
                    campo += "<input type='hidden' id='txturl' name='txturl[]' class='" + clase +
                        "' readonly style='" + style + "' value='" + url + "'>";
                    campo += url;
                    campo += "</div>";
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<div class='controls'>";
                    campo +=
                        "<a class='btn btn-danger btn-sm btnQuitarLink text-white'  onclick='$.DelLink(" +
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

            //======================EVENTO AGREGAR PERIODOS=======================\\

            $.CargTipCont();
        });
    </script>
@endsection
