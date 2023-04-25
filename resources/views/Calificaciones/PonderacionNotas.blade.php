@extends('Plantilla.Principal')
@section('title','Ponderación de Notas')
@section('Contenido')
<input type="hidden" name="Nom_Alumno2" id="Nom_Alumno2" value="">
<input type="hidden" class="form-control" id="tponder" value="{{$tponde}}" />
<input type="hidden" class="form-control" id="tponderU" value="{{$tponde}}" />
<input type="hidden" class="form-control" id="tponderT" value="{{$tponde}}" />
<input type="hidden" class="form-control" id="tponderE" value="{{$tponde}}" />
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{Session::get('des')}}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                    </li>
                    <li  class="breadcrumb-item active">Ponderación de Notas
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
                        <h4 class="card-title" >Ponderación de Notas</h4>                    
                    </div>
                </div> 



                <div class="row match-height">

                    <div class="col-xl-6 col-lg-12">
                        <div class="card">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Periodos</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <fieldset id="HabPondAut"> 
                                            <div class="float-left">
                                                <div class="btn-group btn-group-sm" tabindex="0">
                                                    @if($tponde=="Manual" || $tponde==null)
                                                    <a class="btn active btn-success" id="PondMan" onclick="$.PondManPer();" title="Ponderación Manual">Ponderación Manual</a>
                                                    <a class="btn btn-default" id="PondAut" onclick="$.PondAutPer();" title="Ponderación Automatica">Ponderación Automatica</a>
                                                    @else
                                                    <a class="btn btn-default" id="PondMan" onclick="$.PondManPer();" title="Ponderación Manual">Ponderación Manual</a>
                                                    <a class="btn active btn-success" id="PondAut" onclick="$.PondAutPer();" title="Ponderación Automatica">Ponderación Automatica</a>

                                                    @endif
                                                </div> 
                                            </div> 
                                            <div class="float-right">
                                                @if($tponde!=null)
                                                <span class="font-medium-4 float-right" id="PorcTotalPer">100%</span>
                                                @else
                                                <span class="font-medium-4 float-right" id="PorcTotalPer">0%</span>
                                                @endif
                                                <input type="hidden" class="form-control" id="PorcTotalPerText" value="" />
                                            </div> 
                                        </fieldset>
                                    </div>
                                    <form method='post' action='{{ url('/')}}' id='FormPorcPer'>
                                        <div class="table-responsive">

                                            <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Porcentaje(%)</th>
                                                        <th>Opción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $Sum = 0;
                                                    ?>
                                                    @foreach($Periodo as $Per)

                                                    @if($Per->porcentaje==null)
                                                    <?php $Sum = 0; ?>
                                                    @else
                                                    <?php $Sum = $Per->porcentaje; ?>
                                                    @endif

                                                    <tr>
                                                        <td class="text-truncate">{{$Per->des_periodo}}<input type="hidden" class="form-control" name="Per[]" id="per" value="{{$Per->id}}" /></td>
                                                        <td class="text-truncate"><input type="text" maxlength="3"  onkeypress="return validartxtnum(event);" onchange="$.SumPorPer(this.id);" id="PorcPer{{$Per->id}}" name="PorcPer[]" class="form-control form-control-sm" value="{{ $Sum }}" id="Small" placeholder="%"></td>
                                                        @if($tponde==null)
                                                        <td class="text-truncate"><button type="button" disabled="" name="PondUni[]" id="PondUni{{$Per->id}}" onclick="$.MostUnidades({{$Per->id}});" title="Mostrar Unidades" class="btn mr-1 mb-1 btn-success btn-sm"><i class="fa fa-check"></i> </button></td>
                                                        @else
                                                        <td class="text-truncate"><button type="button" name="PondUni[]" id="PondUni{{$Per->id}}" onclick="$.MostUnidades({{$Per->id}});" title="Mostrar Unidades" class="btn mr-1 mb-1 btn-success btn-sm"><i class="fa fa-check"></i> </button></td>

                                                        @endif
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </form>

                                    <div class="alert alert-warning" id="AlerPer" style="display: none;"role="alert">
                                        <strong>Warning!</strong> El porcentaje no puede ser mayor a 100%
                                    </div>

                                    <div class="swal-footer">

                                        <div class="swal-button-container">

                                            <button type="button" onclick="$.GuardarPeriodo();" class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-12" id="Div_Unidades" style="display: none;">
                        <div class="card">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="Tit_Unidades"></h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <fieldset id="HabPondAut"> 
                                            <div class="float-left">
                                                <div class="btn-group btn-group-sm" tabindex="0">

                                                    <a class="btn active btn-success" id="PondManUnid" onclick="$.PondManUnd();" title="Ponderación Manual">Ponderación Manual</a>
                                                    <a class="btn btn-default" id="PondAutUnid" onclick="$.PondAutUnd();" title="Ponderación Automatica">Ponderación Automatica</a>

                                                </div> 
                                            </div> 
                                            <div class="float-right">

                                                <span class="font-medium-4 float-right" id="PorcTotalUni">100%</span>

                                                <input type="hidden" class="form-control" id="PorcTotalUniText" value="" />
                                            </div> 
                                        </fieldset>
                                    </div>
                                    <form method='post' action='{{ url('/')}}' id='FormPorcUnid'>
                                        <div class="table-responsive">
                                            <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Porcentaje(%)</th>
                                                        <th>Opción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tr_unidades">


                                                </tbody>
                                            </table>
                                        </div>
                                    </form>


                                    <div class="alert alert-warning" id="AlerUni" style="display: none;"role="alert">
                                        <strong>Warning!</strong> El porcentaje no puede ser mayor a 100%
                                    </div>
                                    <div class="swal-footer">

                                        <div class="swal-button-container">
                                            <button type="button" onclick="$.GuardarUnidades();" class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12" id="Div_Temas" style="display: none;">
                        <div class="card">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="Tit_Temas"></h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <fieldset id="HabPondAut"> 
                                            <div class="float-left">
                                                <div class="btn-group btn-group-sm" tabindex="0">

                                                    <a class="btn active btn-success" id="PondManTema" onclick="$.PondManTema();" title="Ponderación Manual">Ponderación Manual</a>
                                                    <a class="btn btn-default" id="PondAutTema" onclick="$.PondAutTema();" title="Ponderación Automatica">Ponderación Automatica</a>

                                                </div> 
                                            </div> 
                                            <div class="float-right">

                                                <span class="font-medium-4 float-right" id="PorcTotalTema">100%</span>

                                                <input type="hidden" class="form-control" id="PorcTotalTemaText" value="" />
                                            </div> 
                                        </fieldset>
                                    </div>
                                    <form method='post' action='{{ url('/')}}' id='FormPorcTema'>
                                        <div class="table-responsive">
                                            <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Porcentaje(%)</th>
                                                        <th>Opción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tr_temas">


                                                </tbody>
                                            </table>
                                        </div>

                                    </form>

                                    <div class="alert alert-warning" id="AlerTema" style="display: none;"role="alert">
                                        <strong>Warning!</strong> El porcentaje no puede ser mayor a 100%
                                    </div>
                                    <div class="swal-footer">

                                        <div class="swal-button-container">
                                            <button type="button" onclick="$.GuardarTemas();" class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade text-left" id="ModEval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
                         aria-hidden="true">
                        <div class="modal-dialog  modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success white">
                                    <h4 class="modal-title"  style="text-transform: capitalize;" id="titu_temaEva"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <fieldset id="HabPondAut"> 
                                                <div class="float-left">
                                                    <div class="btn-group btn-group-sm" tabindex="0">

                                                        <a class="btn active btn-success" id="PondManEval" onclick="$.PondManEval();" title="Ponderación Manual">Ponderación Manual</a>
                                                        <a class="btn btn-default" id="PondAutEval" onclick="$.PondAutEval();" title="Ponderación Automatica">Ponderación Automatica</a>

                                                    </div> 
                                                </div> 
                                                <div class="float-right">

                                                    <span class="font-medium-4 float-right" id="PorcTotalEval">100%</span>

                                                    <input type="hidden" class="form-control" id="PorcTotalEvalText" value="" />
                                                </div> 
                                            </fieldset>
                                        </div>

                                        <form method='post' action='{{ url('/')}}' id='FormPorcEval'>
                                            <div class="table-responsive">
                                                <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                                                    <thead>
                                                        <tr>
                                                            <th>Descripcion</th>
                                                            <th>Porcentaje(%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tr_Evaluaciones">


                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>

                                    </div>

                                    <div class="alert alert-warning" id="AlerEval" style="display: none;"role="alert">
                                        <strong>Warning!</strong> El porcentaje no puede ser mayor a 100%
                                    </div>
                                    <div class="swal-footer"><div class="swal-button-container">

                                            <button type="button"  class="btn mr-1 mb-1 btn-info" data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>

                                        </div><div class="swal-button-container">
                                            <button type="button" onclick="$.GuardarEval();" class="btn mr-1 mb-1 btn-success"><i class="fa fa-save"></i> Guardar</button>

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



{!! Form::open(['url'=>'/Calificaciones/ListUnidades'
,'id'=>'formListUnidades'])!!}
{!! Form::close() !!}

{!! Form::open(['url'=>'/Calificaciones/ListTemas'
,'id'=>'formListTemas'])!!}
{!! Form::close() !!}

{!! Form::open(['url'=>'/Calificaciones/ListEval'
,'id'=>'formListEval'])!!}
{!! Form::close() !!}




@endsection
@section('scripts')
<script>
    $(document).ready(function () {

    $("#Men_Presentacion").removeClass("active");
    $("#Men_Calificaciones").addClass("has-sub open");
    $("#Men_Calificiones_PondCali").addClass("active");
    $.extend({
    MostUnidades: function(Id){
    var contenido = '';
    var form = $("#formListUnidades");
    $("#idPer").remove();
    form.append("<input type='hidden' name='idPer' id='idPer' value='" + Id + "'>");
    var url = form.attr("action");
    var datos = form.serialize();
    $.ajax({
    type: 'POST',
            url: url,
            data: datos,
            dataType: "json",
            success: function (respuesta) {

            $("#Tit_Unidades").html("Unidades - " + respuesta.DesPeriodo);
            var porcUni = "";
            $("#tponderU").val(respuesta.tponde);
            if (respuesta.tponde == 'Manual' || respuesta.tponde == null){
            $("#PondAutUnid").removeClass("btn active btn-success");
            $("#PondAutUnid").addClass("btn btn-default");
            $("#PondManUnid").addClass("btn active btn-success");
            } else{
            $("#PondManUnid").removeClass("btn active btn-success");
            $("#PondManUnid").addClass("btn btn-default");
            $("#PondAutUnid").addClass("btn active btn-success");
            }

            if (respuesta.tponde !== null){
            $("#PorcTotalUni").html("100%");
            $("#PorcTotalUniText").val("100");
            } else{
            $("#PorcTotalUni").html("0%");
            $("#PorcTotalUniText").val("0");
            }


            $.each(respuesta.Unidades, function (i, item) {
            if (item.porcentaje == null){
            porcUni = "0";
            } else{
            porcUni = item.porcentaje;
            }


            contenido += '<tr>'
                    + '<td class="text-truncate">' + item.des_unidad + '<input type="hidden" class="form-control" name="Uni[]" id="Uni" value="' + item.id + '" /></td>'
                    + '<td class="text-truncate"><input type="text" id="PorcUnid' + item.id + '"  maxlength="3"  onkeypress="return validartxtnum(event);" onchange="$.SumPorUni(this.id);" name="PorcUnid[]" class="form-control form-control-sm" value="' + porcUni + '" id="Small" placeholder="%"></td>';
            if (respuesta.tponde !== null){
            contenido += '<td class="text-truncate"><button type="button" name="PondTema[]" id="PondTema' + item.id + '" onclick="$.MostTemas(' + item.id + ');" title="Mostrar Temas" class="btn mr-1 mb-1 btn-success btn-sm"><i class="fa fa-check"></i> </button></td>'
            } else{
            contenido += '<td class="text-truncate"><button type="button" name="PondTema[]" id="PondTema' + item.id + '" disabled onclick="$.MostTemas(' + item.id + ');" title="Mostrar Temas" class="btn mr-1 mb-1 btn-success btn-sm"><i class="fa fa-check"></i> </button></td>'

            }
            contenido += ' </tr>';
            });
            $("#tr_unidades").html(contenido);
            $("#Div_Unidades").show();
            }

    });
    },
            MostTemas: function(Id){
            var contenido = '';
            var form = $("#formListTemas");
            $("#idUni").remove();
            form.append("<input type='hidden' name='idUni' id='idUni' value='" + Id + "'>");
            var url = form.attr("action");
            var datos = form.serialize();
            $.ajax({
            type: "POST",
                    url: url,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {

                    $("#Tit_Temas").html("Temas - " + respuesta.DesUnidad);
                    var porcTem = "";
                    $("#tponderU").val(respuesta.tponde);
                    if (respuesta.tponde == 'Manual' || respuesta.tponde == null){
                    $("#PondAutTema").removeClass("btn active btn-success");
                    $("#PondAutTema").addClass("btn btn-default");
                    $("#PondManTema").addClass("btn active btn-success");
                    } else{
                    $("#PondManTema").removeClass("btn active btn-success");
                    $("#PondManTema").addClass("btn btn-default");
                    $("#PondAutTema").addClass("btn active btn-success");
                    }

                    if (respuesta.tponde !== null){
                    $("#PorcTotalTema").html("100%");
                    $("#PorcTotalTemaText").val("100");
                    } else{
                    $("#PorcTotalTema").html("0%");
                    $("#PorcTotalTemaText").val("0");
                    }

                    $.each(respuesta.Temas, function (i, item) {
                    if (item.porcentaje == null){
                    porcTem = "0";
                    } else{
                    porcTem = item.porcentaje;
                    }
                    contenido += '<tr>'
                            + '<td class="text-truncate">' + item.titu_contenido + '</td><input type="hidden" class="form-control" name="Tema[]" id="Tema" value="' + item.id + '" />'
                            + '<td class="text-truncate"><input type="text" id="PorcTema' + item.id + '"  maxlength="3"  onkeypress="return validartxtnum(event);" onchange="$.SumPorTema(this.id);" name="PorcTema[]" class="form-control form-control-sm" value="' + porcTem + '" id="Small" placeholder="%"></td>';
                    if (respuesta.tponde !== null){
                    contenido += '<td class="text-truncate"><button type="button" name="PondEval[]" id="PondEval' + item.id + '" onclick="$.MostEval(' + item.id + ');" title="Mostrar Evaluaciones" class="btn mr-1 mb-1 btn-success btn-sm"><i class="fa fa-check"></i> </button></td>'

                    } else{
                    contenido += '<td class="text-truncate"><button type="button" name="PondEval[]" id="PondEval' + item.id + '" disabled onclick="$.MostEval(' + item.id + ');" title="Mostrar Evaluaciones" class="btn mr-1 mb-1 btn-success btn-sm"><i class="fa fa-check"></i> </button></td>'


                    }
                    + ' </tr>';
                    });
                    $("#tr_temas").html(contenido);
                    $("#Div_Temas").show();
                    }

            });
            },
            MostEval: function(Id){
            var contenido = '';
            var form = $("#formListEval");
            $("#idTem").remove();
            form.append("<input type='hidden' name='idTem' id='idTem' value='" + Id + "'>");
            var url = form.attr("action");
            var datos = form.serialize();
            $.ajax({
            type: "POST",
                    url: url,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {

                    $("#titu_temaEva").html("Evaluaciones - Tema: " + respuesta.DesTema);
                    var porcTem = "";
                    $("#tponderE").val(respuesta.tponde);
                    if (respuesta.tponde == 'Manual' || respuesta.tponde == null){
                    $("#PondAutEval").removeClass("btn active btn-success");
                    $("#PondAutEval").addClass("btn btn-default");
                    $("#PondManEval").addClass("btn active btn-success");
                    } else{
                    $("#PondManEval").removeClass("btn active btn-success");
                    $("#PondManEval").addClass("btn btn-default");
                    $("#PondAutEval").addClass("btn active btn-success");
                    }

                    if (respuesta.tponde !== null){
                    $("#PorcTotalEval").html("100%");
                    $("#PorcTotalEvalText").val("100");
                    } else{
                    $("#PorcTotalEval").html("0%");
                    $("#PorcTotalEvalText").val("0");
                    }


                    $.each(respuesta.Eval, function (i, item) {
                    if (item.porcentaje == null){
                    porcTem = "";
                    } else{
                    porcTem = item.porcentaje;
                    }
                    contenido += '<tr>'
                            + '<td class="text-truncate">' + item.titulo + '</td><input type="hidden" class="form-control" name="Eval[]" id="Eval" value="' + item.id + '" />'
                            + '<td class="text-truncate"><input type="text" id="PorcEval' + item.id + '"  maxlength="3"  onkeypress="return validartxtnum(event);" onchange="$.SumPorEval(this.id);" name="PorcEval[]" class="form-control form-control-sm" value="' + porcTem + '" id="Small" placeholder="%"></td>'
                            + ' </tr>';
                    });
                    $("#tr_Evaluaciones").html(contenido);
                    $("#ModEval").modal({backdrop: 'static', keyboard: false});
                    }

            });
            },
            SumPorPer: function(id){
            var TotPorc = 0;
            var Flat = 1;
            $("input[name='PorcPer[]']").each(function (indice, elemento) {
            Valor = $(elemento).val().replace(',', '.');
            $(elemento).val(Valor);
            TotPorc = TotPorc + parseFloat(Valor);
            if (TotPorc > 100){
            Flat = 0;
            $("#AlerPer").show();
            } else{
            $("#AlerPer").hide();
            }

            });
            if (Flat == 1){
            $("#PorcTotalPer").html(TotPorc + "%");
            $("#PorcTotalPerText").val(TotPorc);
            }

            },
            SumPorUni: function(id){
            var TotPorc = 0;
            var Flat = 1;
            $("input[name='PorcUnid[]']").each(function (indice, elemento) {
            Valor = $(elemento).val().replace(',', '.');
            $(elemento).val(Valor);
            TotPorc = TotPorc + parseFloat(Valor);
            if (TotPorc > 100){
            Flat = 0;
            $("#AlerUni").show();
            } else{
            $("#AlerUni").hide();
            }

            });
            if (Flat == 1){
            $("#PorcTotalUni").html(TotPorc + "%");
            $("#PorcTotalUniText").val(TotPorc);
            }

            },
            SumPorTema: function(id){
            var TotPorc = 0;
            var Flat = 1;
            $("input[name='PorcTema[]']").each(function (indice, elemento) {
            Valor = $(elemento).val().replace(',', '.');
            $(elemento).val(Valor);
            TotPorc = TotPorc + parseFloat(Valor);
            if (TotPorc > 100){
            Flat = 0;
            $("#AlerTema").show();
            } else{
            $("#AlerTema").hide();
            }

            });
            if (Flat == 1){
            $("#PorcTotalTema").html(TotPorc + "%");
            $("#PorcTotalTemaText").val(TotPorc);
            }

            },
            SumPorEval: function(id){
            var TotPorc = 0;
            var Flat = 1;
            $("input[name='PorcEval[]']").each(function (indice, elemento) {
            Valor = $(elemento).val().replace(',', '.');
            $(elemento).val(Valor);
            TotPorc = TotPorc + parseFloat(Valor);
            if (TotPorc > 100){
            Flat = 0;
            $("#AlerEval").show();
            } else{
            $("#AlerEval").hide();
            }

            });
            if (Flat == 1){
            $("#PorcTotalEval").html(TotPorc + "%");
            $("#PorcTotalTemaEval").val(TotPorc);
            }

            },
            PondAutPer: function(){
            var TotEl = $("input[name='PorcPer[]']").length;
            var Distpor = 0;
            Distpor = 100 / TotEl;
            $("input[name='PorcPer[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PorcTotalPer").html("100%");
            $("#PorcTotalPerText").val("100");
            $("#PondMan").removeClass("btn active btn-success");
            $("#PondMan").addClass("btn btn-default");
            $("#PondAut").addClass("btn active btn-success");
            $("#tponder").val("Automatica");
            },
            PondManPer: function(){

            var Distpor = 0;
            $("input[name='PorcPer[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PondAut").removeClass("btn active btn-success");
            $("#PondAut").addClass("btn btn-default");
            $("#PondMan").addClass("btn active btn-success");
            $("#PorcTotalPer").html("0%");
            $("#PorcTotalPerText").val("0");
            $("#tponder").val("Manual");
            },
            PondAutUnd: function(){
            var TotEl = $("input[name='PorcUnid[]']").length;
            var Distpor = 0;
            Distpor = 100 / TotEl;
            $("input[name='PorcUnid[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PorcTotalUni").html("100%");
            $("#PorcTotalUniText").val("100");
            $("#PondManUnid").removeClass("btn active btn-success");
            $("#PondManUnid").addClass("btn btn-default");
            $("#PondAutUnid").addClass("btn active btn-success");
            $("#tponderU").val("Automatica");
            },
            PondManUnd: function(){

            var Distpor = 0;
            $("input[name='PorcUnid[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PondAutUnid").removeClass("btn active btn-success");
            $("#PondAutUnid").addClass("btn btn-default");
            $("#PondManUnid").addClass("btn active btn-success");
            $("#PorcTotalUni").html("0%");
            $("#PorcTotalUniText").val("0");
            $("#tponderU").val("Manual");
            },
            PondAutTema: function(){
            var TotEl = $("input[name='PorcTema[]']").length;
            var Distpor = 0;
            Distpor = 100 / TotEl;
            $("input[name='PorcTema[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PorcTotalTema").html("100%");
            $("#PorcTotalTemaText").val("100");
            $("#PondManTema").removeClass("btn active btn-success");
            $("#PondManTema").addClass("btn btn-default");
            $("#PondAutTema").addClass("btn active btn-success");
            $("#tponderT").val("Automatica");
            },
            PondManTema: function(){

            var Distpor = 0;
            $("input[name='PorcTema[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PondAutTema").removeClass("btn active btn-success");
            $("#PondAutTema").addClass("btn btn-default");
            $("#PondManTema").addClass("btn active btn-success");
            $("#PorcTotalTema").html("0%");
            $("#PorcTotalTemaText").val("0");
            $("#tponderT").val("Manual");
            },
            PondAutEval: function(){
            var TotEl = $("input[name='PorcEval[]']").length;
            var Distpor = 0;
            Distpor = 100 / TotEl;
            $("input[name='PorcEval[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PorcTotalEval").html("100%");
            $("#PorcTotalEvalText").val("100");
            $("#PondManEval").removeClass("btn active btn-success");
            $("#PondManEval").addClass("btn btn-default");
            $("#PondAutEval").addClass("btn active btn-success");
            $("#tponderE").val("Automatica");
            },
            PondManTema: function(){

            var Distpor = 0;
            $("input[name='PorcEval[]']").each(function (indice, elemento) {
            $(elemento).val(Distpor);
            });
            $("#PondAutEval").removeClass("btn active btn-success");
            $("#PondAutEval").addClass("btn btn-default");
            $("#PondManEval").addClass("btn active btn-success");
            $("#PorcTotalEval").html("0%");
            $("#PorcTotalEvalText").val("0");
            $("#tponderE").val("Manual");
            },
            GuardarPeriodo: function(){
            var token = $("#token").val();
            var tponder = $("#tponder").val();
            var token = $("#token").val();
            var form = $("#FormPorcPer");
            $("#_token").remove();
            $("#t_pondera").remove();
            form.append("<input type='hidden' id='t_pondera' name='t_pondera'  value='" + tponder + "'>");
            form.append("<input type='hidden' id='_token' name='_token'  value='" + token + "'>");
            var url = form.attr("action");
            var datos = form.serialize();
            $.ajax({
            type: "POST",
                    url: url + "/Guardar/PorcPer",
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                    var opc = respuesta.Opc;
                    if (opc === "1"){
                    swal({
                    title: "Ponderación de Periodos",
                            text: respuesta.Mensaje,
                            icon: "success",
                            button: "Aceptar",
                    });
                    $("input[name='Per[]']").each(function (indice, elemento) {
                    IdPer = $(elemento).val();
                    $("#PondUni" + IdPer).prop('disabled', false);
                    });
                    } else{
                    swal({
                    title: "Ponderación de Periodos",
                            text: respuesta.Mensaje,
                            icon: "warning",
                            button: "Aceptar",
                    });
                    }



                    },
                    error: function () {
                    mensaje = "La Ponderación no pudo ser Guardada",
                            swal({
                            title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                            });
                    }
            });
            },
            GuardarUnidades: function(){
            var token = $("#token").val();
            var tponder = $("#tponderU").val();
            var idPer = $("#idPer").val();
            var token = $("#token").val();
            var form = $("#FormPorcUnid");
            $("#_token").remove();
            $("#t_ponderaUni").remove();
            $("#idPeriodo").remove();
            form.append("<input type='hidden' id='t_ponderaUni' name='t_ponderaUni'  value='" + tponder + "'>");
            form.append("<input type='hidden' id='_token' name='_token'  value='" + token + "'>");
            form.append("<input type='hidden' id='idPeriodo' name='idPeriodo'  value='" + idPer + "'>");
            var url = form.attr("action");
            var datos = form.serialize();
            $.ajax({
            type: "POST",
                    url: url + "/Guardar/PorcUni",
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                    var opc = respuesta.Opc;
                    if (opc === "1"){
                    swal({
                    title: "Ponderación de Unidades",
                            text: respuesta.Mensaje,
                            icon: "success",
                            button: "Aceptar",
                    });
                    $("input[name='Uni[]']").each(function (indice, elemento) {
                    IdPer = $(elemento).val();
                    $("#PondTema" + IdPer).prop('disabled', false);
                    });
                    } else{
                    swal({
                    title: "Ponderación de Unidades",
                            text: respuesta.Mensaje,
                            icon: "warning",
                            button: "Aceptar",
                    });
                    }



                    },
                    error: function () {
                    mensaje = "La Ponderación no pudo ser Guardada",
                            swal({
                            title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                            });
                    }
            });
            },
            GuardarTemas: function(){
            var token = $("#token").val();
            var tponder = $("#tponderT").val();
            var idUnidad = $("#idUni").val();
            var token = $("#token").val();
            var form = $("#FormPorcTema");
            $("#_token").remove();
            $("#t_ponderaTema").remove();
            $("#idUnida").remove();
            form.append("<input type='hidden' id='t_ponderaTema' name='t_ponderaTema'  value='" + tponder + "'>");
            form.append("<input type='hidden' id='_token' name='_token'  value='" + token + "'>");
            form.append("<input type='hidden' id='idUnida' name='idUnida'  value='" + idUnidad + "'>");
            var url = form.attr("action");
            var datos = form.serialize();
            $.ajax({
            type: "POST",
                    url: url + "/Guardar/PorcTema",
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                    var opc = respuesta.Opc;
                    if (opc === "1"){
                    swal({
                    title: "Ponderación de Temas",
                            text: respuesta.Mensaje,
                            icon: "success",
                            button: "Aceptar",
                    });
                    $("input[name='Tema[]']").each(function (indice, elemento) {
                    IdPer = $(elemento).val();
                    $("#PondEval" + IdPer).prop('disabled', false);
                    });
                    } else{
                    swal({
                    title: "Ponderación de Temas",
                            text: respuesta.Mensaje,
                            icon: "warning",
                            button: "Aceptar",
                    });
                    }



                    },
                    error: function () {
                    mensaje = "La Ponderación no pudo ser Guardada",
                            swal({
                            title: "",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                            });
                    }
            });
            },
            GuardarEval: function(){
            var token = $("#token").val();
            var tponder = $("#tponderE").val();
            var idTema = $("#idTem").val();
            var token = $("#token").val();
            var form = $("#FormPorcEval");
            $("#_token").remove();
            $("#t_ponderaTema").remove();
            $("#idUnida").remove();
            form.append("<input type='hidden' id='t_ponderaEval' name='t_ponderaEval'  value='" + tponder + "'>");
            form.append("<input type='hidden' id='_token' name='_token'  value='" + token + "'>");
            form.append("<input type='hidden' id='idTema' name='idTema'  value='" + idTema + "'>");
            var url = form.attr("action");
            var datos = form.serialize();
            $.ajax({
            type: "POST",
                    url: url + "/Guardar/PorcEval",
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                    var opc = respuesta.Opc;
                    if (opc === "1"){
                    swal({
                    title: "Ponderación de Evaluaciones",
                            text: respuesta.Mensaje,
                            icon: "success",
                            button: "Aceptar",
                    });
                    } else{
                    swal({
                    title: "Ponderación de Evaluaciones",
                            text: respuesta.Mensaje,
                            icon: "warning",
                            button: "Aceptar",
                    });
                    }
                    },
                    error: function () {
                    mensaje = "La Ponderación no pudo ser Guardada",
                            swal({
                            title: "Ponderación de Evaluaciones",
                                    text: mensaje,
                                    icon: "warning",
                                    button: "Aceptar",
                            });
                    }
            });
            }
    });
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
</script>
@endsection

