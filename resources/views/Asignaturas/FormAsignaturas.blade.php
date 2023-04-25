@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formAsig', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" name="modulo_id" value="" />
<input type="hidden" data-id='id-dat' id="dat" data-ruta="{{ asset('/app-assets/images/Img_Asinaturas') }}" />
<h4 class="form-section"><i class="ft-grid"></i> Información de Asignatura</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="nombre_modulo">Área:</label>
            <select class="form-control select2" data-placeholder="Seleccione..." name="area" id="area">
                <option value="">Seleccione el Area</option>
                @foreach ($Areas as $Area)
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
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="direccion_alumno">Nombre Asignatura:</label>
            {!! Form::text('nombre', old('nombre', $Asig->nombre), ['class' => 'form-control', 'placeholder' => 'Nombre de Asignatura', 'id' => 'nombre']) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">Descripción:</label>
                {!! Form::textarea('descripcion', old('descripcion', $Asig->descripcion), ['class' => 'form-control', 'placeholder' => 'Descripción', 'id' => 'descripcion', 'rows' => 5]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('imagen', 'Imagenes: ', ['class' => 'form-label']) }}
            <input type="file" id="imagen" name="imagen[]" accept="image/*"  multiple />
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
                    <button class="btn btn-outline-primary" href="#" title="Guardar" onclick="$.Guardar();"
                        type="button">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    @if ($opc != 'editar')
                        <a class="btn btn-outline-warning" href="{{ url('/Asignaturas/NuevaAsig') }}"
                            title="Cancelar">
                            <i class="fa fa-close"></i> Cancelar
                        </a>
                    @endif
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/Asignaturas/GestionAsignaturas') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
