@extends('Plantilla.Principal')
@section('title', 'Crear Estudiante')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Gestión de Estudiante</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Gestión de Estudiante
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
                            <h4 class="card-title">Crear Estudiante</h4>
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
                                @include('Alumnos.Form', [
                                    'url' => '/Alumnos/guardar',
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

    {!! Form::open(['url' => '/Alumnos/ValAlumnos', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/Usuarios/ValUsuario', 'id' => 'formAuxiliarUsu']) !!}
    {!! Form::close() !!}


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Estudiantes").addClass("active open");

            $('#fnacimiento').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });

            $.extend({

                ValidIdent: function(ident) {

                    var form = $("#formAuxiliar");
                    $("#idAlumn").remove();

                    var profe_id=$("#alumno_id").val();
                    form.append("<input type='hidden' name='idAlumn' id='idAlumn' value='" + profe_id + "'>");
                    $("#identAlum").remove();
                    form.append("<input type='hidden' name='id' id='identAlum' value='" + ident + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            if (respuesta.exit === "si") {
                                Swal.fire('Alerta!',
                                    'Esta Identificación ya se encuentra Registrada...',
                                    'warning');
                                $("#ident_alumno").val("");
                            }

                        }

                    });
                },
                ValidUsu: function(Usu) {

                    var form = $("#formAuxiliarUsu");

                    $("#Usu").remove();
                    form.append("<input type='hidden' name='Usu' id='Usu' value='" + Usu + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        async: false,
                        dataType: "json",
                        success: function(respuesta) {
                            if (respuesta.exit === "si") {
                                Swal.fire('Alerta!',
                                    'Este Nombre de Usuario ya se Encuentra Registrado...',
                                    'warning');
                                $("#usuario_alumno").val("");
                            }

                        }

                    });
                },
                Guardar: function() {
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
                    
                    $("#formAlum").submit();
                }

            })

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

        function validartxt(e) {
            tecla = e.which || e.keyCode;
            patron = /[a-zA-Z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF\s]+$/;
            te = String.fromCharCode(tecla);
            return (patron.test(te) || tecla == 9 || tecla == 8 || tecla == 37 || tecla == 39 || tecla == 46);
        }

        function validarEmail() {
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

            if (regex.test($('#email').val().trim())) {

            } else {
                Swal.fire('Alerta!', 'El Email no es Valido...', 'warning');
            }

        }
    </script>
@endsection
