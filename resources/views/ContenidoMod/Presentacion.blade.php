@extends('Plantilla.Principal')
@section('title', 'Presentación del Módulo')
@section('Contenido')
    <input type="hidden" class="form-control" id="Tip_Usu" value="{{ Auth::user()->tipo_usuario }}" />
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{ Session::get('des') }}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/Administracion') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Programa</a>
                        </li>
                        <li class="breadcrumb-item active">Presentación
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="modal fade text-left" id="ModGrupos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h4 class="modal-title" style="text-transform: capitalize;">Seleccione el Grupo</h4>
                    </div>
                    <div class="modal-body">
                        <div style="height: 300px; overflow: auto;">

                            <div class="card-body">
                                <div class="list-group">
                                    @if (Auth::user()->tipo_usuario == 'Profesor')
                                        @foreach ($GruposDoc as $Grup)
                                            <a onclick="$.SelGrup({{ $Grup->idgrupo }});"
                                                class="list-group-item list-group-item-action"><span class="float-left">
                                                    <i class="fa fa-slack mr-1"></i>
                                                </span>{{ $Grup->gr }}</a>
                                        @endforeach
                                    @endif


                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <section id="number-tabs">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Presentación</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="#" modulo="{{ $id }}" id="presentacion"
                                        class="list-group-item active">Presentación</a>
                                    <a href="#" modulo="{{ $id }}" id="objetivo"
                                        class="list-group-item list-group-item-action">Objetivo</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="h_titulo">Objetivo General</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-h font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>

                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="card-text" style="text-align: justify;" id="Text_presentacion">
                                    <p>Reconocer las consecuencias positivas del cuidado del cuerpo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

    {!! Form::open(['url' => '/cambiar/PresetancionMod', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => '/cambiar/GrupoMod', 'id' => 'formCambGrupo']) !!}
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").addClass("active");
            if ($("#Tip_Usu").val() === "Profesor") {
                $("#ModGrupos").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }

            $.extend({

                Inicio: function() {
                    var mod = $("#presentacion").attr("modulo");
                    var form = $("#formAuxiliar");
                    $("#idModulo").remove();
                    form.append("<input type='hidden' name='id' id='idModulo' value='" + mod + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Text_presentacion").html("");
                            $("#Text_presentacion").append(respuesta.presentacion
                                .presentacion_modulo);
                        },
                        error: function() {
                            swal(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                    $("#h_titulo").html("Presentación");
                },
                SelGrup: function(id) {

                    var form = $("#formCambGrupo");
                    $("#idGrupo").remove();
                    form.append("<input type='hidden' name='idGrupo' id='idGrupo' value='" + id + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $('#ModGrupos').modal('toggle');
                          
                        },
                        error: function() {
                            swal(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                }

            });
            $.Inicio();
            $("#presentacion").on({
                click: function(e) {
                    e.preventDefault();
                    var mod = $(this).attr("modulo");
                    var form = $("#formAuxiliar");
                    $("#idModulo").remove();
                    form.append("<input type='hidden' name='id' id='idModulo' value='" + mod + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Text_presentacion").html("");
                            $("#Text_presentacion").append(respuesta.presentacion
                                .presentacion_modulo);
                        },
                        error: function() {
                            swal(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                    $("#h_titulo").html("Presentacion");
                    $("#objetivo").removeClass("list-group-item active");
                    $("#objetivo").addClass("list-group-item");
                    $("#presentacion").addClass("list-group-item active");
                }
            });
            $("#objetivo").on({
                click: function(e) {
                    e.preventDefault();
                    var mod = $(this).attr("modulo");
                    var form = $("#formAuxiliar");
                    $("#idModulo").remove();
                    form.append("<input type='hidden' name='id' id='idModulo' value='" + mod + "'>");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        dataType: "json",
                        success: function(respuesta) {
                            $("#Text_presentacion").html("");
                            $("#Text_presentacion").append(respuesta.presentacion
                                .objetivo_modulo);
                        },
                        error: function() {
                            swal(
                                'Error!',
                                'Ocurrio un error...',
                                'error'
                            );
                        }
                    });
                    $("#h_titulo").html("Objetivo General");
                    $("#presentacion").removeClass("list-group-item active");
                    $("#presentacion").addClass("list-group-item");
                    $("#objetivo").addClass("list-group-item active");
                }
            });
        });
    </script>
@endsection
