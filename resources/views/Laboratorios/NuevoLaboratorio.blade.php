@extends('Plantilla.Principal')
@section('title', 'Crear Laboratorio')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">GESTIONAR LABORATORIOS</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Crear Laboratorio
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
                            <h4 class="card-title">Crear Laboratorio</h4>
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
                                @include('Laboratorios.FormLaboratorio', [
                                    'url' => '/Laboratorios/GuardarLaboratorio',
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

        //////////////////////////

        $(document).ready(function() {

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var fecact = d.getFullYear() + '/' +
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day;

            $("#Men_Inicio").removeClass("active");
            $("#Men_Laboratorios").addClass("active");
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
                    //                $.reordenarLink();
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
                SelArchivo: function(id) {
                    var archivos = document.getElementById('file' + id).files;
                    for (x = 0; x < archivos.length; x++) {
                        $("#ArchivoSel" + id).val("si/" + archivos[x].name);
                    }

                }
            });

            $.hab_editTeoria();
            $.hab_editMateriales();
            $.hab_editProced("1");


            //======================EVENTO AGREGAR PROCEDIMIENTOS=======================\\

            $("#AddProc").on({
                click: function(e) {

                    e.preventDefault();

                    var cons = parseFloat($("#ConsProc").val()) + 1;

                    var Procesos = '<div id="proc' + cons + '">' +
                        ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-2">' +
                        ' <h4 class="primary">Procedimiento ' + cons + '</h4>' +
                        ' <textarea cols="80" id="summernoteProce' + cons +
                        '" name="TextProce[]" rows="3"></textarea>' +
                        '  <input id="file' + cons + '" accept="video/*" onchange="$.SelArchivo(' + cons +
                        ');" class="form-control" name="VideoProceso[]" type="file">' +
                        '<input type="hidden" class="form-control" name="ArchivoSel[]" id="ArchivoSel' +
                        cons + '"  value="no/"/>' +
                        '  <br><button id="DelProc" onclick="$.DelProc(' + cons +
                        ')" type="button"  class="btn mr-1 btn-warning"><i class="fa fa-trash-o"></i> Eliminar Procedimiento</button>' +
                        '   </div>' +
                        '</div>';

                    $("#DivProcedimientos").append(Procesos);
                    $("#ConsProc").val(cons);
                    $.hab_editProced(cons);

                }
            });
            //=============================================\\   


        });

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
