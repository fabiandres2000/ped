@extends('Plantilla.Principal')
@section('title','Datos Estaditicos')
@section('Contenido')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Información Estadistica</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                    </li>
                    <li  class="breadcrumb-item active">Información Estadistica
                    </li>
                </ol>
            </div>
        </div>
    </div>     
</div>

<div class="content-body">

    <!--/ Analytics spakline & chartjs  -->
    <!--stats-->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left w-100">
                                <h3 class="primary">{{$Nasig}}</h3>
                                <span>Numero de Asignaturas</span>
                            </div>
                            <div class="media-right media-middle">
                                <i class="ft-grid primary font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left w-100">
                                <h3 class="danger">{{$Nest}}</h3>
                                <span>Número de Estudiantes</span>
                            </div>
                            <div class="media-right media-middle">
                                <i class="icon-users danger font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left w-100">
                                <h3 class="success">{{$Nprof}}</h3>
                                <span>Número de Docentes</span>
                            </div>
                            <div class="media-right media-middle">
                                <i class="icon-users success font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left w-100">
                                <h3 class="warning">{{$Ntema}}</h3>
                                <span>Número de Temas</span>
                            </div>
                            <div class="media-right media-middle">
                                <i class="icon-book-open warning font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/stats-->
    <!-- Audience by country & users visit-->
    <div class="row match-height">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Parametros de Busqueda</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        </ul>
                    </div>

                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-lg-12">
                                <label class="form-label" for="des_unidad">Asignatura:</label>
                                <select class="form-control select2"  name="asignatura" id="asignatura">
                                    {!!$select_Asig!!}
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-12">
                                <label class="form-label" for="des_unidad">Jornada:</label>
                                <select class="form-control select2"  name="jornada" id="jornada">
                                    <option value="">TODAS</option>
                                    <option value="JM">MAÑANA</option>
                                    <option value="JT">TARDE</option>
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-12">
                                <label class="form-label" for="des_unidad">&nbsp;</label>
                                <span class="input-group-append">
                                    <button onclick="$.CargInfGeneral();" type="button" class="btn btn-primary "> <i class="fa fa-search" ></i> Busqueda</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Estudiantes por Grado</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div id="chartdivgrado" style=" width: 100%; height: 300px; padding-left: 10px;" class="chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Estudiantes por Sexo</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div id="chartdivsex" style=" width: 100%; height: 300px; padding-left: 10px;" class="chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Estudiantes por Grupo Etario</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div id="chartdivEtar" style=" width: 100%; height: 300px; padding-left: 10px;" class="chart"></div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Audience by country  & users visit -->

    <!-- Bounce Rate & List -->
    <div class="row match-height">
        <div class="col-xl-4 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Est. con Extraedad</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content" style="padding-left: 12%;padding-right: 11%;">
                    <div id="ClassExtra" class="c100 p25 big" >
                        <span id="PorceExt">50%</span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div>
                    <div class="col-12" style="text-align: center;">
                        <ul class="list-inline clearfix pt-1 mb-0">
                            <li>
                                <span class="success">Estudiantes Totales</span> <h2 id="TotEst" class="grey darken-1 text-bold-400">125 </h2>

                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
        <div class="col-xl-8 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Detalles Extraedad</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div id="audience-list-scroll" class="table-responsive height-300 position-relative">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Grado</th>
                                    <th># de Estudiantes</th>
                                    <th># Est. Extraedad</th>
                                    <th>% Extraedad</th>
                                </tr>
                            </thead>
                            <tbody id="tr_est">


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Bounce Rate & List -->
</div>

{!! Form::open(['url'=>'/Gestion/InfGeneral'
,'id'=>'formAuxiliarEstGen'])!!}
{!! Form::close() !!}

@endsection
@section('scripts')
<script>
    $(document).ready(function () {

        $("#Men_Inicio").removeClass("active");
        $("#Men_Presentacion").removeClass("active");
        $("#Men_EstadisticasAdm").addClass("active open");

        $.extend({
            CargInfGeneral: function () {
                var form = $("#formAuxiliarEstGen");
                var asig = $("#asignatura").val();
                var jorn = $("#jornada").val();
                $("#idasig").remove();
                $("#idjorn").remove();
                form.append("<input type='hidden' name='idasig' id='idasig' value='" + asig + "'><input type='hidden' name='idjorn' id='idjorn' value='" + jorn + "'>");
                var url = form.attr("action");
                var datos = form.serialize();

                // Themes begin
                am4core.useTheme(am4themes_animated);

                ////CONFIGURACION GRAFICA GRADO

                var chartgrad = am4core.create("chartdivgrado", am4charts.PieChart3D);
                chartgrad.hiddenState.properties.opacity = 0; // this creates initial fade-in
                chartgrad.innerRadius = am4core.percent(20);
                chartgrad.paddingTop = 0;
                chartgrad.marginTop = 0;
                chartgrad.valign = 'top';
                chartgrad.contentValign = 'top';
                var pieSeriesgrad = chartgrad.series.push(new am4charts.PieSeries3D());


                ///CONFIGURACION GRAFICA SEXO
                am4core.useTheme(am4themes_animated);
                var chart = am4core.create("chartdivsex", am4charts.PieChart3D);
                chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
                chart.innerRadius = am4core.percent(20);
                chart.paddingTop = 0;
                chart.marginTop = 0;
                chart.valign = 'top';
                chart.contentValign = 'top';
                var pieSeries = chart.series.push(new am4charts.PieSeries3D());

                ///CONFIGURACION GRAFICA FRUPO ETAREO
                am4core.useTheme(am4themes_animated);
                var chartEta = am4core.create("chartdivEtar", am4charts.XYChart);
                chartEta.paddingBottom = 50;

                chartEta.cursor = new am4charts.XYCursor();
                chartEta.scrollbarX = new am4core.Scrollbar();

                $.ajax({
                    type: "POST",
                    async: false,
                    url: url,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {

                        /////DATOS GRAFICA SEXO
                        $("#chartdiv").show();
                        chart.data = respuesta.AlumSex;
                        chart.legend = new am4charts.Legend();
                        pieSeries.dataFields.value = "cant";
                        pieSeries.dataFields.category = "sexo_alumno";
                        pieSeries.slices.template.cornerRadius = 6;
                        pieSeries.labels.template.text = "{category}: {value} ({value.percent.formatNumber('#.0')}%)";

                        /////DATOS GRAFICA GRADO
                        $("#chartdivgrado").show();
                        chartgrad.data = respuesta.AlumGrad;
                        chartgrad.legend = new am4charts.Legend();
                        pieSeriesgrad.dataFields.value = "cant";
                        pieSeriesgrad.dataFields.category = "grado";
                        pieSeriesgrad.slices.template.cornerRadius = 6;
                        pieSeriesgrad.labels.template.text = "{category}: {value} ({value.percent.formatNumber('#.0')}%)";


                        /////DATOS GRAFICA GRUPO ETAREO
                        chartEta.data = respuesta.AlumEtar;
                        $("#chartdivEtar").show();
                        var categoryAxis = chartEta.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "edad";
                        categoryAxis.renderer.grid.template.location = 0;

                        var valueAxis = chartEta.yAxes.push(new am4charts.ValueAxis());

                        // Create series
                        var series = chartEta.series.push(new am4charts.ColumnSeries());
                        series.dataFields.valueY = "cant";
                        series.dataFields.categoryX = "edad";
                        series.tooltipText = "{valueY.cant}";
                        series.columns.template.tooltipText = "Series: {name}\nGrup. Etareo: {categoryX}\nCantidad: {valueY}";
                        series.columns.template.strokeOpacity = 0;
                        series.columns.template.column.cornerRadiusTopRight = 10;
                        series.columns.template.column.cornerRadiusTopLeft = 10;

                        series.columns.template.adapter.add("fill", function (fill, target) {
                            return chartEta.colors.getIndex(target.dataItem.index);
                        });

                        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                        labelBullet.label.verticalCenter = "bottom";
                        labelBullet.label.dy = -10;
                        labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}";

                        ////////DATOS DE EXTRAEDAD
                        var PorExt = respuesta.ExtrEdad * 100 / respuesta.NAlum;
                        $("#ClassExtra").removeClass("c100 p25 big");
                        $("#ClassExtra").addClass("c100 p" + Math.round(PorExt) + " big");
                        $("#PorceExt").html(Math.round(PorExt) + "%");
                        $("#TotEst").html(respuesta.NAlum);

                        var tr_alumnos = "";
                        var Nca = 0;
                        var pEt = 0;
                        var mi_prefijo =
                                $.each(respuesta.AlumGrad, function (i, item) {

                                    if (item.grado_alumno === "1") {
                                        Nca = respuesta.ExtrEdad1;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }
                                    if (item.grado_alumno === "2") {
                                        Nca = respuesta.ExtrEdad2;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }
                                    if (item.grado_alumno === "3") {
                                        Nca = respuesta.ExtrEdad3;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }
                                    if (item.grado_alumno === "4") {
                                        Nca = respuesta.ExtrEdad4;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }
                                    if (item.grado_alumno === "5") {
                                        Nca = respuesta.ExtrEdad5;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }
                                    if (item.grado_alumno === "6") {
                                        Nca = respuesta.ExtrEdad6;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }

                                    if (item.grado_alumno === "7") {
                                        Nca = respuesta.ExtrEdad7;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }
                                    if (item.grado_alumno === "8") {
                                        Nca = respuesta.ExtrEdad8;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }

                                    if (item.grado_alumno === "9") {
                                        Nca = respuesta.ExtrEdad9;
                                        pEt = Nca * 100 / item.cant;
                                        pEt = Math.round(pEt);
                                    }

                                    if (item.grado_alumno > 9) {
                                        Nca = 0;
                                        pEt = 0;
                                    }


                                    tr_alumnos += ' <tr>'
                                            + '<td>' + item.grado + '</td>'
                                            + '<td>' + item.cant + '</td>'
                                            + '<td>' + Nca + '</td>'
                                            + '<td class="text-center font-small-2">' + pEt + '%'
                                            + '    <div class="progress progress-sm mt-1 mb-0">'
                                            + '        <div class="progress-bar bg-success" role="progressbar" style="width: ' + pEt + '%" aria-valuenow="25"'
                                            + '             aria-valuemin="0" aria-valuemax="100"></div>'
                                            + '    </div>'
                                            + '</td>'
                                            + '</tr>';

                                });

                        $("#tr_est").html(tr_alumnos);


                    }

                });

            }
        });

        $.CargInfGeneral();


    });
</script>
@endsection

