{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formProf', 'files' => true]) !!}
{{ csrf_field() }}
<h4 class="form-section"><i class="ft-user"></i> Datos Personales</h4>
<input type="hidden" id="fotodoce" name="fotodoce" value="{{ $Profesores->foto }}" />
<input type="hidden" id="jornada" name="jornada" value="{{ $Profesores->jornada }}" />
<input type="hidden" data-id='id-dat' id="dat" data-ruta="{{ asset('/app-assets/images/Img_Docentes') }}" />
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="identificacion">Identificación:</label>
            {!! Form::text('identificacion', old('identificacion', $Profesores->identificacion), ['class' => 'form-control', 'placeholder' => 'Identificación', 'id' => 'identificacion', 'onkeypress' => 'return validartxtnum(event)']) !!}
            <input type="hidden" name="id" value="{{ $Profesores->id }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="nombre">Nombre:</label>
            {!! Form::text('nombre', old('nombre', $Profesores->nombre), ['class' => 'form-control', 'placeholder' => 'Nombre', 'id' => 'nombre', 'style' => 'text-transform: uppercase', 'onkeypress' => 'return validartxt(event)']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="apellido">Apellido:</label>
            {!! Form::text('apellido', old('apellido', $Profesores->apellido), ['class' => 'form-control', 'placeholder' => 'Apellido', 'id' => 'apellido', 'style' => 'text-transform: uppercase', 'onkeypress' => 'return validartxt(event)']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="direccion">Dirección:</label>
            {!! Form::text('direccion', old('direccion', $Profesores->direccion), ['class' => 'form-control', 'placeholder' => 'Dirección', 'id' => 'direccion']) !!}
        </div>
    </div>

</div>
<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="telefono">Télefono:</label>
            {!! Form::text('telefono', old('telefono', $Profesores->telefono), ['class' => 'form-control', 'placeholder' => 'Télefono', 'id' => 'telefono', 'onkeypress' => 'return validartxtnum(event)']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="email">email:</label>
            {!! Form::text('email', old('email', $Profesores->email), ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email', 'onchange' => 'return validarEmail(event)']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group" id="id_file" style="display:none;">
            <label class="form-label " for="imagen">Cargar Foto Docente:</label>
            <input type="file" name="imagen[]" />
        </div>
        <div class="form-group" id="id_verf">
            <label class="form-label " for="imagen">Cargar Foto Docente:</label>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" onclick="$.VerFotDoce();" class="btn btn-success"><i class="fa fa-search"></i>
                    Ver
                    Foto</button>
                <button type="button" onclick="$.CambFotDoce();" class="btn btn-warning"><i class="fa fa-refresh"></i>
                    Cambiar Foto</button>
            </div>
        </div>
    </div>
</div>


<h4 class="form-section"><i class="ft-unlock"></i> Información de Usuario</h4>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="usuario_profesor">Usuario:</label>
            {!! Form::text('login_usuario', old('login_usuario', $Profesores->login_usuario), ['class' => 'form-control', 'placeholder' => 'Usuario', 'id' => 'usuario_profesor']) !!}
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
{!! Form::close() !!}
