@extends('Plantilla.Principal')
@section('title', 'Datos Estaditicos')
@section('Contenido')

    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">


    <input type="hidden" data-id='id-dat' id="dat" data-ruta="{{ asset('/app-assets/images/Colegios') }}" />


    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Parametros Generales</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Parametros Generales
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">

        <!--/stats-->
        <!-- Audience by country & users visit-->
        <div class="row match-height">

            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title">Información de la Intitución</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="card-content">
                        <form method="post" enctype="multipart/form-data" files="true"
                            action="{{ url('/') }}/ConfParametros/ActualizarInformacionColeg" id="DatosColegio">
                            <input type="hidden" name="Colegio" id="Colegio" value="{{ Session::get('IdColegio') }}">
                            <input type="hidden" id="escudocoleg" name="escudocoleg" value="" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12" id='rowtit'>
                                        <div class="form-group">
                                            <label class="form-label" for="titu_contenido">Nombre de la Institución:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="porc_modulo">Habilitar Contraseña PEDIGITAL
                                                KIDS:</label>
                                            <select class="form-control select2" style="width: 100%;"
                                                data-placeholder="Seleccione" id="HabCont" name="HabCont">
                                                <option value="">Seleccionar</option>
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="porc_modulo">Habilitar Número de Grupos por Grado:</label>
                                            <select class="form-control select2" style="width: 100%;"
                                                data-placeholder="Seleccione" id="CantGrup" name="CantGrup">
                                                <option value="">Seleccionar</option>
                                                <option value="1">1 Grupo</option>
                                                <option value="2">2 Grupos</option>
                                                <option value="3">3 Grupos</option>
                                                <option value="4">4 Grupos</option>
                                                <option value="5">5 Grupos</option>
                                                <option value="6">6 Grupos</option>
                                                <option value="7">7 Grupos</option>
                                                <option value="8">8 Grupos</option>
                                                <option value="9">9 Grupos</option>
                                                <option value="10">10 Grupos</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-12">

                                        <div class="form-group" id="cont_fotos" style="display: none;">
                                            <label class="form-label " for="imagen">Cargar Escudo de la
                                                Institución:</label>
                                            <input type="file" name="imagen" />
                                        </div>

                                        <div class="form-group" id="did_verf" style="display: none;">
                                            <label class="form-label " for="imagen">Cargar Escudo de la
                                                Institución:</label>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" onclick="$.VerFotEst();"
                                                    class="btn btn-success"><i class="fa fa-search"></i> Ver
                                                    Foto</button>

                                                <button type="button" onclick="$.CambFotEst();"
                                                    class="btn btn-warning"><i class="fa fa-refresh"></i> Cambiar
                                                    Foto</button>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="text-right">
                                    <button type="button" onclick="$.ActualizarDatos();" class="btn btn-info">Actualizar
                                        <i class="fa fa-refresh position-right"></i></button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade text-left" id="CargFoto" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel17" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="titu_tema"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">


                            <div id='cont_archi' style="height: 400px; overflow: auto;">
                                <div id="div_arc">

                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                                data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title">Restablecer Información</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="card-content">
                        <form method="post" action="{{ url('/') }}/ConfParametros/RestablecerInf" id="Restablacer">
                            <div class="card-body">
                                <p>Para iniciar un nuevo año se debe relizar una Restauración de la base de datos,
                                    Seleccione la información a restablecer.
                                </p>
                                <div class="row icheck_minimal skin">
                                    <div class="col-md-6 col-sm-12">
                                        <fieldset>
                                            <input type="checkbox" name="check_Calif" id="input-5">
                                            <label for="input-5">Calificaciones</label>
                                        </fieldset>
                                        <fieldset>
                                            <input type="checkbox" name="check_Asist" id="input-6">
                                            <label for="input-6">Asistencia</label>
                                        </fieldset>
                                        <fieldset>
                                            <input type="checkbox" name="check_foros" id="input-7">
                                            <label for="input-7">Foros</label>
                                        </fieldset>
                                        <fieldset>
                                            <input type="checkbox" name="check_Chats" id="input-8">
                                            <label for="input-8">Chats</label>
                                        </fieldset>
                                        <fieldset>
                                            <input type="checkbox" name="check_Zona" id="input-9">
                                            <label for="input-9">Zona LiBre</label>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" onclick="$.Restablecer();" class="btn btn-danger">Restablecer
                                        <i class="fa fa-refresh position-right"></i></button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    {!! Form::open(['url' => '/ConfParametros/InfGeneralColeg', 'id' => 'formAuxiliarCargInf']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_configuracion").addClass("active open");

            $.extend({
                Restablecer: function() {

                    mensaje = "Al Restablecer estos items, la información existente de cada uno de los seleccionados seran borrados de la base de datos. ¿Desea Restablecer los items Seleccionados?";

                    Swal.fire({
                        title: 'Gestionar Paramatros',
                        text: mensaje,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            var form = $("#Restablacer");
                            var url = form.attr("action");
                            var token = $("#token").val();
                            $("#idtoken").remove();
                            form.append(
                                "<input type='hidden' id='idtoken' name='_token'  value='" +
                                token +
                                "'>");

                            var datos = form.serialize();

                            $.ajax({
                                type: "POST",
                                url: url,
                                data: datos,
                                dataType: "json",
                                async: false,
                                success: function(respuesta) {
                                    if (respuesta.flag === "si") {
                                        Swal.fire({
                                            title: "Gestionar Parametros",
                                            text: "Los items selecionados fueron restablecidos Exitosamente.",
                                            icon: "success",
                                            button: "Aceptar",
                                        });

                                    }
                                },
                                error: function() {
                                    mensaje =
                                        "No se pudo Restablcer la Información";
                                    Swal.fire(
                                        'Gestionar Parametros',
                                        mensaje,
                                        'success'
                                    )
                                }
                            });

                        }
                    });


                },
                ActualizarDatos: function() {
                    var form = $("#DatosColegio");
                    var url = form.attr("action");
                    var token = $("#token").val();
                    $("#idtoken").remove();

                    form.append("<input type='hidden' id='idtoken' name='_token'  value='" + token +
                        "'>");
                        

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData($('#DatosColegio')[0]),
                        async: false,
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {

                            if (respuesta.Resp === "Ok") {
                                Swal.fire({
                                    title: "Gestionar Parametros",
                                    text: "La Información fue Actualizadad Correctamente.",
                                    icon: "success",
                                    button: "Aceptar",
                                });

                                $("#escudocoleg").val(respuesta.escudo);
                                $("#cont_fotos").hide();
                                $("#did_verf").show();
                            }
                        }
                    });



                },
                CargarInform: function() {
                    var form = $("#formAuxiliarCargInf");
                    var url = form.attr("action");
                    var token = $("#token").val();
                    var Colegio = $("#Colegio").val();
                    $("#idtoken").remove();
                    $("#idColegio").remove();

                    form.append("<input type='hidden' id='idtoken' name='_token'  value='" + token +
                        "'>");
                    form.append("<input type='hidden' id='idColeg' name='idColegio'  value='" +
                        Colegio + "'>");


                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {

                            $("#nombre").val(respuesta.nombre);

                            $('#CantGrup').val(respuesta.cant_grupos)
                                .trigger('change.select2');

                            $('#HabCont').val(respuesta.habpasw)
                                .trigger('change.select2');
                              
                          
                                $("#escudocoleg").val(respuesta.escudo);
                            if (respuesta.escudo !== "") {
                                $("#cont_fotos").hide();
                                $("#did_verf").show();

                            }
                        }
                    });
                },
                VerFotEst: function() {
                    $("#CargFoto").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#div_arc").html(
                        '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                    );
                    jQuery('#embed_arch').attr('src', $('#dat').data("ruta") + "/" + $('#escudocoleg')
                        .val());
                },
                CambFotEst: function() {
                    $("#did_verf").hide();
                    $("#cont_fotos").show();
                },
            });


            $.CargarInform();
        });
    </script>
@endsection
