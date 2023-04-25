@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formAsig', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" name="asignatura_id" value="" />
<input type="hidden" id="img_asig" name="img_asig" value="{{ $Asig->imagen }}" />
<input type="hidden" data-id='id-dat' id="dat" data-ruta="{{ asset('/app-assets/images/Img_ModuloE') }}" />
<input type="hidden" id="ConsComp" value="{{ $icompe }}" />
<input type="hidden" id="ConsComponente" value="{{ $icompo }}" />

<h4 class="form-section"><i class="ft-grid"></i> Información de Asignatura</h4>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="nombre_area">Área:</label>
            <select class="form-control select2" data-placeholder="Seleccione..." name="area" id="area">
                <option value="">Seleccione el Area</option>
                @foreach ($Areas as $Area)
                    {
                    @if ($Area->id == $Asig->area)
                        <option value="{{ $Area->id }}" {{ Input::old('area') == $Area->id ? 'selected' : '' }}
                            selected>{{ $Area->nombre_area }}</option>
                    @else
                        <option value="{{ $Area->id }}" {{ Input::old('area') == $Area->id ? 'selected' : '' }}>
                            {{ $Area->nombre_area }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="direccion_alumno">Nombre Asignatura:</label>
            {!! Form::text('nombre', old('nombre', $Asig->nombre), [
                'class' => 'form-control',
                'placeholder' => 'Nombre de la Asignatura',
                'id' => 'nombre',
            ]) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group" id='HabConDidact'>
            <label class="form-label" for="tip_eval">Grado:</label><br>
            <select class="form-control select2" style="width: 100%;" onchange="$.CargCompeCompo(this.value);"
                data-placeholder="Seleccione" name="grado" id="grado">
                <option value="" @if (Input::old('grado', $Asig->grado) == '') selected="selected" @endif>Seleccione...
                </option>
                <option value="3" @if (Input::old('grado', $Asig->grado) == '3') selected="selected" @endif>Grado 3°</option>
                <option value="5" @if (Input::old('grado', $Asig->grado) == '5') selected="selected" @endif>Grado 5°</option>
                <option value="7" @if (Input::old('grado', $Asig->grado) == '7') selected="selected" @endif>Grado 7°</option>
                <option value="9" @if (Input::old('grado', $Asig->grado) == '9') selected="selected" @endif>Grado 9°</option>
                <option value="11" @if (Input::old('grado', $Asig->grado) == '11') selected="selected" @endif>Grado 11°</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="nombre_docente">Docente Encargado:</label>
            <select class="form-control select2" name="docente" id="docente">
                <option value="">Seleccione el Docente Encargado</option>
                <option value="0">Sin Asignar</option>
                @foreach ($Docentes as $Doce)
                    {
                    @if ($Doce->id == $Asig->docente)
                        <option value="{{ $Doce->id }}" {{ Input::old('docente') == $Doce->id ? 'selected' : '' }}
                            selected>{{ $Doce->nombre . ' ' . $Doce->apellido }}</option>
                    @else
                        <option value="{{ $Doce->id }}"
                            {{ Input::old('docente') == $Doce->id ? 'selected' : '' }}>
                            {{ $Doce->nombre . ' ' . $Doce->apellido }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">Descripción:</label>
                {!! Form::textarea('descripcion', old('descripcion', $Asig->descripcion), [
                    'class' => 'form-control',
                    'placeholder' => 'Descripción',
                    'id' => 'descripcion',
                    'rows' => 5,
                ]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @if ($opc == 'nuevo')
            <div class="form-group">
                <label class="form-label " for="imagen">Cargar Imagen:</label>
                <input type="file" accept="image/*" id="image" name="imagen[]" />
            </div>
        @elseif($opc == 'editar')
            <div class="form-group" id="id_file" style="display:none;">
                <label class="form-label " for="imagen">Cargar Imagen:</label>
                <input type="file" accept="image/*" id="image" name="imagen[]" />
            </div>
            <div class="form-group" id="id_verf">
                <label class="form-label " for="imagen">Cargar Imagen:</label>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" onclick="$.VerFotEst();" class="btn btn-success"><i class="fa fa-search"></i>
                        Ver Imagen</button>
                    <button type="button" onclick="$.CambFotEst();" class="btn btn-warning"><i
                            class="fa fa-refresh"></i> Cambiar Imagen</button>
                </div>
            </div>
        @else
            <div class="form-group" id="id_verf">
                <label class="form-label " for="imagen">Ver Imagen:</label>
                <br>
                <button type="button" onclick="$.VerFotEst();" class="btn btn-success"><i class="fa fa-search"></i>
                    Ver Foto</button>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h6 class="form-section"><strong>Información de Competencias</strong> </h6>

        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <select class="form-control select2" data-placeholder="Seleccione" disabled name="competencia"
                        id="competencia">
                        <option value="">Seleccione la Competencia</option>

                    </select>
                </div>
            </div>

            <div class="col-md-2 float-right">
                <div class="form-group" id="Butto_add">
                    <label class="form-label" for="grado_modulo"></label>
                    <input type="hidden" id="per_sel" value="" />
                    <button id="AddCompe" type="button" class="btn mr-1 mb-1 btn-success"><i
                            class="fa fa-plus"></i>
                        Agregar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <h6 class="form-section"><strong>Competencias Agregadas.</strong> </h6>
        <table class="table table-hover mb-0" id="table_periodos">
            <thead>
                <tr>
                    <th>Competencia</th>
                    <th>Opción</th>
                </tr>
            </thead>
            <tbody id="tr_compe">
                {!! $trComp !!}
            </tbody>
        </table>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <h6 class="form-section"><strong>Información de Componentes</strong> </h6>

        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <select class="form-control select2" data-placeholder="Seleccione" disabled name="componente"
                        id="componente">
                        <option value="">Seleccione el Componente</option>

                    </select>
                </div>
            </div>
            <div class="col-md-2 float-right">
                <div class="form-group" id="Butto_add">
                    <label class="form-label" for="grado_modulo"></label>
                    <input type="hidden" id="per_sel" value="" />
                    <button id="AddCompo" type="button" class="btn mr-1 mb-1 btn-success"><i
                            class="fa fa-plus"></i>
                        Agregar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <h6 class="form-section"><strong>Componentes Agregados.</strong> </h6>
        <table class="table table-hover mb-0" id="table_componentes">
            <thead>
                <tr>
                    <th>Componentes</th>
                    <th>Opción</th>
                </tr>
            </thead>
            <tbody id="tr_compo">
                {!! $trComponentes !!}
            </tbody>
        </table>
    </div>

</div>


<div class="modal fade text-left" id="CargImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                @if ($opc != 'Consulta')
                    <button class="btn btn-outline-primary" onclick="$.GuardarAsig();" id="Btn_Guardar"
                        title="Guardar" type="button">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    @if ($opc != 'editar')
                        <a class="btn btn-outline-warning" href="{{ url('/ModuloE/NuevaAsignatura') }}"
                            title="Cancelar">
                            <i class="fa fa-close"></i> Cancelar
                        </a>
                    @endif
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/ModuloE/GestionAsignaturas') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
