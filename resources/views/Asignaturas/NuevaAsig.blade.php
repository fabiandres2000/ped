@extends('Plantilla.Principal')
@section('title', 'Crear Curso')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Crear Grados y Cursos
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
                            <h4 class="card-title">Crear Grado.</h4>
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
                                @include('Asignaturas.FormAsignatura', [
                                    'url' => '/Asignaturas/guardarAsignatura',
                                    'method' => 'post',
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


    {!! Form::open(['url' => '/cambiar/Asignaturas', 'id' => 'formAuxiliarAsig']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#Men_Inicio").removeClass("active");
            $("#Men_Asignaturas").addClass("has-sub open");
            $("#Men_Asignaturas_addAdig").addClass("active");

            $.extend({
                DelPer: function(id_fila) {
                    $('#tr_' + id_fila).remove();
                },
                EditPer: function(id) {
                    $('#periodo_modulo').val($("#txtperi" + id).val()).trigger('change.select2');
                    $("#porc_avance").val($("#txtporc" + id).val());
                    $("#per_sel").val(id);

                    $('#periodo_modulo').prop('disabled', true);
                    $("#AddPeriodo").hide();
                    $("#UpdatePeriodo").show();

                },
                UpdatPer: function() {
                    var id = $("#per_sel").val();
                    $('#periodo_modulo').prop('disabled', false);

                    $("#td_porc" + id).html($("#porc_avance").val());
                    $("#txtporc" + id).val($("#porc_avance").val());
                    $('#periodo_modulo').val('').trigger('change.select2');
                    $("#porc_avance").val("");
                    $("#UpdatePeriodo").hide();
                    $("#AddPeriodo").show();
                },
                CargAsignaturas: function(id) {
                    var form = $("#formAuxiliarAsig");
                    $("#idArea").remove();
                    form.append("<input type='hidden' name='id' id='idArea' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#nombre").html(respuesta.select_Asignaturas);
                        }
                    });
                },
                Guardar: function() {

                    if ($('#area').val() === "") {
                        Swal.fire({
                            title: "Gestionar Grados",
                            text: "Seleccione el Área de la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#nombre').val() === "") {
                        Swal.fire({
                            title: "Gestionar Grados",
                            text: "Seleccione la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if ($('#grado_modulo').val() === "") {
                        Swal.fire({
                            title: "Gestionar  Grados",
                            text: "Seleccione el Grado.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    if ($('#grupos').val().length < 1) {
                        Swal.fire({
                            title: "Gestionar Grados",
                            text: "Seleccione el Grupo.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    if (!$('#imagen').val()) {
                        Swal.fire({
                            title: "Gestionar Grados",
                            text: "Seleccione la Imagen a Subir.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }

                    var totper = $(".btnQuitarPer").length;

                    if (totper < 1) {
                        Swal.fire({
                            title: "Gestionar Grados",
                            text: "No se ha Ingresado ningun Periodo.",
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
                    Swal.showLoading()

                    $("#formAsig").submit();


                }
            });

            //======================EVENTO AGREGAR PERIODOS=======================\\
            $("#AddPeriodo").on({
                click: function(e) {
                    e.preventDefault();
                    var perio = $("#periodo_modulo").val();
                    var porc = $("#porc_avance").val();
                    var ConsPer = $("#ConsPer").val();
                    var flag = "ok";
                    var PorcTot = 0;
                    if (perio === "") {
                        Swal.fire('Gestionar Grados!', 'Seleccione un Periodo...', 'warning');
                        return false;
                    }
                    if (porc === "") {
                        Swal.fire('Gestionar Grados!', 'Ingrese el Porcentaje...', 'warning');
                        return false;
                    }

                    $('#tr_periodos input').each(function() {
                        if ($(this).val() === perio) {
                            Swal.fire('Gestionar Grados!',
                                'Este Periodo ya ha sido Asignado...',
                                'warning');
                            flag = "no";
                        }
                    });


                    $('.PorcPer').each(function() {
                        PorcTot = PorcTot + parseInt($(this).val());
                    });

                    PorcTot = PorcTot + parseInt(porc);

                    if (PorcTot > 100) {
                        Swal.fire('Gestionar Grados!',
                            'El porcentaje no puede ser mayor a 100%, Verifique...',
                            'warning');
                        return;

                    }



                    if (flag === "no") {
                        return;
                    }

                    var style = 'text-transform: uppercase;background-color:white;';
                    var clase = 'text-truncate';
                    var campo = "";
                    campo += "<tr id='tr_" + ConsPer + "'>";
                    campo += "<td class='" + clase + "'>";
                    campo += perio;
                    campo += "</td>";
                    campo += "<td class='" + clase + "' id='td_porc" + ConsPer + "'>";
                    campo += porc;
                    campo += "</td>";
                    campo += "<td class='" + clase + "'>";
                    campo += "<a onclick='$.EditPer(" + ConsPer +
                        ")' class='btn btn-info btn-sm btnEditar text-white '  title='Editar'><i class='fa fa-edit font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "<a onclick='$.DelPer(" + ConsPer +
                        ")' class='btn btn-danger btn-sm btnQuitarPer text-white'  title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "<input type='hidden' id='txtperi" + ConsPer +
                        "' name='txtperi[]'   readonly style='" + style + "' value='" + perio +
                        "'><input type='hidden' id='' name='txtidperi[]'  value='0'>";
                    campo += "<input type='hidden' id='txtporc" + ConsPer +
                        "' name='txtporc[]'  class='PorcPer' readonly style='" + style + "' value='" +
                        porc + "'>";
                    campo += "</td>";
                    campo += "</tr>";
                    $("#tr_periodos").append(campo);

                    $('#periodo_modulo').val('').trigger('change.select2');
                    $("#porc_avance").val("");
                    $("#ConsPer").val(parseFloat(ConsPer) + 1);

                    //                }

                }
            });

            //======================EVENTO AGREGAR PERIODOS=======================\\


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
