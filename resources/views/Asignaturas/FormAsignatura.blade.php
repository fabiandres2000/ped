@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formAsig', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" name="modulo_id" value="" />
<input type="hidden" id="ConsPer" value="{{ $i }}" />
<input type="hidden" id="asignatura" value="{{ $Modulo->asignatura }}" />
<input type="hidden" id="idModulo" value="{{$Modulo->id }}" />
<input type="hidden" data-id='id-dat' id="dat" data-ruta="{{ asset('/app-assets/images/Img_Modulos') }}" />
<h4 class="form-section"><i class="ft-grid"></i> Información de Grados y Cursos</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="nombre_modulo">Área:</label>
            <select class="form-control select2" onchange="$.CargAsignaturas(this.value)"
                data-placeholder="Seleccione..." name="area" id="area">
                <option value="">Seleccione el Area</option>
                @foreach ($Areas as $Area)
                      @if ($Area->id == $Modulo->area)
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
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="nombre_modulo">Asignatura:</label>
            <select class="form-control select2" data-placeholder="Seleccione" name="nombre" id="nombre">

            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('Grado', 'Grado:', ['class' => 'form-label']) }}
            <select class="form-control select2" data-placeholder="Seleccione" name="grado_modulo" id="grado_modulo">
                <option value="">Seleccione el Grado</option>
                @for ($i = 1; $i <= 11; $i++)
                    @if ($i == $Modulo->grado_modulo)
                        <option value="{{ $i }}" {{ Input::old('grado_modulo') == $i ? 'selected' : '' }}
                            selected>{{ 'GRADO ' . $i }}</option>
                    @else
                        <option value="{{ $i }}"
                            {{ Input::old('grado_modulo') == $i ? 'selected' : '' }}>{{ 'GRADO ' . $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="nombre_modulo">Grupos:</label>
            <select class="select2-tags form-control" name="grupos[]" data-placeholder="Seleccione" id="grupos"
                multiple="" id="select2-tags">
                {!! $SelGrupos !!}
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">Presentación:</label>
                {!! Form::textarea('presentacion_modulo', old('presentacion_modulo', $Modulo->presentacion_modulo), ['class' => 'form-control', 'placeholder' => 'Presentción', 'id' => 'presentacion_modulo', 'rows' => 5]) !!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="objetivo_modulo">Objetivo:</label>
            {!! Form::textarea('objetivo_modulo', old('objetivo_modulo', $Modulo->objetivo_modulo), ['class' => 'form-control', 'placeholder' => 'Objetivo', 'id' => 'objetivo_modulo', 'rows' => 5]) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('imagen', 'Imagenes', ['class' => 'form-label']) }}
            <input type="file" id="imagen" name="imagen[]" accept="image/*" multiple />
        </div>
    </div>

    @if ($method == 'put')
        <div class="col-md-12">
            <h6 class="form-section"><strong>Imagenes Agregadas</strong> </h6>
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tr_imagenes">
                    {!! $tr_img !!}
                </tbody>
            </table>
        </div>
    @endif

</div><br>

<div class="row">
    <div class="col-md-6">
        <h6 class="form-section"><strong>Información de Periodos</strong> </h6>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="periodo_modulo">Periodo:</label>
                    <select class="form-control select2" data-placeholder="Seleccione" id="periodo_modulo">
                        <option value="">Seleccionar...</option>
                        <option value="Periodo 1">Periodo 1</option>
                        <option value="Periodo 2">Periodo 2</option>
                        <option value="Periodo 3">Periodo 3</option>
                        <option value="Periodo 4">Periodo 4</option>
                        <option value="Periodo 5">Periodo 5</option>

                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">% de Avance:</label>
                    <input type="text" maxlength="3" onkeypress="return validartxtnum(event)" class="form-control"
                        id="porc_avance" placeholder="% de Avance en la Asignatura" value="" />
                </div>
            </div>
            <div class="col-md-12 float-right">
                <div class="form-group" id="Butto_add">
                    <label class="form-label" for="grado_modulo"></label>
                    <input type="hidden" id="per_sel" value="" />
                    <button id="UpdatePeriodo" onclick="$.UpdatPer()" style="display:  none;" type="button"
                        class="btn mr-1 mb-1 btn-info"><i class="fa fa-plus"></i> Actualizar Periodo</button>
                    <button id="AddPeriodo" type="button" class="btn mr-1 mb-1 btn-success"><i
                            class="fa fa-plus"></i> Agregar Periodo</button>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h6 class="form-section"><strong>Periodos Agregados</strong> </h6>
        <table class="table table-hover mb-0" id="table_periodos">
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>% Avance</th>
                    <th>Opcion</th>
                </tr>
            </thead>
            <tbody id="tr_periodos">
                {!! $trPer !!}
            </tbody>
        </table>
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

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                @if ($opc != 'Consulta')
                    <button class="btn btn-outline-primary" href="#" title="Guardar" onclick="$.Guardar();" type="button">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    @if ($opc != 'editar')
                        <a class="btn btn-outline-warning" href="{{ url('/Asignaturas/NuevoModulo') }}"
                            title="Cancelar">
                            <i class="fa fa-close"></i> Cancelar
                        </a>
                    @endif
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/Asignaturas/GestionGrado') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
