@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open([
    'url' => $url,
    'method' => $method,
    'class' => '',
    'id' => 'formAsigEval',
    'files' => true,
    'enctype' => 'multipart/form-data',
]) !!}

<input type="hidden" class="form-control" name="tema_id" id="tema_id" value="{{ $Tema->id }}" />
<input type="hidden" class="form-control" id="ConsGrupPreg" value="2" />
<input type="hidden" class="form-control" id="ConsGrupPregRela" value="2" />
<input type="hidden" class="form-control" id="ConsGrupPregRelaOpc" value="2" />
<input type="hidden" class="form-control" id="ConsVerFal" value="2" />
<input type="hidden" class="form-control" id="Conslink" value="1" />
<input type="hidden" class="form-control" id="Conslink" value="1" />

<input type="hidden" class="form-control" id="ConsPreguntas" value="1" />
<input type="hidden" class="form-control" name="Tipreguntas" id="Tipreguntas" value="" />
<input type="hidden" class="form-control" name="PregConse" id="PregConse" value="" />
<input type="hidden" class="form-control" name="IdpreguntaMul" id="IdpreguntaMul" value="" />
<input type="hidden" class="form-control" name="id-pregverfal" id="id-pregverfal" value="" />
<input type="hidden" class="form-control" name="id-pregensay" id="id-pregensay" value="" />
<input type="hidden" class="form-control" name="id-pregcomplete" id="id-pregcomplete" value="" />
<input type="hidden" class="form-control" name="id-relacione" id="id-relacione" value="" />
<input type="hidden" class="form-control" name="id-taller" id="id-taller" value="" />

<input type="hidden" name="origen_eval" value="ME" />
<input type="hidden" name="TextHabTiempo" id="TextHabTiempo" value="SI" />
<input type="hidden" class="form-control" id="ConsOpcMulPreg" value="2" />

<input type="hidden" class="form-control" id="Nom_Video" name="Nom_Video" value="" />
<input type="hidden" class="form-control" name="Id_Eval" id="Id_Eval" value="{{ $Eval->id }}" />
<input type="hidden" data-id='id-dat' id="dattaller"
    data-ruta="{{ asset('/app-assets/Archivos_EvaluacionTaller') }}" />
<input type="hidden" class="form-control" id="RutEvalVideo" value="{{ url('/') }}/" />
<h4 class="form-section"><i class="ft-grid"></i> Datos del Tema</h4>


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
                    <video width="640" height="360" id="datruta" controls
                        data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}">
                    </video>
                </div>

                <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                        class="ft-corner-up-left position-right"></i> Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="form-group" style="pointer-events: none;">
            <label class="form-label" for="modulo">Asignatura:</label>
            <select class="form-control select2" data-placeholder="Seleccione" onchange="$.CargTemas(this.value);"
                name="asignatura" id="asignatura">
                <option value="">Seleccione la Asignatura</option>
                @foreach ($Asignatura as $Asi)
                    {
                    @if ($Asi->id == $Tema->asignatura)
                        <option value="{{ $Asi->id }}"
                            {{ Input::old('asignatura') == $Asi->id ? 'selected' : '' }} selected>
                            {{ $Asi->nombre . ' - Grado ' . $Asi->grado . '°' }}
                        </option>
                    @else
                        <option value="{{ $Asi->id }}"
                            {{ Input::old('asignatura') == $Asi->id ? 'selected' : '' }}>
                            {{ $Asi->nombre . ' - Grado ' . $Asi->grado . '°' }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" style="pointer-events: none;">
            <label class="form-label" for="tema">Tema:</label>
            <input type="text" value="{{$Tema->titulo}}" class="form-control"/>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-9">
        <h4 class="form-section" id=''><i class="ft-edit"></i> Información de la Evaluación / Actividad</h4>
        <div class="row">
            <div class="col-md-12" id='rowtit'>
                <div class="form-group">
                    <label class="form-label" for="titu_contenido">Titulo de Evaluación:</label>
                    {!! Form::text('titulo', old('titulo', $Eval->titulo), [
                        'class' => 'form-control',
                        'placeholder' => 'Titulo de la Evaluación',
                        'id' => 'titulo',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Ingrese el Enunciado:</label>
                    <textarea cols="80" id="summernoteRelacione" name="summernoteRelacione" rows="5"></textarea>

                    <br>
                </div>
            </div>

        </div>

        <h4 class="form-section" style="padding-top: 15px;" id=''><i class="ft-check"></i> Evaluación /
            Actividad</h4>

        <div id="MensInf">
            <div class="bs-callout-warning callout-bordered mt-1">
                <div class="media align-items-stretch">
                    <div class="media-body p-1 center">
                        <strong>Utilice este espacio para crear su evaluación</strong>
                        <p>Presione <b>Guardar</b> para que los cambios se guarden a medida que los hace. </p>
                        <p>Presione <b>Guardar y Cerrar</b> para guardar y Cerrar los Cambios. </p>
                    </div>
                    <div class="d-flex align-items-center bg-warning p-2">
                        <i class="fa fa-warning white font-medium-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div id="div-evaluaciones">
        </div>
        <div id="vid-adjunto"></div>

        <div class="form-actions center" id="div-addpreg">
            <div class="heading-elements center">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-min-width dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="fa fa-check"></i> Contenido para la
                        Evaluación</button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" onclick="$.AddPregAbierta();">Agregar Pregunta Abierta</a>
                        <a class="dropdown-item" onclick="$.AddPregComplete();">Agregar Pregunta Complete</a>
                        <a class="dropdown-item" onclick="$.AddPregOpcMultiple();">Agregar Pregunta Opción
                            Multiple</a>
                        <a class="dropdown-item" onclick="$.AddPregVerdFalso();">Agregar Pregunta Verdadero /
                            Falso</a>
                        <a class="dropdown-item" onclick="$.AddPregRelacione();">Agregar Pregunta Relacione</a>
                        <a class="dropdown-item" onclick="$.AddPregArchivo();">Agregar Guía para Desarrollar</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" onclick="$.AddVideo();">Adjuntar Video Local</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id='Cont_documento' style="display:none">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Contenido:</label>
                    <div id="cont_sumer"></div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <h4 class="form-section"><i class="ft-edit"></i> Configuración </h4>
        <div class="row border-left-blue">
            <div class="col-lg-12">
                <div class="form-group" id='TipEva'>
                    <label class="form-label" for="tip_eval">Clasif. de la Evaluación:</label><br>
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione..."
                        name="clasificacion" id="clasificacion">
                      <option value="PRACTICA" @if (Input::old('clasificacion', $Eval->clasificacion) == 'PRACTICA') selected="selected" @endif>PRACTICA</option>
                    
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
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
            <div class="col-lg-12">
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
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Puntos Máximos:</label>
                    <input type="text" class="form-control" readonly="" id="Punt_Max" value="0"
                        name="Punt_Max" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Habilitar Comentarios:</label>
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione"
                        id="HabConv" name="HabConv">
                        <option value="NO">NO</option>
                        <option value="SI">SI</option>

                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group" style="cursor: pointer;">
                    <fieldset class="checkboxsas">
                        <label>
                            <input type="checkbox" checked onclick="$.HabTiempo();" id="hab_tiempo"
                                name="hab_tiempo" value=""> Habilitar Limite de Tiempo.
                        </label>
                    </fieldset>
                    <input type="text" id="TEval" value="01:00" onkeyup="$.ValTiempo(this.value);"
                        class="form-control" name="TEval">
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success white">
                    <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">Contenido
                        Didactico Cargado</h4>
                </div>
                <div class="modal-body">
                    <video width="640" height="360" id="datruta" controls
                        data-ruta="{{ asset('/app-assets/Evaluacion_PregDidact') }}">
                    </video>
                </div>

                <button type="button" id="btn_salir" onclick="$.SalirAnim();"
                    class="btn grey btn-outline-secondary" data-dismiss="modal"><i
                        class="ft-corner-up-left position-right"></i> Salir</button>
            </div>
        </div>
    </div>


</div>

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">

                <button class="btn btn-outline-primary" onclick="$.GuardarEval();" id="Btn_Guardar" title="Guardar"
                    type="button">
                    <i class="fa fa-save"></i> Guardar y Cerrar
                </button>
                @if ($opc != 'editar')
                    <a class="btn btn-outline-warning" href="{{ url('/ModuloE/NuevaPractica/' . $Tema->id) }}"
                        title="Cancelar">
                        <i class="fa fa-close"></i> Cancelar
                    </a>
                @endif

                <a class="btn btn-outline-dark" href="{{ url('/ModuloE/ConsulPrePract/' . $Tema->id) }}"
                    title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
