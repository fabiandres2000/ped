@extends('Plantilla.Principal')
@section('title', 'Gestión de Foros')
@section('Contenido')
    <input type="hidden" class="form-control" id="OrAs" value="{{ $ori }}" />
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestión de Asistencia
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="ModAsist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success white">
                    <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">Asistencia del
                        Estudiante en el Mes</h4>
                </div>
                <div class="modal-body">
                    <div id='Tabla_Asit'>

                    </div>
               
                </div>

                <div class="modal-footer">
                    <button type="button" id="btn_atras" data-dismiss="modal" class="btn grey btn-outline-secondary"><i
                            class="ft-corner-up-left position-right"></i>
                        Atras</button>
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
                            <h4 class="card-title">Gestión de Asistencia</h4>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        @if (Session::get('TIPCONT') == 'ASI')
                                            {!! Form::model(Request::all(), ['url' => '/Profesores/asistencia', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}
                                        @else
                                            {!! Form::model(Request::all(), ['url' => '/Profesores/asistenciaMod', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => '']) !!}
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6 col-lg12">
                                                <div class='input-group date' id='fecha_mat'>
                                                    <div class="controls">
                                                        <input type='text' name="fecha" required id="fecha"
                                                            value="{{ $fecha }}"
                                                            class="form-control inhfec txtValidacion"
                                                            placeholder="Fecha de Asistencia" />
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                        <button type="submit" class="btn btn-primary "> <i
                                                                class="fa fa-search"></i> Busqueda</button>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" onclick="$.PrintAsit();" class="btn btn-success "> <i
                                                        class="fa fa-print"></i> Imprimir Asistencia por Mes</button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
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
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="recent-orders"
                                                class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Identificación</th>
                                                        <th>Estudiante</th>
                                                        <th class="text-center">{!! date('Y-m-d') !!}</th>
                                                        <th class="text-center">Presente</th>
                                                        <th class="text-center">Tarde</th>
                                                        <th class="text-center">Ausente</th>
                                                        <th class="text-center">Excusado</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detalle">
                                                    @foreach ($alumnos as $asi)
                                                        <tr data-idmodulo='{{ $asi->IDMODULO }}'
                                                            data-idalumno='{{ $asi->IDALUMNO }}'>
                                                            @php
                                                                $clasePorcentaje = 'btn-outline-info';
                                                                $valorPorcentaje = '--';
                                                                $clasePresente = 'btn-outline-success';
                                                                $claseTarde = 'btn-outline-warning';
                                                                $claseAusente = 'btn-outline-danger';
                                                                $claseExcusado = 'btn-outline-dark';
                                                                switch ($asi->VALOR) {
                                                                    case '100%':
                                                                        $clasePorcentaje = 'btn-success';
                                                                        $valorPorcentaje = '100%';
                                                                        $clasePresente = 'btn-success';
                                                                        break;
                                                                    case '50%':
                                                                        $clasePorcentaje = 'btn-warning';
                                                                        $valorPorcentaje = '50%';
                                                                        $claseTarde = 'btn-warning';
                                                                        break;
                                                                    case '0%':
                                                                        $clasePorcentaje = 'btn-danger';
                                                                        $valorPorcentaje = '0%';
                                                                        $claseAusente = 'btn-danger';
                                                                        break;
                                                                    case '--':
                                                                        $clasePorcentaje = 'btn-dark';
                                                                        $valorPorcentaje = '--';
                                                                        $claseExcusado = 'btn-dark';
                                                                        break;
                                                                }
                                                            @endphp
                                                            <td style='font-weight: bold;vertical-align: middle; cursor: pointer;'
                                                                onclick="$.VerAsiAlum({{ $asi->IDALUMNO }});">
                                                                {{ $loop->iteration }}</td>
                                                            <td class="text-truncate"
                                                                style='font-weight: bold;vertical-align: middle; cursor: pointer;'
                                                                onclick="$.VerAsiAlum({{ $asi->IDALUMNO }});">
                                                                {!! $asi->ident_alumno !!}</td>
                                                            <td class="text-truncate"
                                                                style='font-weight: bold;vertical-align: middle; cursor: pointer;'
                                                                onclick="$.VerAsiAlum({{ $asi->IDALUMNO }});">
                                                                {!! ucwords($asi->nombre_alumno) !!} {!! ucwords($asi->apellido_alumno) !!}</td>
                                                            <td class="text-center">
                                                                <a href='javascript:void(0)'
                                                                    style="vertical-align: middle;text-align: center;"
                                                                    data-toggle="tooltip" title="Porcentaje"
                                                                    id="porcentaje{{ $asi->IDALUMNO }}"
                                                                    class="btn btn-icon btn-social-icon btn-sm {{ $clasePorcentaje }}">
                                                                    <p style='font-weight: bold;vertical-align: middle;font-size:10px;'
                                                                        class="text-center"
                                                                        id="txtpor{{ $asi->IDALUMNO }}">
                                                                        {{ $valorPorcentaje }}
                                                                    </p>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href='javascript:void(0)'
                                                                    style="vertical-align: middle;text-align: center;"
                                                                    data-idfila='{{ $asi->IDALUMNO }}'
                                                                    data-toggle="tooltip" title="Presente"
                                                                    id="presente{{ $asi->IDALUMNO }}"
                                                                    class="btn btn-icon btn-social-icon btn-sm presente {{ $clasePresente }}">
                                                                    <i class="fa fa-check"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href='javascript:void(0)'
                                                                    style="vertical-align: middle;text-align: center;"
                                                                    data-idfila='{{ $asi->IDALUMNO }}'
                                                                    data-toggle="tooltip" title="Tarde"
                                                                    id="tarde{{ $asi->IDALUMNO }}"
                                                                    class="btn btn-icon btn-social-icon btn-sm tarde {{ $claseTarde }}">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href='javascript:void(0)'
                                                                    style="vertical-align: middle;text-align: center;"
                                                                    data-idfila='{{ $asi->IDALUMNO }}'
                                                                    data-toggle="tooltip" title="Ausente"
                                                                    id="ausente{{ $asi->IDALUMNO }}"
                                                                    class="btn btn-icon btn-social-icon btn-sm ausente {{ $claseAusente }}">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href='javascript:void(0)'
                                                                    style="vertical-align: middle;text-align: center;"
                                                                    data-idfila='{{ $asi->IDALUMNO }}'
                                                                    data-toggle="tooltip" title="Excusado"
                                                                    id="excusado{{ $asi->IDALUMNO }}"
                                                                    class="btn btn-icon btn-social-icon btn-sm excusado {{ $claseExcusado }}">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </td>
                                                        <tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
        <div class="modal fade text-left" id="VisAsistencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="DetTitAsiste"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <div class="bd-example" style="height: 500px;">
                                <table class="table table-responsive table2excel" id="TablaAsist">
                                    <thead id="tr_AsitHead">

                                    </thead>
                                    <tbody id="tr_asistReg">

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnExportar" onclick="$.GenerarExcel();"
                            class="btn grey btn-outline-primary"><i class="fa fa-file-excel-o"></i>
                            Generar Excel</button>

                        <button type="button" id="btn_atras" data-dismiss="modal" class="btn grey btn-outline-secondary"><i
                                class="ft-corner-up-left position-right"></i>
                            Atras</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::open(['url' => '/Asistencia/guardar', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}


    {!! Form::open(['url' => '/Consultar/Asitencia', 'id' => 'formAsistencia']) !!}
    {!! Form::close() !!}
    {!! Form::open(['url' => '/Asistencia/ListAsitencia', 'id' => 'formAsistenciaList']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            var valorasistencia = "";
            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Asistencia").addClass("active open");
            $('#fecha_exp,#fecha_mat,#fecha_apertura,#fecha_expedicion_repre').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });
            $.extend({
                VerAsiAlum: function(id) {
                    $("#ModAsist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    var form = $("#formAsistencia");
                    var fecha = $("#fecha").val();
                    var OrAs = $("#OrAs").val();
                    var contenido = '';
                    var j = 1;
                    $("#IdAlumno").remove();
                    $("#Txt_Fecha").remove();
                    $("#OriAsistenc").remove();
                    var Text_Coment = $("#Text_Coment").val();
                    form.append("<input type='hidden' name='IdAlumno' id='IdAlumno' value='" + id +
                        "'>");
                    form.append("<input type='hidden' name='Txt_Fecha' id='Txt_Fecha' value='" + fecha +
                        "'>");
                    form.append("<input type='hidden' name='OrAs' id='OriAsistenc' value='" + OrAs +
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
                            contenido += '<div class="col-md-12">' +
                                '  <h6 class="form-section"><strong>Asistencia</strong> </h6>' +
                                '  <table class="table table-hover mb-0" >' +
                                '      <thead>' +
                                '          <tr> ' +
                                '              <th>#</th>' +
                                '              <th>Días</th>' +
                                '              <th>Asistencia</th>' +
                                '          </tr>' +
                                '      </thead>' +
                                '      <tbody id="tr_archivos">';
                            $.each(respuesta.Asiste, function(i, item) {
                                contenido += '<tr>' +
                                    '<td class="text-truncate">' + j + '</td>' +
                                    '<td class="text-truncate">' + item.fecha +
                                    '</td>' +
                                    '<td class="text-truncate">';
                                if (item.valor === "100%") {
                                    contenido +=
                                        ' <a style="vertical-align: middle;text-align: center;" data-toggle="tooltip" title="Presente"  class="btn btn-icon btn-social-icon btn-sm  presente btn-success"> <i class="fa fa-check"></i></a> Presente';
                                } else if (item.valor === "50%") {
                                    contenido +=
                                        ' <a style="vertical-align: middle;text-align: center;" data-toggle="tooltip" title="Tarde"  class="btn btn-icon btn-social-icon btn-sm tarde btn-warning"> <i class="fa fa-clock-o"></i></a> Tarde';
                                } else if (item.valor === "0%") {
                                    contenido +=
                                        ' <a style="vertical-align: middle;text-align: center;" data-toggle="tooltip" title="Ausente"  class="btn btn-icon btn-social-icon btn-sm ausente btn-danger"> <i class="fa fa-times"></i></a> Ausente';
                                } else {
                                    contenido +=
                                        ' <a style="vertical-align: middle;text-align: center;" data-toggle="tooltip" title="Presente"  class="btn btn-icon btn-social-icon btn-sm excusado btn-dark"> <i class="fa fa-times"></i></a> Excusado';
                                }
                                contenido += '</td>' +
                                    '</tr>';
                                j++;
                            });
                            contenido += '      </tbody>' +
                                '  </table>' +
                                ' </div>';
                        }
                    });
                    $("#Tabla_Asit").html(contenido);
                },
                PrintAsit: function() {

                    $("#VisAsistencia").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    var x = document.getElementById("fecha");
                    let date = new Date(x.value);
                    var mes_name = date.getMonth();
                    let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ];
                    var form = $("#formAsistenciaList");
                    var fecha = $("#fecha").val();
                    $("#textfecha").remove();
                    form.append("<input type='hidden' name='textfecha' id='textfecha' value='" + fecha +
                        "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();


                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            var tablaAsitHead = '';
                            var tablaAsitBody = '';
                            $("#DetTitAsiste").html("Listado de Asistencia del Mes de " +
                                meses[mes_name]);



                            var diasMes = respuesta.DiasMes;
                            tablaAsitHead +=
                                '<tr><th></th><th></th> <th style="text-align: center" colspan="' +
                                diasMes + '">Días de ' + meses[mes_name] + '</th></tr>';
                            tablaAsitHead += '<tr><th>#</th><th>Alumno</th>';
                            for (var i = 1; i <= diasMes; i++) {
                                tablaAsitHead += '<th>' + i + '</th>';
                            }
                            tablaAsitHead += '</tr>';

                            $("#tr_AsitHead").html(tablaAsitHead);
                            $("#tr_asistReg").html(respuesta.tablaAsit);
                        }
                    });

                },
                GenerarExcel: function() {
                    var x = document.getElementById("fecha");
                    let date = new Date(x.value);
                    var mes_name = date.getMonth();
                    let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ];

                    $("#TablaAsist").table2excel({
                        filename: "Listado de Asistencia del Mes de " + meses[mes_name] + ".xls"
                    });
                },

            });
            $('#detalle').on("click", ".presente", function(e) {
                var id = $(this).data('idfila');
                $(this).removeClass('btn-outline-success');
                $(this).addClass('btn-success');
                $("#tarde" + id).addClass('btn-outline-warning');
                $("#tarde" + id).removeClass('btn-warning');
                $("#ausente" + id).addClass('btn-outline-danger');
                $("#ausente" + id).removeClass('btn-danger');
                $("#excusado" + id).addClass('btn-outline-dark');
                $("#excusado" + id).removeClass('btn-dark');
                $("#txtpor" + id).html("100%");
                $("#porcentaje" + id).addClass('btn-success');
                $("#porcentaje" + id).removeClass('btn-outline-info');
                $("#porcentaje" + id).removeClass('btn-dark');
                $("#porcentaje" + id).removeClass('btn-danger');
                $("#porcentaje" + id).removeClass('btn-warning');
                var fila = $(this).parents('tr');
                var id_alumno = fila.data('idalumno');
                var id_modulo = fila.data('idmodulo');
                valorasistencia = "100%";
                guardar(id_alumno, id_modulo);
            });
            $('#detalle').on("click", ".tarde", function(e) {
                var id = $(this).data('idfila');
                $(this).removeClass('btn-outline-warning');
                $(this).addClass('btn-warning');
                $("#presente" + id).removeClass('btn-success');
                $("#presente" + id).addClass('btn-outline-success');
                $("#ausente" + id).addClass('btn-outline-danger');
                $("#ausente" + id).removeClass('btn-danger');
                $("#excusado" + id).addClass('btn-outline-dark');
                $("#excusado" + id).removeClass('btn-dark');
                $("#txtpor" + id).html("50%");
                $("#porcentaje" + id).addClass('btn-warning');
                $("#porcentaje" + id).removeClass('btn-outline-info');
                $("#porcentaje" + id).removeClass('btn-success');
                $("#porcentaje" + id).removeClass('btn-dark');
                $("#porcentaje" + id).removeClass('btn-danger');
                valorasistencia = "50%";
                var fila = $(this).parents('tr');
                var id_alumno = fila.data('idalumno');
                var id_modulo = fila.data('idmodulo');
                guardar(id_alumno, id_modulo);
            });
            $('#detalle').on("click", ".ausente", function(e) {
                var id = $(this).data('idfila');
                $(this).removeClass('btn-outline-danger');
                $(this).addClass('btn-danger');
                $("#presente" + id).removeClass('btn-success');
                $("#presente" + id).addClass('btn-outline-success');
                $("#tarde" + id).addClass('btn-outline-warning');
                $("#tarde" + id).removeClass('btn-warning');
                $("#excusado" + id).addClass('btn-outline-dark');
                $("#excusado" + id).removeClass('btn-dark');
                $("#txtpor" + id).html("0%");
                $("#porcentaje" + id).addClass('btn-danger');
                $("#porcentaje" + id).removeClass('btn-outline-info');
                $("#porcentaje" + id).removeClass('btn-success');
                $("#porcentaje" + id).removeClass('btn-dark');
                $("#porcentaje" + id).removeClass('btn-warning');
                valorasistencia = "0%";
                var fila = $(this).parents('tr');
                var id_alumno = fila.data('idalumno');
                var id_modulo = fila.data('idmodulo');
                guardar(id_alumno, id_modulo);
            });
            $('#detalle').on("click", ".excusado", function(e) {
                var id = $(this).data('idfila');
                $(this).removeClass('btn-outline-dark');
                $(this).addClass('btn-dark');
                $("#presente" + id).removeClass('btn-success');
                $("#presente" + id).addClass('btn-outline-success');
                $("#tarde" + id).addClass('btn-outline-warning');
                $("#tarde" + id).removeClass('btn-warning');
                $("#ausente" + id).addClass('btn-outline-danger');
                $("#ausente" + id).removeClass('btn-danger');
                $("#txtpor" + id).html("--");
                $("#porcentaje" + id).addClass('btn-dark');
                $("#porcentaje" + id).removeClass('btn-outline-info');
                $("#porcentaje" + id).removeClass('btn-success');
                $("#porcentaje" + id).removeClass('btn-danger');
                $("#porcentaje" + id).removeClass('btn-warning');
                valorasistencia = "--";
                var fila = $(this).parents('tr');
                var id_alumno = fila.data('idalumno');
                var id_modulo = fila.data('idmodulo');
                guardar(id_alumno, id_modulo);
            });

            function guardar(id_alumno, id_modulo) {
                var form = $("#formAuxiliar");
                $("#idalumnoAuxiliar").remove();
                $("#idmoduloAuxiliar").remove();
                $("#valorAuxiliar").remove();
                $("#fechaAuxiliar").remove();
                $("#OriAsistenc").remove();
                var fecha = $("#fecha").val();
                var OrAs = $("#OrAs").val();
                form.append("<input type='hidden' name='id_alumno' id='idalumnoAuxiliar' value='" + id_alumno +
                    "'>");
                form.append("<input type='hidden' name='id_modulo' id='idmoduloAuxiliar' value='" + id_modulo +
                    "'>");
                form.append("<input type='hidden' name='valor' id='valorAuxiliar' value='" + valorasistencia +
                    "'>");
                form.append("<input type='hidden' name='fecha' id='fechaAuxiliar' value='" + fecha + "'>");
                form.append("<input type='hidden' name='OrAs' id='OriAsistenc' value='" + OrAs + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                $.ajax({
                    type: "post",
                    url: url,
                    data: datos,
                    success: function(respuesta) {

                    },
                    error: function() {
                        mensaje = "No se pudo guardar";
                        Swal.fire({
                            title: "",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                    }
                });
            }
        });
    </script>
@endsection
