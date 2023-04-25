@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formAlum', 'files' => true]) !!}
{{ csrf_field() }}
<h4 class="form-section"><i class="ft-user"></i> Información del Usuario</h4>


<input type="hidden" class="form-control" name="tipo_usuario" placeholder="Repetir Contraseña" value="Estudiante" />
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label class="form-label" for="nombre_usuario">Nombre y Apellido:</label>
            {!! Form::text('nombre_usuario', old('nombre_usuario', $Usuarios->nombre_usuario), ['class' => 'form-control', 'placeholder' => 'Nombre y Apellido', 'id' => 'nombre_usuario']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="email_usuario">Email:</label>
            {!! Form::text('email_usuario', old('email', $Usuarios->email_usuario), ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email', 'onchange' => 'return validarEmail(event)']) !!}
        </div>
    </div>


    <div class="col-md-4">
        <div class="form-group">
            {{ Form::label('Tipo de Usuario', 'Tipo de Usuario:', ['class' => 'form-label']) }}
            <select name="tipo_usuario" onchange="$.ValTipUsu(this.value);" id="tipo_usuario" class="form-control select2">
                <option value="">-- Seleccionar --</option>
                <option value="Administrador" @if (Input::old('tipo_usuario', $Usuarios->tipo_usuario) == 'Administrador') selected="selected" @endif>Administrador</option>
                <option  value="Estudiante" @if (Input::old('tipo_usuario', $Usuarios->tipo_usuario) == 'Estudiante') selected="selected" @endif>Estudiante</option>
                <option readonly  value="Profesor" @if (Input::old('tipo_usuario', $Usuarios->tipo_usuario) == 'Profesor') selected="selected" @endif>Profesor</option>
            </select>

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {{ Form::label('Estado', 'Estado:', ['class' => 'form-label']) }}
            <select name="estado_usuario" id="estado_usuario" class="form-control select2">
                <option value="">-- Seleccionar --</option>
                <option value="ACTIVO" @if (Input::old('estado_usuario', $Usuarios->estado_usuario) == 'ACTIVO') selected="selected" @endif>ACTIVO</option>
                <option value="INACTIVO" @if (Input::old('estado_usuario', $Usuarios->estado_usuario) == 'INACTIVO') selected="selected" @endif>INACTIVO</option>
            </select>

        </div>
    </div>

</div>



<h4 class="form-section"><i class="ft-unlock"></i> Información de Usuario.</h4>

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="login_usuario">Usuario:</label>
            {!! Form::text('login_usuario', old('login_usuario', $Usuarios->login_usuario), ['class' => 'form-control', 'placeholder' => 'Usuario', 'id' => 'usuario_alumno']) !!}
        </div>
    </div>
<input type="hidden" value="{{$method}}" id="proc"/>
    @if ($method == 'put')
        <div class="col-md-3" id="div-passw" style="display: none;">
            <div class="form-group">
                <label class="form-label" for="password">Contraseña:</label>
                <input type="password" class="form-control" name="password" placeholder="Contraseña" value='' />
            </div>
        </div>
        <div class="col-md-3" id="div-passw2" style="display: none;">
            <div class="form-group">
                <label class="form-label" for="passw_alumno2">Repetir Contraseña:</label>
                <input type="password" class="form-control" name="password_confirmation"
                    placeholder="Repetir Contraseña" value='' />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" for="passw_alumno2">Cambiar Contraseña:</label>
                <select name="cambi_passw" onchange="$.CambioPassw(this.value);" id="cambi_passw" class="form-control select2">
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
        </div>

    @else
    <div class="col-md-3" id="div-passw">
        <div class="form-group">
            <label class="form-label" for="password">Contraseña:</label>
            <input type="password" class="form-control" name="password" placeholder="Contraseña" value='' />
        </div>
    </div>
    <div class="col-md-3" id="div-passw2" >
        <div class="form-group">
            <label class="form-label" for="passw_alumno2">Repetir Contraseña:</label>
            <input type="password" class="form-control" name="password_confirmation"
                placeholder="Repetir Contraseña" value='' />
        </div>
    </div>
    @endif
</div>


<div class="form-actions">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                @if ($opc != 'Consulta')
                    <button class="btn btn-outline-primary" href="#" onclick="$.Guardar();" title="Guardar" type="button">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <a class="btn btn-outline-warning" href="{{ url('/Usuarios/Nuevo') }}" title="Cancelar">
                        <i class="fa fa-close"></i> Cancelar
                    </a>
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/Usuarios/Gestion') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
