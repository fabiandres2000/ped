@extends('Plantilla.Principal')
@section('title', 'Crear Tema')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
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
                            <h4 class="card-title">Crear Tema.</h4>
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
                                    'url' => '/ModuloE/GuardarTemas',
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


    {!! Form::open(['url' => '/ModuloE/CargarCompTema', 'id' => 'formAuxiliar']) !!}
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
                DelDid: function(id_fila) {
                    $('#but_' + id_fila).remove();
                    $('#imp_' + id_fila).remove();
                    ConsAnim = $('#ConsAnima').val() - 1;
                    $("#ConsAnima").val(ConsAnim);
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

                    }else if ($("#componente").val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Debe seleccionar el Componente.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;

                    }

                    if ($("#tipo_contenido").val() === "IMAGEN" && !$('#ImageFile').val()) {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione la Imagen a Subir.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    } else if ($("#tipo_contenido").val() === "VIDEO" && !$('#VideoFile').val()) {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione el video a Subir.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var flag="ok";
                    $('.SelAnima').each(function() {
                        if($(this).val()===""){
                            flag="no";
                        }
                    });

                    if ($("#animacion").val() === "SI" && flag === "no") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione la Animación a Subir",
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
                HabiContDid: function(val) {

                    if (val === "SI") {
                        $("#Div_ContDidactico").show();

                    } else {
                        $("#Div_ContDidactico").hide();

                    }
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
