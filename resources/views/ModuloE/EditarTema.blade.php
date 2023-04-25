@extends('Plantilla.Principal')
@section('title', 'Editar Tema')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
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
                            <h4 class="card-title">Editar Tema.</h4>
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
                                @include('ModuloE.FormTema', [
                                    'url' => '/ModuloE/ModificarTema/' . $Tema->id,
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

    {!! Form::open(['url' => '/ModuloE/BuncInfContenido', 'id' => 'formAuxiliarDocumento']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/DelImgModE/DelImgModuloE', 'id' => 'formAuxiliarDelImg']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/DelvidModE/DelVidModuloE', 'id' => 'formAuxiliarDelVid']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloE/CargarCompTema', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}


@endsection
@section('scripts')
    <script>
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
            config.colorButton_enableAutomatic = true;

            config.removeButtons =
                'Source,Save,NewPage,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,Smiley,PageBreak,Iframe,ShowBlocks,About';
        };
        $(document).ready(function() {
            $("#Men_Inicio").removeClass("active");
            $("#Men_Modulos_E").addClass("has-sub open");
            $("#Men_ModulosE_addTem").addClass("active");


            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var fecact = d.getFullYear() + '/' +
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day;

            $.extend({
                TipDoc: function(tipdoc) {
                    if (tipdoc === "DOCUMENTO") {
                        $("#titulo").val("Nuevo Contenido " + fecact);
                        $("#Div_Cont_Doc").show();
                        $("#Div_Cont_Img").hide();
                        $("#Div_Cont_Vid").hide();
                        $('#Btn_Guardar').prop('disabled', false);
                        $.hab_editor();
                    } else if (tipdoc === "IMAGEN") {
                        $("#titulo").val("Nueva Imagen " + fecact);
                        $("#Div_Cont_Vid").hide();
                        $("#Div_Cont_Doc").hide();
                        $("#Div_Cont_Img").show();
                        $('#Btn_Guardar').prop('disabled', false);
                    } else if (tipdoc === "VIDEO") {
                        $("#titulo").val("Nuevo Video " + fecact);
                        $("#Div_Cont_Doc").hide();
                        $("#Div_Cont_Img").hide();
                        $("#Div_Cont_Vid").show();
                        $('#Btn_Guardar').prop('disabled', false);
                    }
                },
                hab_editor: function() {
                    CKEDITOR.replace('summernoteCont', {});
                },
                GuardarTema: function() {

                    if ($("#asignatura").val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Debe seleccionar la asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    } else if ($("#titulo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Debe ingresar un Título.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    } else if ($("#objetivo").val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Debe ingresar un Objetivo.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    if ($("#tipo_contenido").val() === "IMAGEN" && !$('#ImageFile').val() && $(
                            "#tema_img").val() < 1) {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione la Imagen a Subir.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    } else if ($("#tipo_contenido").val() === "VIDEO" && !$('#VideoFile').val() && $(
                            "#tema_vid").val() == "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione el video a Subir.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var totvid = $(".btnQuitarVid").length;

                    if ($("#vidanima").val() === "") {
                        if ($("#animacion").val() === "SI" && $("#archidida").val() === "" && totvid <
                            1) {
                            Swal.fire({
                                title: "Gestionar Módulo E",
                                text: "Seleccione la Animación a Subir",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }
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

                MostImg: function(id) {

                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#div_arc").html(
                        '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                    );
                    jQuery('#embed_arch').attr('src', $('#datimg').data("ruta") + "/" + $('#' + id)
                        .data("archivo"));
                },
                CargTipCont: function() {
                    var TipCon = $("#tipo_contenido").val();
                    var IdTema = $("#tema_id").val();

                    var form = $("#formAuxiliarDocumento");

                    if ($("#animacion").val() === "SI") {
                        $("#Div_ContDidactico").show();
                    }

                    $("#IdTema").remove();
                    $("#TipCon").remove();
                    form.append("<input type='hidden' name='IdTema' id='IdTema' value='" + IdTema +
                        "'>");
                    form.append("<input type='hidden' name='TipCon' id='TipCon' value='" + TipCon +
                        "'>");

                    var url = form.attr("action");
                    var datos = form.serialize();

                    $.ajax({
                        type: "POST",
                        url: url,
                        async: false,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {

                            $('#componente').val($("#comp").val()).trigger('change.select2');

                            if (TipCon == "DOCUMENTO") {
                                $("#Div_Cont_Doc").show();
                                $('#Btn_Guardar').prop('disabled', false);
                                $.hab_editor();
                                $('#summernoteCont').val(respuesta.DesCont.contenido);
                            } else if (TipCon == "IMAGEN") {
                                $("#Div_Cont_Img").show();
                                $('#Btn_Guardar').prop('disabled', false);

                                var tablaimg = '';
                                var j = 1;

                                $.each(respuesta.DesCont, function(i, item) {
                                    tablaimg += '<tr id="trImg_' + item.id + '">' +
                                        '<td class="text-truncate">' + j + '</td>' +
                                        '<td class="text-truncate">' + item
                                        .imagen + '</td>' +
                                        '<td class="text-truncate">' +
                                        '<a onclick="$.DelImg(' + item.id +
                                        ')" class="btn btn-danger btn-sm btnQuitar text-white"  id="' +
                                        item.id + '"  data-archivo="' + item
                                        .imagen +
                                        '" title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '<a onclick="$.MostImg(this.id)" id="' +
                                        item.id + '"  data-archivo="' + item
                                        .imagen +
                                        '" class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;' +
                                        '</td>' +
                                        '</tr>';
                                    j++;
                                });
                                $("#tema_img").val(j);

                                $("#tr_imagenes").html(tablaimg);

                            } else {
                                $("#Div_Cont_Vid").show();
                                $('#Btn_Guardar').prop('disabled', false);
                                $('#tema_vid').val(respuesta.DesCont.video);

                                var tablaVid =
                                    '<div class="form-group" id="id_file" style="display:none;">' +
                                    ' <input type="file" name="ImageFile[]" />' +
                                    ' </div>' +
                                    ' <div class="form-group" id="id_verf">' +
                                    '  <div class="btn-group" role="group" aria-label="Basic example">' +
                                    '  <button type="button" onclick="$.VerVideo();" class="btn btn-success"><i' +
                                    '   class="fa fa-search"></i> Ver Video</button>' +
                                    '  <button type="button" onclick="$.CambVideo();" class="btn btn-warning"><i' +
                                    '   class="fa fa-refresh"></i> Cambiar Video</button>' +
                                    '    </div>' +
                                    '</div>';

                                $("#CargVid").html(tablaVid);


                            }
                        }
                    });

                    
                },
                HabiContDid: function(val) {

                    if (val === "SI") {
                        $("#Cont_didactico").show();

                    } else {
                        $("#Cont_didactico").hide();

                    }
                },
                VerImg: function() {
                    $("#CarImg").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#div_img").html(
                        '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                    );
                    jQuery('#embed_arch').attr('src', $('#datimg').data("ruta") + "/" + $(
                            '#tema_img')
                        .val());
                },
                CambImg: function() {
                    $("#id_verf").hide();
                    $("#id_file").show();
                },
                CambVid: function() {
                    $("#Div_VerImg").hide();
                    $("#Div_ArcDidac").show();
                },
                VerVideo: function() {
                    var videoID = 'videoclip';
                    var sourceID = 'mp4video';
                    var nomarchi = $('#tema_vid').val();
                    var newmp4 = $('#datvid').data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    //$('#'+videoID).attr('poster', newposter); //Change video poster
                    $('#' + videoID).get(0).play();

                    $("#CarVid").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                CambVideo: function() {
                    $("#id_verf").hide();
                    $("#id_file").show();
                },
                MostVid: function() {
                    var videoID = 'videoclip';
                    var sourceID = 'mp4video';
                    var nomarchi = $('#vidanima').val();
                    var newmp4 = $('#datvid').data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    //$('#'+videoID).attr('poster', newposter); //Change video poster
                    $('#' + videoID).get(0).play();

                    $("#CarVid").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                DelVide: function(id) {
                    var form = $("#formAuxiliarDelVid");
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
                            Swal.fire({
                                title: "",
                                text: respuesta.mensaje,
                                icon: "success",
                                button: "Aceptar"
                            });

                            if (respuesta.estado === "ok") {
                                $("#trVid_" + id).hide();
                            }
                        }

                    });
                },

                MostVide: function(id) {
                    var videoID = 'videoclip';
                    var sourceID = 'mp4video';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#datvid2').data("ruta") + "/" + nomarchi;
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
                DelImg: function(id) {
                    var form = $("#formAuxiliarDelImg");
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
                            Swal.fire({
                                title: "",
                                text: respuesta.mensaje,
                                icon: "success",
                                button: "Aceptar"
                            });

                            if (respuesta.estado === "ok") {
                                $("#trImg_" + id).hide();
                            }
                        }

                    });
                },
                CargarComponentes: function(Asig) {

                    var form = $("#formAuxiliar");
                    var token = $("#token").val();

                    $("#_token").remove();
                    $("#asig").remove();
                    form.append("<input type='hidden' name='asig' id='asign' value='" + Asig + "'>");
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
                                $("#componente").html(respuesta.select_compo);
                        },
                        error: function() {
                            mensaje = "No se pudo Cargar los Componentes";

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
            $.CargarComponentes($("#asignatura").val());
            $.CargTipCont();
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

        //======================AGREGAR ANIMACIONES=======================\\
        $("#AddAnimaciones").on({
            click: function(e) {
                var cons = $("#ConsAnima").val();

                e.preventDefault();
                var Animaciones = "<div id='imp_" + cons + "' class='col-md-11'>" +
                    "<input id='file' class='form-control SelAnima' name='archididatico[]' type='file'>" +
                    "</div>";
                Animaciones += "<div id='but_" + cons + "' class='col-md-01'>" +
                    "<button type='button' onclick='$.DelDid(" + cons +
                    ")'  class='btn btn-icon btn-outline-warning btn-social-icon btn-sm'><i class='fa fa-trash'></i></button>" +
                    "</div><br>";

                $("#Arch_Didact").append(Animaciones);
            }
        });
    </script>
@endsection
