@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formTema', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" name="tema_id" id="tema_id" value="{{ $Tema->id }}" />
<input type="hidden" class="form-control" name="comp" id="comp" value="{{ $Tema->componente }}" />
<input type="hidden" class="form-control" name="tema_img" id="tema_img" value="" />
<input type="hidden" class="form-control" name="tema_vid" id="tema_vid" value="" />
<input type="hidden" id="vidanima" name="vidanima" value="{{ $Tema->ulr_animacion }}" />
<input type="hidden" class="form-control" id="ConsAnima" value="2" />

<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
<input type="hidden" data-id='id-datimg' id="datimg"
    data-ruta="{{ asset('/app-assets/images/Imagen_Tema_ModuloE') }}" />
<input type="hidden" data-id='id-datvid' id="datvid" data-ruta="{{ asset('/app-assets/Video_Tema_ModuloE') }}" />
<input type="hidden" data-id='id-datvid2' id="datvid2" data-ruta="{{ asset('/app-assets/Contenido_Didactico_ME') }}" />




<h4 class="form-section"><i class="ft-grid"></i> Datos del Tema</h4>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="modulo">Asignaturas:</label>
            <select class="form-control select2" data-placeholder="Seleccione" onchange="$.CargarComponentes(this.value);" name="asignatura" id="asignatura">
                <option value="">Seleccione una Asignatura</option>
                @foreach ($Asignaturas as $Asig)
                    {
                    @if ($Asig->id == $Tema->asignatura)
                        <option value="{{ $Asig->id }}" {{ Input::old('modulo') == $Asig->id ? 'selected' : '' }}
                            selected>{{ $Asig->nombre . ' - Grado ' . $Asig->grado . '°' }}</option>
                    @else
                        <option value="{{ $Asig->id }}" {{ Input::old('modulo') == $Asig->id ? 'selected' : '' }}>
                            {{ $Asig->nombre . ' - Grado ' . $Asig->grado . '°' }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="tipo_contenido">Tipo Contenido:</label>
            <select class="form-control select2" onchange='$.TipDoc(this.value)' data-placeholder="Seleccione"
                name="tipo_contenido" id="tipo_contenido">
                <option value="" @if (Input::old('tipo_contenido', $Tema->tipo_contenido) == '') selected="selected" @endif>Seleccionar</option>
                <optgroup label="Elementos del Contenido del Tema">
                    <option value="DOCUMENTO" @if (Input::old('tipo_contenido', $Tema->tipo_contenido) == 'DOCUMENTO') selected="selected" @endif>DOCUMENTO
                    </option>
                    <option value="IMAGEN" @if (Input::old('tipo_contenido', $Tema->tipo_contenido) == 'IMAGEN') selected="selected" @endif>IMAGEN</option>
                    <option value="VIDEO" @if (Input::old('tipo_contenido', $Tema->tipo_contenido) == 'VIDEO') selected="selected" @endif>VIDEO</option>
            </select>
        </div>
    </div>


    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="animacion">Animación:</label>
            <select class="form-control select2" onchange='$.HabiContDid(this.value)' data-placeholder="Seleccione"
                name="animacion" id="animacion">
                <option value="" @if (Input::old('animacion', $Tema->animacion) == '') selected="selected" @endif>Seleccionar</option>
                <option value="SI" @if (Input::old('animacion', $Tema->animacion) == 'SI') selected="selected" @endif>SI</option>
                <option value="NO" @if (Input::old('animacion', $Tema->animacion) == 'NO') selected="selected" @endif>NO</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4 class="form-section" id=''><i class="ft-edit"></i> Información del Tema</h4>
        <div class="row">
            <div class="col-md-7" id='rowtit'>
                <div class="form-group">
                    <label class="form-label" for="titu_contenido">Título:</label>
                    {!! Form::text('titulo', old('titulo', $Tema->titulo), ['class' => 'form-control', 'placeholder' => 'Titulo del Tema', 'id' => 'titulo', 'style' => 'text-transform: uppercase']) !!}
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label class="form-label" for="animacion">Componente:</label>
                    <select class="form-control select2" data-placeholder="Seleccione" name="componente" id="componente">
                      
                    </select>
                </div>
            </div>
        </div>

        <div class="row" id='Div_Cont_Doc' style="display:none">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Contenido:</label>
                    <textarea cols="80" id="summernoteCont" name="summernoteCont" rows="10"></textarea>

                    <br>
                </div>
            </div>

        </div>




        <div class="row" style="display: none;" id="Div_Cont_Img">
            <div class="col-md-12">
                <div class="col-md-12">
                    <label class="form-label bold"><strong>Adjuntar Imagen:</strong></label>
                    <div class="col-md-11" id="CargImg">
                        <input id="ImageFile" accept="image/*" class="form-control" multiple="multiple"
                            name="ImageFile[]" type="file">

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

                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="row" style="display: none;" id="Div_Cont_Vid">
            <div class="col-md-12">
                <div class="col-md-9">
                    <label class="form-label bold"><strong>Adjuntar Video:</strong></label>
                    <div class="col-md-11" id="CargVid">
                        <input id="VideoFile" class="form-control" accept="video/*" name="VideoFile[]" type="file">
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12 pt-2" style="display: none;" id="Div_ContDidactico">
            <div class="row">
                <div class="col-md-9">
                    <label class="form-label bold"><strong>Adjuntar Animaciones:</strong></label>
                </div>
                <div class="col-md-3">
                    <button type="button" id="AddAnimaciones" class="btn mr-1 mb-1 btn-success"><i
                            class="fa fa-plus"></i> Agregar Otra Animación</button>
                </div>

            </div>
            <div id="Arch_Didact" class="row">
                <div class="col-md-11">
                    <input id="file" class="form-control SelAnima" accept="video/*" name="archididatico[]" type="file">
                </div>
                <div class="col-md-1">

                </div>
            </div>
            @if ($method == 'put')
                <div class="col-md-12">
                    <h6 class="form-section"><strong>Videos Agregados</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_imagenes">
                            {!! $tr_Vid !!}
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
            aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h4 class="modal-title" style="text-transform: capitalize;" id="titu_temaEva">Contenido
                            Didactico Cargado</h4>
                    </div>
                    <div class="modal-body">
                        <div id='ListEval' style="height: 400px; overflow: auto;text-align: center;">
                            <video id="videoclip" width="640" height="360" controls="controls" title="Video title">
                                <source id="mp4video" src="" type="video/mp4" />
                            </video>
                        </div>

                        <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                            data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                    </div>
                </div>
            </div>
        </div>





        <div class="row" id='Cont_didactico' style="display:none">
            @if ($method == 'put')
                <div class="col-lg-12" id="Div_ArcDidac" style="display:none">

                    <div class="bs-callout-success callout-bordered mt-1">
                        <div class="media align-items-stretch">
                            <div class="d-flex align-items-center bg-success p-2">
                                <i class="fa fa-video-camera white font-medium-5"></i>
                            </div>
                            <div class="media-body p-1">
                                <strong>Seleccionar Animación</strong>
                                <div class="form-group">

                                    <label id="projectinput7" class="file center-block">
                                        <input id="archidida" name="archidida[]" type="file">
                                        <span class="file-custom"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" id="Div_VerImg">
                    <div class="form-group">
                        <label class="form-label bold"><strong>Animación: </strong></label><br />
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" onclick="$.MostVid();" class="btn btn-success"><i
                                    class="fa fa-search"></i> Ver Contenido Didactico</button>
                            <button type="button" onclick="$.CambVid();" class="btn btn-warning"><i
                                    class="fa fa-refresh"></i> Cambiar Contenido</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-12" id="Div_ArcDidac">
                    <div class="bs-callout-success callout-bordered mt-1">
                        <div class="media align-items-stretch">
                            <div class="d-flex align-items-center bg-success p-2">
                                <i class="fa fa-video-camera white font-medium-5"></i>
                            </div>
                            <div class="media-body p-1">
                                <strong>Seleccionar Animación</strong>
                                <div class="form-group">
                                    <label id="projectinput7" class="file center-block">
                                        <input id="archidida" name="archidida[]" type="file">
                                        <span class="file-custom"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                        <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                            data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-left" id="CarImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
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
                            <div id="div_img">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                            data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-left" id="CarVid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titu_tema">Video Cargado</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id='cont_archi' style="height: 400px; width:100%; overflow: auto;">
                            <video id="videoclip" style="height: 400px; width:100%;" controls="controls"
                                title="Video title">
                                <source id="mp4video" src="" type="video/mp4" />
                            </video>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                            data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                    </div>
                </div>
            </div>
        </div>




    </div>

</div>

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                <button class="btn btn-outline-primary" disabled onclick="$.GuardarTema();" id="Btn_Guardar" href="#"
                    title="Guardar" type="button">
                    <i class="fa fa-save"></i> Guardar
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
