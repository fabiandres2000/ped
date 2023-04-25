@extends('Plantilla.Principal')
@section('title', 'Libro de Calificaciones')
@section('Contenido')

    <input type="hidden" data-id='id-dat' id="dattaller" data-ruta="{{ asset('/app-assets/Archivos_EvaluacionTaller') }}" />
    <input type="hidden" data-id='id-dat' id="Respdattaller" data-ruta="{{ asset('/app-assets/Archivos_EvalTaller_Resp') }}" />

    <div class="content-header row">
        <input type='hidden' name='idAlumno' id='idAlumno' value='{{ Auth::user()->id }}'>
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Libro de Calificaciones
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
                            <h4 class="card-title">Libro de Calificaciones</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body" id="Div_Search">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (Session::has('error'))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-icon-right alert-warning alert-dismissible mb-2"
                                                        role="alert">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                        <strong>Alerta!</strong> {!! session('error') !!}

                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                        @if (Session::has('success'))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-icon-right alert-info alert-dismissible mb-2"
                                                        role="alert">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                        <strong>{!! session('success') !!}</strong>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row" id="Div_ListEval">
                                    <div class="col-12" style="padding-bottom: 10px;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label" for="unidad">Periodo:</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    onchange='$.CargUnidades(this.value)' name="Periodos" id="Periodos">
                                                    {!! $Select_Peri !!}
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="unidad">Unidad:</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    onchange='$.CargTemas(this.value)' name="Unidad" id="Unidad">
                                                    {!! $Select_Unid !!}
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="unidad">Tema:</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    onchange="$.BuscEval('HTML');" name="Temas" id="Temas">
                                                    {!! $Select_Tem !!}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive"> <input type='hidden' id='Id_Eval' name='Id_Eval'
                                                value='' />
                                            <table id="recent-orders"
                                                class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Evaluación</th>
                                                        <th>Tema </th>
                                                        <th>Estado </th>
                                                        <th>Calificación</th>
                                                        <th>Revisar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="Tb_Calif">
                                                    @php
                                                        $i = 1;
                                                    @endphp

                                                    @foreach ($Alumno as $Alum)
                                                        <tr>
                                                            <td class="text-truncate">{!! $i !!}</td>
                                                            <td class="text-truncate" style="text-transform: capitalize;">
                                                                {!! mb_strtolower($Alum->titulo) !!}</td>
                                                            <td class="text-truncate" style="text-transform: capitalize;">
                                                                {!! mb_strtolower($Alum->titu_contenido) !!}</td>

                                                            @if ($Alum->calf_prof === 'no')
                                                                <td class="text-truncate">Pendiente</td>
                                                            @else
                                                                <td class="text-truncate">Presentada</td>
                                                            @endif

                                                            @php
                                                                if ($Alum->puntuacion !== null) {
                                                                    $puntMax = $Alum->punt_max;
                                                                    $Punt = $Alum->puntuacion;
                                                                    $porc = ((int) $Punt / $puntMax) * 100;
                                                                    $clase = 'btn bg-info btn-round mr-1 mb-1';
                                                                    switch ($porc) {
                                                                        case $porc <= 50:
                                                                            $clase = 'btn btn-danger btn-round mr-1 mb-1';
                                                                            break;
                                                                        case $porc > 50 && $porc <= 60:
                                                                            $clase = 'btn bg-warning  btn-round mr-1 mb-1';
                                                                            break;
                                                                        case $porc > 60 && $porc <= 70:
                                                                            $clase = 'btn bg-yellow  btn-round mr-1 mb-1';
                                                                            break;
                                                                        case $porc > 70 && $porc <= 80:
                                                                            $clase = 'btn btn-primary btn-round mr-1 mb-1';
                                                                            break;
                                                                        case $porc > 80 && $porc <= 100:
                                                                            $clase = 'btn bg-success btn-round mr-1 mb-1';
                                                                            break;
                                                                    }
                                                                    if ($Alum->calf_prof == 'si') {
                                                                        $Calf = $Alum->calificacion;
                                                                    } else {
                                                                        $Calf = 'Por Calificar';
                                                                    }
                                                                } else {
                                                                    $Calf = '0/' . $Alum->punt_max;
                                                                    $clase = 'btn bg-info btn-round mr-1 mb-1';
                                                                }
                                                                $i++;
                                                            @endphp
                                                            <td class="text-truncate" style="vertical-align: middle;">
                                                                <button type="button"
                                                                    class="{!! $clase !!}">{!! $Calf !!}</button>
                                                            </td>
                                                            <td class="text-truncate">
                                                                <button data-eval='{{ $Alum->evaluacion }}'
                                                                    data-prof='{{ $Alum->calf_prof }}'
                                                                    id='{{ $Alum->id }}'
                                                                    onclick="$.detalleEvaluacion(this.id);" type="button"
                                                                    class="btn bg-info btn-round mr-1 mb-1">
                                                                    <li class="fa fa-search"></li>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="px-1"></p>
                                    </div>
                                </div>

                                <div class="modal fade text-left" id="modDetEval" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel15" aria-hidden="true">
                                    <div class="modal-dialog  modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title" style="text-transform: capitalize;"
                                                    id="titu_temaAnim">
                                                    Retroalimentación de la Evaluación
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div id='DetaEval' style="height: 400px; overflow: auto;">
                                                    <div class="card-content collapse show">
                                                        <div class="card-body" id="RespPreg">
                                                            {{--  <p>Use <code>.callout-arrow</code> for callout with right arrow.</p>  --}}
                                                        </div>
                                                        <div class="card-body" style="display: none;"
                                                            id="DetRespPregunta">

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="btn_atras" data-dismiss="modal"
                                                    class="btn grey btn-outline-secondary"><i
                                                        class="ft-corner-up-left position-right"></i>
                                                    Atras</button>
                                                <button type="button" id="btn_atras2" style="display: none;"
                                                    onClick="$.mosRetro();" class="btn grey btn-outline-secondary"><i
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
        </section>
    </div>

    {!! Form::open(['url' => '/Calificaciones/ListCalifEva', 'id' => 'formCalifEva']) !!}
    {!! Form::close() !!}


    {!! Form::open(['url' => '/Calificaciones/ListCalifEva2', 'id' => 'formCalifEva2']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Calificaciones/VerRespAlumno', 'id' => 'formAuxiliarCargEval']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Calificaciones/ConsulRetroalimentacion', 'id' => 'formCalifRetro']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Unidad', 'id' => 'formAuxiliarUnid']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/Temas', 'id' => 'formAuxiliarTema']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Calificaciones").addClass("open");

            $.extend({
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
                            $("#Unidad").html(respuesta.select_Unidades);
                        }

                    });
                    $.BuscEval("HTML");
                },
                mosRetro: function() {
                    $("#DetRespPregunta").hide();
                    $("#RespPreg").show();
                    $("#btn_atras2").hide();
                    $("#btn_atras").show();
                },
                CargTemas: function(id) {

                    var form = $("#formAuxiliarTema");
                    $("#idUni").remove();
                    form.append("<input type='hidden' name='idUni' id='idUni' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Temas").html(respuesta.Select_Temas);
                        }

                    });
                    $.BuscEval("HTML");
                },

                detalleEvaluacion: function(ideval) {

                    let ide = $('#' + ideval).data("eval");
                    let calf = $('#' + ideval).data("prof");

                    if (calf === "si") {
                        $("#modDetEval").modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        var form = $("#formCalifRetro");

                        $("#idEvalRetro").remove();

                        form.append("<input type='hidden' name='idEvalRetro' id='idEvalRetro' value='" +
                            ide + "'>");
                        var url = form.attr("action");
                        var datos = form.serialize();
                        var Respreg = "";
                        var consPreg = 1;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: datos,
                            async: false,
                            dataType: "json",
                            success: function(respuesta) {
                                $.each(respuesta.Retro, function(i, item) {

                                    let retro = item.retro === null ?
                                        "No se realizo ninguna Retroalimentació" :
                                        "Se realizo una Retroalimentación";


                                    if (item.promPunt >= 60) {
                                        Respreg +=
                                            '  <div class="bs-callout-success callout-transparent callout-bordered mt-1" >' +
                                            '<div class="media align-items-stretch">' +
                                            '<div class="media-body p-1">' +
                                            '<strong>Pregunta ' + consPreg +
                                            '</strong>' +
                                            '     <ul class="list-inline mb-1">' +
                                            '  <li class="pr-1">' +
                                            '  <a href="#"  class="">' +
                                            '  <span class="fa fa-thumbs-o-up"></span> Puntos: ' +
                                            item.puntos + ' Pts.</a>' +
                                            ' </li>' +
                                            '  <li class="pr-1">' +
                                            '  <a href="#"  onclick="$.VerRespPreg(' +
                                            item.pregunta + ');" class="">' +
                                            '  <span class="fa fa-eye"></span> Ver Respuesta</a>' +
                                            ' </li>' +
                                            '</ul>' +
                                            '    <h6 class="form-section"><i class="fa fa-undo"></i> ' +
                                            retro + '</h6>' +
                                            ' </div>' +
                                            ' <div class="d-flex align-items-center bg-success  position-relative callout-arrow-right p-2">' +
                                            '   <i class="fa fa-check-circle white font-medium-5"></i>' +
                                            '  </div>' +
                                            ' </div>' +
                                            '    </div>';
                                    } else {
                                        Respreg +=
                                            '  <div  style="cursor: pointer;" class="bs-callout-warning  callout-transparent callout-bordered mt-1"";>' +
                                            '<div class="media align-items-stretch">' +
                                            '<div class="media-body p-1">' +
                                            '<strong>Pregunta ' + consPreg +
                                            '</strong>' +
                                            '     <ul class="list-inline mb-1">' +
                                            '  <li class="pr-1">' +
                                            '  <a href="#" class="">' +
                                            '  <span class="fa fa-thumbs-o-down"></span> Puntos: ' +
                                            item.puntos + ' Pts.</a>' +
                                            ' </li>' +
                                            '  <li class="pr-1">' +
                                            '  <a href="#"  onclick="$.VerRespPreg(' +
                                            item.pregunta + ');" class="">' +
                                            '  <span class="fa fa-eye"></span> Ver Respuesta</a>' +
                                            ' </li>' +
                                            '</ul>' +
                                            '    <h6 class="form-section"><i class="fa fa-undo"></i> ' +
                                            retro + '</h6>' +
                                            ' </div>' +
                                            ' <div class="d-flex align-items-center bg-warning  position-relative callout-arrow-right p-2">' +
                                            '   <i class="fa fa-times-circle white font-medium-5"></i>' +
                                            '  </div>' +
                                            ' </div>' +
                                            '    </div>';

                                    }

                                    consPreg++;

                                });

                                $("#RespPreg").html(Respreg);


                            }

                        });
                    } else {
                        Swal.fire({
                            title: "Libro de Calificaciones",
                            text: "Esta Evaluación no ha sido Calificada.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }



                },
                VerRespPreg: function(idpreg) {
                    var form = $("#formAuxiliarCargEval");
                    var eval = $("#idEvalRetro").val();
                    var Pregunta = "";
                    $("#PreguntaResp").remove();
                    $("#idEvaVerResp").remove();
                    form.append("<input type='hidden' name='PreguntaResp' id='PreguntaResp' value='" +
                        idpreg + "'>");
                    form.append(
                        "<input type='hidden' name='idEvaVerResp' id='idEvaVerResp' value='" +
                        eval +
                        "'>");

                    var url = form.attr("action");
                    var datos = form.serialize();

                    $("#RespPreg").hide();
                    $("#DetRespPregunta").show();
                    $("#btn_atras").hide();
                    $("#btn_atras2").show();

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: true,
                        dataType: "json",
                        success: function(respuesta) {

                            if (respuesta.tipo === "PREGENSAY") {

                                Pregunta +=
                                    '<div class="bs-callout-primary callout-transparent callout-bordered">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-question fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Pregunta</strong>' +
                                    '<div >' + respuesta.PregEnsayo.pregunta + '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                Pregunta +=
                                    '<div class="bs-callout-success callout-transparent callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-check fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Respuesta</strong>' +
                                    '<div >' + respuesta.RespPregEnsayo.respuesta +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                if (respuesta.retro !== null) {
                                    Pregunta +=
                                        '<div class="bs-callout-danger callout-transparent callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">' +
                                        '<i class="fa fa-repeat fa-lg white font-medium-5"></i>' +
                                        '</div>' +
                                        '<div class="media-body p-1">' +
                                        '<strong>Retroalimentación</strong>' +
                                        '<div >' + respuesta.retro + '</div>' +
                                        ' </div>' +
                                        '  </div>' +
                                        ' </div>';
                                }

                                $("#DetRespPregunta").html(Pregunta);

                            } else if (respuesta.tipo === "COMPLETE") {

                                Pregunta +=
                                    '<div class="bs-callout-primary callout-transparent callout-bordered">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-question fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Complete el Parrafo con las Siguientes Opciones</strong>' +
                                    '<div >' + respuesta.PregComple.opciones + '</div>' +
                                    '<div >' + respuesta.PregComple.parrafo + '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                Pregunta +=
                                    '<div class="bs-callout-success callout-transparent callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-pencil-square-o fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Respuesta</strong>' +
                                    '<div >' + respuesta.RespPregComple.respuesta +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                if (respuesta.retro !== null) {
                                    Pregunta +=
                                        '<div class="bs-callout-danger callout-transparent callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">' +
                                        '<i class="fa fa-repeat fa-lg white font-medium-5"></i>' +
                                        '</div>' +
                                        '<div class="media-body p-1">' +
                                        '<strong>Retroalimentación</strong>' +
                                        '<div >' + respuesta.retro + '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }

                                $("#DetRespPregunta").html(Pregunta);
                            } else if (respuesta.tipo === "OPCMULT") {
                                Pregunta +=
                                    '<div class="bs-callout-primary callout-transparent callout-bordered">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-question fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Seleccione una Opción Segun la siguiente Pregunta</strong>' +
                                    '<div >' + respuesta.PregMult.pregunta + '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                Pregunta +=
                                    '<div class="bs-callout-success callout-transparent callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-pencil-square-o fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Opciones</strong>';


                                let cheked = "";
                                $.each(respuesta.OpciMult, function(i, item) {
                                    cheked = "";
                                    console.log(item.opciones);
                                    if (respuesta.RespPregMul.respuesta == item
                                        .id) {
                                        cheked = "checked";
                                    }

                                    Pregunta +=
                                        '<fieldset class="checkbox disabled">' +

                                        ' <input type="checkbox" value="" disabled="" ' +
                                        cheked + ' > ';
                                    Pregunta +=
                                        ' <label for="input-15"> ' +
                                        item
                                        .opciones +
                                        '</label>' +
                                        '</fieldset>';
                                });

                                Pregunta += '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                if (respuesta.retro !== null) {
                                    Pregunta +=
                                        '<div class="bs-callout-danger callout-transparent callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">' +
                                        '<i class="fa fa-repeat fa-lg white font-medium-5"></i>' +
                                        '</div>' +
                                        '<div class="media-body p-1">' +
                                        '<strong>Retroalimentación</strong>' +
                                        '<div >' + respuesta.retro + '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }

                                $("#DetRespPregunta").html(Pregunta);
                            } else if (respuesta.tipo === "VERFAL") {
                                Pregunta +=
                                    '<div class="bs-callout-primary callout-transparent callout-bordered">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-question fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Indique Verdadero o Falso segun la Afirmación</strong>' +
                                    '<div >' + respuesta.PregVerFal.pregunta + '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                Pregunta +=
                                    '<div class="bs-callout-success callout-transparent callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-pencil-square-o fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Respuesta</strong>' +
                                    '<div class="form-group row">' +
                                    '<div class="col-md-12">' +
                                    '    <fieldset >' +
                                    '        <div class="input-group">';

                                Pregunta +=
                                    '<input name="radpregVerFal[]" id="RadVer" disabled value="si"  type="radio">';

                                Pregunta +=
                                    ' <div class="input-group-append" style="margin-left:5px;">' +
                                    '            <span  id="basic-addon2">Verdadero</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>' +
                                    '</div>' +
                                    '<div  class="col-md-12">' +
                                    '    <fieldset >' +
                                    '        <div class="input-group">';
                                Pregunta +=
                                    ' <input name="radpregVerFal[]" id="RadFal" disabled value="no"  type="radio">';
                                Pregunta +=
                                    '<div class="input-group-append" style="margin-left:5px;">' +
                                    '            <span  id="basic-addon2">Falso</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '      </fieldset>' +
                                    '</div>' +
                                    '            </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                if (respuesta.retro !== null) {
                                    Pregunta +=
                                        '<div class="bs-callout-danger callout-transparent callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">' +
                                        '<i class="fa fa-repeat fa-lg white font-medium-5"></i>' +
                                        '</div>' +
                                        '<div class="media-body p-1">' +
                                        '<strong>Retroalimentación</strong>' +
                                        '<div >' + respuesta.retro + '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }
                                $("#DetRespPregunta").html(Pregunta);

                                if (respuesta.RespPregVerFal) {
                                    if (respuesta.RespPregVerFal.respuesta_alumno ===
                                        "si") {
                                        $('#RadVer').prop("checked", "checked");
                                    } else {
                                        $('#RadFal').prop("checked", "checked");
                                    }
                                }

                            } else if (respuesta.tipo === "RELACIONE") {

                                Pregunta +=
                                    '<div class="bs-callout-primary callout-transparent callout-bordered">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-question fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>' + respuesta.PregRelacione.enunciado +
                                    '</strong>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                Pregunta +=
                                    '<div class="bs-callout-success callout-transparent callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-pencil-square-o fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Respuesta</strong>' +
                                    '<div class="row">';
                                let j = 1;
                                $.each(respuesta.PregRelIndi, function(k, item) {
                                    Pregunta +=
                                        '<div class="col-md-5 pb-2" style="display: flex;align-items: center;justify-content: center;"> <div  id="DivInd' +
                                        j + '">' + item.definicion + '</div></div>';

                                    Pregunta +=
                                        '<div class="col-md-2 pb-2" style="display: flex;align-items: center;justify-content: center;"><li class="fa fa-long-arrow-right"></li></div>';

                                    Pregunta +=
                                        '<div class="col-md-5 pb-2" style="display: flex;align-items: center;justify-content: center;"> <div  id="DivDefi' +
                                        j + '"></div></div>';
                                    j++;
                                });

                                Pregunta += '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                if (respuesta.retro !== null) {
                                    Pregunta +=
                                        '<div class="bs-callout-danger callout-transparent callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">' +
                                        '<i class="fa fa-repeat fa-lg white font-medium-5"></i>' +
                                        '</div>' +
                                        '<div class="media-body p-1">' +
                                        '<strong>Retroalimentación</strong>' +
                                        '<div >' + respuesta.retro + '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }

                                $("#DetRespPregunta").html(Pregunta);
                                j = 1;
                                $.each(respuesta.RespPregRelacione, function(k, item) {
                                    $("#DivDefi" + j).html(item.respuesta);
                                    j++;
                                });
                            } else if (respuesta.tipo === "TALLER") {
                                let id = "1";

                                Pregunta +=
                                    '<div class="bs-callout-primary callout-transparent callout-bordered">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-question fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Desarrolle el siguiente Taller: </strong>' +
                                    ' <div class="btn-group" role="group" aria-label="Basic example">' +
                                    '   <button id="idimg' + id +
                                    '" type="button" data-archivo="' + respuesta.PregTaller
                                    .nom_archivo +
                                    '" onclick="$.MostArc(this.id);" class="btn btn-success"><i' +
                                    '             class="fa fa-download"></i> Descargar Archivo</button>' +
                                    '      </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                Pregunta +=
                                    '<div class="bs-callout-success callout-transparent callout-bordered mt-1">' +
                                    '<div class="media align-items-stretch">' +
                                    '<div class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">' +
                                    '<i class="fa fa-pencil-square-o fa-lg white font-medium-5"></i>' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<strong>Respuesta: </strong>' +
                                    '<div class="btn-group" role="group" aria-label="Basic example">' +
                                    ' <button type="button" id="archi" onclick="$.VerArchResp(this.id);" data-archivo="' +
                                    respuesta.RespPregTaller.archivo +
                                    '" class="btn btn-success"><i' +
                                    '            class="fa fa-search"></i> Ver Archivo</button>' +
                                    ' </div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                if (respuesta.retro !== null) {
                                    Pregunta +=
                                        '<div class="bs-callout-danger callout-transparent callout-bordered mt-1">' +
                                        '<div class="media align-items-stretch">' +
                                        '<div class="d-flex align-items-center bg-danger position-relative callout-arrow-left p-2">' +
                                        '<i class="fa fa-repeat fa-lg white font-medium-5"></i>' +
                                        '</div>' +
                                        '<div class="media-body p-1">' +
                                        '<strong>Retroalimentación</strong>' +
                                        '<div >' + respuesta.retro + '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }

                                $("#DetRespPregunta").html(Pregunta);


                            }


                        }
                    });
                },
                BuscEval: function(opc) {

                    var Periodo = $("#Periodos").val();
                    var Unidad = $("#Unidad").val();
                    var Tema = $("#Temas").val();
                    var contenido = "";
                    var idAlum = $("#idAlumno").val();
                    var j = 1;
                    var form = $("#formCalifEva2");
                    $("#pagEva").hide();
                    $("#idTem").remove();
                    $("#idPer").remove();
                    $("#idUnid").remove();
                    $("#idAlumno2").remove();
                    form.append("<input type='hidden' name='idTem' id='idTem' value='" + Tema + "'>");
                    form.append("<input type='hidden' name='idPer' id='idPer' value='" + Periodo +
                        "'>");
                    form.append("<input type='hidden' name='idUnid' id='idUnid' value='" + Unidad +
                        "'>");
                    form.append("<input type='hidden' name='idAlumno2' id='idAlumno2' value='" +
                        idAlum + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            if (opc === "HTML") {


                                $("#large").modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $.each(respuesta.Dat, function(i, item) {

                                    contenido += '<tr data-id="' + item.id +
                                        '" id="' + item.id + '">' +
                                        '  <td class="text-truncate">' + j +
                                        '</td>' +
                                        '  <td class="text-truncate" style="text-transform: capitalize;">' +
                                        item.titulo.toLowerCase() + '</td>' +
                                        '  <td class="text-truncate" style="text-transform: capitalize;">' +
                                        item.titu_contenido.toLowerCase() + '</td>';
                                    if (item.calf_prof === 'no') {
                                        contenido +=
                                            '  <td class="text-truncate">Pendiente</td>';
                                    } else {
                                        contenido +=
                                            '  <td class="text-truncate">Presentada</td>';
                                    }
                                    var puntMax = item.punt_max;
                                    var clase = 'btn bg-info btn-round mr-1 mb-1';


                                    if (item.puntuacion !== null) {

                                        var Punt = item.puntuacion.split("/");
                                        var porc = (Punt[0] / puntMax) * 100;

                                        switch (true) {
                                            case (porc <= 50):
                                                clase =
                                                    'btn btn-danger btn-round mr-1 mb-1';
                                                break;
                                            case (porc > 50 && porc <= 60):
                                                clase =
                                                    'btn bg-warning  btn-round mr-1 mb-1';
                                                break;
                                            case (porc > 60 && porc <= 70):
                                                clase =
                                                    'btn bg-yellow  btn-round mr-1 mb-1';
                                                break;
                                            case (porc > 70 && porc <= 80):
                                                clase =
                                                    'btn bg-success.bg-accent-3 btn-round mr-1 mb-1';
                                                break;
                                            case (porc > 80 && porc <= 100):
                                                clase =
                                                    'btn bg-success btn-round mr-1 mb-1';
                                                break;
                                        }

                                        Calf = item.calificacion;
                                        if (item.calf_prof === "si") {
                                            Calf = item.calificacion;
                                        } else {
                                            Calf = "Por Calificar";
                                        }


                                    } else {
                                        Calf = "0/" + puntMax;
                                        clase = 'btn bg-info btn-round mr-1 mb-1';
                                    }


                                    contenido +=
                                        '<td class="text-truncate" style="vertical-align: middle;">';
                                    contenido += ' <button type="button"  class="' +
                                        clase + '">' + Calf + '</button>';
                                    contenido += ' </td></tr>';
                                    //                                nom_alumno = item.nombre_alumno + " " + item.apellido_alumno;
                                    j++;
                                });
                                $("#Tb_Calif").html(contenido);
                                //                            $("#Nom_Alumno").html(nom_alumno);


                                $("#Nom_Alumno2").val(nom_alumno);
                            } else {
                                /////////////////////GENERAR PDF////////////////////

                                var doc = {
                                    pageSize: "LETTER",
                                    pageOrientation: "portrait",
                                    pageMargins: [30, 30, 30, 30],
                                    content: []
                                };
                                alert($("#Nom_Alumno2").val());
                                doc.content.push({
                                    text: "LISTADO DE CALIFICACIONES DE " + $(
                                        "#Nom_Alumno2").val().toUpperCase(),
                                    alignment: 'center',
                                    fontSize: 13,
                                    bold: true,
                                    margin: [0, 20, 0, 15]
                                });


                                doc.content.push({
                                    text: 'Listado Calificación por Tema',
                                    fontSize: 10,
                                    bold: true,
                                    italics: true,
                                    margin: [0, 10, 0, 0]
                                }, {
                                    table: {
                                        widths: ['5%', '30%', '30%', '10%', '10%',
                                            '15%'
                                        ],
                                        body: [
                                            ['#', 'Evaluación', 'Tema', 'Grado',
                                                'Estado', 'Calificación'
                                            ]

                                        ]
                                    },
                                    fontSize: 10,
                                    bold: true,
                                    fillColor: '#f4efef'

                                });
                                var contador = 1;
                                $.each(respuesta.Dat, function(i, item) {

                                    var Presetada = "Presentada";
                                    if (item.evaluacion === null) {
                                        Presetada = "Pendiente";
                                    }

                                    ////****************************
                                    if (item.puntuacion !== null) {
                                        var puntMax = parseInt(item.punt_max);
                                        var Cali = item.calificacion;
                                        var TipCali = item.calif_usando;
                                        var Calificacion = "";
                                        var Color = "";
                                        var pcal = Cali.split("/");
                                        var Calif = parseInt(pcal[0]);
                                        var porcentaje = (Calif / puntMax) * 100;
                                        Color = "";
                                        if (porcentaje <= 50) {
                                            Color = '#f20d00';
                                        } else if (porcentaje > 50 && porcentaje <=
                                            60) {
                                            Color = '#F08D0E';
                                        } else if (porcentaje > 60 && porcentaje <=
                                            70) {
                                            Color = '#F5DA00';
                                        } else if (porcentaje > 70 && porcentaje <=
                                            80) {
                                            Color = '#C0EA1C';
                                        } else if (porcentaje > 80 && porcentaje <=
                                            100) {
                                            Color = '#1ECD60';
                                        }

                                        //            $(txt_calif).css('color', '#002633');
                                        if (item.calf_prof == "si") {
                                            Calificacion = item.calificacion;
                                        } else {
                                            Calificacion = "Por Calificar";
                                        }
                                    } else {
                                        Calificacion = "0/" + item.punt_max;
                                        Color = '#42A5F5';
                                    }

                                    ////**************************** 

                                    doc.content.push({

                                        table: {
                                            widths: ['5%', '30%', '30%',
                                                '10%', '10%', '15%'
                                            ],
                                            body: [
                                                [contador, item.titulo
                                                    .toUpperCase(), item
                                                    .titu_contenido,
                                                    "Grado " + item
                                                    .grado_alumno + "°",
                                                    Presetada, {
                                                        text: Calificacion,
                                                        bold: true,
                                                        italics: true,
                                                        color: Color
                                                    }
                                                ]

                                            ]
                                        },
                                        fontSize: 8,
                                        bold: true
                                    });
                                    contador++;
                                });

                                pdfMake.createPdf(doc).download("LibroCalificaciones.pdf");
                            }
                        }


                    });
                },
                MostArc: function(id) {
                    window.open($('#dattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
                },
                VerArchResp: function(id) {
                    window.open($('#Respdattaller').data("ruta") + "/" + $('#' + id).data("archivo"),
                        '_blank');
                }
            });

        });
    </script>
@endsection
