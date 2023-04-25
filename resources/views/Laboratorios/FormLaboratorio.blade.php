@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formLaboratorio', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" name="labo_id" id="labo_id" value="{{ $Laboratorio->id }}" />
<input type="hidden" class="form-control" id="ConsProc" value="0" />
<input type="hidden" class="form-control" id="labo_modulo" value="{{ $Laboratorio->modulo }}" />
<input type="hidden" class="form-control" id="labo_periodo" value="{{ $Laboratorio->periodo }}" />
<input type="hidden" class="form-control" id="labo_unidad" value="{{ $Laboratorio->unidad }}" />
<input type="hidden" data-id='id-dat' id="dat-vid" data-ruta="{{ asset('/app-assets/Contenido_Laboratorio/') }}" />

<h4 class="form-section"><i class="ft-grid"></i> Datos del Laboratorio.</h4>


<div class="row">

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="modulo">Asignatura:</label>
            <select class="form-control select2" onchange="$.CargPeriodos(this.value)" data-placeholder="Seleccione"
                name="modulo" id="modulo">
                <option value="">Seleccione una Asignatura</option>
                @foreach ($Asigna as $Asig)
                    {
                    @if ($Asig->id == $Laboratorio->modulo)
                        <option value="{{ $Asig->id }}" {{ Input::old('modulo') == $Asig->id ? 'selected' : '' }}
                            selected>{{ $Asig->nombre . ' - Grado ' . $Asig->grado_modulo . '°' }}</option>
                    @else
                        <option value="{{ $Asig->id }}"
                            {{ Input::old('modulo') == $Asig->id ? 'selected' : '' }}>
                            {{ $Asig->nombre . ' - Grado ' . $Asig->grado_modulo . '°' }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="periodo">Periodo:</label>
            <select class="form-control select2" onchange='$.CargUnidades(this.value)' data-placeholder="Seleccione"
                id="periodo" name="periodo">

            </select>
        </div>
    </div>
    <div class="col-md-9">
        <div class="form-group">
            <label class="form-label" for="unidad">Unidad:</label>
            <select class="form-control select2" data-placeholder="Seleccione" id='unidad' name="unidad">

            </select>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <h4 class="form-section" id=''><i class="ft-edit"></i> Información del Laboratorio</h4>
        <div class="row">
            <div class="col-md-10" id='rowtit'>
                <div class="form-group">
                    <label class="form-label" for="titulo">Título:</label>
                    {!! Form::text('titulo', old('titulo', $Laboratorio->titulo), ['class' => 'form-control', 'placeholder' => 'Titulo del Laboratorio', 'id' => 'titulo', 'style' => 'text-transform: uppercase']) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="objetivo">Objetivo:</label>
                    {!! Form::textarea('objetivo', old('objetivo', $Laboratorio->objetivo), ['class' => 'form-control', 'placeholder' => 'Objetivo', 'id' => 'objetivo', 'style' => 'text-transform: uppercase', 'rows' => 4]) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="objetivo">Fundamento Teorico:</label>
                    <textarea cols="80" id="summernoteTeoria" name="summernoteTeoria" rows="10"></textarea>

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="objetivo">Materiales:</label>
                    <textarea cols="80" id="summernoteMateriales" name="summernoteMateriales" rows="10"></textarea>

                </div>
            </div>

        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h4 class="form-section" id=''><i class="ft-edit"></i> Procedimientos</h4>
        <div id="DivProcedimientos">

        </div>
        <div class="col-md-12">
            <div class="form-group float-right"><br>
                <label class="form-label" for="grado_modulo"></label>
                <button id="AddProc" type="button" class="btn mr-1 mb-1 btn-success"><i class="fa fa-plus"></i>
                    Agregar Procedimiento</button>
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







<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">

                <button class="btn btn-outline-primary" id="Btn_Guardar" onclick="$.GuardarLabo();" title="Guardar"
                    type="button">
                    <i class="fa fa-save "></i> Guardar
                </button>
                <a class="btn btn-outline-warning" href="{{ url('/Asignaturas/NuevoTema') }}" title="Cancelar">
                    <i class="fa fa-close"></i> Cancelar
                </a>

                <a class="btn btn-outline-dark" href="javascript:history.go(-1)" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
