@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formAlum', 'files' => true]) !!}
{{ csrf_field() }}
<h4 class="form-section"><i class="ft-user"></i> Datos Personales</h4>
<input type="hidden" id="fotoalumno" name="fotoalumno" value="{{ $Alumno->foto_alumno }}" />
<input type="hidden" data-id='id-dat' id="dat" data-ruta="{{ asset('/app-assets/images/Img_Estudiantes') }}" />
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="ident_alumno">Identificación:</label>
            {!! Form::text('ident_alumno', old('identificacion', $Alumno->ident_alumno), ['class' => 'form-control', 'placeholder' => 'Identificación', 'id' => 'ident_alumno', 'onkeypress' => 'return validartxtnum(event)']) !!}
            <input type="hidden" name="id" value="{{ $Alumno->id }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="nombre_alumno">Nombre:</label>
            {!! Form::text('nombre_alumno', old('nombre', $Alumno->nombre_alumno), ['class' => 'form-control', 'placeholder' => 'Nombre', 'id' => 'nombre_alumno', 'style' => 'text-transform: uppercase', 'onkeypress' => 'return validartxt(event)']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="nombre">Apellido:</label>
            {!! Form::text('apellido_alumno', old('apellido', $Alumno->apellido_alumno), ['class' => 'form-control', 'placeholder' => 'Apellido', 'id' => 'apellido_alumno', 'style' => 'text-transform: uppercase', 'onkeypress' => 'return validartxt(event)']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('Sexo', 'Sexo:', ['class' => 'form-label']) }}
            <select name="sexo_alumno" id="sexo_alumno" class="form-control select2">
                <option value="">-- Seleccionar --</option>
                <option value="Masculino" @if (Input::old('sexo_alumno', $Alumno->sexo_alumno) == 'Masculino') selected="selected" @endif>Masculino</option>
                <option value="Femenino" @if (Input::old('sexo_alumno', $Alumno->sexo_alumno) == 'Femenino') selected="selected" @endif>Femenino</option>
            </select>

        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label " for="fnacimiento">Fecha Nacimiento:</label>
            {!! Form::text('fnacimiento', old('fnacimiento', $Alumno->nacimiento_alumno), ['class' => 'form-control', 'placeholder' => 'Ingresar Fecha', 'id' => 'fnacimiento']) !!}

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="direccion_alumno">Dirección:</label>
            {!! Form::text('direccion_alumno', old('direccion_alumno', $Alumno->direccion_alumno), ['class' => 'form-control', 'placeholder' => 'Dirección', 'id' => 'direccion']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="telefono_alumno">Télefono:</label>
            {!! Form::text('telefono_alumno', old('telefono_alumno', $Alumno->telefono_alumno), ['class' => 'form-control', 'placeholder' => 'Télefono', 'id' => 'puesto', 'onkeypress' => 'return validartxtnum(event)']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="email_alumno">Email:</label>
            {!! Form::text('email_alumno', old('email', $Alumno->email_alumno), ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email', 'onchange' => 'return validarEmail(event)']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group" id="id_file" style="display:none;">
            <label class="form-label " for="imagen">Cargar Foto Estudiante:</label>
            <input type="file" name="imagen[]" />
        </div>
        <div class="form-group" id="id_verf">
            <label class="form-label " for="imagen">Cargar Foto Estudiante:</label>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" onclick="$.VerFotEst();" class="btn btn-success"><i
                        class="fa fa-search"></i> Ver Foto</button>
                <button type="button" onclick="$.CambFotEst();" class="btn btn-warning"><i
                        class="fa fa-refresh"></i> Cambiar Foto</button>
            </div>
        </div>
    </div>
</div>
<h4 class="form-section"><i class="ft-unlock"></i> Información de Usuario</h4>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="usuario_alumno">Usuario:</label>
            {!! Form::text('login_usuario', old('login_usuario', $Alumno->login_usuario), ['class' => 'form-control', 'placeholder' => 'Usuario', 'id' => 'usuario_alumno']) !!}
        </div>
    </div>
</div>


<div class="modal fade text-left" id="CargFoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
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

<div class="form-actions">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                <button class="btn btn-outline-primary" href="#" title="Guardar" type="submit">
                    <i class="fa fa-save"></i> Guardar
                </button>
                <a class="btn btn-outline-warning" href="{{ url('/perfil') }}" title="Cancelar">
                    <i class="fa fa-close"></i> Cancelar
                </a>
                <a class="btn btn-outline-dark" href="{{ url('/Administracion') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
