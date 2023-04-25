@extends('Plantilla.Principal')
@section('title', 'Libro de Calificaciones')
@section('Contenido')
    <input type="hidden" name="Nom_Alumno2" id="Nom_Alumno2" value="">

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Listado de Alumnos
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

                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-md-4 col-lg-6 float-md-right">
                                                {!! Form::model(Request::all(), [
                                                    'url' => '/Calificaciones/LibroCalificaciones/' . Session::get('IDMODULO'),
                                                    'method' => 'GET',
                                                    'autocomplete' => 'off',
                                                    'role' => 'search',
                                                    'class' => '',
                                                ]) !!}
                                                <div class="input-group">
                                                    {!! Form::text('txtbusqueda', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'BUSQUEDA..']) !!}
                                                    <span class="input-group-append">
                                                        <button type="submit" class="btn btn-primary "> <i
                                                                class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="px-1"></p>
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
                                    <div class="col-12">
                                        <div class="table-responsive"> <input type='hidden' id='Id_Eval' name='Id_Eval'
                                                value='' />
                                            <table id="recent-orders"
                                                class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Identificación</th>
                                                        <th>Nombre </th>
                                                        <th>Grado</th>
                                                        <th>Revisar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($Eval as $Ev)
                                                        <tr data-id='{{ $Ev->id }}' id='alumno{{ $Ev->id }}'>
                                                            <td class="text-truncate">{!! $Ev->ident_alumno !!}</td>
                                                            <td class="text-truncate" style="text-transform: capitalize;">
                                                                {!! $Ev->nombre_alumno . ' ' . $Ev->apellido_alumno !!}</td>
                                                            <td class="text-truncate">{!! 'Grado ' . $Ev->grado_alumno . '°' !!}</td>
                                                            <td class="text-truncate">
                                                                <a onclick="$.VerCalificaciones({{ $Ev->usuario_alumno }});"
                                                                    title="Ver Calificaciones"
                                                                    class="btn btn-icon btn-outline-info btn-social-icon btn-sm"><i
                                                                        class="fa fa-search"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <p class="px-1"></p>
                                        @include('Calificaciones.PaginacionLibro')
                                    </div>
                                </div>
                                <div class="modal fade text-left" id="large" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel17" aria-hidden="true">

                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" style="text-transform: capitalize" id="Nom_Alumno">
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-footer">
                                                <div class="row" style="width: 100%;">
                                                    <div class="col-12" style="padding-bottom: 10px; ">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label class="form-label"
                                                                    for="unidad">Evaluaciones:</label>
                                                                <select class="form-control form-control"
                                                                    onchange="$.CambEval(this.value);" id="CalEval"
                                                                    name="CalEval">
                                                                    <option value="T">TEMAS</option>
                                                                    <option value="L">LABORATORIOS</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label" for="unidad">Periodo:</label>
                                                                <select class="form-control select2" style="width: 100%;"
                                                                    onchange='$.CargUnidades(this.value)' name="Periodos"
                                                                    id="Periodos">
                                                                    {!! $Select_Peri !!}
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="unidad">Unidad:</label>
                                                                <select class="form-control select2" style="width: 100%;"
                                                                    onchange='$.CargTemas(this.value)' name="Unidad"
                                                                    id="Unidad">
                                                                    {!! $Select_Unid !!}
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label" id="Tit-Tem"
                                                                    for="unidad">Tema:</label>
                                                                <select class="form-control select2" style="width: 100%;"
                                                                    onchange="$.BuscEval('HTML');" name="Temas"
                                                                    id="Temas">
                                                                    {!! $Select_Tem !!}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="table-responsive scrollable-container ps-container ps-theme-dark ps-active-x">
                                                            <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Evaluacion / Actividad</th>
                                                                        <th>Tema</th>
                                                                        <th>Grados</th>
                                                                        <th>Estado</th>
                                                                        <th>Calificacion</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="Tb_Calif">

                                                                </tbody>
                                                            </table>

                                                        </div>

                                                    </div>
                                                    <div class="col-12" style="padding-top: 10px;">
                                                        <button type="button" id="btn_salir"
                                                            class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                                                                class="ft-corner-up-left position-right"></i>
                                                            Salir</button>
                                                        <a class="btn btn-blue" onclick="$.BuscEval('PDF');"
                                                            title="Imprimir">
                                                            <i class="fa fa-file-pdf-o"></i> Generar PDF
                                                        </a>
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
        </section>
    </div>



    {!! Form::open(['url' => '/Calificaciones/ListCalifEva', 'id' => 'formCalifEva']) !!}
    {!! Form::close() !!}


    {!! Form::open(['url' => '/Calificaciones/ListCalifEva2', 'id' => 'formCalifEva2']) !!}
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
            $("#Men_Calificaciones").addClass("has-sub open");
            $("#Men_Calificiones_LibrrCal").addClass("active");
            $.extend({
                VerCalificaciones: function(id) {
                    var j = 1;
                    var contenido = "";
                    var nom_alumo = "";
                    var form = $("#formCalifEva");
                    $("#idAlumno").remove();
                    form.append("<input type='hidden' name='idAlumno' id='idAlumno' value='" + id +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            if (JSON.stringify(respuesta.Dat) == '[]') {
                                Swal.fire({
                                    title: "",
                                    text: 'Este Alumno no ha Presentado alguna Evaluación',
                                    icon: "warning",
                                    button: "Aceptar",
                                });
                            } else {
                                $("#large").modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $.each(respuesta.Dat, function(i, item) {

                                    contenido += '<tr data-id="' + item.id +
                                        '" id="' + item.id + '">' +
                                        '  <td class="text-truncate">' + j +
                                        '</td>' +
                                        '  <td class="text-truncate" style="text-transform:uppercase;">' +
                                        item.titulo + '</td>' +
                                        '  <td class="text-truncate" style="text-transform:uppercase;">' +
                                        item.titu_contenido + '</td>' +
                                        '  <td class="text-truncate">Grado ' + item
                                        .grado_alumno + '°</td>';
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

                                        var Punt = item.puntuacion;
                                        var porc = (Punt / puntMax) * 100;
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
                                                    'btn btn-primary btn-round mr-1 mb-1';
                                                break;
                                            case (porc > 80 && porc <= 100):
                                                clase =
                                                    'btn bg-success btn-round mr-1 mb-1';
                                                break;
                                        }

                                        Calf = item.calificacion;
                                        if (item.calf_prof == "si") {
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
                                    nom_alumno = item.nombre_alumno + " " + item
                                        .apellido_alumno;
                                    j++;
                                });
                                $("#Tb_Calif").html(contenido);
                                $("#Nom_Alumno").html(nom_alumno);
                                $("#Nom_Alumno2").val(nom_alumno);
                            }
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
                            $("#Unidad").html(respuesta.select_Unidades);
                        }

                    });
                    $.BuscEval("HTML");
                },
                CambEval: function(eval) {
                    if (eval === "L") {
                        $("#Tit-Tem").html("Laboratorio");
                    } else {
                        $("#Tit-Tem").html("Tema");
                    }
                    $('#Periodos').val('').trigger('change');
                    $('#Unidad').val('').trigger('change');
                    $('#Temas').val('').trigger('change');

                },
                CargTemas: function(id) {

                    var form = $("#formAuxiliarTema");
                    var eval = $("#CalEval").val();
                    $("#idUni").remove();
                    $("#evalCal").remove();
                    form.append("<input type='hidden' name='idUni' id='idUni' value='" + id + "'>");
                    form.append("<input type='hidden' name='evalCal' id='evalCal' value='" + eval +
                        "'>");
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
                BuscEval: function(opc) {

                    var Periodo = $("#Periodos").val();
                    var Unidad = $("#Unidad").val();
                    var Tema = $("#Temas").val();
                    var contenido = "";
                    var idAlum = $("#idAlumno").val();
                    var eval = $("#CalEval").val();
                    var j = 1;
                    var form = $("#formCalifEva2");
                    $("#pagEva").hide();
                    $("#idTem").remove();
                    $("#idPer").remove();
                    $("#idUnid").remove();
                    $("#idAlumno2").remove();
                    $("#evalCal").remove();
                    form.append("<input type='hidden' name='idTem' id='idTem' value='" + Tema + "'>");
                    form.append("<input type='hidden' name='idPer' id='idPer' value='" + Periodo +
                        "'>");
                    form.append("<input type='hidden' name='idUnid' id='idUnid' value='" + Unidad +
                        "'>");
                    form.append("<input type='hidden' name='idAlumno2' id='idAlumno2' value='" +
                        idAlum + "'>");
                    form.append("<input type='hidden' name='evalCal' id='evalCal' value='" + eval +
                        "'>");
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
                                        '  <td class="text-truncate">' + item
                                        .titulo + '</td>' +
                                        '  <td class="text-truncate">' + item
                                        .titu_contenido + '</td>' +
                                        '  <td class="text-truncate">Grado ' + item
                                        .grado_alumno + '°</td>';
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

                                        var Punt = item.puntuacion;
                                        var porc = (Punt / puntMax) * 100;

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
                                                    'btn btn-primary btn-round mr-1 mb-1';
                                                break;
                                            case (porc > 80 && porc <= 100):
                                                clase =
                                                    'btn bg-success btn-round mr-1 mb-1';
                                                break;
                                        }

                                        Calf = item.calificacion;
                                        if (item.calf_prof == "si") {
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
                                    nom_alumno = item.nombre_alumno + " " + item
                                        .apellido_alumno;
                                    j++;
                                });
                                $("#Tb_Calif").html(contenido);
                                $("#Nom_Alumno").html(nom_alumno);


                                $("#Nom_Alumno2").val(nom_alumno);
                            } else {
                                /////////////////////GENERAR PDF////////////////////

                                var doc = {
                                    pageSize: "LETTER",
                                    pageOrientation: "portrait",
                                    pageMargins: [30, 30, 30, 30],
                                    content: []
                                };
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

                                pdfMake.createPdf(doc).open();
                            }
                        }


                    });
                },
            });
        });
    </script>
@endsection
