@extends('Plantilla.Principal')
@section('title', 'Contenido del Programa')
@section('Contenido')
    <input type="hidden" class="form-control" id="Tip_Usu" value="{{ Auth::user()->tipo_usuario }}" />
    <input type="hidden" class="form-control" id="Id_Doce" value="{{ Session::get('DOCENTE') }}" />
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="form-control" id="RutEvalRelDef"
        data-ruta="{{ Session::get('URL') }}/Archivos_EvalRelImgDef" />
    <input type="hidden" class="form-control" id="RutEvalRelOpc"
        data-ruta="{{ Session::get('URL') }}/Archivos_EvalRelImgOpc" />
    <input type="hidden" data-id='id-dat' id="dattaller"
        data-ruta="{{ asset('/app-assets/Archivos_EvaluacionTaller') }}" />
    <input type="hidden" data-id='id-dat' id="Respdattaller"
        data-ruta="{{ asset('/app-assets/Archivos_EvalTaller_Resp') }}" />

    <input type="hidden" class="form-control" id="RutSound" data-ruta="{{ asset('/app-assets/sound') }}" />
    <input type="hidden" class="form-control" id="h" value="" />
    <input type="hidden" class="form-control" id="m" value="" />
    <input type="hidden" class="form-control" id="s" value="" />

    <input type="hidden" class="form-control" name="CargArchi" id="CargArchi" value="" />

    <input type="hidden" class="form-control" id="tiempEvaluacion" value="" />

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Programa</a>
                        </li>
                        <li class="breadcrumb-item active">Contenido
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="number-tabs">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12" id="Carg_periodos">
                    <div class="card-content collapse show">
                        <div class="card">
                            <div class="vertical-tab-steps wizard-circle pt-1">
                                @foreach ($Periodo as $peri)
                                    <?php
                                    $sumsi = 0;
                                    $sumtot = 0;
                                    ?>

                                    @foreach ($temas as $tem)
                                        @if ($peri->id == $tem->periodo)
                                            @if ($tem->visto_doc == 'SI')
                                                <?php $sumsi++; ?>
                                            @endif
                                            <?php $sumtot++; ?>
                                        @endif
                                    @endforeach

                                    <?php
                                    if ($sumtot != 0) {
                                        $por = ($sumsi * 100) / $sumtot;
                                        $por = round($por);
                                    } else {
                                        $por = 0;
                                    }
                                    ?>

                                    <!-- Step 1 -->
                                    <h6>Periodo</h6>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <div class="media">
                                                            <div class="media-body text-left w-100">
                                                                <h3 class="success">{{ $por }}%</h3>
                                                                <span>Periodo Completado</span>
                                                            </div>
                                                            <div class="media-right media-middle">
                                                                <i
                                                                    class="ft-check-square success font-large-2 float-right"></i>
                                                            </div>
                                                        </div>
                                                        <div class="progress progress-sm mt-1 mb-0" style="height: 18px;">
                                                            <div class="progress-bar progress-bar-striped bg-success"
                                                                role="progressbar" style="width: {{ $por }}%"
                                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($unidad as $uni)
                                                @if ($peri->id == $uni->periodo)
                                                    <div class="bs-callout-primary callout-transparent callout-bordered mt-1"
                                                        style="cursor: pointer;"
                                                        onclick="$.carg_contenido({{ $uni->id }});">
                                                        <div class="media align-items-stretch">
                                                            <div
                                                                class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                                                <i class="fa fa-list-alt fa-lg white font-medium-5"></i>
                                                            </div>
                                                            <div style="text-transform: capitalize;" class="media-body p-1">
                                                                <strong> {{ $uni->nom_unidad }}:
                                                                </strong>{{ $uni->des_unidad }}
                                                                <p>{{ $uni->introduccion }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>

                <div id="Carg_contenido" class="col-xl-12 col-lg-12 col-md-12" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="Text_Unidad"></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>

                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>

                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div id="contenedor">

                                </div>
                            </div>
                            <div id="contenedor2">

                            </div>

                            <div class="modal-footer">
                                <button type="button" id="btn_atrasPeri" onclick="$.mostListPeri();"
                                    class="btn grey btn-outline-secondary"><i
                                        class="ft-corner-up-left position-right"></i>Atras</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
                    aria-hidden="true">
                    <input type="hidden" class="form-control" id="IdEval" value="" />
                    <input type="hidden" class="form-control" id="Id_PregEns" value="" />
                    <input type="hidden" class="form-control" id="TipEva" value="" />
                    <input type="hidden" class="form-control" id="RutContDid"
                        data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}" />
                    <input type="hidden" class="form-control" id="RutEvalDid"
                        data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}" />
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">

                            <div class="modal-body">

                                <article id='cont_tema' class="wrapper">
                                    <header style="text-transform: capitalize;"></header>
                                    <main style="height: 400px; overflow: auto;"></main>
                                </article>
                                <div id='cont_archi' style="display: none;height: 400px; overflow: auto;">
                                    <div id="div_arc">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btn_DescargarPdf" style="display: none;"
                                onclick="$.AbrirpDF();" class="btn btn-outline-info"><i
                                    class="ft-download position-right"></i> Generar PDF</button>
                                <button type="button" id="btn_Animaciones" style="display: none;"
                                    onclick="$.AbrirAnimaciones();" class="btn btn-outline-amber"><i
                                        class="ft-video position-right"></i> Ver Animaciones</button>
                                <button type="button" id="btn_ActIni" style="display: none;" onclick="$.AbrirAct('ACTINI');"
                                    class="btn btn-outline-pink"><i class="ft-user-check position-right"></i> Actividad De
                                    Inicio</button>
                                <button type="button" id="btn_Prod" style="display: none;" onclick="$.AbrirAct('PRODUC');"
                                    class="btn btn-outline-success"><i class="ft-user-check position-right"></i>
                                    Producción</button>
                                <button type="button" id="btn_atras" style="display: none;" onclick="$.mostListArc();"
                                    class="btn grey btn-outline-secondary"><i class="ft-corner-up-left position-right"></i>
                                    Atras</button>
                                <button type="button" id="btn_salir" onclick="$.mostLiistemas();" class="btn grey btn-outline-secondary"
                                    data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                                <button type="button" id="btn_Conversa" onclick="$.AbrirConv('M');" style="display: none;"
                                    class="btn btn-outline-primary"><i class="ft-message-square position-right"></i>
                                    Comentarios</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade text-left" id="ModEval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
                    aria-hidden="true">
                    <div class="modal-dialog  modal-xl" role="document">
                        <div class="modal-content ">
                            <div class="modal-body">

                                <article id='ListEval' style="text-transform: capitalize;" class="wrapper">
                                    <header></header>
                                    <main style="height: 400px; overflow: auto;"></main>
                                </article>

                                <article id='DetEval' style="display: none;text-transform: capitalize;"
                                    class="wrapper">
                                    <header></header>
                                    <main style="height: 400px; overflow: auto;"></main>
                                </article>


                                <article id='DetEvalFin' style="display: none;text-transform: capitalize;"
                                    class="wrapper">
                                    <header></header>
                                    <main style="height: 400px; overflow: auto;"></main>
                                </article>

                            </div>
                            <div class="modal-footer">
                                <div id="contTiempo"
                                    style="text-align: left; font-size: 25px;display: none; padding-right: 20px;">
                                    <div class="content-header row">

                                        <div class="content-header-left col-md-12 col-12">
                                            <div class="btn-group float-md-right" role="group"
                                                aria-label="Button group with nested dropdown">

                                                <a class="btn btn-outline-primary"><i class="ft-clock"> Tiempo para
                                                        Terminar</i></a>
                                                <a class="btn btn-outline-primary" style="color: #CE2605;" id="cuenta"></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row" id="Dat_Cal" style="display: none;">
                                    <div class="col-md-5" style="text-align: center;">
                                        <labe>Intentos Permitidos:</labe><br>
                                        <labe id="label_IntPerm" style="color:  #CE2605;"></labe>
                                    </div>
                                    <div class="col-md-5" style="text-align: center;">
                                        <labe>Intentos Realizados:</labe><br>
                                        <labe id="label_IntReal"></labe>
                                    </div>
                                </div>
                                <button type="button" id="VidDidac" onclick="$.MostVid();" style="display: none;"
                                    class="btn btn-success"><i class="fa fa-video-camera"></i> Ver Contenido
                                    Didactico</button>
                                <button type="button" id="btn_ConversaEval" onclick="$.AbrirConvEval('M');"
                                    style="display: none;" class="btn btn-outline-pink"><i
                                        class="ft-message-square position-right"></i> Comentarios</button>
                                <button type="button" id="btn_salirModEv" class="btn grey btn-outline-secondary"
                                    onclick="$.CloseModActIni();" data-dismiss="modal"><i
                                        class="ft-corner-up-left position-right"></i> Salir</button>
                                <button type="button" id="btn_atrasModEv" style="display: none;"
                                    class="btn grey btn-outline-secondary" onclick="$.AtrasModActIni();"><i
                                        class="ft-corner-up-left position-right"></i>
                                    Atras</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-left" id="ModComent" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel15" aria-hidden="true">
                    <div class="modal-dialog comenta" role="document">
                        <div class="modal-content border-pink">
                            <div class="modal-header bg-pink white">
                                <h4 class="modal-title" id="titu_tema">Comentarios</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="row ">
                                            <div class="row scrollable-container" style="height:200px;"
                                                id="Div_Comentarios">
                                                <span id='etiquetafinal'></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <fieldset class="form-group position-relative has-icon-left mb-0">
                                        <input id="Text_Coment" class="form-control"
                                            placeholder="Escribir un Comentario..." type="text">
                                        <div class="form-control-position">
                                            <i class="fa fa-dashcube"></i>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btn_GuarComent" onclick="$.GuarComent();"
                                    class="btn grey btn-outline-success"><i class="ft-navigation position-right"></i>
                                    Enviar
                                    Comentario</button>
                                <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                                    data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel15" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success white">
                                <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">Contenido
                                    Didactico Cargado</h4>
                            </div>
                            <div class="modal-body">
                                <div id='ListEvalVid' style="height: 400px; overflow: auto;text-align: center;">
                                    <video width="640" height="360" id="datruta" controls
                                        data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}">
                                    </video>
                                </div>

                                <button type="button" id="btn_salir" onclick="$.SalirAnim();"
                                    class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                                        class="ft-corner-up-left position-right"></i> Salir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-left" id="ModAnima" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel15" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success white">
                                <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaAnim">
                                    Animaciones
                                    Cargadas al Tema</h4>
                                <h4 class="modal-title" style="text-transform: capitalize; display: none;"
                                    id="titu_Anima">
                                    Animacion</h4>

                            </div>
                            <div class="modal-body">
                                <div id='ListAnimaciones' style="height: 400px; overflow: auto;">
                                </div>
                                <div id='DetAnimaciones' style="height: 400px; overflow: auto;display: none;">
                                    <video id="videoclipAnima" width="100%" height="360" controls="controls"
                                        title="Video title">
                                        <source id="mp4videoAnima" src="" type="video/mp4" />
                                    </video>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btn_atrasModAnima" class="btn grey btn-outline-secondary"
                                    style="display: none;" onclick="$.AtrasModAnima();"><i
                                        class="ft-corner-up-left position-right"></i> Atras</button>
                                <button type="button" id="btn_salirModAnima" class="btn grey btn-outline-secondary"
                                    onclick="$.CloseModAnimaciones();" data-dismiss="modal"><i
                                        class="ft-corner-up-left position-right"></i> Salir</button>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>


    </div>
    <div class="contenidoPDF"  id="DivcontenidoPDF"></div>


    {!! Form::open(['url' => '/cambiar/ContenidoMod', 'id' => 'formContenido']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoDocumentoMod', 'id' => 'formContenidoDocumento']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoDidactico', 'id' => 'formContenidoDidactico']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoLink', 'id' => 'formContenidoLink']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoArch', 'id' => 'formContenidoArch']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/ModuloTV/ContenidoEva', 'id' => 'formContenidoEva']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoActMod', 'id' => 'formConsuAct']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoAnimMod', 'id' => 'formConsuAnim']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Guardar/Comentario', 'id' => 'formGuarComent']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Guardar/RespEval', 'id' => 'formGuarEval']) !!}
    {!! Form::close() !!}


    {!! Form::open(['url' => '/Consultar/Comentario', 'id' => 'formConsuComent']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/vistoContenidoMod', 'id' => 'formvistoContenido']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/habilContenidoMod', 'id' => 'formHabiContenido']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Asignaturas/consulPregAlumno', 'id' => 'formAuxiliarCargEval']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoVideoMod', 'id' => 'formContenidoVideo']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Modulos/consulTemaPDF', 'id' => 'formGenPDFTema']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/MostContenidoMod', 'id' => 'formMostContenido']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')


    <script>
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


            var flagGlobal = "n";
            var flagTimExt = "n";
            var flagTimFin = "n";
            var flagIntent = "ok"
            var xtiempo;

            var TotalTemas = 0;
            var MostCot = 1;
            var PorcentajeTotal = 0;
            var parts;
            $(".btnVer").on({
                click: function(e) {
                    e.preventDefault();
                }
            });
            //////////////////////////////


            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Contenido").addClass("active");
            $('#contenedor').on("change", ".cambiarPorcentaje", function(e) {


                var estado = false;
                var valor = $(this).attr("valor");
                if (valor === "SI") {
                    $(this).attr("valor", "NO");
                    estado = "NO";
                } else {
                    $(this).attr("valor", "SI");
                    estado = "SI";
                }
                // return;
                var id = $(this).attr("valorid");
                var form = $("#formvistoContenido");
                $("#idvisto").remove();
                $("#estadovisto").remove();
                form.append("<input type='hidden' name='id' id='idvisto' value='" + id + "'>");
                form.append("<input type='hidden' name='estado' id='estadovisto' value='" + estado + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    dataType: "json",
                    success: function(respuesta) {
                        $(this).attr("valor", respuesta.estado);
                        $('.switch:checkbox').checkboxpicker({
                            html: true,
                            offLabel: 'NO',
                            onLabel: 'SI'
                        });
                    },
                    error: function() {
                        $(this).attr("valor");
                        Swal.fire(
                            'Error!',
                            'Ocurrio un error...',
                            'error'
                        );
                    }
                });
                // $("#large").modal("hide");

            });
            var myResp = new Object();


            function formatState(state) {
                if (!state.id) {
                    return state.text;
                } else {

                    var resp = "";
                    $.each(myResp, function(k, itemr) {

                        if ($.trim(itemr.id) === $.trim(state.id)) {
                            resp = itemr.respuesta;
                        }
                    });
                    var $state = $(resp);
                    return $state;
                }
            }

            $.extend({
                carg_contenido: function(id) {
                    $("#Carg_periodos").hide();
                    $("#Carg_contenido").show();
                    var form = $("#formContenido");
                    $("#idUnidad").remove();
                    form.append("<input type='hidden' name='id' id='idUnidad' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var Tip_Usu = $("#Tip_Usu").val();
                    var objetivo = "";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            TotalTemas = 0;
                            var contenido = "";
                            $("#Text_Unidad").html("");
                            $("#Text_Unidad").append(respuesta.TitUnidad.des_unidad);

                            
                            var myClass = ["primary", "info", "success", "danger", "pink"];
                            var margin = "";
                            contenido+='  <form method="post" action="{{ url('/') }}/Guardar/OrdenTemasModulos" id="FormTemas" >';

                            $.each(respuesta.Temas, function(i, item) {
                                var checked = "";
                                var visto = "";
                                var habi = "";
                                var Mostrar="";
                                var disp="";
                                var move="none";
                                if (item.visto_doc === null) {
                                    visto == "NO";
                                } else {
                                    visto = item.visto_doc;
                                }

                                if (item.habilitado_doc === null) {
                                    habi == "NO";
                                } else {
                                    habi = item.habilitado_doc;
                                }


                                if (visto === "SI") {
                                    checked = "checked";
                                } else {
                                    checked = "";
                                }

                                if (item.ocultar_doc === null || item.ocultar_doc === "") {
                                    Mostrar = "SI";
                                    disp="block";
                                }else if (item.ocultar_doc==="NO"){
                                    Mostrar = "NO";
                                    disp="none;";
                                } else {
                                    Mostrar = item.ocultar_doc;
                                    disp="block";
                                }

                                if(Tip_Usu == "Profesor"){
                                    disp="block";
                                    move="block";
                                }



                                if (item.objetivo_general === null) {
                                    objetivo = "";
                                } else {
                                    objetivo = item.objetivo_general;
                                }

                                var rand = Math.floor(Math.random() * myClass
                                .length);
                            var rValue = myClass[rand];

                            TotalTemas > 0 ? margin = "mt-1" : margin = "";

                         

                                if (item.tip_contenido == "DOCUMENTO") {

                                    contenido +=
                                        '<div style="cursor:pointer; display: '+disp+'"  class="bs-callout-' +  rValue +' callout-transparent callout-bordered ' +
                                        margin + '">' +
                                        '<input type="hidden" id="id_tema' + item
                                        .id + '" value="' + habi + '">' +
                                        '<input type="hidden" name="ConsTem[]" id="ConsTem' + item
                                        .id + '" value="' + item.id  + '">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div onclick="$.MostConteDoc(' + item.id  + ');" class="d-flex align-items-center bg-' +
                                        rValue +
                                        ' position-relative callout-arrow-left p-2">' +
                                        '<i class="icon-book-open fa-xl white font-medium-5"></i>' +
                                        '</div>' +
                                        ' <div  class="media-body p-1">' +
                                            '<div class="row">'+
                                                ' <div class="col-11">'+
                                                 '   <strong onclick="$.MostConteDoc(' + item
                                                .id +
                                                ');" style="text-transform: capitalize;">' +
                                                item.titu_contenido + '</strong>' +
                                                '  <p onclick="$.MostConteDoc(' + item.id +
                                                ');">' + objetivo + '</p>'+
                                                '</div>'+
                                                '<div class="col-1" style="display:'+move+'">'+
                                                '<a class="btn color-primary"><i class="fa fa-arrows-v"></i></a>'+
                                                '</div>'+
                                                '</div>';
                                        if (Tip_Usu == "Profesor") {
                                            contenido +=
                                                " <fieldset id='HabTemVisto'>" +
                                                " <div class='float-left'>" +
                                                "<input type='checkbox'  valor='" +
                                                visto + "' valorid='" + item.id +
                                                "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                                checked + "/>" +
                                                " </div>" +
                                                " <div class='float-right'>";
                                            if (habi === "SI") {
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Habi" + item.id +
                                                    "' onclick='$.HabTema(this.id);'style='display:none;' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Desa" + item.id +
                                                    "' onclick='$.HabTema(this.id);'  class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                            } else {
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Habi" + item.id +
                                                    "' onclick='$.HabTema(this.id);' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Desa" + item.id +
                                                    "' onclick='$.HabTema(this.id);' style='display:none;' class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                            }


                                            if (Mostrar === "SI") {
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Most" + item.id +
                                                    "' onclick='$.MostTema(this.id);'style='display:none;' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Ocul" + item.id +
                                                    "' onclick='$.MostTema(this.id);'  class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                            } else {
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Most" + item.id +
                                                    "' onclick='$.MostTema(this.id);' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                                contenido +=
                                                    "<button type='button' valorid='" +
                                                    item.id + "' id='Ocul" + item.id +
                                                    "' onclick='$.MostTema(this.id);' style='display:none;' class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                            }

                                            contenido += " </div>" +
                                                " </fieldset>";
                                        }
                                        contenido +=' </div>' +
                                        '  </div>' +
                                        '  </div>';

                                } else if (item.tip_contenido == "ARCHIVO") {

                                    contenido +=
                                    '<div style="cursor:pointer; display: '+disp+'"  class="bs-callout-' +  rValue +' callout-transparent callout-bordered ' +
                                    margin + '">' +
                                    '<input type="hidden" id="id_tema' + item
                                        .id + '" value="' + habi + '">' +
                                        '<input type="hidden" name="ConsTem[]" id="ConsTem' + item
                                        .id + '" value="' + item.id  + '">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div onclick="$.MostConteArch(' + item.id  + ');" class="d-flex align-items-center bg-' +
                                    rValue +
                                    ' position-relative callout-arrow-left p-2">' +
                                    '<i class="icon-paper-clip fa-xl white font-medium-5"></i>' +
                                    '</div>' +
                                    ' <div  class="media-body p-1">' +
                                        '<div class="row">'+
                                            ' <div class="col-11">'+
                                             '   <strong onclick="$.MostConteDoc(' + item
                                            .id +
                                            ');" style="text-transform: capitalize;">' +
                                            item.titu_contenido + '</strong>' +
                                            '  <p onclick="$.MostConteDoc(' + item.id +
                                            ');">' + objetivo + '</p>'+
                                            '</div>'+
                                            '<div class="col-1" style="display:'+move+'">'+
                                            '<a class="btn color-primary"><i class="fa fa-arrows-v"></i></a>'+
                                            '</div>'+
                                            '</div>';
                                    if (Tip_Usu == "Profesor") {
                                        contenido +=
                                            " <fieldset id='HabTemVisto'>" +
                                            " <div class='float-left'>" +
                                            "<input type='checkbox'  valor='" +
                                            visto + "' valorid='" + item.id +
                                            "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                            checked + "/>" +
                                            " </div>" +
                                            " <div class='float-right'>";
                                        if (habi === "SI") {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Habi" + item.id +
                                                "' onclick='$.HabTema(this.id);'style='display:none;' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Desa" + item.id +
                                                "' onclick='$.HabTema(this.id);'  class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                        } else {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Habi" + item.id +
                                                "' onclick='$.HabTema(this.id);' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Desa" + item.id +
                                                "' onclick='$.HabTema(this.id);' style='display:none;' class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                        }

                                        if (Mostrar === "SI") {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Most" + item.id +
                                                "' onclick='$.MostTema(this.id);'style='display:none;' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Ocul" + item.id +
                                                "' onclick='$.MostTema(this.id);'  class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                        } else {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Most" + item.id +
                                                "' onclick='$.MostTema(this.id);' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Ocul" + item.id +
                                                "' onclick='$.MostTema(this.id);' style='display:none;' class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                        }

                                        contenido += " </div>" +
                                            " </fieldset>";
                                    }
                                    contenido +=' </div>' +
                                    '  </div>' +
                                    '  </div>';

                                } else if (item.tip_contenido == "CONTENIDO DIDACTICO") {

                                    contenido +=
                                        '<div style="cursor:pointer; display: '+disp+'"  class="bs-callout-' +
                                        rValue +
                                        ' callout-transparent callout-bordered ' +
                                        margin + '">' +
                                        '<input type="hidden" id="id_tema' + item
                                        .id + '" value="' + habi + '">' +
                                        '<input type="hidden" name="ConsTem[]" id="ConsTem' + item
                                        .id + '" value="' + item.id  + '">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div onclick="$.MostConteVideo(' + item.id +
                                        ');" class="d-flex align-items-center bg-' +
                                        rValue +
                                        ' position-relative callout-arrow-left p-2">' +
                                        '<i class="note-icon-video fa-xl white font-medium-5"></i>' +
                                        '</div>' +
                                        ' <div  class="media-body p-1">' +
                                            '<div class="row">'+
                                                ' <div class="col-11">'+
                                                 '   <strong onclick="$.MostConteDoc(' + item
                                                .id +
                                                ');" style="text-transform: capitalize;">' +
                                                item.titu_contenido + '</strong>' +
                                                '  <p onclick="$.MostConteDoc(' + item.id +
                                                ');">' + objetivo + '</p>'+
                                                '</div>'+
                                                '<div class="col-1" style="display:'+move+'">'+
                                                '<a class="btn color-primary"><i class="fa fa-arrows-v"></i></a>'+
                                                '</div>'+
                                                '</div>';
                                    if (Tip_Usu == "Profesor") {
                                        contenido +=
                                            " <fieldset id='HabTemVisto'>" +
                                            " <div class='float-left'>" +
                                            "<input type='checkbox'  valor='" +
                                            visto + "' valorid='" + item.id +
                                            "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                            checked + "/>" +
                                            " </div>" +
                                            " <div class='float-right'>";
                                        if (habi === "SI") {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Habi" + item.id +
                                                "' onclick='$.HabTema(this.id);'style='display:none;' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Desa" + item.id +
                                                "' onclick='$.HabTema(this.id);'  class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                        } else {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Habi" + item.id +
                                                "' onclick='$.HabTema(this.id);' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Desa" + item.id +
                                                "' onclick='$.HabTema(this.id);' style='display:none;' class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                        }

                                        if (Mostrar === "SI") {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Most" + item.id +
                                                "' onclick='$.MostTema(this.id);'style='display:none;' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Ocul" + item.id +
                                                "' onclick='$.MostTema(this.id);'  class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                        } else {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Most" + item.id +
                                                "' onclick='$.MostTema(this.id);' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Ocul" + item.id +
                                                "' onclick='$.MostTema(this.id);' style='display:none;' class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                        }

                                        contenido += " </div>" +
                                            " </fieldset>";
                                    }
                                    contenido += ' </div>' +
                                        '  </div>' +
                                        '  </div>';

                                } else if (item.tip_contenido == "LINK") {
                                    contenido +=
                                    '<div style="cursor:pointer; display: '+disp+'"  class="bs-callout-' +  rValue +' callout-transparent callout-bordered ' +
                                    margin + '">' +
                                    '<input type="hidden" id="id_tema' + item
                                        .id + '" value="' + habi + '">' +
                                        '<input type="hidden" name="ConsTem[]" id="ConsTem' + item
                                        .id + '" value="' + item.id  + '">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div onclick="$.MostConteLink(' + item.id  + ');" class="d-flex align-items-center bg-' +
                                    rValue +
                                    ' position-relative callout-arrow-left p-2">' +
                                    '<i class="icon-link  fa-xl white font-medium-5"></i>' +
                                    '</div>' +
                                    ' <div  class="media-body p-1">' +
                                        '<div class="row">'+
                                            ' <div class="col-11">'+
                                             '   <strong onclick="$.MostConteDoc(' + item
                                            .id +
                                            ');" style="text-transform: capitalize;">' +
                                            item.titu_contenido + '</strong>' +
                                            '  <p onclick="$.MostConteDoc(' + item.id +
                                            ');">' + objetivo + '</p>'+
                                            '</div>'+
                                            '<div class="col-1" style="display:'+move+'">'+
                                            '<a class="btn color-primary"><i class="fa fa-arrows-v"></i></a>'+
                                            '</div>'+
                                            '</div>';
                                    if (Tip_Usu == "Profesor") {
                                        contenido +=
                                            " <fieldset id='HabTemVisto'>" +
                                            " <div class='float-left'>" +
                                            "<input type='checkbox'  valor='" +
                                            visto + "' valorid='" + item.id +
                                            "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                            checked + "/>" +
                                            " </div>" +
                                            " <div class='float-right'>";
                                        if (habi === "SI") {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Habi" + item.id +
                                                "' onclick='$.HabTema(this.id);'style='display:none;' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Desa" + item.id +
                                                "' onclick='$.HabTema(this.id);'  class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                        } else {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Habi" + item.id +
                                                "' onclick='$.HabTema(this.id);' class='btn btn-danger btn-sm'><i class='fa fa-lock'></i> Desabilitado</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Desa" + item.id +
                                                "' onclick='$.HabTema(this.id);' style='display:none;' class='btn btn-success btn-sm'><i class='fa fa-unlock'></i> Habilitado</button>";
                                        }


                                        if (Mostrar === "SI") {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Most" + item.id +
                                                "' onclick='$.MostTema(this.id);'style='display:none;' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Ocul" + item.id +
                                                "' onclick='$.MostTema(this.id);'  class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                        } else {
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Most" + item.id +
                                                "' onclick='$.MostTema(this.id);' class='btn btn-light btn-sm ml-1'><i class='fa fa-eye-slash'></i> Ocultar</button>";
                                            contenido +=
                                                "<button type='button' valorid='" +
                                                item.id + "' id='Ocul" + item.id +
                                                "' onclick='$.MostTema(this.id);' style='display:none;' class='btn btn-info btn-sm ml-1'><i class='fa fa-eye'></i> Mostrar</button>";
                                        }

                                        contenido += " </div>" +
                                            " </fieldset>";
                                    }
                                    contenido +=' </div>' +
                                    '  </div>' +
                                    '  </div>';
                                }


                                TotalTemas++;
                            });
                            contenido+='</form>';
                            $("#contenedor").html(contenido);
                            $('.switch:checkbox').checkboxpicker({
                                html: true,
                                offLabel: 'NO',
                                onLabel: 'SI'
                            });

                            if(Tip_Usu == "Profesor"){
                                $ ("#FormTemas").sortable({
                                    update: function(event, ui){
                                       $.CambiarOrden(id);
                                    }
                            });
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                }, 
                CambiarOrden: function(id) {
                  
                   
                    var form = $("#FormTemas");
                    var token = $("#token").val();
                    $("#_token").remove();
                    $("#UniTema").remove();
                    form.append("<input type='hidden' id='UniTema' name='UniTema'  value='" + id + "'>");
                    form.append("<input type='hidden' id='_token' name='_token'  value='" + token + "'>");
                                      
                    var url = form.attr("action");
                    var datos = form.serialize();
                  
                    
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                          
                        },
                        error: function() {
                            $(this).attr("valor");
                            Swal.fire(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                },
                
                //////////CARGAR SEGUN EL TIPO DE CONTENIDO
                MostConteDoc: function(id) {
                    var $wrapper = $('#cont_tema');
                    $("#DivcontenidoPDF").show();
                    $("#DivcontenidoPDF").html("");
                    if ($("#id_tema" + id).val() === "SI" || $("#Tip_Usu").val() !== "Estudiante") {
                        $wrapper.avnSkeleton('display');
                        if (MostCot == 1) {
                            $("#btn_eval").hide();
                            $("#cont_archi").hide();
                            $("#Dat_Cal").hide();
                            $("#large").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            var form = $("#formContenidoDocumento");
                            $("#idTema").remove();
                            form.append("<input type='hidden' name='id_tema' id='idTema' value='" + id +
                                "'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                dataType: "json",
                                success: function(respuesta) {
                                    $wrapper.avnSkeleton('remove');
                                    $wrapper.find('> header').append(respuesta.DesaTema
                                        .titulo);
                                    //                    $("#titu_tema").html(respuesta.DesaTema.titulo);
                                    $wrapper.find('> main').append(respuesta.DesaTema
                                        .cont_documento);
                                    if (respuesta.DesaTema.hab_conversacion == "SI") {
                                        $("#btn_Conversa").show();
                                    } else {
                                        $("#btn_Conversa").hide();
                                    }


                                    if (respuesta.ActIni == "s") {
                                        $("#btn_ActIni").show();
                                    } else {
                                        $("#btn_ActIni").hide();
                                    }

                                    if (respuesta.Produc == "s") {
                                        $("#btn_Prod").show();
                                    } else {
                                        $("#btn_Prod").hide();
                                    }

                                    if (respuesta.Animac == "s") {
                                        $("#btn_Animaciones").show();
                                    } else {
                                        $("#btn_Animaciones").hide();
                                    }

                                    $("#btn_DescargarPdf").show();

                                },
                                error: function(xhr, status) {
                                    alert('Disculpe, existió un problema');
                                },
                                // código a ejecutar sin importar si la petición falló o no
                                complete: function(xhr, status) {
                                    $wrapper.avnSkeleton('remove');
                                }
                            });
                        } else {

                        }
                    } else {
                        mensaje = "Este Tema no ha sido Habilitado por el Docente Encargado.";
                        Swal.fire({
                            title: "",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        ////7
                    }
                },

                MostConteVideo: function(id) {

                    var $wrapper = $('#cont_tema');
                    if ($("#id_tema" + id).val() === "SI" || $("#Tip_Usu").val() !== "Estudiante") {
                        $wrapper.avnSkeleton('display');
                        var contenido = "";
                        if (MostCot == 1) {
                            $("#btn_eval").hide();
                            $("#cont_archi").hide();
                            $("#Dat_Cal").hide();
                            $("#large").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            var form = $("#formContenidoVideo");
                            $("#idTema").remove();
                            form.append("<input type='hidden' name='id_tema' id='idTema' value='" + id +
                                "'>");
                            var url = form.attr("action");
                            var datos = form.serialize();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                dataType: "json",
                                success: function(respuesta) {
                                    $wrapper.avnSkeleton('remove');
                                    $wrapper.find('> header').append(respuesta.DatCont
                                        .titu_contenido);
                                    var j = 1;
                                    contenido +=
                                        '<div class="card-content collapse show">' +
                                        '<div class="card-body">' +
                                        '<ul class="list-group">';
                                    $.each(respuesta.DesVid, function(i, item) {

                                        contenido += '<li id="' + j +
                                            '" onclick="$.MostVideArc(this.id);" style="cursor: pointer;" data-archivo="' +
                                            item.cont_didactico +
                                            '" data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}"  class="list-group-item">' +
                                            '<span class="float-left">' +
                                            ' <i class="fa fa-video-camera mr-1"></i>' +
                                            '</span>' + item.titulo.substring(0, item.titulo.length - 4) +
                                            '  </li>';
                                        j++;
                                    });

                                    contenido += '</ul>' +
                                    '     </div>' +
                                    '    </div>';


                                    $wrapper.find('> main').append(contenido);

                                    if (respuesta.ActIni == "s") {
                                        $("#btn_ActIni").show();
                                    } else {
                                        $("#btn_ActIni").hide();
                                    }

                                    if (respuesta.Produc == "s") {
                                        $("#btn_Prod").show();
                                    } else {
                                        $("#btn_Prod").hide();
                                    }

                                    if (respuesta.Animac == "s") {
                                        $("#btn_Animaciones").show();
                                    } else {
                                        $("#btn_Animaciones").hide();
                                    }

                                },
                                error: function(xhr, status) {
                                    alert('Disculpe, existió un problema');
                                },
                                // código a ejecutar sin importar si la petición falló o no
                                complete: function(xhr, status) {
                                    $wrapper.avnSkeleton('remove');
                                }
                            });
                        } else {

                        }
                    } else {
                        mensaje = "Este Tema no ha sido Habilitado por el Docente Encargado.";
                        Swal.fire({
                            title: "",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });

                    }
                },

                MostVideArc: function(id){
                    $("#ModAnima").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    
                    $("#DetAnimaciones").show();
                    $("#ListAnimaciones").hide();
                    var videoID = 'videoclipAnima';
                    var sourceID = 'mp4videoAnima';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#' + id).data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    $('#' + videoID).get(0).play();

                },
                HabTema: function(opc) {
                    var idTem = $("#" + opc).attr("valorid");
                    //1=Habilitar
                    //2=Desabilitar
                    idhab = opc.slice(0, 4);
                    var form = $("#formHabiContenido");
                    $("#idhabi").remove();
                    $("#idTema").remove();
                    form.append("<input type='hidden' name='idhabi' id='idhabi' value='" + idhab +
                        "'>");
                    form.append("<input type='hidden' name='idTema' id='idTema' value='" + idTem +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            if (respuesta.mensaje === "SI") {
                                if (idhab === "Habi") {
                                    $("#id_tema" + idTem).val("SI");
                                    $("#Habi" + idTem).hide();
                                    $("#Desa" + idTem).show();
                                } else {
                                    $("#id_tema" + idTem).val("NO");
                                    $("#Desa" + idTem).hide();
                                    $("#Habi" + idTem).show();
                                }
                            }
                        },
                        error: function() {
                            $(this).attr("valor");
                            Swal.fire(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                },
                MostTema: function(opc) {
                    var idTem = $("#" + opc).attr("valorid");
                    //1=Habilitar
                    //2=Desabilitar
                    idhab = opc.slice(0, 4);
                    var form = $("#formMostContenido");
                    $("#idMost").remove();
                    $("#idTema").remove();
                    form.append("<input type='hidden' name='idMost' id='idMost' value='" + idhab +
                        "'>");
                    form.append("<input type='hidden' name='idTema' id='idTema' value='" + idTem +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            if (respuesta.mensaje === "SI") {
                                if (idhab === "Most") {
                                  
                                    $("#Most" + idTem).hide();
                                    $("#Ocul" + idTem).show();
                                } else {
                                    
                                    $("#Ocul" + idTem).hide();
                                    $("#Most" + idTem).show();
                                }
                            }
                        },
                        error: function() {
                            $(this).attr("valor");
                            Swal.fire(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                },
                MostConteDidactico: function(id) {
                    $("#btn_eval").hide();
                    $("#cont_tema").show();
                    $("#cont_archi").hide();
                    $("#cont_tema").html("");
                    $("#Dat_Cal").hide();
                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formContenidoDidactico");
                    $("#idTema").remove();
                    form.append("<input type='hidden' name='id_tema' id='idTema' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var contenido = '';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {

                            {{-- $("#titu_tema").html(respuesta.DesaTema.titulo); --}}
                            contenido = respuesta.DesaTema.cont_documento;
                            contenido +=
                                '<br> <div id="ListEvalvid"  style="height: 400px; overflow: auto;text-align: center;">' +
                                '<video style="width: 100%; height:360px;" id="datruta" controls >' +
                                '<source src="" id="sour_video" type="video/mp4">' +
                                '</video></div>';
                            $("#cont_tema").html(contenido);
                            jQuery('#sour_video').attr('src', $('#RutContDid').data(
                                "ruta") + "/" + respuesta.DesaTema.cont_didactico);
                            if (respuesta.DesaTema.hab_conversacion == "SI") {
                                $("#btn_Conversa").show();
                            } else {
                                $("#btn_Conversa").hide();
                            }

                            if (respuesta.ActIni == "s") {
                                $("#btn_ActIni").show();
                            } else {
                                $("#btn_ActIni").hide();
                            }

                            if (respuesta.Produc == "s") {
                                $("#btn_Prod").show();
                            } else {
                                $("#btn_Prod").hide();
                            }

                        }
                    });
                },
                mostLiistemas: function() {

                    $("#DivcontenidoPDF").hide();

                },
                MostConteLink: function(id) {

                    $("#btn_ActIni").hide();
                    $("#btn_Prod").hide();
                    $("#btn_Animaciones").hide();
                    $("#btn_eval").hide();
                    $("#Dat_Cal").hide();
                    $("#cont_tema").html("");
                    $("#cont_archi").hide();
                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formContenidoLink");
                    $("#idTema").remove();
                    form.append("<input type='hidden' name='id_tema' id='idTema' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var contenido =
                        "<div class='dropdown-menu' style='display: block; position: static; width: 100%; margin-top: 0; float: none;'>";
                    $.ajax({

                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $.each(respuesta.DesaLink, function(i, item) {
                                $("#titu_tema").html(item.titulo);
                                contenido +=
                                    '<button class="dropdown-item" onclick="$.mostmodlink(\'' +
                                    item.url +
                                    '\');" type="button"><i class="fa fa-paperclip"></i>  ' +
                                    item.url + ' </button>';
                            });
                            contenido += "</div>";
                            $("#cont_tema").html(contenido);
                        }

                    });
                },
                MostConteArch: function(id) {

                    $("#Dat_Cal").hide();
                    $("#btn_eval").hide();
                    $("#cont_tema").html("");
                    $("#cont_archi").hide();
                    $("#btn_ActIni").hide();
                    $("#btn_Prod").hide();
                    $("#btn_Animaciones").hide();
                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formContenidoArch");
                    $("#idTema").remove();
                    form.append("<input type='hidden' name='id_tema' id='idTema' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var contenido =
                        "<div class='dropdown-menu' style='display: block; position: static; width: 100%; margin-top: 0; float: none;'>";
                    var j = 1;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $.each(respuesta.DesArch, function(i, item) {

                                $("#titu_tema").html(item.titulo);
                                contenido += '<button id="' + j +
                                    '"  onclick="$.mostmodArch(this.id);" data-archivo="' +
                                    item.nom_arch +
                                    '" data-ruta="{{ asset('/app-assets/Archivos_Contenidos') }}" class="dropdown-item" type="button"><i class="fa fa-paperclip"></i>  ' +
                                    item.nom_arch + ' </button>';
                                j++;
                            });
                            contenido += "</div>";
                            $("#cont_tema").html(contenido);
                        }

                    });
                },
                MostEval: function(id) {
                    $("#ListEval").hide();
                    $("#DetEval").show();
                    $("#IdEval").val(id);

                    var $wrapper = $('#DetEval');
                    $wrapper.avnSkeleton('display');
                    $("#label_IntPerm").html("");
                    $("#label_IntReal").html("");
                    $("#txt_califVis").val("");
                    $("#txt_califVis").css('background-color', '#ffffff');
                    var NomVidEval = "";
                    var Parrafo = "";
                    var PregMul = "";
                    var TipEval = "";
                    var Tiempo = "";
                    var HabTie = "";
                    var Enunciado = "";
                    var form = $("#formContenidoEva");
                    var token = $("#token").val();
                    $("#idTemaEva").remove();
                    form.append("<input type='hidden' name='id_tema' id='idTemaEva' value='" + id +
                        "'>");
                    form.append("<input type='hidden' name='_token'  value='" + token + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {

                            $wrapper.avnSkeleton('remove');
                            var n = 1;
                            TipEval = respuesta.tipeval;
                            Tiempo = respuesta.tiempo;
                            HabTie = respuesta.hab_tiempo;
                            var contenido = '';
                            $wrapper.find('> header').append(respuesta.titulo
                                .toLowerCase());
                            Enunciado = respuesta.enunciado;
                            if (Enunciado == null) {
                                Enunciado = "";
                            }

                            //////////////CARGAR ENUNCIADO
                            contenido += ' <div class="row">' +
                                '<div class="col-md-12">' +
                                '<p>' + Enunciado + '</p>' +
                                '</div>' +
                                ' </div>';

                            /////////

                            //////// CARGAR INFORMACIÓN DE VIDEOS

                            if (respuesta.VideoEval !== "no") {
                                $("#VidDidac").show();
                                $("#datruta").html(
                                    '<source src="" id="sour_video" type="video/mp4">'
                                );
                                jQuery('#sour_video').attr('src', $('#datruta').data(
                                    "ruta") + "/" + respuesta.VideoEval);
                                $("#Nom_Video").val(respuesta.VideoEval);
                            } else {
                                $("#VidDidac").hide();
                            }

                            //////////////

                            //////CARGAR INFORMACIÓN DE INTENTOS

                            $("#Dat_Cal").show();
                            var int_real = respuesta.int_realizados;
                            var int_perm = respuesta.int_perm;

                            $("#label_IntPerm").html(int_perm);
                            $("#label_IntReal").html(int_real);
                            if (respuesta.perfil === "Estudiante") {
                                if (parseInt(respuesta.int_realizados) >= parseInt(respuesta
                                        .int_perm)) {
                                    flagIntent = "fail";
                                } else {
                                    flagIntent = "ok";
                                }
                            } else {
                                flagIntent = "ok";
                            }
                            /////////////////////////

                            contenido +=
                                '  <div class="row"><div class="card-content collapse show">' +
                                '  <div class="card-body" style="padding-top: 0px;">' +
                                '        <form method="post" action="{{ url('/') }}/ModuloTv/RespEvaluaciones" id="Evaluacion" class="number-tab-stepsPreg wizard-circle">';
                            var Preg = 1;
                            var ConsPre = 0;

                            ////////////////CARGAR PREGUNTAS
                            $.each(respuesta.PregEval, function(i, item) {
                                contenido += '         <h6>Pregunta</h6>' +
                                    '         <fieldset>' +
                                    '              <div class="row p-1">' +
                                    '   <div  style="width: 100%" class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1" >' +
                                    '              <div class="row" >' +
                                    '<input type="hidden" id="id-pregunta' +
                                    ConsPre + '"  value="' + item.idpreg + '" />' +
                                    '<input type="hidden" id="tip-pregunta' +
                                    ConsPre + '"  value="' + item.tipo + '" />' +
                                    '      <div class="col-md-9"><h4 class="primary">Pregunta ' +
                                    Preg + '</h4></div>' +
                                    '      <div class="col-md-3"><span class=" float-right"><i class="fa fa-circle success"></i id="Puntaje' +
                                    ConsPre + '"> 10 Puntos</span></div>' +
                                    '      <div class="col-md-12" id="Pregunta' +
                                    ConsPre + '">' +
                                    '           </div>    ' +
                                    '           </div>    ' +
                                    '           </div>    ' +
                                    '             </div>' +
                                    '        </fieldset>';
                                Preg++;
                                ConsPre++;

                            });

                            //////////////////////

                            contenido += '</form>' +
                                ' </div>' +
                                '</div></div>';


                            $wrapper.find('> main').append(contenido);

                            $.CargPreg("0");

                            ///////////////INICALIZAR STEPS

                            $(".number-tab-stepsPreg").steps({
                                headerTag: "h6",
                                bodyTag: "fieldset",
                                transitionEffect: "fade",
                                titleTemplate: '<span class="step">#index#</span> #title#',
                                labels: {
                                    finish: 'Finalizar'
                                },
                                onFinished: function(event, currentIndex) {

                                    if (flagTimFin === "s") {
                                        mensaje =
                                            "El Tiempo de Evaluación a Finalizado";
                                        Swal.fire({
                                            title: "",
                                            text: mensaje,
                                            icon: "warning",
                                            button: "Aceptar",
                                        });
                                        return;
                                    }

                                    $.GuarPreg(currentIndex, 'Ultima');
                                    if (flagGlobal === "s") {
                                        return;
                                    }
                                },
                                onStepChanging: function(event, currentIndex,
                                    newIndex) {
                                    // Allways allow previous action even if the current form is not valid!
                                    if (flagTimFin === "s") {
                                        mensaje =
                                            "El Tiempo de Evaluación a Finalizado";
                                        Swal.fire({
                                            title: "",
                                            text: mensaje,
                                            icon: "warning",
                                            button: "Aceptar",
                                        });
                                        return;
                                    }

                                    $.GuarPreg(currentIndex, 'next');

                                    if (flagGlobal === "s") {
                                        return;
                                    }
                                    $.CargPreg(newIndex);

                                    if (currentIndex > newIndex) {
                                        return true;
                                    }
                                    form.validate().settings.ignore =
                                        ":disabled,:hidden";
                                    return form.valid();
                                },
                            });

                            ///////////////////////


                        }

                    });

                    $("#btn_salirModEv").hide();
                    $("#btn_atrasModEv").show();



                    //////MOSTRAR CONTADOR DE EVALUACIÓN//////////
                    if (HabTie === "SI") {
                        mensaje = "Esta Evaluación Cuenta con un Tiempo de " + Tiempo +
                            " para ser Desarrollada. ¿Desea Realizar Esta Evaluación?";
                            Swal.fire({
                                title: 'Notificación Evaluación',
                                text: mensaje,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Si, Comenzar!',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                $("#btn_eval").show();
                                $("#titu_Eva").show();
                                $("#titu_temaEva").hide();

                                if (HabTie == "SI") {
                                    var hora = Tiempo;

                                    parts = hora.split(':');
                                    var hora = Tiempo;
                                    parts = hora.split(':');
                                    var hor = parts[0];
                                    var min = parts[1];

                                    var milhor = parseInt(hor) * 3600000;
                                    var milmin = parseInt(min) * 60000;


                                    $("#contTiempo").show();
                                    // Establece la fecha hasta la que estamos contando
                                    var countDownDate = milhor + milmin;

                                    var ahora = new Date().getTime();
                                    countDownDate = countDownDate + ahora;
                                    var tiempoextra = 300000;

                                    // Actualiza la cuenta atrás cada 1 segundo.
                                    xtiempo = setInterval(function() {

                                        var oElem = document.getElementById('cuenta');
                                        oElem.style.backgroundColor = oElem.style
                                            .backgroundColor == 'white' ? '#00b5b8' :
                                            'white';

                                        // Obtener la fecha y la hora de hoy
                                        var now = new Date().getTime();

                                        // Encuentra la distancia entre ahora y la fecha de la cuenta regresiva
                                        var distance = countDownDate - now;

                                        // Cálculos de tiempo para días, horas, minutos y segundos
                                        var days = Math.floor(distance / (1000 * 60 *
                                            60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 *
                                            60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 *
                                            60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 *
                                            60)) / 1000);

                                        var tiempoCompl = now - ahora;


                                        // Muestra el resultado en un elemento
                                        document.getElementById("cuenta").innerHTML =
                                            hours + "h " + minutes + "m " + seconds +
                                            "s ";
                                        var horas = Math.floor(tiempoCompl / (1000 *
                                            60 * 60));
                                        var minutes = Math.floor(tiempoCompl / 60000);
                                        var seconds = ((tiempoCompl % 60000) / 1000)
                                            .toFixed(0);

                                        $("#tiempEvaluacion").val(horas + ":" +
                                            minutes + ":" + (seconds < 10 ? '0' :
                                                '') + seconds);

                                        // Si la cuenta atrás ha terminado, escribe un texto.

                                        if (flagTimExt === "n") {
                                            if (distance < tiempoextra) {
                                                flagTimExt = "s";
                                                mensaje =
                                                    "La Evaluación finalizara en 5 Minutos, si aún tiene preguntas por responder por favor responda y presione el botón Finalizar.";
                                                Swal.fire({
                                                    title: "Notificación de Evaluación",
                                                    text: mensaje,
                                                    icon: "warning",
                                                    button: "Aceptar",
                                                });
                                            }
                                        }

                                        if (flagTimExt === "s") {
                                            if (distance < 0) {
                                                flagTimFin = "s";
                                                clearInterval(x);
                                                document.getElementById("cuenta")
                                                    .innerHTML =
                                                    "TIEMPO DE EVALUACIÓN TERMINADO";

                                                mensaje =
                                                    "La Evaluación ha finalizado, si no logro terminar informe al Docente encargado.";
                                                Swal.fire({
                                                    title: "Notificación de Evaluación",
                                                    text: mensaje,
                                                    icon: "warning",
                                                    button: "Aceptar",
                                                });

                                            }
                                        }

                                    }, 1000);
                                }
                                ////////////////////////FIN CONTADOR////////////////////////


                            } else {
                                $.AtrasModActIni('F');
                            }
                        });
                    }


                },
                AbrirpDF: function() {

                    var Tema = $("#idTema").val();
                    var form = $("#formGenPDFTema");
                    $("#idTem").remove();
                    form.append("<input type='hidden' name='idTem' id='idTem' value='" + Tema + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                             $("#DivcontenidoPDF").html(respuesta.DeTema.cont_documento);
                             $(".contenidoPDF").printThis();
                            }
                    });

                },
                CargPreg: function(id) {


                    var form = $("#formAuxiliarCargEval");
                    var Preg = $("#id-pregunta" + id).val();
                    var tipo = $("#tip-pregunta" + id).val();

                    var opci = "";
                    var parr = "";
                    var punt = "";

                    $("#Pregunta").remove();
                    $("#TipPregunta").remove();
                    form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                        Preg + "'>");
                    form.append(
                        "<input type='hidden' name='TipPregunta' id='TipPregunta' value='" + tipo +
                        "'>"
                    );
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var j = 1;
                    var Pregunta = "";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: true,
                        dataType: "json",
                        success: function(respuesta) {
                            if (tipo === "PREGENSAY") {
                                $("#Puntaje" + id).html(respuesta.PregEnsayo.puntaje +
                                    " Puntos");

                                Pregunta += respuesta.PregEnsayo.pregunta;
                                Pregunta += '<div class="col-xl-12 col-lg-6 col-md-12">' +
                                    '   <label for="placeTextarea">Respuesta:</label>' +
                                    ' <textarea cols="80" id="RespPregEns" name="RespPregEns"' +
                                    ' rows="3"></textarea>' +
                                    ' </div>';
                                $("#Pregunta" + id).html(Pregunta);
                                $.hab_ediContPregEnsayo();
                                if (respuesta.RespPregEnsayo) {
                                    $('#RespPregEns').val(respuesta.RespPregEnsayo
                                        .respuesta);
                                }
                            } else if (tipo === "COMPLETE") {
                                $("#Puntaje" + id).html(respuesta.PregComple.puntaje +
                                    " Puntos");
                                Pregunta += '<div class="col-xl-12 col-lg-6 col-md-12">' +
                                    '   <label for="placeTextarea">Complete el Parrafo con las siguientes Opciones:</label>' +
                                    '<p>' + respuesta.PregComple.opciones + '</p>' +
                                    ' <textarea cols="80" id="RespPregComplete" name="RespPregComplete"' +
                                    ' rows="3"></textarea>' +
                                    ' </div>';
                                $("#Pregunta" + id).html(Pregunta);
                                $.hab_ediContComplete();
                                $('#RespPregComplete').val(respuesta.PregComple.parrafo);
                                if (respuesta.RespPregComple) {
                                    $('#RespPregComplete').val(respuesta.RespPregComple
                                        .respuesta);
                                }

                            } else if (tipo === "OPCMULT") {
                                $("#Puntaje" + id).html(respuesta.PregMult.puntuacion +
                                    " Puntos");
                                Pregunta +=
                                    '<div class="pb-1"><input type="hidden"  name="PreguntaOpc" value="' +
                                    respuesta.PregMult.id + '" />' + respuesta.PregMult
                                    .pregunta + '</div>';
                                opciones = '';
                                var l = 1;
                                $.each(respuesta.OpciMult,
                                    function(k, itemo) {

                                        if ($.trim(itemo
                                                .pregunta
                                            ) === $
                                            .trim(respuesta.PregMult.id)) {
                                            if (respuesta.RespPregMul) {
                                                opciones +=
                                                    '<fieldset>';
                                                if ($.trim(respuesta.RespPregMul
                                                        .respuesta) === $.trim(itemo
                                                        .id)) {
                                                    opciones +=
                                                        '<input type="hidden" id="OpcionSel_' +
                                                        l +
                                                        '" class="OpcionSel"  name="OpcionSel[]" value="si"/>';
                                                    opciones +=
                                                        ' <input type="hidden" id=""  name="Opciones[]" value="' +
                                                        itemo.id + '"/>';
                                                    opciones +=
                                                        '<input onclick="$.RespMulPreg(this.id)" id="' +
                                                        l +
                                                        '" class="checksel" checked type="checkbox" >';
                                                } else {
                                                    opciones +=
                                                        '<input type="hidden" id="OpcionSel_' +
                                                        l +
                                                        '" class="OpcionSel"  name="OpcionSel[]" value="no"/>';
                                                    opciones +=
                                                        ' <input type="hidden" id=""  name="Opciones[]" value="' +
                                                        itemo.id + '"/>';
                                                    opciones +=
                                                        '<input onclick="$.RespMulPreg(this.id)" id="' +
                                                        l +
                                                        '" class="checksel" type="checkbox" >';
                                                }


                                                opciones +=
                                                    ' <label for="input-15"> ' +
                                                    itemo
                                                    .opciones +
                                                    '</label>' +
                                                    '</fieldset>';
                                                l++;
                                            } else {
                                                opciones +=
                                                    '<fieldset>';
                                                opciones +=
                                                    '<input type="hidden" id="OpcionSel_' +
                                                    l +
                                                    '" class="OpcionSel"  name="OpcionSel[]" value="-"/>';
                                                opciones +=
                                                    ' <input type="hidden" id=""  name="Opciones[]" value="' +
                                                    itemo.id + '"/>';
                                                opciones +=
                                                    '<input onclick="$.RespMulPreg(this.id)" id="' +
                                                    l +
                                                    '" class="checksel" type="checkbox" >';

                                                opciones +=
                                                    ' <label for="input-15"> ' +
                                                    itemo
                                                    .opciones +
                                                    '</label>' +
                                                    '</fieldset>';
                                                l++;
                                            }

                                        }

                                    });

                                $("#Pregunta" + id).html(Pregunta + opciones);


                            } else if (tipo === "VERFAL") {
                                $("#Puntaje" + id).html(respuesta.PregVerFal.puntaje +
                                    " Puntos");


                                Pregunta += respuesta.PregVerFal.pregunta;
                                var Opc =
                                    '<div class="form-group row">' +
                                    '<div class="col-md-12">' +
                                    '    <fieldset >' +
                                    '        <div class="input-group">';

                                Opc +=
                                    '<input name="radpregVerFal[]" id="RadVer" value="si"  type="radio">';

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
                                Opc +=
                                    ' <input name="radpregVerFal[]" id="RadFal"  value="no"  type="radio">';
                                Opc +=
                                    '<div class="input-group-append" style="margin-left:5px;">' +
                                    '            <span  id="basic-addon2">Falso</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>' +
                                    '</div>' +
                                    '            </div>';


                                $("#Pregunta" + id).html(Pregunta + Opc);

                                if (respuesta.RespPregVerFal) {
                                    if (respuesta.RespPregVerFal.respuesta_alumno ===
                                        "si") {
                                        $('#RadVer').prop("checked", "checked");
                                    } else {
                                        $('#RadFal').prop("checked", "checked");
                                    }
                                }
                            } else if (tipo === "RELACIONE") {
                                $("#Puntaje" + id).html(respuesta.PregRelacione.puntaje +
                                    " Puntos");
                                var enun = respuesta.PregRelacione.enunciado;
                                if (enun === null) {
                                    enun = "";
                                }
                                Pregunta += '<div class="row"><div class="col-md-12"><p>' +
                                    enun + '</p></div></div><div class="row">';
                                var j = 1;
                                var selectPreg = '';
                                var cons = 1;

                                $.each(respuesta.PregRelIndi, function(k, item) {

                                    selectPreg = '<div  style="text-transform: none;" class="contenedor' + cons +
                                        '">' +
                                        '    <div class="selectbox">' +
                                        '        <div class="select" id="select' +
                                        cons + '">' +
                                        '            <div class="contenido-select">' +
                                        '               <h5 class="titulo">Seleccione Una Respuesta</h5>' +
                                        '            </div>' +
                                        '           <i class="fa fa-angle-down"></i>' +
                                        '       </div>' +
                                        '<div class="opciones" id="opciones' +
                                        cons + '">';
                                    var j = 1;
                                    $.each(respuesta.PregRelResp, function(k,
                                        itemr) {
                                        selectPreg +=
                                            ' <a onclick="$.selopc(this.id,' +
                                            cons + ')" id="' + j +
                                            '" data-id="' + itemr.id +
                                            '" class="opcion">' +
                                            '<div class="contenido-opcion">' +
                                            itemr.respuesta +
                                            '     </div>' +
                                            '   </a>';
                                        j++;
                                    });
                                    selectPreg += '</div>' +
                                        '   </div>' +
                                        '    <input type="hidden"  name="RespSelect[]" id="RespSelect' +
                                        cons + '" value="">' +
                                        '    <input type="hidden"  name="RespPreg[]" value="' +
                                        item.id + '">' +
                                        '    <input type="hidden"  name="ConsPreg[]" id="ConsPreg' +
                                        cons + '" value="">' +
                                        ' </div>';
                                    Pregunta +=
                                        '<div class="col-md-6 pb-2" style="display: flex;align-items: center;justify-content: center;"> <div  id="DivInd' +
                                        j + '">' + item.definicion + '</div></div>';
                                    Pregunta +=
                                        '<div class="col-md-6 pb-2"> <div id="DivRes' +
                                        j + '">' + selectPreg + '</div></div>';
                                    cons++;
                                });

                                Pregunta += '</div>';

                                $("#Pregunta" + id).html(Pregunta);
                                cons = 1;
                                $.each(respuesta.PregRelIndi, function(k, item) {
                                    const select = document.querySelector(
                                        '#select' + cons);
                                    const opciones = document.querySelector(
                                        '#opciones' + cons);
                                    const contenidoSelect = document.querySelector(
                                        '#select' + cons + ' .contenido-select');
                                    const hiddenInput = document.querySelector(
                                        '#inputSelect' + cons);

                                    document.querySelectorAll('#opciones' + cons +
                                        ' > .opcion').forEach((opcion) => {
                                        opcion.addEventListener('click', (
                                            e) => {
                                            e.preventDefault();
                                            contenidoSelect
                                                .innerHTML = e
                                                .currentTarget
                                                .innerHTML;
                                            select.classList.toggle(
                                                'active');
                                            opciones.classList
                                                .toggle('active');
                                        });
                                    });

                                    select.addEventListener('click', () => {
                                        select.classList.toggle('active');
                                        opciones.classList.toggle('active');
                                    });
                                    cons++;

                                });

                                cons = 1;
                                $.each(respuesta.RespPregRelacione, function(k, item) {
                                    const select = document.querySelector(
                                        '#select' + cons);
                                    const opciones = document.querySelector(
                                        '#opciones' + cons);
                                    const contenidoSelect = document.querySelector(
                                        '#select' + cons + ' .contenido-select');
                                    const hiddenInput = document.querySelector(
                                        '#inputSelect' + cons);
                                    const sel = document.querySelectorAll(
                                        '#opciones' + cons + ' > .opcion')

                                    contenidoSelect.innerHTML = sel[item.consecu -
                                        1].innerHTML;
                                    select.classList.toggle('active');
                                    $.selopc(item.consecu, cons)
                                    cons++;
                                });

                            } else if (tipo === "TALLER") {
                                $("#Puntaje" + id).html(respuesta.PregTaller.puntaje +
                                    " Puntos");

                                $("#CargArchi").val("");

                                Pregunta +=
                                    '<div class="row"><div class="col-md-12 pb-1">' +
                                    ' <label class="form-label " for="imagen">Ver Archivo Cargado:</label>' +
                                    ' <div class="btn-group" role="group" aria-label="Basic example">' +
                                    '   <button id="idimg' + id +
                                    '" type="button" data-archivo="' + respuesta.PregTaller
                                    .nom_archivo +
                                    '" onclick="$.MostArc(this.id);" class="btn btn-success"><i' +
                                    '             class="fa fa-download"></i> Descargar Archivo</button>' +
                                    '      </div>' +
                                    '</div></div>';

                                Pregunta += ' <div class="row">' +
                                    '   <div class="col-md-12">' +
                                    '       <div class="form-group" id="divarchi">' +
                                    '       <h6 class="form-section"><strong>Agregar Desarrollo de Taller: </strong> </h6>' +
                                    '             <input id="archiTaller"  name="archiTaller" type="file">' +
                                    '       </div>' +
                                    '  </div>' +
                                    '</div>';

                                $("#Pregunta" + id).html(Pregunta);

                                var archivo = "";

                                if (respuesta.RespPregTaller) {
                                    $("#CargArchi").val(respuesta.RespPregTaller.archivo);
                                    archivo +=
                                        ' <div class="form-group" id="id_file" style="display:none;">' +
                                        '<label class="form-label " for="imagen">Agregar Desarrollo de Taller: </label>' +
                                        '<input type="file" id="archiTaller" name="archiTaller" />' +
                                        '</div>' +
                                        '<div class="form-group" id="id_verf">' +
                                        '<label class="form-label " for="imagen">Ver Desarrollo de Taller: </label>' +
                                        '<div class="btn-group" role="group" aria-label="Basic example">' +
                                        '<button type="button" id="archi" onclick="$.VerArchResp(this.id);" data-archivo="' +
                                        respuesta.RespPregTaller.archivo +
                                        '" class="btn btn-success"><i' +
                                        '            class="fa fa-search"></i> Ver Archivo</button>' +
                                        '<button type="button" onclick="$.CambArchivo();" class="btn btn-warning"><i' +
                                        '           class="fa fa-refresh"></i> Cambiar Archivo</button>' +
                                        ' </div>' +
                                        ' </div>';

                                    $("#divarchi").html(archivo);
                                }

                            }

                        }

                    });

                },
                CambArchivo: function() {
                    $("#id_file").show();
                    $("#id_verf").hide();
                    $("#CargArchi").val("s");
                },
                MostArc: function(id) {
                    window.open($('#dattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
                },
                VerArchResp: function(id) {
                    window.open($('#Respdattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
                },
                selopc: function(id, cons) {
                    $("#RespSelect" + cons).val($("#" + id).data("id"));
                    $("#ConsPreg" + cons).val(id);

                },
                GuarPreg: function(id, npreg) {

                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                    flagGlobal = "n";
                    var form = $("#Evaluacion");
                    var url = form.attr("action");
                    var IdEval = $("#IdEval").val();
                    var token = $("#token").val();
                    var Id_Doce = $("#Id_Doce").val();
                    var archivo = $("#CargArchi").val();
                    var Preg = $("#id-pregunta" + id).val();
                    var tipo = $("#tip-pregunta" + id).val();
                    var tiempo = $("#tiempEvaluacion").val();
                    if ($("#Tip_Usu").val() === "Estudiante") {

                        if (tipo === "OPCMULT") {
                            var sel = "n";
                            if ($('.checksel').is(':checked')) {
                                sel = "s";
                            }

                            if (sel === "n") {
                                flagGlobal = "s";
                                mensaje = "No ha seleccionado ninguna Opción";
                                Swal.fire({
                                    title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                                return;
                            }
                        } else if (tipo === "VERFAL") {
                            var sel = "n";
                            if ($("input:radio[name='radpregVerFal[]']").is(":checked")) {
                                sel = "s";
                            }

                            if (sel === "n") {
                                flagGlobal = "s";
                                mensaje = "No ha seleccionado ninguna Opción";
                                Swal.fire({
                                    title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                                return;
                            }

                        } else if (tipo === "RELACIONE") {
                            var sel = "s";
                            $("input[name='RespSelect[]']").each(function(indice, elemento) {
                                if ($(elemento).val() === '') {
                                    sel = "n";
                                }
                            });

                            if (sel === "n") {
                                flagGlobal = "s";
                                mensaje = "No se han completado las relaciones";
                                Swal.fire({
                                    title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                                return;
                            }
                        } else if (tipo === "TALLER") {
                            var sel = "s";
                            if ($('#archiTaller').val()) {} else {
                                sel = "n";
                            }

                            if (sel === "n" && archivo === "") {
                                flagGlobal = "s";
                                mensaje = "No se ha cargado ningun archivo";
                                Swal.fire({
                                    title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                                return;
                            }


                        }

                        if (flagIntent === "fail" && $("#Tip_Usu").val() === "Estudiante") {
                            flagGlobal = "s";
                            mensaje = "Ha superado Los Intentos Permitidos";
                            Swal.fire({
                                title: "",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return;
                        }

                    } else {
                        if(npreg === "Ultima"){
                            mensaje = "Solo los Estudiantes pueden Responder las Evaluaciones";
                            Swal.fire({
                                title: "",
                                text: mensaje,
                                icon: "warning",
                                button: "Aceptar",
                            });
                        }else{
                            $("#Pregunta" + id).html("");
                        }
                

                      
                        return;
                    }

                    $("#Pregunta").remove();
                    $("#TipPregunta").remove();
                    $("#nPregunta").remove();
                    $("#NArchivo").remove();
                    $("#IdEvaluacion").remove();
                    $("#idtoken").remove();
                    $("#Id_Docente").remove();
                    $("#Tiempo").remove();
                    clearInterval(xtiempo);
                    xtiempo = null;
                    form.append("<input type='hidden' name='Pregunta' id='Pregunta' value='" +
                        Preg + "'>");
                    form.append("<input type='hidden' name='nPregunta' id='nPregunta' value='" +
                        npreg + "'>");
                    form.append(
                        "<input type='hidden' name='TipPregunta' id='TipPregunta' value='" + tipo +
                        "'>");
                    form.append(
                        "<input type='hidden' name='NArchivo' id='NArchivo' value='" + archivo +
                        "'>"
                    );
                    form.append("<input type='hidden' name='IdEvaluacion' id='IdEvaluacion' value='" +
                        IdEval + "'>");
                    form.append("<input type='hidden' id='idtoken' name='_token'  value='" + token +
                        "'>");
                    form.append("<input type='hidden' id='Id_Docente' name='Id_Docente'  value='" +
                        Id_Doce + "'>");
                    form.append("<input type='hidden' id='Tiempo' name='Tiempo'  value='" +
                        tiempo + "'>");



                    if (tipo === "TALLER") {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: new FormData($('#Evaluacion')[0]),
                            processData: false,
                            contentType: false,
                            success: function(respuesta) {
                                if (npreg === "Ultima") {
                                    $.MostrResulEval(respuesta);

                                }

                            },
                            error: function() {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }
                        });
                    } else {
                        var datos = form.serialize();

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            dataType: "json",
                            async: false,
                            success: function(respuesta) {
                                if (npreg === "Ultima") {
                                    $.MostrResulEval(respuesta);

                                }
                            },
                            error: function() {
                                mensaje = "La Evaluación no pudo ser Guardada";
                                Swal.fire({
                                    title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            }
                        });

                    }


                    if (npreg === "Ultima") {

                    } else {
                        $("#Pregunta" + id).html("");
                    }


                },
                MostrResulEval: function(respuesta) {

                    Swal.fire({
                        title: "Notificación de Evaluación",
                        text: "La Evaluación fue Guardada Exitosamente",
                        icon: "success",
                        button: "Aceptar",
                    });
                    var IntReal = respuesta.InfEval.int_realizados;
                    var IntPerm = respuesta.InfEval.intentos_perm;
                    var puntMax = parseInt(respuesta.InfEval.punt_max);
                    var puntTotal = parseInt(respuesta.Libro.puntuacion);
                    var TipCali = respuesta.InfEval.calif_usando;

                    $("#DetEval").hide();
                    $("#contTiempo").hide();
                    $("#Dat_Cal").hide();
                    $("#VidDidac").hide();

                    $("#DetEvalFin").show();

                    var $wrapper = $('#DetEvalFin');
                    $wrapper.avnSkeleton('display');

                    $wrapper.avnSkeleton('remove');

                    $wrapper.find('> header').append("Resultado de Evaluación");
                    var tiempoEval = "";
                    var tiempoUsad = "";

                    if (respuesta.InfEval.hab_tiempo === "NO") {
                        tiempoEval = "Esta Evaluación no Cuenta con un tiempo para su Desarrollo";
                        tiempoUsad = "No Aplica";
                    } else {
                        tiempoEval = respuesta.InfEval.tiempo;
                        tiempoUsad = respuesta.Libro.tiempo_usado;
                    }


                    var contenido = '<div class="card">' +
                        '<div class="card-content">' +
                        '  <div class="card-body">' +
                        '    <h4 class="card-title">' + respuesta.InfEval.titulo + '</h4>' +
                        '  </div>' +
                        '  <ul class="list-group list-group-flush">' +
                        '    <li class="list-group-item">' +
                        '      <span class="badge badge-default badge-pill bg-info float-right">' +
                        respuesta.InfEval.titu_contenido + '</span><b>Nombre del Tema:</b>  ' +
                        '    </li>' +
                        '    <li class="list-group-item">' +
                        '      <span class="badge badge-default badge-pill bg-info float-right">' +
                        tiempoEval + '</span> <b>Tiempo de la Evaluación:</b>' +
                        '    </li>' +
                        '     <li class="list-group-item">' +
                        '       <span class="badge badge-default badge-pill bg-danger float-right">' +
                        tiempoUsad + '</span> <b>Tiempo Utilizado:</b>' +
                        '</li>' +
                        '<li class="list-group-item">' +
                        '<span class="badge badge-default badge-pill bg-warning float-right">' +
                        respuesta.InfEval.int_realizados + '/' + respuesta.InfEval.intentos_perm +
                        '</span> <b>Intentos</b>' +
                        '</li>' +
                        '<li class="list-group-item">' +
                        '<span id="txt_califVis" class="badge badge-default badge-pill  float-right">30/60</span> <b>Calificación:</b> ' +
                        '</li>' +
                        '</ul>' +
                        '</div>' +
                        '</div>';

                    $wrapper.find('> main').append(contenido);


                    if (respuesta.InfEval.calxdoc == "SI") {
                        $("#txt_califVis").css('background-color', '#2DCEE3');
                        $("#txt_califVis").html("PENDIENTE POR CALIFICAR.");

                    } else {

                        var porcentaje = (puntTotal / puntMax) * 100;
                        if (porcentaje <= 50) {
                            $("#txt_califVis").css('background-color', '#f20d00');
                        } else if (porcentaje > 50 && porcentaje <= 60) {
                            $("#txt_califVis").css('background-color', '#F08D0E');
                        } else if (porcentaje > 60 && porcentaje <= 70) {
                            $("#txt_califVis").css('background-color', '#F5DA00');
                        } else if (porcentaje > 70 && porcentaje <= 80) {
                            $("#txt_califVis").css('background-color', '#C0EA1C');
                        } else if (porcentaje > 80 && porcentaje <= 100) {
                            $("#txt_califVis").css('background-color', '#1ECD60');
                        }
                        $("#txt_califVis").css('color', '#ffffff');

                        if (TipCali == "Puntos") {
                            $("#txt_califVis").html(puntTotal + "/" + puntMax);
                        } else if (TipCali == "Porcentaje") {
                            $("#txt_califVis").html(porcentaje + "%");
                        } else {
                            switch (true) {
                                case (porcentaje < 35):
                                    $("#txt_califVis").html("Super Bajo");
                                    break;
                                case (porcentaje >= 35 && porcentaje < 60):
                                    $("#txt_califVis").html("Bajo");
                                    break;
                                case (porcentaje >= 60 && porcentaje < 80):
                                    $("#txt_califVis").html("Basico");
                                    break;
                                case (porcentaje >= 80 && porcentaje < 95):
                                    $("#txt_califVis").html("Alto");
                                    break;
                                case (porcentaje >= 95):
                                    $("#txt_califVis").html("Superior");
                                    break;
                            }
                        }

                    }

                },
                ReiniciarCont: function() {
                    $("#contTiempo").hide();
                    $('#cuenta').timer('remove');
                    clearInterval(xtiempo);
                    xtiempo = null;
                },
                mostmodArch: function(id) {

                    var nomarchi=$('#' + id).data("archivo");

                    var ext = nomarchi.substring(nomarchi.lastIndexOf("."));
                    if(ext != ".jpg" && ext != ".png" && ext != ".gif" && ext != ".jpeg" && ext != ".pdf"){
                        window.open($('#' + id).data("ruta") + "/" +nomarchi,'_blank');
                    }else{
                        $("#cont_tema").hide();
                        $("#cont_archi").show();
                        $("#btn_atras").show();
                        $("#btn_salir").hide();
                        $("#div_arc").html(
                            '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                        );
                        jQuery('#embed_arch').attr('src', $('#' + id).data("ruta") + "/" + nomarchi);
                    }
                },
                mostmodlink: function(url) {
                    $("#cont_tema").hide();
                    $("#cont_archi").show();
                    $("#btn_atras").show();
                    $("#btn_salir").hide();
                    $("#div_arc").html('<embed src="' + url +
                        '" type="application/pdf" width="100%" height="600px" />');
                },
                mostListArc: function() {
                    $("#cont_archi").hide();
                    $("#cont_tema").show();
                    $("#btn_atras").hide();
                    $("#btn_salir").show();
                },
                mostListPeri: function() {
                    $("#Carg_contenido").hide();
                    $("#Carg_periodos").show();
                },
                MostVid: function() {
                    $("#ModVidelo").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#ModEval').modal('toggle');
                },
                SalirAnim: function() {
                    $('#ModVidelo').modal('toggle');
                    $("#ModEval").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var videoID = 'datruta';
                    $('#' + videoID).get(0).pause();
                },
                AbrirConv: function(opc) {
                    if (opc === "M") {
                        $("#ModComent").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                    var form = $("#formConsuComent");
                    var contenido = '';
                    var id = $("#idTemaEva").val();
                    var Id_Doce = $("#Id_Doce").val();
                    $("#idEvalComent2").remove();
                    $("#idDoce2").remove();
                    form.append("<input type='hidden' name='idEvalComent2' id='idEvalComent2' value='" +
                        id + "'>");
                    form.append("<input type='hidden' name='idDoce2' id='idDoce2' value='" + Id_Doce +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var j = 1;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $.each(respuesta.Comet, function(i, item) {

                                contenido += '<div id=' + j +
                                    ' class="col-lg-12" style="padding-bottom: 5px;">' +
                                    '<div class="media-body">' +
                                    '    <p class="text-bold-600 mb-0" style="text-transform: capitalize;"><a href="#">' +
                                    item.nombre_usuario + '</a></p>' +
                                    '    <p>' + item.comentario + '</p>' +
                                    ' </div>' +
                                    ' </div>';
                                j++;
                            });
                            $("#Div_Comentarios").append(contenido).html("").append(
                                contenido);
                            $("#etiquetafinal").remove();
                            $("#Div_Comentarios").append(
                                "<span id='etiquetafinal'></span>");
                            document.getElementById('etiquetafinal').scrollIntoView(true);
                        }
                    });
                },
                AbrirConvEval: function(opc) {

                    if (opc == "M") {
                        $("#ModComent").modal();
                        $('#ModComent').modal({
                            keyboard: false,
                            show: true
                        });
                        $('.comenta').draggable({
                            handle: ".modal-header"
                        });
                    }

                    var contenido = '';
                    var form = $("#formConsuComent");
                    var id = $("#idTemaEva").val();
                    var Id_Doce = $("#Id_Doce").val();
                    $("#idEvalComent2").remove();
                    $("#idDoce2").remove();
                    form.append("<input type='hidden' name='idEvalComent2' id='idEvalComent2' value='" +
                        id + "'>");
                    form.append("<input type='hidden' name='idDoce2' id='idDoce2' value='" + Id_Doce +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var j = 1;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $.each(respuesta.Comet, function(i, item) {

                                contenido += '<div id=' + j +
                                    ' class="col-lg-12">' +
                                    '<div class="media-body">' +
                                    '    <p class="text-bold-600 mb-0" style="text-transform: capitalize;"><a href="#">' +
                                    item.nombre_usuario + '</a></p>' +
                                    '    <p>' + item.comentario + '</p>' +
                                    ' </div>' +
                                    ' </div>';
                                j++;
                            });
                            $("#Div_Comentarios").append(contenido).html("").append(
                                contenido);
                            $("#etiquetafinal").remove();
                            $("#Div_Comentarios").append(
                                "<span id='etiquetafinal'></span>");
                            document.getElementById('etiquetafinal').scrollIntoView(true);
                        }
                    });
                },
                CloseModActIni: function() {
                    $("#ModEval").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#large').modal('toggle');
                },
                CloseModAnimaciones: function() {
                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#ModAnima').modal('toggle');
                },
                hab_ediContComplete: function() {
                    CKEDITOR.replace('RespPregComplete', {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediContGrupPreg: function(id) {

                    CKEDITOR.replace('summernoteContPregMul' + id, {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediContPregEnsayo: function() {
                    CKEDITOR.replace('RespPregEns', {
                        width: '100%',
                        height: 100
                    });
                },
                AtrasModActIni: function(opc) {
                    if (opc == "F") {
                        $("#ListEval").show();
                        $("#contTiempo").hide();
                        $("#VidDidac").hide();
                        $("#btn_ConversaEval").hide();
                        $("#DetEval").hide();
                        $("#DetEvalFin").hide();
                        $("#btn_eval").hide();
                        $("#Dat_Cal").hide();
                        $("#btn_atrasModEv").show();
                        $("#btn_salirModEv").hide();
                        $("#titu_Eva").hide();
                        $("#titu_temaEva").show();
                        //            $("#DetEval").html("");
                    } else {

                        var mensaje = "¿Esta seguro de Cerrar La Evaluación?";
                        Swal.fire({
                            title: 'Notificación Evaluación',
                            text: mensaje,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Cerrar!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#ListEval").show();
                                $("#contTiempo").hide();
                                $("#btn_ConversaEval").hide();
                                $("#DetEval").hide();
                                $("#DetEvalFin").hide();
                                $("#btn_eval").hide();
                                $("#VidDidac").hide();
                                $("#Dat_Cal").hide();
                                $("#btn_salirModEv").show();
                                $("#btn_atrasModEv").hide();
                                $("#titu_Eva").hide();
                                $("#titu_temaEva").show();
                                //            $("#DetEval").html("");
                                $.ReiniciarCont();
                            }
                        });
                    }

                },
                AtrasModAnima: function() {
                    $("#ListAnimaciones").show();
                    $("#DetAnimaciones").hide();
                    $("#btn_salirModAnima").show();
                    $("#btn_atrasModAnima").hide();
                    var videoID = 'videoclipAnima';
                    $('#' + videoID).get(0).pause();
                },
                AbrirAct: function(opc) {

                    var $wrapper = $('#ListEval');
                    $wrapper.avnSkeleton('display');
                    $("#ModEval").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    $('#large').modal('toggle');
                    var form = $("#formConsuAct");
                    var id = $("#idTema").val();
                    var contenido = '';
                    $("#Tema").remove();
                    $("#clasf").remove();
                    var Text_Coment = $("#Text_Coment").val();
                    form.append("<input type='hidden' name='Tema' id='Tema' value='" + id +
                        "'><input type='hidden' name='clasf' id='clasf' value='" + opc + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var j = 1;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $wrapper.avnSkeleton('remove');
                            $.each(respuesta.Eval, function(i, item) {
                                contenido +=
                                    "<div class='bs-callout-success callout-square callout-bordered mt-1'>" +
                                        "<div class='media align-items-stretch'>" +
                                            " <div style='cursor:pointer' onclick='$.MostEval(" + item.id +");' class='d-flex align-items-center bg-success p-2'>" +
                                                "       <i class='ft-user-check white font-medium-5'></i>" +
                                                " </div>" +
                                                "  <div class='media-body p-1'>" +
                                                "<a style='cursor:pointer;text-transform: capitalize;font-weight: bold;' onclick='$.MostEval(" + item.id + ");'>" + item.titulo.toLowerCase() +"</a>"+   
                                                " </div>" +
                                    "    </div>" +
                                    "  </div>";
                                j++;
                            });
                            if (opc === "PRODUC") {
                                $wrapper.find('> header').append('PRODUCCIÓN - ' + respuesta
                                    .TitTemas);
                            } else {
                                $wrapper.find('> header').append(
                                    'ACTIVIDADES DE INICIO - ' + respuesta.TitTemas);
                            }
                            $wrapper.find('> main').append(contenido);

                        }
                    });
                },
                AbrirAnimaciones: function(opc) {

                    $("#ModAnima").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#large').modal('toggle');
                    var form = $("#formConsuAnim");
                    var id = $("#idTema").val();
                    var contenido = '';
                    $("#TemaAni").remove();
                    form.append("<input type='hidden' name='TemaAni' id='TemaAni' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var j = 1;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $.each(respuesta.DesAnim, function(i, item) {
                                contenido +=
                                    '<div class="bs-callout-warning callout-square callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    ' <div style="cursor:pointer" class="d-flex align-items-center bg-success p-2">' +
                                    '       <i class="ft-video white font-medium-5"></i>' +
                                    '    </div>' +
                                    '    <div class="media-body p-1">' +
                                    '    <a style="cursor:pointer"  class="text-truncate">' +
                                    '     <a onclick="$.MostAnim(this.id)" id="' +
                                    item.id + '"  data-archivo="' + item
                                    .cont_didactico +
                                    '" data-ruta="{{ asset('/app-assets/Contenido_DidacticoModulos') }}" > <strong style="text-transform: capitalize;" >' +
                                    item.titulo.slice(0, -4).toLowerCase() +
                                '</strong></a>' +
                                '       </div>' +
                                '    </div>' +
                                '  </div>';
                                j++;
                            });
                            $("#ListAnimaciones").html(contenido);
                            $("#titu_temaAnim").html('ANIMACIONES DEL TEMA - ' + respuesta
                                .TitTema);
                        }
                    });
                },
                MostAnim: function(id) {
                    $("#DetAnimaciones").show();
                    $("#ListAnimaciones").hide();
                    $("#btn_atrasModAnima").show();
                    $("#btn_salirModAnima").hide();
                    var videoID = 'videoclipAnima';
                    var sourceID = 'mp4videoAnima';
                    var nomarchi = $('#' + id).data("archivo");
                    var newmp4 = $('#' + id).data("ruta") + "/" + nomarchi;
                    $('#' + videoID).get(0).pause();
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    $('#' + videoID).get(0).play();
                },
                GuarComent: function() {
                    var form = $("#formGuarComent");
                    var id = $("#idTemaEva").val();
                    var Text_Coment = $("#Text_Coment").val();
                    var Id_Doce = $("#Id_Doce").val();
                    $("#idEvalComent").remove();
                    $("#idDoce").remove();
                    $("#Coment").remove();
                    form.append("<input type='hidden' name='idEvalComent' id='idEvalComent' value='" +
                        id + "'>");
                    form.append("<input type='hidden' name='idDoce' id='idDoce' value='" + Id_Doce +
                        "'>");
                    form.append("<input type='hidden' name='Coment' id='Coment' value='" + Text_Coment +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $.AbrirConvEval('G');
                        }
                    });
                    $("#Text_Coment").val("")
                },

                RespMulPreg: function(id) {

                    $('.OpcionSel').val("no");

                    if ($('#' + id).prop('checked')) {
                        $('.checksel').prop("checked", "");
                        $('#' + id).prop("checked", "checked");
                        $('#OpcionSel_' + id).val("si");
                    }

                }
            });
        });
    </script>
@endsection
