@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formBanco', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" name="preg_id" id="preg_id" value="{{ $Preg->id }}" />
<input type="hidden" class="form-control" id="ConsPreguntas" value="1" />
<input type="hidden" class="form-control" id="ConsOpcRel" value="1" />


<input type="hidden" class="form-control" name="npreguntas" id="npreguntas" value="" />
<input type="hidden" class="form-control" name="IdpreguntaMul" id="IdpreguntaMul" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart1" id="IdpreguntaPart1" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart2" id="IdpreguntaPart2" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart3" id="IdpreguntaPart3" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart4" id="IdpreguntaPart4" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart5" id="IdpreguntaPart5" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart6" id="IdpreguntaPart6" value="" />
<input type="hidden" class="form-control" name="IdpreguntaPart7" id="IdpreguntaPart7" value="" />
<input type="hidden" class="form-control" name="PregConse" id="PregConse" value="" />
<input type="hidden" class="form-control" id="Ruta" value="{{ url('/') }}/" />
<input type="hidden" class="form-control" name="Tipreguntas" id="Tipreguntas" value="" />
<input type="hidden" class="form-control" name="ParteSel" id="ParteSel" value="" />
<input type="hidden" class="form-control" name="titulo" id="titulo" value="" />


<h4 class="form-section"><i class="ft-grid"></i> Datos de la Pregunta</h4>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="modulo">Asignatura:</label>
            <select class="form-control select2" onc data-placeholder="Seleccione"
                name="asignatura" onChange="$.CargPartes(this.value)" id="asignatura">
                <option value="">Seleccione la Asignatura</option>
                @foreach ($Asignatura as $Asi)
                    @if ($Asi->id == $Preg->asignatura)
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

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="tipo_contenido">Enunciado:</label>
            <textarea cols="80" id="enunciado" name="enunciado" rows="10"></textarea>
        </div>
    </div>
</div>

<h4 class="form-section" style="padding-top: 15px;" id=''><i class="ft-check"></i> Información de Preguntas</h4>

<div id="MensInf">
    <div class="bs-callout-warning callout-bordered mt-1">
        <div class="media align-items-stretch">
            <div class="media-body p-1 center">
                <strong>Utilice este espacio para crear sus Preguntas</strong>
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
            <button type="button" class="btn btn-success btn-min-width dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><i class="fa fa-check"></i> Contenido Para
                Agregar</button>
            <div class="dropdown-menu" id="Bts_Preg">
                <a class="dropdown-item" onclick="$.AddPregOpcMultiple();">Agregar Pregunta Opción Multiple</a>
            </div>
        </div>
    </div>
</div>

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                <button class="btn btn-outline-primary" onclick="$.GuardarPregunta();" id="Btn_Guardar"
                    href="#" title="Guardar" type="button">
                    <i class="fa fa-save"></i> Guardar y Cerrar
                </button>
                @if ($opc != 'editar')
                    <a class="btn btn-outline-warning" href="{{ url('/ModuloE/NuevoTema') }}" title="Cancelar">
                        <i class="fa fa-close"></i> Cancelar
                    </a>
                @endif
                <a class="btn btn-outline-dark" href="javascript:history.go(-1)" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
