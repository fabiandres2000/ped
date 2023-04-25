@extends('Plantilla.Principal')
@section('title', 'Crear Tema')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIÓN DE TEMAS</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Crear Tema
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
                            <h4 class="card-title">Crear Tema</h4>
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
                                @include('Asignaturas.FormTemas', ['url' => '/Asignaturas/guardarTemas', 'method' => 'post'])
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
    {!! Form::open(['url' => '/cambiar/docentes', 'id' => 'formAuxiliarCargDocentes']) !!}
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

        //////////////////////////

        $(document).ready(function() {

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var fecact = d.getFullYear() + '/' +
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day;



            $("#Men_Inicio").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addTem").addClass("active");
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

                cargarDocentes: function() {

                    $("#ModCompartir").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    var Tabla="";
                    var j=1;

                    var id= $("#idAsig").val();
                    var form = $("#formAuxiliarCargDocentes");
                    $("#idAsig2").remove();
                    form.append("<input type='hidden' name='id2' id='idAsig2' value='" + id + "'>");
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
                                            Tabla +=    "<input type='hidden' id='DoceSel" + j +"' name='DoceSel[]' value='no'>"+
                                            "<td class='text-truncate text-center'>"+
                                            "<input type='checkbox' onclick='$.SelDocente(" + j + ");' id='CheckSeleccion" + j +"' style='cursor: pointer;' name='checkDocenteSel' value=''>";
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
                            title: "Administrar Temas",
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


                    if ($('#tip_contenido').val() === "DOCUMENTO" && $('#hab_cont_didact').val() ===
                        "SI" && flag === "no") {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Seleccione el Video a Cargar",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#tip_contenido').val() === "ARCHIVO" && $("#fileArch").val() === "") {
                        Swal.fire({
                            title: "Administrar  Temas",
                            text: "Seleccione el Archivo a Cargar",
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

                    $("#formTema").submit();
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
                    CKEDITOR.replace('summernoteCont', {

                    });
                },
                TipDoc: function(tipdoc) {
                    if (tipdoc === "DOCUMENTO") {
                        $("#titu_contenido").val("Nuevo Contenido " + fecact);
                        $("#Cont_documento").show();
                        $("#HabConDidact").show();
                        $.hab_edi();
                        $("#Archivo").hide();
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

                    } else if (tipdoc === "CONTENIDO DIDACTICO") {
                        $("#titu_contenido").val("Nuevo Video " + fecact);
                        $("#ArchivoVideo").show();
                        $("#HabConDidact").hide();
                        $("#Archivo").hide();

                        $("#Cont_documento").hide();
                        $("#TipUrl").hide();
                        $("#rowtit").removeClass("col-md-7");
                        $("#rowtit").addClass("col-md-9");
                    } else if (tipdoc === "ARCHIVO") {
                        $("#titu_contenido").val("Nuevo Archivo " + fecact);
                        $("#Archivo").show();
                        $("#HabConDidact").hide();
                        $("#ArchivoVideo").hide();

                        $("#Cont_documento").hide();
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
                        $("#TipUrl").show();
                        $("#TipEva").hide();
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
                DelDid: function(id_fila) {
                    $('#but_' + id_fila).remove();
                    $('#imp_' + id_fila).remove();
                    ConsAnim = $('#ConsAnima').val() - 1;
                    $("#ConsAnima").val(ConsAnim);

                }

            });

        });
        //======================AGREGAR ANIMACIONES=======================\\
        $("#AddAnimaciones").on({
            click: function(e) {
                var cons = $("#ConsAnima").val();

                e.preventDefault();
                var Animaciones = "<div id='imp_" + cons + "' class='col-md-11'>" +
                    "<input id='file' class='form-control Selanima' name='archididatico[]' type='file'>" +
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
                campo += "<input type='hidden' id='txturl' name='txturl[]' class='" + clase +
                    "' readonly style='" + style + "' value='" + url + "'>";
                campo += url;
                campo += "</td>";
                campo += "<td>";
                campo += "<a onclick='$.DelLink(" + cons +
                    ")' class='btn btn-danger btn-sm btnQuitar text-white' title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                campo += "</td>";
                campo += "</tr>";
                $("#tr_urls").append(campo);

                $("#Conslink").val(parseFloat(cons) + 1);
                $("#url_tema").val("");
            }
        });
        //======================EVENTO AGREGAR PERIODOS=======================\\


        //    $('#summernote').focus();

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
