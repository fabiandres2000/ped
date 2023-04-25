@extends('Plantilla.Principal')
@section('title', 'Editar Asignatura')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Asignatura
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
                            <h4 class="card-title">Editar Asignatura.</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-dismissible show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">×</button>
                                                <h6 style="font: 16px EXODO;">Por favor corrige los siguientes errores:</h6>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <strong style="font: 15px EXODO;">
                                                            <li>{{ $error }}</li>
                                                        </strong>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <p class="px-1"></p>

                                <!--begin::Form-->
                                @include('ModuloE.FormAsignatura', [
                                    'url' => '/ModuloE/ModificarAsignaturas/' . $Asig->id,
                                    'method' => 'put',
                                ])
                                <!--end::Form-->
                                <p class="px-1"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>




 
    {!! Form::open(['url' => '/ModuloE/CargcompetenciasAsig', 'id' => 'formAuxiliarCompe']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#Men_Inicio").removeClass("active");
            $("#Men_Modulos_E").addClass("has-sub open");
            $("#Men_ModulosE_addAdig").addClass("active");

            $.extend({

                VerFotEst: function() {
                    $("#CargImg").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#div_arc").html(
                        '<embed src="" type="application/pdf" id="embed_arch" width="100%" height="600px" />'
                    );
                    jQuery('#embed_arch').attr('src', $('#dat').data("ruta") + "/" + $('#img_asig')
                        .val());
                },
                CambFotEst: function() {
                    $("#id_verf").hide();
                    $("#id_file").show();
                },
                GuardarAsig: function() {
                    if ($('#area').val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione el Área.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    if ($('#nombre').val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Ingrese el Nombre de la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#grado').val() === "") {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "Seleccione el Grado.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (!$('#image').val() && $("#img_asig").val() == "") {
                        mensaje = "Debe Seleccionar una imagen";
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: mensaje,
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var totcompe = $(".btnQuitarCompe").length;

                    if (totcompe < 1) {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "No se ha agregado ninguna Competencia.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    var totcompe = $(".btnQuitarCompo").length;

                    if (totcompe < 1) {
                        Swal.fire({
                            title: "Gestionar Módulo E",
                            text: "No se ha agregado ningun Componente.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    Swal.fire({
                        title: 'Espere Por Favor',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        background: '#FFFFFF',
                        showConfirmButton: false,
                        onOpen: () => {
                            Swal.showLoading();
                        }
                    })

                    Swal.showLoading();
                    $("#formAsig").submit();
                },
                DelComp: function(id_fila) {
                    $('#tr_Compe_' + id_fila).remove();
                },
                DelCompo: function(id_fila) {
                    $('#tr_Compo_' + id_fila).remove();
                },
                CargCompeCompo: function(Grado) {
                    var form = $("#formAuxiliarCompe");
                    $("#Grado").remove();
                    form.append("<input type='hidden' name='Grado' id='Grado' value='" + Grado + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#competencia").html(respuesta.select_compe);
                            $("#componente").html(respuesta.select_compo);

                            $('#competencia').prop('disabled', false);
                            $('#componente').prop('disabled', false);
                        }

                    });
                },
                CargarInfAsig: function(){
                    var form = $("#formAuxiliarCompe");

                    $("#Grado").remove();
                    form.append("<input type='hidden' name='Grado' id='Grado' value='" + $("#grado").val() + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#competencia").html(respuesta.select_compe);
                            $("#componente").html(respuesta.select_compo);

                            $('#competencia').prop('disabled', false);
                            $('#componente').prop('disabled', false);
                        }

                    });

                }



            });

            $.CargarInfAsig();

            $("#AddCompe").on({
                click: function(e) {
                    e.preventDefault();
                    var IdComp = $("#competencia").val();
                    var ConsComp = $("#ConsComp").val();
                    var flag = "ok";
                    var TxtCom = $('select[name="competencia"] option:selected').text();

                    if (IdComp === "") {
                        Swal.fire('Error!', 'Seleccione una Competencia...', 'warning');
                        return;
                    }

                    $('#tr_compe input').each(function() {
                        if ($(this).val() === IdComp) {
                            Swal.fire('Error!', 'Esta Competencia ya esta Agregada...', 'warning');
                            flag = "no";
                        }
                    });

                    if (flag === "no") {
                        return;
                    }

                    var style = 'text-transform: uppercase;background-color:white;';
                    var clase = 'text-truncate';
                    var campo = "";
                    campo += "<tr id='trCompe_" + ConsComp + "'>";
                    campo += "<td class='" + clase + "'>";
                    campo += TxtCom;
                    campo += "</td>";
                    campo += "<td class='" + clase + "'>";
                    campo += "<a onclick='$.DelComp(" + ConsComp +
                        ")' class='btn btn-danger btn-sm btnQuitarCompe text-white'  title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "<input type='hidden' id='txtcomp" + ConsComp +
                        "' name='txtComp[]'   readonly style='" + style + "' value='" + IdComp + "'>";
                    campo += "</td>";
                    campo += "</tr>";
                    $("#tr_compe").append(campo);
                    $('#competencia').val('').trigger('change.select2');
                    $("#ConsComp").val(parseFloat(ConsComp) + 1);

                    //                }

                }
            });


            //======================EVENTO AGREGAR COMPONENTES=======================\\

            $("#AddCompo").on({
                click: function(e) {
                    e.preventDefault();
                    var IdCompo = $("#componente").val();
                    var ConsCompo = $("#ConsComponente").val();
                    var flag = "ok";
                    var TxtCom = $('select[name="componente"] option:selected').text();

                    if (IdCompo === "") {
                        Swal.fire('Error!', 'Seleccione un Componente...', 'warning');
                        return;
                    }

                    $('#tr_compo input').each(function() {
                        if ($(this).val() === IdCompo) {
                            Swal.fire('Error!', 'Este Componente ya esta Agregado...', 'warning');
                            flag = "no";
                        }
                    });

                    if (flag === "no") {
                        return;
                    }

                    var style = 'text-transform: uppercase;background-color:white;';
                    var clase = 'text-truncate';
                    var campo = "";
                    campo += "<tr id='trCompo_" + ConsCompo + "'>";
                    campo += "<td class='" + clase + "'>";
                    campo += TxtCom;
                    campo += "</td>";
                    campo += "<td class='" + clase + "'>";
                    campo += "<a onclick='$.DelCompo(" + ConsCompo +
                        ")' class='btn btn-danger btn-sm btnQuitarCompo text-white'  title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "<input type='hidden' id='txtcomponente" + ConsCompo +
                        "' name='txtComponentes[]' readonly style='" + style + "' value='" + IdCompo +
                        "'>";
                    campo += "</td>";
                    campo += "</tr>";
                    $("#tr_compo").append(campo);
                    $('#componente').val('').trigger('change.select2');
                    $("#ConsComponente").val(parseFloat(ConsCompo) + 1);


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
