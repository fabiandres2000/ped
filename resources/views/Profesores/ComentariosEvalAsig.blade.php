@extends('Plantilla.Principal')
@section('title','Presentación de la Asignatura')
@section('Contenido')
<input type="hidden" class="form-control" id="Tip_Usu"  value="{{Auth::user()->tipo_usuario}}"/>
<input type="hidden" class="form-control" id="idTemaEva"  value=""/>
<input type="hidden" class="form-control" id="Id_Doce"  value=""/>
<input type="hidden" class="form-control" id="Id_Alu"  value=""/>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{Session::get('des')}}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                    </li>
                    <li  class="breadcrumb-item"><a href="#">Comentarios A Evaluaciones</a>
                    </li>

                </ol>
            </div>
        </div>
    </div>     
</div>
<div class="modal fade text-left" id="ModComent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
     aria-hidden="true">
    <div class="modal-dialog comenta" role="document">
        <div class="modal-content border-pink">
            <div class="modal-header bg-pink white">
                <h4 class="modal-title" id="titu_tema">Comentarios</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="media">
                        <div  class="row " >
                            <div class="row scrollable-container" style="height:200px;" id="Div_Comentarios">
                                <span id='etiquetafinal'></span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        <input id="Text_Coment" class="form-control" placeholder="Escribir un Comentario..." type="text">
                        <div class="form-control-position">
                            <i class="fa fa-dashcube"></i>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"  id="btn_GuarComent" onclick="$.GuarComent();" class="btn grey btn-outline-success"><i class="ft-navigation position-right"></i> Enviar Comentario</button>
                <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <section id="basic-callouts">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Notificaciones de Comentarios a Evaluaciones</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body" id="idNotif">
                            {!!$lisNoti!!} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>



{!! Form::open(['url'=>'/Consultar/ComentarioDoce'
,'id'=>'formConsuComent'])!!}
{!! Form::close() !!}

{!! Form::open(['url'=>'/Notificaciones/ComentEvaluacion2'
,'id'=>'formConsuComentEva'])!!}
{!! Form::close() !!}

{!! Form::open(['url'=>'/Guardar/ComentarioDoce'
,'id'=>'formGuarComentDoce'])!!}
{!! Form::close() !!}

@endsection
@section('scripts')
<script>
    $(document).ready(function () {

        $("#Men_Inicio").removeClass("active");
//    $("#Men_Presentacion").addClass("active");


        $.extend({
            RespNotEval: function (id) {

                var parComt = $("#" + id).data("item-id").split("/");
                var Eva = parComt[0];
                var Alu = parComt[1];
                var Doc = parComt[2];
                

                $("#idTemaEva").val(Eva);
                $("#Id_Doce").val(Doc);
                $("#Id_Alu").val(Alu);

                $("#ModComent").modal();
                $('#ModComent').modal({keyboard: false,
                    show: true
                });
                $('.comenta').draggable({
                    handle: ".modal-header"
                });

                var contenido = '';
                var form = $("#formConsuComent");


                $("#idAlum").remove();
                $("#idDoce").remove();
                $("#idEvalComent").remove();
                form.append("<input type='hidden' name='idEvalComent' id='idEvalComent' value='" + Eva + "'>");
                form.append("<input type='hidden' name='idDoce' id='idDoce' value='" + Doc + "'>");
                form.append("<input type='hidden' name='idAlum' id='idAlum' value='" + Alu + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                var j = 0;
                $.ajax({
                    type: "POST",
                    url: url,
                    async: false,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                        $.each(respuesta.Comet, function (i, item) {
                            j++;
                            contenido += '<div id="Com' + j + '" class="col-lg-12">'
                                    + '<div class="media-body">'
                                    + '    <p class="text-bold-600 mb-0" style="text-transform: capitalize;"><a href="#">' + item.nombre_usuario + '</a></p>'
                                    + '    <p>' + item.comentario + '</p>'
                                    + ' </div>'
                                    + ' </div>';

                        });
                        $("#Div_Comentarios").append(contenido).html("").append(contenido);
                        $("#etiquetafinal").remove();
                        $("#Div_Comentarios").append("<span id='etiquetafinal'></span>");
                        document.getElementById('etiquetafinal').scrollIntoView(true);



                        $.UpdatNotVis();


                    }
                });



            },
            ConsNotEval: function (Eva, Alu, Doc) {

                $("#idTemaEva").val(Eva);
                $("#Id_Doce").val(Doc);
                $("#Id_Alu").val(Alu);

                var contenido = '';
                var form = $("#formConsuComent");


                $("#idAlum").remove();
                $("#idDoce").remove();
                $("#idEvalComent").remove();
                form.append("<input type='hidden' name='idEvalComent' id='idEvalComent' value='" + Eva + "'>");
                form.append("<input type='hidden' name='idDoce' id='idDoce' value='" + Doc + "'>");
                form.append("<input type='hidden' name='idAlum' id='idAlum' value='" + Alu + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                var j = 0;
                $.ajax({
                    type: "POST",
                    url: url,
                    async: false,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                        $.each(respuesta.Comet, function (i, item) {
                            j++;
                            contenido += '<div id="Com' + j + '" class="col-lg-12">'
                                    + '<div class="media-body">'
                                    + '    <p class="text-bold-600 mb-0" style="text-transform: capitalize;"><a href="#">' + item.nombre_usuario + '</a></p>'
                                    + '    <p>' + item.comentario + '</p>'
                                    + ' </div>'
                                    + ' </div>';

                        });
                        $("#Div_Comentarios").append(contenido).html("").append(contenido);
                        $("#etiquetafinal").remove();
                        $("#Div_Comentarios").append("<span id='etiquetafinal'></span>");
                        document.getElementById('etiquetafinal').scrollIntoView(true);

                        $.UpdatNotVis();
                    }
                });



            },
            UpdatNotVis: function () {
                var contenido = '';
                var form = $("#formConsuComentEva");
                $("#idEvalComent2").remove();
                form.append("<input type='hidden' name='idEvalComent' id='idEvalComent2' value='1'>");
                var url = form.attr("action");
                var datos = form.serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    async: false,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                        $("#idNotif").html(respuesta.lisNoti);
                    }

                });
            },
            GuarComent: function () {
                var form = $("#formGuarComentDoce");
                var id = $("#idTemaEva").val();
                var Text_Coment = $("#Text_Coment").val();
                var Id_Doce = $("#Id_Doce").val();
                var Id_Alu = $("#Id_Alu").val();

          
                $("#idEvalComent").remove();
                $("#idDoce").remove();
                $("#idAlu").remove();
                $("#Coment").remove();
                form.append("<input type='hidden' name='idEvalComent' id='idEvalComent' value='" + id + "'>");
                form.append("<input type='hidden' name='idDoce' id='idDoce' value='" + Id_Doce + "'>");
                form.append("<input type='hidden' name='idAlu' id='idAlu' value='" + Id_Alu + "'>");
                form.append("<input type='hidden' name='Coment' id='Coment' value='" + Text_Coment + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,
                    dataType: "json",
                    success: function (respuesta) {
                        $.ConsNotEval(id, Id_Alu, Id_Doce);
                    }
                });
                $("#Text_Coment").val("");
            }

        });

    });
</script>
@endsection

