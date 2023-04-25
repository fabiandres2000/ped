@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formUnidad', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" name="tema_id" value="" />
<input type="hidden" class="form-control" id="tema_per" value="{{ $unid->periodo }}" />

<h4 class="form-section"><i class="ft-grid"></i> Datos de la Unidad</h4>
<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            {{ Form::label('Unidad', 'Unidad:', ['class' => 'form-label']) }}
            <select class="form-control select2" data-placeholder="Seleccione" name="nom_unidad" id="nom_unidad">
                <option value="">Seleccione la Unidad</option>
                @for ($i = 1; $i <= 10; $i++)
                    @if ('Unidad ' . $i == $unid->nom_unidad)
                        <option value="{{ 'Unidad ' . $i }}"
                            {{ Input::old('nom_unidad') == 'Unidad ' . $i ? 'selected' : '' }} selected>
                            {{ 'Unidad ' . $i }}</option>
                    @else
                        <option value="{{ 'Unidad ' . $i }}"
                            {{ Input::old('nom_unidad') == 'Unidad ' . $i ? 'selected' : '' }}>{{ 'Unidad ' . $i }}
                        </option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <div class="col-md-9">
        <div class="form-group">
            <label class="form-label" for="des_unidad">Descripción:</label>
            {!! Form::text('des_unidad', old('des_unidad', $unid->des_unidad), ['class' => 'form-control', 'placeholder' => 'Descripción de la Unidad', 'id' => 'des_unidad', 'style' => 'text-transform: uppercase']) !!}
        </div>
    </div>

    <div class="col-md-12" style="display: none;">
        <div class="form-group">
            <label class="form-label" for="introduccion">DBA (Derecho Básico de Aprendizaje):</label>
            {!! Form::textarea('introduccion', old('introduccion', $unid->introduccion), ['class' => 'form-control', 'placeholder' => 'Objetivo', 'id' => 'introduccion', 'rows' => 4]) !!}
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group">
            <label class="form-label" for="modulo">Módulo:</label>
            <select class="form-control select2" onchange="$.CargPeriodos(this.value)" data-placeholder="Seleccione"
                name="modulo" id="modulo">
                <option value="">Seleccione la Asignatura</option>
                @foreach ($Asigna as $Asig) {
                    @if ($Asig->id == $unid->modulo)
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
            <select class="form-control select2" data-placeholder="Seleccione" id="periodo" name="periodo">

            </select>
        </div>
    </div>
</div>


<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                @if ($opc != 'Consulta')
                    <button class="btn btn-outline-primary" href="#" title="Guardar" type="submit">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    @if ($opc != 'editar')
                        <a class="btn btn-outline-warning" href="{{ url('/Modulos/NuevaUnidad') }}" title="Cancelar">
                            <i class="fa fa-close"></i> Cancelar
                        </a>
                    @endif
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/Modulos/GestionUnid') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
