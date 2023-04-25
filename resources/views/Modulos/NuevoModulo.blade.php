@extends('Plantilla.Principal')
@section('title', 'Crear Módulo')
@section('Contenido')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Crear Módulo
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
                            <h4 class="card-title">Crear Módulo.</h4>
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
                                @include('Modulos.FormModulos', ['url' => '/Modulos/GuardarModulos', 'method' => 'post'])
                                <!--end::Form-->
                                <p class="px-1"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#Men_Inicio").removeClass("active");
            $("#Men_Modulos").addClass("has-sub open");
            $("#Men_Modulos_addModulo").addClass("active");

            $.extend({
                GuardarModu: function() {

                    if ($('#nombre').val()==="") {
                        Swal.fire({
                            title: "Administrar Asignaturas",
                            text: "Seleccione el Área de la Asignatura.",
                            icon: "warning",
                            button: "Aceptar",
                        });
                        return;
                    }


                    if (!$('#image').val()) {
                        Swal.fire({
                            title: "Administrar Asignaturas",
                            text: "Seleccione la Imagen a Subir.",
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

                    $("#formModu").submit();
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
