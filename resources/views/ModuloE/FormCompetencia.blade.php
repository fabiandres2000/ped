@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formComp', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" name="competencia_id" value="" />
<h4 class="form-section"><i class="ft-grid"></i> Información de Competencia</h4>
<div class="row">

    <div class="col-md-9">
        <div class="form-group">
            <label class="form-label" for="direccion_alumno">Nombre Competencia:</label>
            {!! Form::text('nombre', old('nombre', $Comp->nombre), ['class' => 'form-control', 'placeholder' => 'Nombre de la Competencia', 'id' => 'nombre']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group" id='HabConDidact'>
            <label class="form-label" for="tip_eval">Grado:</label><br>
            <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione" name="grado"
                id="grado">
                <option value="" @if (Input::old('grado', $Comp->grado) == '') selected="selected" @endif>Seleccione...</option>
                <option value="3" @if (Input::old('grado', $Comp->grado) == '3') selected="selected" @endif>Grado 3°</option>
                <option value="5" @if (Input::old('grado', $Comp->grado) == '5') selected="selected" @endif>Grado 5°</option>
                <option value="7" @if (Input::old('grado', $Comp->grado) == '7') selected="selected" @endif>Grado 7°</option>
                <option value="9" @if (Input::old('grado', $Comp->grado) == '9') selected="selected" @endif>Grado 9°</option>
                <option value="11" @if (Input::old('grado', $Comp->grado) == '11') selected="selected" @endif>Grado 11°</option>
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">Descripción:</label>
                {!! Form::textarea('descripcion', old('descripcion', $Comp->descripcion), ['class' => 'form-control', 'placeholder' => 'Descripción', 'id' => 'descripcion', 'rows' => 5]) !!}
            </div>
        </div>
    </div>

</div><br>

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                @if ($opc != 'Consulta')
                    <button class="btn btn-outline-primary" onclick="$.GuardarComp();" id="Btn_Guardar" title="Guardar"
                        type="button">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    @if ($opc != 'editar')
                        <a class="btn btn-outline-warning" href="{{ url('/ModuloE/NuevaCompetencia') }}"
                            title="Cancelar">
                            <i class="fa fa-close"></i> Cancelar
                        </a>
                    @endif
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/ModuloE/GestionCompetencia') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
