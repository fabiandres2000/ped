@extends('Plantilla.Principal')
@section('title', 'Contenido Zona Libre')
@section('Contenido')
    <input type="hidden" class="form-control" id="Tip_Usu" value="{{ Auth::user()->tipo_usuario }}" />
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="form-control" id="RutImg" data-ruta="{{ Session::get('URL') }}/images/novideo.png" />
    <input type="hidden" class="form-control" id="RutEvalRelOpc"
        data-ruta="{{ Session::get('URL') }}/Archivos_EvalRelImgOpc" />
    <input type="hidden" class="form-control" id="RutEvalTaller" value="{{ url('/') }}/" />
    <input type="hidden" class="form-control" id="IdEval" value="" />
    <input type="hidden" class="form-control" id="Id_PregEns" value="" />
    <input type="hidden" class="form-control" id="TipEva" value="" />
    <input type="hidden" data-id='id-dat' id="dattaller"
        data-ruta="{{ asset('/app-assets/Archivos_EvaluacionTaller') }}" />
    <input type="hidden" class="form-control" name="CargArchi" id="CargArchi" value="" />
    <input type="hidden" class="form-control" id="tiempEvaluacion" value="" />

    <input type="hidden" class="form-control" id="RutContDid"
        data-ruta="{{ asset('/app-assets/Contenido_Laboratorio') }}" />
    <input type="hidden" data-id='id-dat' id="Respdattaller"
        data-ruta="{{ asset('/app-assets/Archivos_EvalTaller_Resp') }}" />
    <input type="hidden" class="form-control" id="RutEvalDid"
        data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}" />
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0">Laboratorios - {{ Session::get('des') }}</h3>

        </div>
    </div>

    <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
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

                    <button type="button" id="btn_salir" onclick="$.SalirAnim();" class="btn grey btn-outline-secondary"
                        data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="number-tabs">
            {{-- <div class="card" style="background-image: url({{ asset('../app-assets/images/logo/stack-logo.png') }}); width: 100%; height: 100vh; "> --}}
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-8 col-lg-6 col-md-12 text-left clearfix">
                                <h3 class="pt-1">
                                    <span class="fa fa-flask" id="TitLabo"> Laboratorios - Practicas</span>
                                </h3>

                            </div>

                            <div class="col-xl-12" id="ListLab">
                                @foreach ($DesLabo as $Lab)
                                    <div class="bs-callout-primary callout-transparent callout-bordered mt-1"
                                        style="cursor: pointer;" onclick="$.carg_labo({{ $Lab->id }});">
                                        <div class="media align-items-stretch">
                                            <div
                                                class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                                <i class="fa fa-list-alt fa-lg white font-medium-5"></i>
                                            </div>
                                            <div style="text-transform: capitalize;" class="media-body p-1">
                                                <strong> {{ $Lab->nom_unidad }}: </strong>{{ $Lab->des_unidad }}
                                                <strong style="color: springgreen; ">({{ $Lab->nlab }})</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-xl-12" id="ListLabUnid" style="display: none;">
                                <div id="contenedor">

                                </div>
                            </div>
                            <div class="col-xl-12" id="DetLabUnid" style="display: none;">
                                <div id="contenedorDeta">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title" id="Tit-Labo"></h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-top-border no-hover-bg">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="baseIcon-tab11" data-toggle="tab"
                                                            aria-controls="tabIcon11" href="#tabIcon11"
                                                            aria-expanded="true"><i class="fa fa-pencil-square-o"></i>
                                                            Fundamento Teorico</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseIcon-tab12" data-toggle="tab"
                                                            aria-controls="tabIcon12" href="#tabIcon12"
                                                            aria-expanded="false"><i class="fa fa-list"></i>
                                                            Materiales</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseIcon-tab13" data-toggle="tab"
                                                            aria-controls="tabIcon13" href="#tabIcon13"
                                                            aria-expanded="false"><i class="fa fa-list-ol"></i>
                                                            Procedimientos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseIcon-tab14" data-toggle="tab"
                                                            aria-controls="tabIcon14" href="#tabIcon14"
                                                            aria-expanded="false"><i class="fa fa-check-square-o"></i>
                                                            Producción</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content px-1 pt-1">
                                                    <div role="tabpanel" class="tab-pane active" id="tabIcon11"
                                                        aria-expanded="true" aria-labelledby="baseIcon-tab11">
                                                        <article id='cont_labo' class="wrapper">
                                                            <header style="text-transform: capitalize;font-size: 20px;"></header>
                                                            <main style="height: 400px; overflow: auto;"></main>
                                                        </article>
                                                    </div>
                                                    <div class="tab-pane" id="tabIcon12"
                                                        aria-labelledby="baseIcon-tab12">
                                                        <article id='mat_labo' class="wrapper">
                                                            <header style="text-transform: capitalize;font-size: 20px;"></header>
                                                            <main style="height: 400px; padding:20px; overflow: auto;"></main>
                                                        </article>
                                                    </div>
                                                    <div class="tab-pane" id="tabIcon13"
                                                        aria-labelledby="baseIcon-tab3">
                                                        <div id="DivProcedimientos"></div>
                                                    </div>
                                                    <div class="tab-pane" id="tabIcon14"
                                                        aria-labelledby="baseIcon-tab4">
                                                        <div class="modal-content ">
                                                            <div class="modal-body">

                                                                <article id='ListEval' style="text-transform: capitalize;"
                                                                    class="wrapper">
                                                                    <header></header>
                                                                    <main style="height: 400px; overflow: auto;"></main>
                                                                </article>

                                                                <article id='DetEval'
                                                                    style="display: none;text-transform: capitalize;"
                                                                    class="wrapper">
                                                                    <header></header>
                                                                    <main style="height: 400px; overflow: auto;"></main>
                                                                </article>

                                                                <article id='DetEvalFin'
                                                                    style="display: none;text-transform: capitalize;"
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
                                                                            <div class="btn-group float-md-right"
                                                                                role="group"
                                                                                aria-label="Button group with nested dropdown">

                                                                                <a class="btn btn-outline-primary"><i
                                                                                        class="ft-clock"> Tiempo para
                                                                                        Terminar</i></a>
                                                                                <a class="btn btn-outline-primary"
                                                                                    style="color: #CE2605;" id="cuenta"></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row" id="Dat_Cal"
                                                                    style="display: none;">
                                                                    <div class="col-md-5" style="text-align: center;">
                                                                        <labe>Intentos Permitidos:</labe><br>
                                                                        <labe id="label_IntPerm" style="color:  #CE2605;">
                                                                        </labe>
                                                                    </div>
                                                                    <div class="col-md-5" style="text-align: center;">
                                                                        <labe>Intentos Realizados:</labe><br>
                                                                        <labe id="label_IntReal"></labe>
                                                                    </div>
                                                                </div>
                                                                <button type="button" id="VidDidac" onclick="$.MostVid();"
                                                                    style="display: none;" class="btn btn-success"><i
                                                                        class="fa fa-video-camera"></i> Ver Video
                                                                </button>
                                                                <button type="button" id="btn_ConversaEval"
                                                                    onclick="$.AbrirConvEval('M');" style="display: none;"
                                                                    class="btn btn-outline-pink"><i
                                                                        class="ft-message-square position-right"></i>
                                                                    Comentarios</button>
                                                                <button type="button" id="btn_salirModEv"
                                                                    class="btn grey btn-outline-secondary"
                                                                    onclick="$.CloseModActIni();" data-dismiss="modal"><i
                                                                        class="ft-corner-up-left position-right"></i>
                                                                    Salir</button>
                                                                <button type="button" id="btn_atrasModEv"
                                                                    style="display: none;"
                                                                    class="btn grey btn-outline-secondary"
                                                                    onclick="$.AtrasModActIni();"><i
                                                                        class="ft-corner-up-left position-right"></i>
                                                                    Atras</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="btn_atrasLabUnid" style="display: none;">
                        <button type="button" onclick="$.mostListLabUnid();" class="btn grey btn-outline-secondary"><i
                                class="ft-corner-up-left position-right"></i>Atras</button>
                    </div>
                    <div class="modal-footer" id="btn_atrasLab" style="display: none;">
                        <button type="button" onclick="$.mostListLab();" class="btn grey btn-outline-secondary"><i
                                class="ft-corner-up-left position-right"></i>Atras</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    </div>

    {!! Form::open(['url' => '/Laboratorios/MostLaboratorios', 'id' => 'formLabo']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Laboratorios/MostDetLaboratorios', 'id' => 'formLaboDet']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Laboratorios/ContenidoEva', 'id' => 'formContenidoEva']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Asignaturas/consulPregAlumno', 'id' => 'formAuxiliarCargEval']) !!}
    {!! Form::close() !!}


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            ///////////////////CONFIGURACION EDITOR

            var flagGlobal = "n";
            var flagTimExt = "n";
            var flagTimFin = "n";
            var flagIntent = "ok"
            var xtiempo;

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

            var TotalTemas = 0;
            var PorcentajeTotal = 0;
            $(".btnVer").on({
                click: function(e) {
                    e.preventDefault();
                }
            });
            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Laboratorios").addClass("active");

            $.extend({
                carg_labo: function(id) {
                    $("#ListLab").hide();
                    $("#ListLabUnid").show();
                    $("#btn_atrasLabUnid").show();
                    var form = $("#formLabo");
                    $("#idUnidad").remove();
                    form.append("<input type='hidden' name='id' id='idLabo' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    var Tip_Usu = $("#Tip_Usu").val();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            TotalTemas = 0;
                            var contenido = "";
                            $("#TitLabo").html("");
                            $("#TitLabo").html(" LABORATORIOS - " + respuesta.TitUnidad
                                .des_unidad);
                            $.each(respuesta.Laboratorios, function(i, item) {

                                contenido +=
                                    "<div class='bs-callout-success callout-square callout-bordered mt-1'>" +
                                    "<div class='media align-items-stretch'>" +
                                    " <div style='cursor:pointer' onclick='$.MostConteLab(" +
                                    item.id +
                                    ");' class='d-flex align-items-center bg-success p-2'>" +
                                    "       <i class='fa fa-flask white font-medium-5'></i>" +
                                    "    </div>" +
                                    "    <div class='media-body p-1'>" +
                                    "    <a  style='cursor:pointer;text-transform: capitalize;' onclick='$.MostConteLab(" +
                                    item.id + ");'> <strong>" + item.titulo +
                                    "</strong></a>"; +
                                "        <h4 style='cursor:pointer' onclick='$.MostConteLab(" +
                                item.id + ");'>" + item.titulo + "</h4>" +
                                    "       <span style='cursor:pointer' onclick='$.MostConteLab(" +
                                    item.id + ");'>" + item.objetivo + "</span>"
                                contenido += "       </div>" +
                                    "    </div>" +
                                    "  </div>";

                            });
                            $("#contenedor").html(contenido);
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                }, //////////CARGAR SEGUN EL TIPO DE CONTENIDO
                mostListLabUnid: function() {
                    $("#ListLab").show();
                    $("#ListLabUnid").hide();
                    $("#btn_atrasLabUnid").hide();
                },
                mostListLab: function() {
                    $("#DetLabUnid").hide();
                    $("#ListLabUnid").show();
                    $("#btn_atrasLabUnid").show();
                    $("#btn_atrasLab").hide();
                },
                mostEvalLab: function() {
                    $("#DetEval").hide();
                    $("#ListEval").show();
                },
                MostConteLab: function(id) {
                    $("#DetLabUnid").show();
                    $("#ListLabUnid").hide();
                    $("#btn_atrasLabUnid").hide();
                    $("#btn_atrasLab").show();

                    var $wrapper = $('#cont_labo');
                    var $wrapperMat = $('#mat_labo');
                    $wrapper.avnSkeleton('display');
                    $wrapperMat.avnSkeleton('display');

                    var form = $("#formLaboDet");
                    $("#idLabo").remove();
                    form.append("<input type='hidden' name='idLabo' id='idLabo' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Tit-Labo").html(respuesta.Laboratorios.titulo);
                            ////CARGAR FUNDAMENTO TEORICO
                            $wrapper.avnSkeleton('remove');
                            $wrapper.find('> header').append("Fundamento Teórico");
                            $wrapper.find('> main').append(respuesta.Laboratorios
                                .fund_teorico);
                            /////CARGAR MATERIALES
                            $wrapperMat.avnSkeleton('remove');
                            $wrapperMat.find('> header').append("Materiales");
                            $wrapperMat.find('> main').append(respuesta.Laboratorios
                                .materiales);
                            /////CARGAR PROCEDIMIENTOS
                            var Procesos = "";
                            var j = 1;
                            $.each(respuesta.ProcLabo, function(i, item) {

                                Procesos = '<div id="proc' + j +
                                    '" style="padding-bottom: 10px;">' +
                                    ' <div class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1">' +
                                    ' <h4 class="primary">Procedimiento ' + j +
                                    '</h4>' +
                                    '<div class="row match-height">' +
                                    '    <div class="col-md-8 col-sm-12">' +
                                    '    <div class="card border-primary text-center bg-transparent" style="height: 314.017px; overflow: auto; padding:5PX;">' +
                                    '      <div class="card-content">' +
                                    item.procedimiento +
                                    '</div>' +
                                    '    </div>' +
                                    '  </div>' +
                                    '<div class="col-md-4 col-sm-12">' +
                                    '   <div class="card border-blue text-center bg-transparent" style="height: 309.333px;">' +
                                    ' <div class="card">' +
                                    '    <div class="card-content">' +
                                    '       <h4 class="card-title">Video Procedimiento.</h4>' +
                                    '       <div id="cont-video' + j + '">' +
                                    '<video id="datruta' + j +
                                    '" height="200" style="width: 100%"  controls >' +
                                    '<source src="" id="sour_video' + j +
                                    '" type="video/mp4">' +
                                    '</video></div>' +
                                    '      </div>' +
                                    '          </div>' +
                                    '            </div>' +
                                    ' </div>' +
                                    ' </div>' +
                                    ' </div>';

                                Procesos +=
                                    '   </div>' +
                                    '</div>';
                                $("#DivProcedimientos").append(Procesos);

                                if (item.vide_proced !== "") {
                                    jQuery('#sour_video' + j).attr('src', $(
                                            '#RutContDid')
                                        .data("ruta") + "/" + item.vide_proced);
                                } else {
                                    $("#cont-video" + j).html(
                                        "<img src='' style='width:250px; height:200px;' id='embed_arch" +
                                        j +
                                        "' alt='Este procedimiento no contiene video.'>"
                                    );
                                    jQuery('#embed_arch' + j).attr('src', $(
                                        '#RutImg').data("ruta"));
                                }


                                j++;

                            });

                            ////CARGAR PRODUCCIÓN
                            var $wrapperEval = $('#ListEval');
                            $wrapperEval.avnSkeleton('display');

                            var ContenidoEval = "";
                            if (respuesta.EvalLabo.length > 0) {
                                $.each(respuesta.EvalLabo, function(i, item) {
                                    $wrapperEval.avnSkeleton('remove');
                                    ContenidoEval +=
                                        "<div class='bs-callout-success callout-square callout-bordered mt-1'>" +
                                        "<div class='media align-items-stretch'>" +
                                        " <div style='cursor:pointer' onclick='$.MostEval(" +
                                        item.id +
                                        ");' class='d-flex align-items-center bg-success p-2'>" +
                                        "       <i class='ft-user-check white font-medium-5'></i>" +
                                        " </div>" +
                                        "    <div class='media-body p-1'>" +
                                        "    <a style='cursor:pointer;text-transform: capitalize;font-weight: bold;' onclick='$.MostEval(" +
                                        item.id + ");'> " + item.titulo
                                        .toLowerCase(); +
                                    "</a>";
                                    ContenidoEval += " </div>" +
                                        "    </div>" +
                                        "  </div>";
                                    j++;
                                });

                                $wrapperEval.find('> header').append('PRODUCCIÓN');

                                $wrapperEval.find('> main').append(ContenidoEval);
                            } else {
                                $wrapperEval.avnSkeleton('remove');
                                $wrapperEval.find('> header').append('PRODUCCIÓN');

                                $wrapperEval.find('> main').append(
                                    '<h1 style="text-align: center;">No Existe Evaluación <small class="text-muted"> para este Laboratorio</small></h1>'
                                    );

                            }

                        }
                    });
                },
                AtrasModActIni: function() {
                    $("#btn_atrasEv").hide();
                    $("#ListEval").show();
                    $("#DetEval").hide();
                    $("#Dat_Cal").hide();
                    $("#VidDidac").hide();
                    $("#contTiempo").hide();
                    $("#DetEvalFin").hide();
                    $("#btn_atrasModEv").hide();
                    clearInterval(xtiempo);
                    xtiempo = null;


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
                CambArchivo: function() {
                    $("#id_file").show();
                    $("#id_verf").hide();
                    $("#CargArchi").val("");
                },
                VerArchResp: function(id) {
                    window.open($('#Respdattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
                },
                hab_ediContComplete: function() {
                    CKEDITOR.replace('summernoteContTema', {
                        width: '100%',
                        height: 200
                    });
                },
                hab_ediContDidac: function() {
                    $("#cont_sumerDidactico").html(
                        '<textarea name="Text_Resp"  id="summernoteDidactico" class="summernote"></textarea>'
                    );
                    $('#summernoteDidactico').summernote({
                        focus: true,
                        height: 100, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
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
                        height: 250
                    });
                },
                MostVid: function() {
                    $("#ModVidelo").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#ModEval').modal('toggle');
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
                                '        <form method="post" action="{{ url('/') }}/Guardar/RespEvaluaciones" id="Evaluacion" class="number-tab-stepsPreg wizard-circle">';
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
                                    xtiempo  = setInterval(function() {

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

                                    selectPreg = '<div class="contenedor' + cons +
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
                RespMulPreg: function(id) {

                    $('.OpcionSel').val("no");

                    if ($('#' + id).prop('checked')) {
                        $('.checksel').prop("checked", "");
                        $('#' + id).prop("checked", "checked");
                        $('#OpcionSel_' + id).val("si");
                    }

                },
                selopc: function(id, cons) {
                    $("#RespSelect" + cons).val($("#" + id).data("id"));
                    $("#ConsPreg" + cons).val(id);

                },
                MostArc: function(id) {
                    window.open($('#dattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
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

                MostConteArchivo: function(id) {
                    $("#btn_eval").hide();
                    $("#cont_archi").show();
                    $("#cont_tema").hide();
                    $("#Dat_Cal").hide();
                    //            $("#large").modal({backdrop: 'static', keyboard: false});
                    var form = $("#formContenidoArch");
                    $("#idTema").remove();
                    form.append("<input type='hidden' name='id_tema' id='idTema' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            window.open($('#RutArcZonL').data("ruta") + "/" + respuesta
                                .DesArch.nom_arch, '_blank');
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

                            $("#titu_tema").html(respuesta.DesaTema.titulo);
                            contenido = respuesta.DesaTema.cont_documento;
                            contenido +=
                                '<br> <div id="ListEval"  style="height: 400px; overflow: auto;text-align: center;">' +
                                '<video width="640" height="360" id="datruta" controls >' +
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
                CloseModAnimaciones: function() {
                    $('#ModAnima').modal('toggle');
                },
                hab_ediContComplete: function() {
                    CKEDITOR.replace('RespPregComplete', {
                        width: '100%',
                        height: 100
                    });
                },
                hab_ediContDidac: function() {
                    $("#cont_sumerDidactico").html(
                        '<textarea name="Text_Resp"  id="summernoteDidactico" class="summernote"></textarea>'
                    );
                    $('#summernoteDidactico').summernote({
                        focus: true,
                        height: 100, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
                    });
                },
                AtrasModAnima: function() {
                    $("#ListAnimaciones").show();
                    $("#DetAnimaciones").hide();
                    $("#DetLinkVideo").hide();
                    $("#btn_salirModAnima").show();
                    $("#btn_atrasModAnima").hide();
                },
                AbrirAnimaciones: function(id) {

                    $("#ModAnima").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formConsuAnim");
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
                                if (respuesta.tip_video === "LINK") {
                                    contenido +=
                                        '<div class="bs-callout-warning callout-square callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        ' <div style="cursor:pointer" class="d-flex align-items-center bg-success p-2">' +
                                        '       <i class="ft-video white font-medium-5"></i>' +
                                        '    </div>' +
                                        '    <div class="media-body p-1">' +
                                        '    <a style="cursor:pointer"  class="text-truncate">' +
                                        '     <a onclick="$.MostVideoLink(this.id)" id="' +
                                        item.id + '"  data-archivo="' + item.url +
                                        '" > <strong>' + item.titulo +
                                        '</strong></a>' +
                                        '       </div>' +
                                        '    </div>' +
                                        '  </div>';
                                } else {
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
                                        '" data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}" > <strong>' +
                                        item.titulo.slice(0, -4) + '</strong></a>' +
                                        '       </div>' +
                                        '    </div>' +
                                        '  </div>';
                                }

                                j++;
                            });
                            $("#ListAnimaciones").html(contenido);
                            $("#titu_temaAnim").html(respuesta.TitTema);
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
                }
            });
        });
    </script>
@endsection
