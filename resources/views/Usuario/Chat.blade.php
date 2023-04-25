@extends('Plantilla.Principal')
@section('title', 'CHAT')
@section('CHAT')
    <div class="sidebar-left sidebar-fixed ps-container ps-theme-dark ps-active-y"
        data-ps-id="c908fa82-b36a-9bbd-b158-5c9f6f7f93b4">
        <div class="sidebar">
            <div class="sidebar-content card d-none d-lg-block">
                <div class="card-body chat-fixed-search pt-1 pb-0">
                    <div>
                        <p style="color:seagreen;font-weight: bold;text-align: left;line-height: 1.45;font-size: 1rem;text-transform: capitalize;"
                            id="txtNomUsuChat"></p>
                    </div>
                </div>
                <div id="users-list" class="list-group position-relative" style="height: auto;">
                    <input type="hidden" name="rutaFoto" id="rutaFoto"
                        value="{{ asset('app-assets/images/') }}">
                    <div class="users-list-padding media-list">
                        @if (Auth::user()->tipo_usuario == 'Profesor')
                            <center>
                                <a href='#' class='btn btn-purple' id="btnDifusion">
                                    Mensaje de Difusión
                                </a>
                            </center>
                            <div id="ladoAlumnos">
                                <p class='text-center' style='padding-top: 10px;font-weight: bold;font-size:15px;'>
                                    Estudiantes
                                </p>
                                @foreach ($alumnos as $usu)
                                    @if (Auth::user()->id != $usu->id)
                                        <a href='#' class=' media border-0 btnUsuario' data-id='{{ $usu->id }}'
                                            data-nomusuario='{{ $usu->nombre_usuario }}'>
                                            <div class='media-left pr-1'>
                                                <span class='avatar avatar-md avatar-online'>
                                                    <img class='media-object rounded-circle'
                                                        src='{{ asset('app-assets/images/Img_Estudiantes/' . $usu->foto_alumno) }}'
                                                        alt='image'>
                                                </span>
                                            </div>
                                            <div class='media-body w-100' style='margin-top:10px;'>
                                                <h6 class='list-group-item-heading'
                                                    style='font-size:10px;font-weight: bold;'>
                                                    <span style="text-transform: capitalize;">
                                                        {{ $usu->nombre_usuario }}
                                                    </span>
                                                    <p class='list-group-item-text text-muted mb-0'><i
                                                            class='ft-check primary font-small-2'></i>
                                                        {{ str_limit($usu->MENSAJE, 30, '...') }}
                                                        <span
                                                            class='font-small-3 float-right primary'>{{ $usu->FECHA }}</span>
                                                    </p>
                                                </h6>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach

                            </div>
                        @else
                            <div id="ladoProfesores">
                                <p class='text-center' style='padding-top: 10px;font-weight: bold;font-size:15px;'>
                                    Profesores
                                </p>
                                @foreach ($profesores as $usu)
                                    @if (Auth::user()->id != $usu->id)
                                        <a href='#' class=' media border-0 btnUsuarioPro' data-id='{{ $usu->id }}'
                                            data-nomusuario='{{ $usu->nombre_usuario }}'>
                                            <div class='media-left pr-1'>
                                                <span class='avatar avatar-md avatar-online'>
                                                    <img class='media-object rounded-circle'
                                                        src='{{ asset('app-assets/images/Img_Docentes/' . $usu->foto) }}'
                                                        alt='image'>
                                                </span>
                                            </div>
                                            <div class='media-body w-100' style='margin-top:10px;'>
                                                <h6 class='list-group-item-heading'
                                                    style='font-size:10px;font-weight: bold;'>
                                                    {{ $usu->nombre_usuario }}
                                                    <p class='list-group-item-text text-muted mb-0'><i
                                                            class='ft-check primary font-small-2'></i>
                                                        {{ str_limit($usu->MENSAJE, 30, '...') }}
                                                        <span
                                                            class='font-small-3 float-right primary'>{{ $usu->FECHA }}</span>
                                                    </p>
                                                </h6>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;">
            <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps-scrollbar-y-rail" style="top: 0px; height: 654px; right: 3px;">
            <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 443px;"></div>
        </div>
    </div>
    <div class="content-right">
        <div class="content-wrapper">
            <div class="content-body" id="conte1">
                <section class="chat-app-window" style="">
                    <div class="badge badge-default mb-1">Chat History</div>
                    <div class="chats">
                        <input type="hidden" name="rutaimagen" value="{{ asset('app-assets/images/') }}"
                            id="rutaimagen">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="{{ Auth::user()->id }}">
                        <div class="chats" id="contenidoChat">
                            <span id='etiquetafinal'></span>
                        </div>
                    </div>
                </section>
                <section class="chat-app-form">
                    <form class="chat-app-input d-flex" action="{{ url('/guardarchat') }}" method="POST" name="formGua"
                        id="formGua">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_receptor" id="id_receptor" value="0">
                        <fieldset class="form-group position-relative has-icon-left col-10 m-0">
                            <input type="text" class="form-control " id="txtMensaje" name="txtMensaje"
                                placeholder="Escriba mensaje">
                            <input type="hidden" class="form-control " id="txtMensaje2" name="txtMensaje2">
                        </fieldset>
                        <fieldset class="form-group position-relative has-icon-left col-2 m-0">
                            <a href="#" class="btn btn-primary" id="btnEnviar">
                                <i class="fa fa-paper-plane-o d-lg-none"></i>
                                <span class="d-none d-lg-block">Enviar</span>
                            </a>
                        </fieldset>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <form class="chat-app-input d-flex" action="{{ url('/cargar') }}" method="POST" name="formCargar" id="formCargar">
        {{ csrf_field() }}
    </form>

    <form class="chat-app-input d-flex" action="{{ url('/cargarUsuarios') }}" method="POST" name="formCargarUsuarios"
        id="formCargarUsuarios">
        {{ csrf_field() }}
    </form>

    <form class="chat-app-input d-flex" action="{{ url('/guardarDifusion') }}" method="POST" name="formGuaDif"
        id="formGuaDif">
        {{ csrf_field() }}
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $("#Div_InfCol").hide();
            var banderaEnvio = 1;
            $('body').addClass('content-left-sidebar chat-application');

            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Chat").addClass("active");

            var menaux = "";
            var bandera = 0;
            $("#btnEnviar").on({
                click: function(e) {
                    e.preventDefault();
                    enviar();
                }
            });
            $("#txtMensaje").on({
                keypress: function(e) {
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if (code === 13) {
                        enviar();
                        return false;
                    }
                }
            });

            function enviar() {
                if (banderaEnvio === 1) {
                    var ruta = $("#rutaimagen").val();
                    if ($("#txtMensaje").val() === "" || $("#id_receptor").val() === "0") {
                        return;
                    }
                    var mensaje = $("#txtMensaje").val();
                    var form = $("#formGua");
                    var url = form.attr("action");
                    var datos = form.serialize();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: datos,
                        success: function(respuesta) {
                            $("#txtMensaje").val("");
                            cargar();
                        },
                        error: function() {
                            Swal.fire("Ocurrio un error!", "", "error");
                        }
                    });
                } else {
                    enviarDifusion();
                }
            }

            $('#ladoAlumnos').on("click", ".btnUsuario", function(e) {
                e.preventDefault();
                var id_receptor = $(this).data("id");
                var nomusuario = $(this).data("nomusuario");
                $("#txtNomUsuChat").html("Chat Con: " + nomusuario);
                $("#id_receptor").val(id_receptor);
                banderaEnvio = 1;
                $("#txtMensaje").focus();
                $("#txtMensaje").val("");
                cargar();
            });

            $('#ladoProfesores').on("click", ".btnUsuarioPro", function(e) {
                e.preventDefault();
                var id_receptor = $(this).data("id");
                var nomusuario = $(this).data("nomusuario");
                $("#txtNomUsuChat").html("Chat Con: " + nomusuario);
                $("#id_receptor").val(id_receptor);
                $("#txtMensaje").focus();
                $("#txtMensaje").val("");
                banderaEnvio = 1;
                cargar();
            });

            function cargar() {
                if ($("#id_receptor").val() === "0") {
                    return;
                }
                var auxi = $("#contenidoChat").html();
                var form = $("#formCargar");
                var url = form.attr("action");
                var id_receptor = $("#id_receptor").val();
                $("#idAuxiliar").remove();
                form.append("<input type='hidden' name='id_receptor' id='idAuxiliar' value='" + id_receptor + "'>");
                var datos = form.serialize();
                $("#idAuxiliar").remove();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    success: function(respuesta) {
                        var ruta = $("#rutaimagen").val();
                        var chat = "";
                        var color = "";
                        var clase = "";
                        var style = "";
                        var idusu = $("#id_usuario").val();
                        var txtNomUsuChat = "";
                        for (var i = 0; i < respuesta.mensajes.length; i++) {
                            if (respuesta.mensajes[i].IDUSU == idusu) {
                                color = "text-danger text-bold-600";
                                clase = "chat ";
                                style = "float: right;";
                            } else {
                                color = "";
                                clase = "chat chat-left";
                                style = "float: left;text-transform: capitalize;font-weight: 600;";
                            }
                            chat += "<div class='" + clase + "'>";
                            chat += "<div class='chat-avatar'>";
                            chat +=
                                "<a class='avatar' data-toggle='tooltip' style='padding-right: 10px;' href='#' data-placement='right' title=''data-original-title=''>";
                            chat += "<img src='"  + ruta +"/"+ respuesta.mensajes[i].foto  + "' alt='avatar'/>";
                            chat += "</a>";
                            chat += "</div>";
                            chat += "<div class='chat-body'>";
                            chat += " <p  class='cajanombre " + color + " ' style='" + style + "'>" +
                                respuesta.mensajes[i].NICK + "</p>";
                            chat += "<div class='chat-content'>";
                            chat += " <p>" + respuesta.mensajes[i].MENSAJE + "</p>";
                            chat += "</div>";
                            chat += "</div>";
                            chat += "</div>";
                            txtNomUsuChat = respuesta.mensajes[i].NICK;
                        }

                        $("#contenidoChat").append(chat).html("").append(chat);
                        $("#etiquetafinal").remove();
                        $("#contenidoChat").append("<span id='etiquetafinal'></span>");
                        document.getElementById('etiquetafinal').scrollIntoView(true);
                    },
                    error: function() {
                        Swal.fire("Ocurrio un error!", "", "error");
                    }
                });
            }

            function cargarUsuarios() {
                var form = $("#formCargarUsuarios");
                var url = form.attr("action");
                var datos = form.serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    success: function(respuesta) {
                        var rutaFoto = $("#rutaFoto").val();
                        $("#ladoAlumnos").html("");
                        $("#ladoProfesores").html("");
                        var chat = "";
                        $("#ladoAlumnos").append(respuesta.ladoAlumnos);
                        $("#ladoProfesores").append(respuesta.ladoProfesores);
                    },
                    error: function() {
                        Swal.fire("Ocurrio un error!", "", "error");
                    }
                });
            }

            $("#btnDifusion").on({
                click: function(e) {
                    e.preventDefault();
                    $("#contenidoChat").html("");
                    $("#txtNomUsuChat").html("Mensaje De Difusión");
                    $("#txtMensaje").focus();
                    $("#txtMensaje").val("");
                    banderaEnvio = 2;
                }
            });

            function enviarDifusion() {
                var ruta = $("#rutaimagen").val();
                if ($("#txtMensaje").val() === "") {
                    return;
                }
                var mensaje = $("#txtMensaje").val();
                var form = $("#formGuaDif");
                $("#idAuxiliar").remove();
                form.append("<input type='hidden' name='txtMensaje' id='idAuxiliar' value='" + mensaje + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    success: function(respuesta) {
                        $("#txtMensaje").val("");
                        var color = "";
                        var idusu = $("#id_usuario").val();
                        console.log("esta:" + idusu);
                        console.log("viene:" + respuesta.id_usuario);
                        if (respuesta.id_usuario == idusu) {
                            color = "text-danger text-bold-600";
                            clase = "chat ";
                            style = "float: right;";
                        } else {
                            color = "";
                            clase = "chat chat-left";
                            style = "float: left;";
                        }
                        var chat = "";
                        chat += "<div class='" + clase + "'>";
                        chat += "<div class='chat-avatar'>";
                        chat +=
                            "<a class='avatar' data-toggle='tooltip' href='#' data-placement='right' title=''data-original-title=''>";
                        chat += "<img src='" + ruta +"/"+respuesta.foto+ "' alt='avatar'/>";
                        chat += "</a>";
                        chat += "</div>";
                        chat += "<div class='chat-body'>";
                        chat += "<p class='cajanombre " + color + " ' style='" + style + "'>" +
                            respuesta.usuario + "</p>";
                        chat += "<div class='chat-content'>";
                        chat += "<p>" + mensaje + "</p>";
                        chat += "</div>";
                        chat += "</div>";
                        chat += "</div>";
                        $("#contenidoChat").append(chat);
                        $("#etiquetafinal").remove();
                        $("#contenidoChat").append("<span id='etiquetafinal'></span>");
                        document.getElementById('etiquetafinal').scrollIntoView(true);
                        $("#conte1").animate({
                            scrollTop: $(this).prop("scrollHeight")
                        }, 1000);
                    },
                    error: function() {
                        Swal.fire("Ocurrio un error!", "", "error");
                    }
                });
            }
            setInterval(cargar, 30000);
            setInterval(cargarUsuarios, 30000);
        });
    </script>
@endsection
