@extends('Plantilla.Principal')
@section('title', 'Tablero')
@section('Contenido')

    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <div class="content-header row" id="cabe_asig">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0" id="Titulo">Administrar Parametros</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item" id='li_cursos'><a href="#">Configutación de Parametros</a>
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>
    <form method="post" action="{{ url('/') }}/Parametros/GuardarParametros" id="Parametros"
        class="number-tab-stepsPreg wizard-circle">
        <div class="content-body">
            <div class="row" id="Div_Asig">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">Institución:</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <select class="form-control select2 pb-2" data-placeholder="Seleccione..."
                                    name="institucion" id="institucion">
                                    <option value="">Seleccione el Area</option>
                                    @foreach ($Colegios as $Col)
                                        <option value="{{ $Col->id }}">{{ $Col->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">URL PEDIGITAL:</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <input type="text" class="form-control" id="url-pedigital" name="url-pedigital"
                                    value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">URL PEDIGITAL-KID:</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <input type="text" class="form-control" id="url-pedigital-kid" name="url-pedigital-kid"
                                    value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title"><label style="font-weight: bold"><input type="checkbox"
                                        name="check_All_Asig" id="check_All_Asig" value="All"> PERMISOS
                                    ASIGNATURAS</label></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>

                        </div>
                        <div class="card-content">
                            <div class="card-body" id="per_asi">
                                @foreach ($Asignatura as $Asig)
                                    <fieldset class="right-checkbox">
                                        <label>
                                            <input type="checkbox" class="check_asig" name="check_asignaturas[]"
                                                value="{!! $Asig->id !!}">
                                            {!! $Asig->nombre !!}
                                        </label>
                                    </fieldset>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title"><label style="font-weight: bold"><input type="checkbox"
                                        name="check_All_Mod" id="check_All_Mod" value="All"> PERMISOS
                                    MÓDULOS TRANSVERSALES</label></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>

                        </div>
                        <div class="card-content">
                            <div class="card-body" id="per_mod">

                                @foreach ($Modulos as $Modu)
                                    <fieldset class="right-checkbox">
                                        <label>
                                            <input type="checkbox" class="check_modu" name="check_modulos[]"
                                                value="{!! $Modu->id !!}"> {!! $Modu->nombre !!}
                                        </label>
                                    </fieldset>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title"><label style="font-weight: bold"><input type="checkbox"
                                        name="check_All_Otros" id="check_All_Otros" value="All"> OTROS
                                    PERMISOS</label></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>

                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <fieldset class="right-checkbox">
                                    <label>
                                        <input type="checkbox" class="check_otros" id="check_Asig" name="check_otros[]"
                                            value="Asig">
                                        CONTENIDO ASIGNATURAS
                                    </label>
                                </fieldset>

                                <fieldset class="right-checkbox">
                                    <label>
                                        <input type="checkbox" class="check_otros" id="check_Modu" name="check_otros[]"
                                            value="Modu">
                                        CONTENIDO MÓDULOS TRANSVERSALES
                                    </label>
                                </fieldset>

                                <fieldset class="right-checkbox">
                                    <label>
                                        <input type="checkbox" class="check_otros" id="check_me" name="check_otros[]"
                                            value="ModE">
                                        MÓDULO DE ENTRENAMIENTO (MÓDULO
                                        E)
                                    </label>
                                </fieldset>
                                <fieldset class="right-checkbox">
                                    <label>
                                        <input type="checkbox" class="check_otros" id="check_la" name="check_otros[]"
                                            value="Labo">
                                        MÓDULO DE LABORATORIO
                                    </label>
                                </fieldset>
                                <fieldset class="right-checkbox">
                                    <label>
                                        <input type="checkbox" class="check_otros" id="check_zl" name="check_otros[]"
                                            value="ZonL">
                                        ZONA LIBRE
                                    </label>
                                </fieldset>
                                <fieldset class="right-checkbox">
                                    <label>
                                        <input type="checkbox" class="check_otros" id="check_zl" name="check_otros[]"
                                            value="ModJ">
                                        MÓDULO JEA
                                    </label>
                                </fieldset>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions right">
                <div class="row ">
                    <div class="col-md-12 col-lg-12 ">
                        <div class="btn-list">
                            <button class="btn btn-outline-primary" href="#" title="Guardar" onclick="$.Guardar();"
                                type="button">
                                <i class="fa fa-save"></i> Guardar Parametros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>



    {!! Form::open(['url' => '/Parametros/CargarParametros', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}


@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            ///////////SELECCIÓN ASIGNATURAS
            $("#check_All_Asig").on("click", function() {
                $(".check_asig").prop("checked", this.checked);
            });

            $(".check_asig").on("click", function() {
                if ($(".check_asig").length == $(".check_asig:checked").length) {
                    $("#check_All_Asig").prop("checked", true);
                } else {
                    $("#check_All_Asig").prop("checked", false);
                }
            });

            ///////////SELECCIÓN MÓDULOS
            $("#check_All_Mod").on("click", function() {
                $(".check_modu").prop("checked", this.checked);
            });

            $(".check_modu").on("click", function() {
                if ($(".check_modu").length == $(".check_modu:checked").length) {
                    $("#check_All_Mod").prop("checked", true);
                } else {
                    $("#check_All_Mod").prop("checked", false);
                }
            });

            ///////////SELECCIÓN OTROS
            $("#check_All_Otros").on("click", function() {
                $(".check_otros").prop("checked", this.checked);
            });

            $(".check_otros").on("click", function() {
                if ($(".check_otros").length == $(".check_otros:checked").length) {
                    $("#check_All_Otros").prop("checked", true);
                } else {
                    $("#check_All_Otros").prop("checked", false);
                }
            });



            $("#Men_Tablero").addClass("active");
            $.extend({
                Guardar: function(id) {


                    if ($("#institucion").val() === '') {
                        Swal.fire('Parametrización Plataforma', 'Seleccione la Institución...',
                            'warning');
                        return false;

                    }

                    var form = $("#Parametros");
                    var url = form.attr("action");
                    var token = $("#token").val();

                    form.append("<input type='hidden' id='idtoken' name='_token'  value='" + token +
                        "'>");
                    var contenido = '';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData($('#Parametros')[0]),
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function(respuesta) {

                            if (respuesta.respu === "OK") {
                                Swal.fire({
                                    title: "Parametrización Plataforma",
                                    text: 'Operación Realizada Exitosamente',
                                    icon: "success",
                                    button: "Aceptar"
                                });
                            }

                        }
                    });

                },
                CargarParametros: function() {
                    var form = $("#formAuxiliar");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {

                            var Institucion = "";
                            var ModE = 'no';
                            var Labo = 'no';
                            var ZonL = 'no';
                            var Asig = 'no';
                            var Modu = 'no';


                            $.each(respuesta.Permiso, function(i, item) {
                                if (item.plataf === "PED") {
                                    $("#url-pedigital").val(item.url);
                                } else {
                                    $("#url-pedigital-kid").val(item.url);
                                }

                                Institucion = item.colegio;
                                ModE = item.mod_entre;
                                Labo = item.mod_labo;
                                ZonL = item.mod_zona;
                                Asig = item.mod_asig;
                                Modu = item.mod_modu;

                            });
                            $('#institucion').val(Institucion).trigger('change.select2');
                            ModE == "si" ? $("#check_me").prop("checked", true) : $(
                                "#check_me").prop("checked", false);
                            Labo == "si" ? $("#check_la").prop("checked", true) : $(
                                "#check_la").prop("checked", false);
                            ZonL == "si" ? $("#check_zl").prop("checked", true) : $(
                                "#check_zl").prop("checked", false);
                            Asig == "si" ? $("#check_Asig").prop("checked", true) : $(
                                "#check_Asig").prop("checked", false);
                            Modu == "si" ? $("#check_Modu").prop("checked", true) : $(
                                "#check_Modu").prop("checked", false);

                            var Asignaturas = "";
                            var Modulos = "";

                            var check = "";
                            var flag = "on";

                            $.each(respuesta.Asignatura, function(i, item) {
                                item.estado == "ACTIVO" ? check = "checked" :
                                    check = "";
                                Asignaturas += '<fieldset class="right-checkbox">' +
                                    '<label>' +
                                    '    <input type="checkbox" class="check_asig" ' +
                                    check + ' name="check_asignaturas[]"' +
                                    '        value="' + item.id + '"> ' + item
                                    .nombre + '</label>' +
                                    '</fieldset>';

                            });

                            $("#per_asi").html(Asignaturas);


                            $.each(respuesta.Modulos, function(i, item) {
                                item.estado == "ACTIVO" ? check = "checked" :
                                    check = "";
                                Modulos += '<fieldset class="right-checkbox">' +
                                    '<label>' +
                                    '    <input type="checkbox" class="check_modu" ' +
                                    check + ' name="check_modulos[]"' +
                                    '        value="' + item.id + '"> ' + item
                                    .nombre + '</label>' +
                                    '</fieldset>';

                            });

                            $("#per_mod").html(Modulos);


                        }
                    });
                }
            });


            $.CargarParametros();
        });
    </script>
@endsection
