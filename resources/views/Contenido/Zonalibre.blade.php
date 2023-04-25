@extends('Plantilla.Principal')
@section('title', 'Contenido Zona Libre')
@section('Contenido')
    <input type="hidden" class="form-control" id="Tip_Usu" value="{{ Auth::user()->tipo_usuario }}" />

    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="form-control" id="RutEvalRelDef"
        data-ruta="{{ Session::get('URL') }}/Archivos_EvalRelImgDef" />
    <input type="hidden" class="form-control" id="RutEvalRelOpc"
        data-ruta="{{ Session::get('URL') }}/Archivos_EvalRelImgOpc" />
    <input type="hidden" name="rutaFoto" id="rutaFoto" value="{{ asset('app-assets/images/') }}">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Zona Libre</h3>

        </div>
    </div>

    <section id="basic-callouts">
        <div class="row">
            <div class="col-12">

                @if ($DatDoce == '')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title enter">NO EXISTE NINGUN CONTENIDO EN LA ZONA LIBRE</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis font-medium-3"></i></a>

                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                @else
                    <input type="hidden" class="form-control" id="Usu_pro" value="{{ $DatDoce->usuario_profesor }}" />
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Datos del Docente</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis font-medium-3"></i></a>

                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div
                                        class="col-xl-4 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5 clearfix">
                                        <div class="media">
                                            <div class="media-left pr-1">
                                                <img class="media-object img-xl"
                                                    src="{{ asset('app-assets/images/Img_Docentes/' . $DatDoce->foto) }}"
                                                    alt="Generic placeholder image">
                                            </div>
                                            <div class="media-body">
                                                <h4 style="text-transform: capitalize;" class="text-bold-500 pt-1 mb-0">
                                                    {{ $DatDoce->nombre . ' ' . $DatDoce->apellido }}</h4>
                                                <p>Docente</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-6 col-md-12 text-left clearfix">
                                        <h3 class="pt-1">
                                            <span class="icon-book-open"></span> Contenidos y Comentarios
                                        </h3>
                                        @if (Auth::user()->tipo_usuario == 'Profesor')
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Grados</label>
                                                        <select class="form-control select2" data-placeholder="Seleccione"
                                                            name="grado" id="grado">
                                                            <option value="">Seleccione el Grado</option>
                                                            @for ($i = 1; $i <= 11; $i++)
                                                                <option value="{{ $i }}">{{ 'GRADO ' . $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Grupo</label>
                                                        <select class="form-control select2" data-placeholder="Seleccione"
                                                            name="grupo" id="grupo">
                                                            <option value="">Seleccione el Grupo</option>
                                                            {!! $SelGrupos !!}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Jornada</label>
                                                        <select name="jornada" id="jornada" class="form-control select2">
                                                            <option value="">-- Seleccionar --</option>
                                                            <option value="JM">Jornada Mañana</option>
                                                            <option value="JT">Jornada Tarte</option>
                                                            <option value="JN">Jornada Nocturna</option>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <label>&nbsp;</label>
                                                    <span class="input-group-append">
                                                        <button type="submit" onclick="$.CargarContZonaLibre();"
                                                            class="btn btn-primary "> <i class="fa fa-search"></i></button>
                                                    </span>

                                                </div>
                                            </div>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @if (Auth::user()->tipo_usuario == 'Profesor')
                        <div class="row" id="div-doce" style="display: none;">
                            <div class="col-8">
                                <div id="Cont_ZonaLibre" class="card">

                                </div>
                            </div>
                            <div class="col-4">
                                <div class="project-sidebar-content">
                                    <!-- Project Overview -->
                                    <div class="card">
                                        <div class="card-header pb-0">
                                            <a class="heading-elements-toggle"><i
                                                    class="fa fa-ellipsis-h font-medium-3"></i></a>
                                            <h4 class="text-uppercase">Comentarios.</h4>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body pt-0" id="conte1">
                                                <div class="chats " id="contenidoComent"
                                                    style="height: 350px; overflow-y: scroll;">
                                                    <span id='etiquetafinal'></span>
                                                </div>

                                            </div>
                                            <div class="card-body">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <input type="text" id="Text_Coment" class="form-control"
                                                            placeholder="Escribir Comentario"
                                                            aria-describedby="button-addon4">
                                                        <div class="input-group-append" id="button-addon4">
                                                            <button id="Btn_Enviar" class="btn btn-primary"
                                                                type="button"><i
                                                                    class="fa fa-location-arrow"></i></button>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Project Overview -->

                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->tipo_usuario == 'Estudiante')
                        <div class="row">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-content collapse show">
                                        <div class="card-body">

                                            {{-- CARGAR ANUNCIOS --}}
                                            @php
                                                $j = 1;
                                                $ClaseCom = ['overlay-danger overlay-lighten-2', 'overlay-warning', ' overlay-blue', 'overlay-yellow'];
                                            @endphp
                                            @foreach ($temas as $Tem)
                                                @if ($Tem->tip_contenido == 'ANUNCIO')
                                                    @foreach ($Comentarios as $Coment)
                                                        @php
                                                            $color = $ClaseCom[array_rand($ClaseCom)];
                                                        @endphp
                                                        <div class="row match-height">
                                                            @if ($j == 1)
                                                                <div class="col-12 mt-1 mb-1">
                                                                    <h4 class="text-uppercase">ANUNCIOS DEL DOCENTE.</h4>
                                                                </div>
                                                            @endif
                                                            @if ($Tem->id == $Coment->contenido)
                                                                <div class="col-xl-3 col-lg-6 col-sm-12">
                                                                    <div class="card border-0 box-shadow-0"
                                                                        style="height: 181.617px;">
                                                                        <div class="card-content">
                                                                            <img class="card-img img-fluid"
                                                                                src="{{ asset('app-assets/images/backgrounds/bg_coment.jpg') }}"
                                                                                alt="Card image">
                                                                            <div
                                                                                class="card-img-overlay {{ $color }}">
                                                                                <h4 class="card-title">
                                                                                    {{ $Coment->titulo }}
                                                                                </h4>
                                                                                <p class="card-text">
                                                                                    {!! $Coment->cont_comentario !!}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @php
                                                            $j++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    {{-- CARGAR CONTENIDO TEMATICO --}}

                                    <div class="card-content collapse show">
                                        <div class="card-body">
                                            @php
                                                $j = 1;
                                                $ClasDiv = 'col-xl-12';
                                                if ($TamTem > 1) {
                                                    $ClasDiv = 'col-xl-6';
                                                }
                                            @endphp
                                            <div class="row">
                                                @foreach ($temas as $Tem)
                                                    @if ($Tem->tip_contenido == 'DOCUMENTO')
                                                        @if ($j == 1)
                                                            <div class="col-12 mt-1 mb-1">
                                                                <h4 class="text-uppercase">CONTENIDO TEMATICO.</h4>
                                                            </div>
                                                        @endif

                                                        <div class="{{ $ClasDiv }} col-md-12"
                                                            style="cursor: pointer;"
                                                            onclick='$.MostConteDoc({{ $Tem->id }});'>
                                                            <div
                                                                class="bs-callout-primary callout-transparent callout-bordered">
                                                                <div class="media align-items-stretch">
                                                                    <div
                                                                        class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                                                        <i
                                                                            class="icon-book-open fa-lg white font-medium-5"></i>
                                                                    </div>
                                                                    <div class="media-body p-1">
                                                                        <strong>{{ $Tem->titu_contenido }}</strong>

                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                        @php
                                                            $j++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- CARGAR CONTENIDO ARCHIVOS --}}

                                    <div class="card-content collapse show">
                                        <div class="card-body">
                                            @php
                                                $j = 1;
                                                $ClasDiv = 'col-xl-12';
                                                if ($TamArc > 1) {
                                                    $ClasDiv = 'col-xl-6';
                                                }
                                            @endphp

                                            <div class="row">
                                                @foreach ($temas as $Tem)
                                                    @if ($Tem->tip_contenido == 'ARCHIVO')
                                                        @if ($j == 1)
                                                            <div class="col-12 mt-1 mb-1">
                                                                <h4 class="text-uppercase">ARCHIVOS.</h4>
                                                            </div>
                                                        @endif
                                                        <div class="{{ $ClasDiv }} col-md-12"
                                                            style="cursor: pointer;"
                                                            onclick='$.MostConteArchivo({{ $Tem->id }});'>
                                                            <div
                                                                class="bs-callout-success  callout-transparent callout-bordered">
                                                                <div class="media align-items-stretch">
                                                                    <div
                                                                        class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">
                                                                        <i
                                                                            class="fa fa-paperclip fa-lg white font-medium-5"></i>
                                                                    </div>
                                                                    <div class="media-body p-1">
                                                                        <strong>{{ $Tem->titu_contenido }}</strong>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $j++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- CARGAR CONTENIDO VIDEOS --}}

                                    <div class="card-content collapse show">
                                        <div class="card-body">
                                            @php
                                                $j = 1;
                                                $ClasDiv = 'col-xl-12';
                                                if ($TamVid > 1) {
                                                    $ClasDiv = 'col-xl-6';
                                                }
                                            @endphp
                                            <div class="row">
                                                @foreach ($temas as $Tem)
                                                    @if ($Tem->tip_contenido == 'VIDEOS')
                                                        @if ($j == 1)
                                                            <div class="col-12 mt-1 mb-1">
                                                                <h4 class="text-uppercase">CONTENIDO VIDEOS.</h4>
                                                            </div>
                                                        @endif
                                                        <div class="{{ $ClasDiv }} col-md-12"
                                                            style="cursor: pointer;"
                                                            onclick='$.AbrirAnimaciones({{ $Tem->id }});'>
                                                            <div
                                                                class="bs-callout-info  callout-transparent callout-bordered">
                                                                <div class="media align-items-stretch">
                                                                    <div
                                                                        class="d-flex align-items-center bg-info position-relative callout-arrow-left p-2">
                                                                        <i
                                                                            class="icon-social-youtube fa-lg white font-medium-5"></i>
                                                                    </div>
                                                                    <div class="media-body p-1">
                                                                        <strong>{{ $Tem->titu_contenido }}</strong>

                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                        @php
                                                            $j++;
                                                        @endphp
                                                    @endif
                                                @endforeach


                                            </div>
                                        </div>
                                    </div>


                                    {{-- CARGAR CONTENIDO LINK --}}
                                    <div class="card-content collapse show">
                                        <div class="card-body">

                                            @php
                                                $j = 1;
                                                $ClasDiv = 'col-xl-12';
                                                if ($TamLin > 1) {
                                                    $ClasDiv = 'col-xl-6';
                                                }
                                            @endphp

                                            <div class="row">
                                                @foreach ($temas as $Tem)
                                                    @if ($Tem->tip_contenido == 'LINK')
                                                        @if ($j == 1)
                                                            <div class="col-12 mt-1 mb-1">
                                                                <h4 class="text-uppercase">CONTENIDO LINKS.</h4>
                                                            </div>
                                                        @endif
                                                        <div class="{{ $ClasDiv }} col-md-12"
                                                            style="cursor: pointer;"
                                                            onclick='$.AbrirLink({{ $Tem->id }});'>
                                                            <div
                                                                class="bs-callout-danger  callout-transparent callout-bordered">
                                                                <div class="media align-items-stretch">
                                                                    <div
                                                                        class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">
                                                                        <i
                                                                            class="icon-social-youtube fa-lg white font-medium-5"></i>
                                                                    </div>
                                                                    <div class="media-body p-1">
                                                                        <strong>{{ $Tem->titu_contenido }}</strong>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $j++;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="project-sidebar-content">
                                    <!-- Project Overview -->
                                    <div class="card">
                                        <div class="card-header pb-0">
                                            <a class="heading-elements-toggle"><i
                                                    class="fa fa-ellipsis-h font-medium-3"></i></a>
                                            <h4 class="text-uppercase">Comentarios.</h4>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body pt-0" id="conte1">
                                                <div class="chats " id="contenidoComent"
                                                    style="height: 350px; overflow-y: scroll;">
                                                    <span id='etiquetafinal'></span>
                                                </div>

                                            </div>
                                            <div class="card-body">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <input type="text" id="Text_Coment" class="form-control"
                                                            placeholder="Escribir Comentario"
                                                            aria-describedby="button-addon4">
                                                        <div class="input-group-append" id="button-addon4">
                                                            <button id="Btn_Enviar" class="btn btn-primary"
                                                                type="button"><i
                                                                    class="fa fa-location-arrow"></i></button>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Project Overview -->

                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
        aria-hidden="true">
        <input type="hidden" class="form-control" id="IdEval" value="" />
        <input type="hidden" class="form-control" id="Id_PregEns" value="" />
        <input type="hidden" class="form-control" id="TipEva" value="" />
        <input type="hidden" class="form-control" id="RutContDid"
            data-ruta="{{ asset('/app-assets/Contenido_Didactico') }}" />
        <input type="hidden" class="form-control" id="RutEvalDid"
            data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}" />
        <input type="hidden" class="form-control" id="RutArcZonL"
            data-ruta="{{ asset('/app-assets/Archivos_Contenidos') }}" />
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success white">
                    <h4 class="modal-title" id="titu_tema"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id='cont_tema' style="height: 400px; overflow: auto;">
                    </div>
                    <div id='cont_archi' style="display: none;height: 400px; overflow: auto;">
                        <iframe src='' id="IframArc" width='560px' height='350px' frameborder='0'></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_atras" style="display: none;" onclick="$.mostListArc();"
                        class="btn grey btn-outline-secondary"><i class="ft-corner-up-left position-right"></i>
                        Atras</button>
                    <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                            class="ft-corner-up-left position-right"></i> Salir</button>
                    <button type="button" id="btn_Conversa" onclick="$.AbrirConv('M');" style="display: none;"
                        class="btn btn-outline-primary"><i class="ft-message-square position-right"></i>
                        Comentarios</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade text-left" id="ModAnima" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success white">
                    <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaAnim">Animaciones Cargadas
                        al Tema</h4>
                    <h4 class="modal-title" style="text-transform: capitalize; display: none;" id="titu_Anima">Animacion
                    </h4>

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
                    <div id='DetLinkVideo' style="height: 400px; overflow: auto;display: none;">
                        <iframe id="LinkVideo" width="100%" height="100%" src="" frameborder="0"
                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
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


    </section>


    </div>

    {!! Form::open(['url' => '/cambiar/Contenido', 'id' => 'formContenido']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoDocumentoLibre', 'id' => 'formContenidoDocumento']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoDidactico', 'id' => 'formContenidoDidactico']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoLink', 'id' => 'formContenidoLink']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoArchZona', 'id' => 'formContenidoArch']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoEva', 'id' => 'formContenidoEva']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/ContenidoAct', 'id' => 'formConsuAct']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Consultar/ContenidoAnimZonaLibre', 'id' => 'formConsuAnim']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Consultar/ContenidoLinkZonaLibre', 'id' => 'formConsuLink']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Guardar/ComentarioZona', 'id' => 'formGuarComent']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Guardar/RespEval', 'id' => 'formGuarEval']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Consultar/ComentarioZona', 'id' => 'formCargarComent']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/vistoContenido', 'id' => 'formvistoContenido']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Consultar/ContZonaLibre', 'id' => 'formContZonaLibre']) !!}
    {!! Form::close() !!}



@endsection
@section('scripts')


    <script>
        $(document).ready(function() {
            var TotalTemas = 0;
            var PorcentajeTotal = 0;
            $(".btnVer").on({
                click: function(e) {
                    e.preventDefault();
                }
            });
            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_ZonaVis").addClass("active");
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
                            $.each(respuesta.Temas, function(i, item) {
                                var checked = "";
                                if (item.visto === "SI") {
                                    checked = "checked";
                                } else {
                                    checked = "";
                                }

                                if (item.tip_contenido == "DOCUMENTO") {
                                    contenido +=
                                        "<div class='bs-callout-warning callout-square callout-bordered mt-1'>" +
                                        "<div class='media align-items-stretch'>" +
                                        " <div style='cursor:pointer' onclick='$.MostConteDoc(" +
                                        item.id +
                                        ");' class='d-flex align-items-center bg-warning p-2'>" +
                                        "       <i class='fa fa-file-text-o white font-medium-5'></i>" +
                                        "    </div>" +
                                        "    <div class='media-body p-1'>" +
                                        "    <a  style='cursor:pointer;text-transform: capitalize;' onclick='$.MostConteDoc(" +
                                        item.id + ");'> <strong>" + item
                                        .titu_contenido + "</strong></a>";
                                    if (Tip_Usu == "Profesor") {
                                        contenido += " <fieldset>" +
                                            " <div class='float-left'>" +
                                            "<input type='checkbox'  valor='" + item
                                            .visto + "' valorid='" + item.id +
                                            "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                            checked + "/>" +
                                            " </div>" +
                                            " </fieldset>";
                                    }

                                    contenido += "       </div>" +
                                        "    </div>" +
                                        "  </div>";
                                } else if (item.tip_contenido == "ARCHIVO") {
                                    contenido +=
                                        "<div class='bs-callout-warning callout-square callout-bordered mt-1'>" +
                                        "<div class='media align-items-stretch'>" +
                                        " <div style='cursor:pointer;text-transform: capitalize;' onclick='$.MostConteArch(" +
                                        item.id +
                                        ");' class='d-flex align-items-center bg-primary p-2'>" +
                                        "       <i class='fa fa-paperclip white font-medium-5'></i>" +
                                        "    </div>" +
                                        "    <div class='media-body p-1'>" +
                                        "       <a style='cursor:pointer' onclick='$.MostConteArch(" +
                                        item.id + ");'> <strong>" + item
                                        .titu_contenido + "</strong></a>";
                                    if (Tip_Usu == "Profesor") {
                                        contenido += " <fieldset>" +
                                            " <div class='float-left'>" +
                                            "<input type='checkbox'  valor='" + item
                                            .visto + "' valorid='" + item.id +
                                            "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                            checked + "/>" +
                                            " </div>" +
                                            " </fieldset>";
                                    }
                                    contenido += "       </div>" +
                                        "    </div>" +
                                        "      </div>";
                                } else if (item.tip_contenido == "LINK") {
                                    contenido +=
                                        "<div class='bs-callout-warning callout-square callout-bordered mt-1'>" +
                                        "<div class='media align-items-stretch'>" +
                                        " <div style='cursor:pointer;text-transform: capitalize;' onclick='$.MostConteLink(" +
                                        item.id +
                                        ");' class='d-flex align-items-center bg-info p-2'>" +
                                        "       <i class='ft-link white font-medium-5'></i>" +
                                        "    </div>" +
                                        "    <div class='media-body p-1'>" +
                                        "       <a style='cursor:pointer' onclick='$.MostConteLink(" +
                                        item.id + ");'> <strong>" + item
                                        .titu_contenido + "</strong></a>"
                                    if (Tip_Usu == "Profesor") {
                                        contenido += " <fieldset>" +
                                            " <div class='float-left'>" +
                                            "<input type='checkbox'  valor='" + item
                                            .visto + "' valorid='" + item.id +
                                            "' class='switch cambiarPorcentaje' id='switch8'  data-group-cls='btn-group-sm' data-off-title='Tema No Visto' data-on-title='Tema Visto' data-reverse " +
                                            checked + "/>" +
                                            " </div>" +
                                            " </fieldset>";
                                    }
                                    contenido += "       </div>" +
                                        "    </div>" +
                                        "      </div>";
                                }
                                TotalTemas++;
                            });
                            $("#contenedor").html(contenido);
                            $('.switch:checkbox').checkboxpicker({
                                html: true,
                                offLabel: 'NO',
                                onLabel: 'SI'
                            });
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
                MostConteDoc: function(id) {
                    $("#btn_eval").hide();
                    $("#cont_tema").show();
                    $("#cont_archi").hide();
                    $("#cont_tema").html("");
                    $("#Dat_Cal").hide();
                    $("#large").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formContenidoDocumento");
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
                            $("#titu_tema").html(respuesta.DesaTema.titulo);
                            $("#cont_tema").html(respuesta.DesaTema.cont_documento);
                        }
                    });
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
                MostConteLink: function(id) {
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
                MostArc: function(id) {
                    window.open($('#' + id).data("ruta") + "/" + $('#' + id).data("archivo"), '_blank');
                },
                mostmodArch: function(id) {

                    $("#cont_tema").hide();
                    $("#cont_archi").show();
                    $("#btn_atras").show();
                    $("#btn_salir").hide();
                    $("#div_arc").html(
                        '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                    );
                    jQuery('#embed_arch').attr('src', $('#' + id).data("ruta") + "/" + $('#' + id).data(
                        "archivo"));
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
                CloseModAnimaciones: function() {
                    $('#ModAnima').modal('toggle');
                },
                hab_ediContComplete: function() {

                    $("#cont_sumerComplete").html(
                        '<textarea name="summernoteContTema"  id="summernoteContTema" class="summernote"></textarea>'
                    );
                    $('#summernoteContTema').summernote({
                        focus: true,
                        height: 250, //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'

                        }
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
                AbrirLink: function(id) {

                    $("#ModAnima").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formConsuLink");
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
                            $.each(respuesta.DesLink, function(i, item) {
                                contenido +=
                                    '<div class="bs-callout-warning callout-square callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    ' <div style="cursor:pointer" class="d-flex align-items-center bg-success p-2">' +
                                    '       <i class="ft-link white font-medium-5"></i>' +
                                    '    </div>' +
                                    '    <div class="media-body p-1">' +
                                    '    <a style="cursor:pointer"  class="text-truncate">' +
                                    '     <a onclick="$.MostLink(this.id)" id="' +
                                    item.id + '"  data-archivo="' + item.url +
                                    '" > <strong>' + item.titulo + '</strong></a>' +
                                    '       </div>' +
                                    '    </div>' +
                                    '  </div>';
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
                },
                MostVideoLink: function(id) {

                    $("#DetLinkVideo").show();
                    $("#ListAnimaciones").hide();
                    $("#btn_atrasModAnima").show();
                    $("#btn_salirModAnima").hide();
                    var nomarchi = $('#' + id).data("archivo");
                    nomarchi = nomarchi.replace("watch?v", "embed/");
                    $('#LinkVideo').attr('src', nomarchi);
                },
                MostLink: function(id) {

                    $("#DetLinkVideo").show();
                    $("#ListAnimaciones").hide();
                    $("#btn_atrasModAnima").show();
                    $("#btn_salirModAnima").hide();
                    var nomarchi = $('#' + id).data("archivo");
                    $('#LinkVideo').attr('src', nomarchi);
                },
                CargarContZonaLibre: function() {

                    var form = $("#formContZonaLibre");
                    var grado = $("#grado").val();
                    var grupo = $("#grupo").val();
                    $("#div-doce").show();
                    var jornada = $("#jornada").val();
                    $("#Cont_ZonaLibre").html("");
                    $("#idgrado").remove();
                    $("#idgrupo").remove();
                    $("#idjorna").remove();
                    form.append("<input type='hidden' name='idgrado' id='idgrado' value='" + grado +
                        "'>");
                    form.append("<input type='hidden' name='idgrupo' id='idgrupo' value='" + grupo +
                        "'>");
                    form.append("<input type='hidden' name='idjorna' id='idjorna' value='" + jornada +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {

                            $("#Cont_ZonaLibre").append(respuesta.anuncios);
                            $("#Cont_ZonaLibre").append(respuesta.contenidos);
                            $("#div_coment").show();
                        }
                    });

                }
            });
            $("#Btn_Enviar").on({
                click: function(e) {
                    e.preventDefault();
                    enviar();
                }
            });
            $("#Text_Coment").on({
                keypress: function(e) {
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if (code === 13) {
                        enviar();
                        return false;
                    }
                }
            });

            function enviar() {

                if ($("#Text_Coment").val() === "") {
                    return;
                }

                var mensaje = $("#Text_Coment").val();
                var auxi = $("#contenidoComent").html();
                var form = $("#formGuarComent");
                var url = form.attr("action");
                var Usu_pro = $("#Usu_pro").val();
                var rutaFoto = $("#rutaFoto").val();
                var token = $("#token").val();
                $("#_token").remove();
                form.append("<input type='hidden' name='_token' id='_token' value='" + token + "'>");
                if ($("#Tip_Usu").val() === "Profesor") {
                    var grado = $("#grado").val();
                    var grupo = $("#grupo").val();
                    var jornada = $("#jornada").val();
                    $("#ComGrado").remove();
                    $("#ComGrupo").remove();
                    $("#ComJorn").remove();
                    $("#usuprof").remove();

                    form.append("<input type='hidden' name='ComGrado' id='ComGrado' value='" + grado + "'>");
                    form.append("<input type='hidden' name='ComGrupo' id='ComGrupo' value='" + grupo + "'>");
                    form.append("<input type='hidden' name='ComJorn' id='ComJorn' value='" + jornada + "'>");
                }

                form.append("<input type='hidden' name='Mensaje' id='Mensaje' value='" + mensaje + "'>");
                form.append("<input type='hidden' name='usuprof' id='usuprof' value='" + Usu_pro + "'>");
                var datos = form.serialize();
                $("#Text_Coment").val("");
                var Coment =
                    '<li class="scrollable-container media-list ps-container ps-theme-dark ps-active-y" data-ps-id="c8763af3-e472-5207-3d53-ee024a1b74f3">';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    success: function(respuesta) {
                        $.each(respuesta.RespComen, function(i, item) {

                            Coment += '  <a href="javascript:void(0)">';

                            if (item.tipo_usuario === "Profesor") {
                                Coment += ' <div class="media">' +
                                    '     <div class="media-left">' +
                                    '         <span class="avatar avatar-sm avatar-online rounded-circle">' +
                                    '             <img src="' + rutaFoto + '/Img_Docentes/' +
                                    item.foto + '" alt="avatar"><i></i></span>' +
                                    '    </div>' +
                                    '    <div class="media-body">' +
                                    ' <h6 class="media-heading" style=" text-transform: capitalize;line-height:0.45  font-weight: bold;color: #404E67">' +
                                    item.nombre_usuario + ' (' + item.tipo_usuario.replace(
                                        "Profesor", "Docente") + ')</h6>';
                            } else {
                                Coment += ' <div class="media">' +
                                    '     <div class="media-left">' +
                                    '         <span class="avatar avatar-sm avatar-online rounded-circle">' +
                                    '             <img src="' + rutaFoto + '/Img_Estudiantes/' +
                                    item
                                    .foto + '" alt="avatar"><i></i></span>' +
                                    '    </div>' +
                                    '    <div class="media-body">' +
                                    ' <h6 class="media-heading" style=" text-transform: capitalize;line-height:0.45  font-weight: bold;color: #404E67">' +
                                    item.nombre_usuario + '</h6>';
                            }

                            Coment += '         <p>' + item.comentario + ' </p>' +
                                '       </div>' +
                                '    </div>' +
                                '</a>';
                        });
                        Coment +=
                            '<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 255px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 188px;"></div></div></li>';
                        $("#contenidoComent").append(Coment);
                        $("#etiquetafinal").remove();
                        $("#contenidoComent").append("<span id='etiquetafinal'></span>");
                        document.getElementById('etiquetafinal').scrollIntoView(true);
                        $("#conte1").animate({
                            scrollTop: $(this).prop("scrollHeight")
                        }, 1000);
                        $("#Text_Coment").focus()
                    },
                    error: function() {
                        Swal.fire("Ocurrio un error!", "", "error");
                    }
                });
            }

            function cargar() {
                var auxi = $("#contenidoChat").html();
                var form = $("#formCargarComent");
                var url = form.attr("action");
                var grado = $("#grado").val();
                var grupo = $("#grupo").val();
                var jornada = $("#jornada").val();
                var rutaFoto = $("#rutaFoto").val();
                var token = $("#token").val();
                $("#_token").remove();
                form.append("<input type='hidden' name='_token' id='_token' value='" + token + "'>");
                $("#ComGrado").remove();
                $("#ComGrupo").remove();
                $("#ComJorn").remove();
                form.append("<input type='hidden' name='ComGrado' id='ComGrado' value='" + grado + "'>");
                form.append("<input type='hidden' name='ComGrupo' id='ComGrupo' value='" + grupo + "'>");
                form.append("<input type='hidden' name='ComJorn' id='ComJorn' value='" + jornada + "'>");
                var datos = form.serialize();
                $("#idAuxiliar").remove();
                var Coment =
                    '<li class="scrollable-container media-list ps-container ps-theme-dark ps-active-y" data-ps-id="c8763af3-e472-5207-3d53-ee024a1b74f3">';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    success: function(respuesta) {
                        $.each(respuesta.RespComen, function(i, item) {

                            Coment += '  <a href="javascript:void(0)">';

                            if (item.tipo_usuario === "Profesor") {
                                Coment += ' <div class="media">' +
                                    '     <div class="media-left">' +
                                    '         <span class="avatar avatar-sm avatar-online rounded-circle">' +
                                    '             <img src="' + rutaFoto + '/Img_Docentes/' +
                                    item.foto + '" alt="avatar"><i></i></span>' +
                                    '    </div>' +
                                    '    <div class="media-body">' +
                                    ' <h6 class="media-heading" style=" text-transform: capitalize;line-height:0.45  font-weight: bold;color: #404E67">' +
                                    item.nombre_usuario + ' (' + item.tipo_usuario.replace(
                                        "Profesor", "Docente") + ')</h6>';
                            } else {
                                Coment += ' <div class="media">' +
                                    '     <div class="media-left">' +
                                    '         <span class="avatar avatar-sm avatar-online rounded-circle">' +
                                    '             <img src="' + rutaFoto + '/Img_Estudiantes/' +
                                    item
                                    .foto + '" alt="avatar"><i></i></span>' +
                                    '    </div>' +
                                    '    <div class="media-body">' +
                                    ' <h6 class="media-heading" style=" text-transform: capitalize;line-height:0.45  font-weight: bold;color: #404E67">' +
                                    item.nombre_usuario + '</h6>';
                            }

                            Coment += '         <p>' + item.comentario + ' </p>' +
                                '       </div>' +
                                '    </div>' +
                                '</a>';
                        });
                        Coment +=
                            '<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 255px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 188px;"></div></div></li>';
                        $("#contenidoComent").append(Coment);
                        $("#contenidoComent").append(Coment).html("").append(Coment);
                        $("#etiquetafinal").remove();
                        $("#contenidoComent").append("<span id='etiquetafinal'></span>");
                    },
                    error: function() {
                        Swal.fire("Ocurrio un error!", "", "error");
                    }
                });
            }


            setInterval(cargar, 6000);
        });
    </script>
@endsection
