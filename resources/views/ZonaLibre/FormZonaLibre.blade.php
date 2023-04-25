@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formZona', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" name="tema_id" id="tema_id" value="{{ $Tema->id }}" />
<input type="hidden" class="form-control" id="ConsGrupPreg" value="2" />
<input type="hidden" class="form-control" id="ConsPregMul" value="1" />
<input type="hidden" class="form-control" id="ConsVerFal" value="2" />
<input type="hidden" class="form-control" id="Conslink" value="1" />
<input type="hidden" class="form-control" id="ConsAnima" value="2" />
<input type="hidden" class="form-control" id="ConsLinkAnim" value="2" />
<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />
<input type="hidden" class="form-control" id="ConsOpcMulPreg" value="2" />
<input type="hidden" class="form-control" id="Id_Eval" name="Id_Eval" value="" />
<input type="hidden" class="form-control" id="Nom_Video" name="Nom_Video" value="" />
<input type="hidden" class="form-control" id="tema_modulo" value="{{ $Tema->modulo }}" />
<input type="hidden" class="form-control" id="tema_periodo" value="{{ $Tema->periodo }}" />
<input type="hidden" class="form-control" id="tema_unidad" value="{{ $Tema->unidad }}" />
<h4 class="form-section"><i class="ft-grid"></i> Datos del Contenido</h4>

<div class="modal fade text-left" id="ModConfEval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-success">
            <div class="modal-header">
                <h4 class="modal-title">Configuraciones de Evaluación</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="porc_modulo">Intentos Permitidos:</label>
                                <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione"
                                    id="cb_intentosPer" name="cb_intentosPer">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="0">Ilimitado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="porc_modulo">Calificar Usando:</label>
                                <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione"
                                    id="cb_CalUsando" name="cb_CalUsando">
                                    <option value="Puntos">Puntos</option>
                                    <option value="Porcentaje">Porcentaje</option>
                                    <option value="Letra">Letra</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="porc_modulo">Puntos Máximos:</label>
                                <input type="text" class="form-control" readonly="" id="Punt_Max" value=""
                                    name="Punt_Max" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="btn_salir" class="btn grey btn-outline-success" data-dismiss="modal"><i
                        class="fa fa-check"></i> Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
    aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">Contenido Didactico
                    Cargado</h4>
            </div>
            <div class="modal-body">
                <div id='ListEval' style="height: 400px; overflow: auto;text-align: center;">
                    <video id="videoclip" width="640" height="360" controls="controls" title="Video title">
                        <source id="mp4video" src="" type="video/mp4" />
                    </video>
                </div>

                <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                        class="ft-corner-up-left position-right"></i> Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="ModAnima" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
    aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaAnim">Animaciones Cargadas
                    al Tema</h4>
                <h4 class="modal-title" style="text-transform: capitalize; display: none;" id="titu_Anima">Animación
                </h4>

            </div>
            <div class="modal-body">

                <div id='DetAnimaciones' style="height: 400px; overflow: auto;display: none;">
                    <video id="videoclipAnima" width="100%" height="360" controls="controls" title="Video title">
                        <source id="mp4videoAnima" src="" type="video/mp4" />
                    </video>
                </div>
                <div id='DetLinkVideo' style="height: 400px; overflow: auto;display: none;">
                    <iframe id="LinkVideo" width="100%" height="100%" src="" frameborder="0"
                        allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_salirModAnima" class="btn grey btn-outline-secondary"
                    onclick="$.CloseModAnimaciones();" data-dismiss="modal"><i
                        class="ft-corner-up-left position-right"></i> Salir</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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
                <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                        class="ft-corner-up-left position-right"></i> Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="Visualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title" id="titu_tema"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id='prueba' style="height:400px; overflow: scroll;"></div>

            </div>
            <div class="modal-footer">
                <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                        class="ft-corner-up-left position-right"></i> Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('Grado', 'Grado:', ['class' => 'form-label']) }}
            <select class="form-control select2" data-placeholder="Seleccione" name="grado" id="grado">
                <option value="">Seleccione el Grado</option>
                @for ($i = 1; $i <= 11; $i++)
                    @if ($i == $Tema->grado)
                        <option value="{{ $i }}" {{ Input::old('grado') == $i ? 'selected' : '' }}
                            selected>{{ 'GRADO ' . $i }}</option>
                    @else
                        <option value="{{ $i }}" {{ Input::old('grado') == $i ? 'selected' : '' }}>
                            {{ 'GRADO ' . $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('Grupo', 'Grupo:', ['class' => 'form-label']) }}
            <select class="form-control select2" data-placeholder="Seleccione" name="grupo" id="grupo">
                <option value="">Seleccione el Grupo</option>
                {!! $SelGrupos !!}
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {{ Form::label('Jornada', 'Jornada:', ['class' => 'form-label']) }}
            <select name="jornada" id="jornada" class="form-control select2">
                <option value="">-- Seleccionar --</option>
                <option value="JM" @if (Input::old('jornada', $Tema->jornada) == 'JM') selected="selected" @endif>Jornada Mañana</option>
                <option value="JT" @if (Input::old('jornada', $Tema->jornada) == 'JT') selected="selected" @endif>Jornada Tarte</option>
                <option value="JN" @if (Input::old('jornada', $Tema->jornada) == 'JN') selected="selected" @endif>Jornada Nocturna
                </option>
            </select>

        </div>
    </div>


    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="tip_contenido">Tipo Contenido:</label>
            <select class="form-control select2" onchange='$.TipDoc(this.value)' data-placeholder="Seleccione"
                name="tip_contenido" id="tip_contenido">
                <option value="" @if (Input::old('tip_contenido', $Tema->tip_contenido) == '') selected="selected" @endif>Seleccionar</option>
                <optgroup label="Elementos del Contenido">
                    <option value="ANUNCIO" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'ANUNCIO') selected="selected" @endif>ANUNCIO
                    </option>
                    <option value="DOCUMENTO" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'DOCUMENTO') selected="selected" @endif>DOCUMENTO
                    </option>
                    <option value="VIDEOS" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'VIDEOS') selected="selected" @endif>VIDEOS</option>
                    <option value="ARCHIVO" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'ARCHIVO') selected="selected" @endif>ARCHIVO
                    </option>
                    <option value="LINK" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'LINK') selected="selected" @endif>LINK</option>
                </optgroup>
            </select>
        </div>
    </div>
    <div id='TipVideo' style="display: none;" class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="tip_video">Tipo de Video:</label><br>
            <select class="form-control select2" style="width: 100%;" onchange='$.CamContVideo(this.value)'
                data-placeholder="Seleccione..." name="tip_video" id="tip_video">
                <option value="" @if (Input::old('tip_video', $Tema->tip_video) == '') selected="selected" @endif>Seleccionar </option>
                <option value="LINK" @if (Input::old('tip_video', $Tema->tip_video) == 'LINK') selected="selected" @endif>LINK</option>
                <option value="ARCHIVO" @if (Input::old('tip_video', $Tema->tip_video) == 'ARCHIVO') selected="selected" @endif>ARCHIVO</option>
            </select>
        </div>
    </div>
    <div id='TipEva' style="display: none;" class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="tip_eval">Tipo de Evaluación:</label><br>
            <select class="form-control select2" style="width: 100%;" onchange='$.CamContEva(this.value)'
                data-placeholder="Seleccione..." name="tip_evaluacion" id="tip_evaluacion">
                <option value="" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == '') selected="selected" @endif>Seleccionar </option>
                <option value="GRUPREGUNTA" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'GRUPREGUNTA') selected="selected" @endif>GRUPO DE
                    PREGUNTAS</option>
                <option value="OPCMULT" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'OPCMULT') selected="selected" @endif>OPCIÓN MULTIPLE
                </option>
                <option value="PREGENSAY" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'PREGENSAY') selected="selected" @endif>PREGUNTA TIPO
                    ENSAYO</option>
                <option value="VERFAL" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'VERFAL') selected="selected" @endif>PREGUNTAS
                    VERDADERO / FALSO</option>
                <option value="COMPLETE" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'COMPLETE') selected="selected" @endif>COMPLETE
                </option>
                <option value="RELACIONE" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'RELACIONE') selected="selected" @endif>RELACIONE
                </option>
                <option value="DIDACTICO" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'DIDACTICO') selected="selected" @endif>CONTENIDO
                    DIDACTICO</option>
                <option value="TALLER" @if (Input::old('tip_eval', $Eval->tip_evaluacion) == 'TALLER') selected="selected" @endif>DESARROLLO DE
                    TALLER</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label " for="fecha">Fecha:</label>
            {!! Form::text('fecha', old('fecha', $Tema->fecha), ['class' => 'form-control', 'placeholder' => 'Ingresar Fecha', 'id' => 'fecha']) !!}

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4 class="form-section" id=''><i class="ft-edit"></i> Información del Contenido</h4>
        <div class="row">
            <div class="col-md-12" id='rowtit'>
                <div class="form-group">
                    <label class="form-label" for="titu_contenido">Título:</label>
                    {!! Form::text('titu_contenido', old('titu_contenido', $Tema->titu_contenido), ['class' => 'form-control', 'placeholder' => 'Titulo del Tema', 'id' => 'titu_contenido', 'style' => 'text-transform: uppercase']) !!}
                </div>
            </div>
            <div class="col-md-3" style="display: none;">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Habilitar Cometarios:</label>
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione"
                        id="HabConv" name="HabConv">
                        <option value="">Seleccionar</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" id='Cont_Comentario' style="display:none;">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Contenido:</label>
                    <textarea cols="80" id="summernoteComent" name="summernoteComent" rows="10"></textarea>
                </div>
            </div>

        </div>
        <div class="row" id='Cont_documento' style="display:none">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Contenido:</label>
                    <textarea cols="80" id="summernoteCont" name="summernoteCont" rows="10"></textarea>
                    <br>
                </div>
            </div>

        </div>
        <div class="row" id='Cont_didactico'>
            <div class="col-md-12" style="display: none;" id="Div_ContAnimaciones">
                <div class="row">
                    <div class="col-md-9">
                        <label class="form-label bold"><strong>Adjuntar Animaciones:</strong></label>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="AddAnimaciones" class="btn mr-1 mb-1 btn-success"><i
                                class="fa fa-plus"></i> Agregar Otra Animación</button>
                    </div>

                </div>
                <div id="Arch_Didact" class="row">
                    <div class="col-md-11">
                        <input id="file" accept="video/*" class="form-control" name="archididatico[]" type="file">
                    </div>
                    <div class="col-md-1">

                    </div>
                </div>
                <div class="col-md-12" id='ListAnima' style="display: none; padding-top: 10px;">
                    <h6 class="form-section"><strong>Animaciones Agregadas</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_animaciones">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12" style="display: none;" id="Div_link">
                <div class="row">
                    <div class="col-md-9">
                        <label class="form-label bold"><strong>Link Videos:</strong></label>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="AddLinkVideo" class="btn mr-1 mb-1 btn-success"><i
                                class="fa fa-plus"></i> Agregar Otro Link</button>
                    </div>

                </div>
                <div id="Arch_Link" class="row">
                    <div class="col-md-11">
                        <input id="Text_Link" style='padding-top: 10px;' class="form-control LinkVideo" name="linkVideo[]"
                            type="text">
                    </div>
                    <div class="col-md-1">

                    </div>
                </div>
                <div class="col-md-12" id='ListLinkVideo' style="display: none; padding-top: 10px;">
                    <h6 class="form-section"><strong>Links Agregados</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_linkVideos">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" id='Archivo' style="display:none">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Seleccionar Contenido Tematico</label>
                    <label id="projectinput7" class="file center-block">
                        <input id="fileArchivo" multiple="" name="archi[]" type="file">
                        <span class="file-custom"></span>
                    </label>
                </div>
            </div>
            @if ($method == 'put')
                <div class="col-md-12">
                    <h6 class="form-section"><strong>Archivos Agregados</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_archivos">

                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="row" id='TipUrl' style="display:none">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Url del Tema:</label>
                    <input type="text" class="form-control" name="url_tema" id="url_tema" placeholder="Url del Tema "
                        value="" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group float-right  mb-0">
                    <button id="AddLink" type="button" class="btn mr-1 mb-1 btn-success"><i
                            class="fa fa-plus"></i> Agregar Url</button>
                </div>
            </div>
            <div class="col-md-12">
                <h6 class="form-section"> Urls Agregadas</h6>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Url</th>
                            <th>Opcion</th>
                        </tr>
                    </thead>
                    <tbody id="tr_urls">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">


                <button class="btn btn-outline-primary" href="#" id="Btn_Guardar" title="Guardar"
                    onclick="$.Guardar();" type="button">
                    <i class="fa fa-save"></i> Guardar
                </button>
                @if ($opc != 'editar')
                    <a class="btn btn-outline-warning" href="{{ url('/Asignaturas/NuevaZona') }}" title="Cancelar">
                        <i class="fa fa-close"></i> Cancelar
                    </a>
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/Asignaturas/ZonaLibre') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
