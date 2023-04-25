@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formTema', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" name="tema_id" id="tema_id" value="{{ $Tema->id }}" />
<input type="hidden" class="form-control" id="ConsGrupPreg" value="2" />
<input type="hidden" class="form-control" id="ConsPregMul" value="1" />
<input type="hidden" class="form-control" id="ConsVerFal" value="2" />
<input type="hidden" class="form-control" id="Conslink" value="1" />
<input type="hidden" class="form-control" id="ConsAnima" value="2" />
<input type="hidden" class="form-control" id="ConsOpcMul" value="2" />
<input type="hidden" class="form-control" id="ConsOpcMulPreg" value="2" />
<input type="hidden" class="form-control" id="Id_Eval" name="Id_Eval" value="" />
<input type="hidden" class="form-control" id="Nom_Video" name="Nom_Video" value="" />
<input type="hidden" class="form-control" id="tema_modulo" value="{{ $Tema->modulo }}" />
<input type="hidden" class="form-control" id="tema_periodo" value="{{ $Tema->periodo }}" />
<input type="hidden" class="form-control" id="tema_unidad" value="{{ $Tema->unidad }}" />
<input type="hidden" class="form-control" id="id_usuario" value="{{ Auth::user()->id }}" />

<h4 class="form-section"><i class="ft-grid"></i> Datos del Tema</h4>

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

<div class="row">

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="modulo">Módulo:</label>
            <select class="form-control select2" onchange="$.CargPeriodos(this.value)" data-placeholder="Seleccione"
                name="modulo" id="modulo">
                <option value="">Seleccione un Módulo</option>
                @foreach ($Asigna as $Asig) {
                    @if ($Asig->id == $Tema->modulo)
                        <option value="{{ $Asig->id }}" {{ Input::old('modulo') == $Asig->id ? 'selected' : '' }}
                            selected>{{ $Asig->nombre . ' - Grado ' . $Asig->grado_modulo . '°' }}</option>
                    @else
                        <option value="{{ $Asig->id }}" {{ Input::old('modulo') == $Asig->id ? 'selected' : '' }}>
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
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="tip_contenido">Tipo Contenido:</label>
            <select class="form-control select2" onchange='$.TipDoc(this.value)' data-placeholder="Seleccione"
                name="tip_contenido" id="tip_contenido">
                <option value="" @if (Input::old('tip_contenido', $Tema->tip_contenido) == '') selected="selected" @endif>Seleccionar</option>
                <optgroup label="Elementos del Contenido de la Asignatura">
                    <option value="DOCUMENTO" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'DOCUMENTO') selected="selected" @endif>DOCUMENTO</option>
                    <option value="ARCHIVO" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'ARCHIVO') selected="selected" @endif>ARCHIVO</option>
                    <option value="CONTENIDO DIDACTICO" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'CONTENIDO DIDACTICO') selected="selected" @endif>CONTENIDO DIDACTICO - VIDEO</option>
                    <option value="LINK" @if (Input::old('tip_contenido', $Tema->tip_contenido) == 'LINK') selected="selected" @endif>LINK</option>
            
                </optgroup>
         
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group" id='HabConDidact' style="display: none;">
            <label class="form-label" for="tip_eval">Adjuntar Animaciones:</label><br>
            <select class="form-control select2" style="width: 100%;" onchange='$.HabiContDid(this.value)'
                data-placeholder="Seleccione" name="hab_cont_didact" id="hab_cont_didact">
                <option value="NO" @if (Input::old('hab_cont_didact', $Tema->hab_cont_didact) == 'NO') selected="selected" @endif>NO</option>
                <option value="SI" @if (Input::old('hab_cont_didact', $Tema->hab_cont_didact) == 'SI') selected="selected" @endif>SI</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 text-right">
        <div class="form-group">
           <label>&nbsp;</label>
           <div class="btn-list">
            <button type="button" onclick="$.cargarDocentes();"  class="btn btn-info btn-min-width mr-1 mb-1"><i class="fa fa-share"></i> Compartir Tema</button>
           </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4 class="form-section" id=''><i class="ft-edit"></i> Información del Tema</h4>
        <div class="row">
            <div class="col-md-12" id='rowtit'>
                <div class="form-group">
                    <label class="form-label" for="titu_contenido">Título:</label>
                    {!! Form::text('titu_contenido', old('titu_contenido', $Tema->titu_contenido), ['class' => 'form-control', 'placeholder' => 'Titulo del Tema', 'id' => 'titu_contenido', 'style' => 'text-transform: uppercase']) !!}
                </div>
            </div>
            <div class="col-md-3" style="display: none;">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Habilitar Cometarios:</label>
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Seleccione" id="HabConv"
                        name="HabConv">
                        <option value="">Seleccionar</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="objetivo_general">Objetivo General:</label>
                    {!! Form::textarea('objetivo_general', old('objetivo_general', $Tema->objetivo_general), ['class' => 'form-control', 'placeholder' => 'Objetivo General', 'id' => 'objetivo_general', 'style' => 'text-transform: uppercase', 'rows' => 4]) !!}
                </div>
            </div>
            <div class="col-md-2" id='rowcofev' style="display: none;">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">&nbsp;</label><br>
                    <button type="button" onclick="$.AbrirConfEval();" class="btn grey btn-outline-success"><i
                            class="fa fa-cog"></i>Configuracion</button>
                </div>
            </div>
        </div>

        <div class="row" id='Cont_documento' style="display:none">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Contenido:</label>
                    <textarea cols="80" id="summernoteCont" name="summernoteCont" rows="10"></textarea>

                    <br>
                </div>
            </div>
            <div class="col-md-12" style="display: none;" id="Div_ContDidactico">
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
                        <input id="file" class="form-control Selanima"  accept="video/*" name="archididatico[]" type="file">
                    </div>
                    <div class="col-md-1">

                    </div>
                </div>
                <div class="col-md-12" id='ListAnima' style="display: none; padding-top: 10px;">
                    <h6 class="form-section"><strong>Animaciones Agregadas</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_animaciones">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row" id='Cont_didactico' style="display:none">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Contenido:</label>
                    <div id="cont_sumerdidactico"></div>
                    <br>
                </div>
            </div>
            @if ($method == 'put')
                <div class="col-lg-12" id="Div_ArcDidac" style="display:none">
                    <div class="form-group">
                        <label>Seleccionar Contenido Didactico:</label>
                        <label id="projectinput7" class="file center-block">
                            <input id="file" accept="video/*" name="archidida" type="file">
                            <span class="file-custom"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-12" id="Div_VerImg">
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" onclick="$.MostVid();" class="btn btn-success"><i
                                    class="fa fa-search"></i> Ver Contenido Didactico:</button>
                            <button type="button" onclick="$.CambVid();" class="btn btn-warning"><i
                                    class="fa fa-refresh"></i> Cambiar Contenido</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-12" id="Div_ArcDidac">
                    <div class="form-group">
                        <label>Seleccionar Contenido Didactico:</label>
                        <label id="projectinput7" class="file center-block">
                            <input id="file" accept="video/*" name="archidida" type="file">
                            <span class="file-custom"></span>
                        </label>
                    </div>
                </div>

            @endif


        </div>
        <div class="row" id='Archivo' style="display:none">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Seleccionar Contenido Tematico:</label>
                    <label id="projectinput7" class="file center-block">
                        <input id="fileArch" multiple="" name="archi[]" type="file">
                        <span class="file-custom"></span>
                    </label>
                </div>
            </div>
            @if ($method == 'put')
                <div class="col-md-12">
                    <h6 class="form-section"><strong>Archivos Agregados</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_archivos">

                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="row" id='ArchivoVideo' style="display:none">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Seleccionar Video a Subir:</label>
                    <label id="projectinput7" class="file center-block">
                        <input id="fileArchVideo"  accept="video/*"  multiple="" name="archiVideo[]" type="file">
                        <span class="file-custom"></span>
                    </label>
                </div>
            </div>
            @if ($method == 'put')
                <div class="col-md-12">
                    <h6 class="form-section"><strong>Archivos Agregados</strong> </h6>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tr_archivos_videos">

                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="row" id='TipUrl' style="display:none">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Url del Tema:</label>
                    <input type="text" class="form-control SelURL" name="url_tema" id="url_tema" placeholder="Url del Tema "
                        value="" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group float-right  mb-0">
                    <button id="AddLink" type="button" class="btn mr-1 mb-1 btn-success"><i
                            class="fa fa-plus"></i> Agregar Url</button>
                </div>
            </div>
            <div class="col-md-12">
                <h6 class="form-section"> Urls Agregadas</h6>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Url</th>
                            <th>Opcion</th>
                        </tr>
                    </thead>
                    <tbody id="tr_urls">

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
                        <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                            data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-left" id="largeVideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
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


                    <div id='DetAnimaciones' style="height: 400px; overflow: auto">
                        <video id="videoclipAnima" width="100%" height="360" controls="controls"
                            title="Video title">
                            <source id="mp4videoAnima" src="" type="video/mp4" />
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

        <div class="modal fade text-left" id="Visualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h4 class="modal-title" id="titu_tema"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id='prueba' style="height:400px; overflow: scroll;"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                            data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-left show" id="ModCompartir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15">
            <div class="modal-dialog comenta" role="document">
                <div class="modal-content border-blue">
                    <div class="modal-header bg-blue white">
                        <h4 class="modal-title" id="titu_tema">Docentes con los que puedes compartir</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="width:100%">
                            <div class="col-md-12">
                                <div class="table-responsive" style="height:250px;">
                                    <table id="recent-orders"
                                        class="table table-hover mb-0 ps-container ps-theme-default table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Docente</th>
                                                <th>Seleccionar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tdcompartir" style="text-transform: capitalize; ">
        
                                        </tbody>
                                    </table>
        
                                </div>
                              
                            </div>
                         
        
                        </div>
        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn_GuarComent" data-dismiss="modal" class="btn grey btn-outline-success"><i class="fa fa-check"></i>
                            Aceptar</button>
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
                @if ($opc != 'Consulta')
                <button class="btn btn-outline-primary" onclick="$.GuardarTema();" id="Btn_Guardar" href="#"
                    title="Guardar" type="button">
                    <i class="fa fa-save"></i> Guardar
                </button>
                @if ($opc != 'editar')
                <a class="btn btn-outline-warning" href="{{ url('/Modulos/NuevoTema') }}" title="Cancelar">
                    <i class="fa fa-close"></i> Cancelar
                </a>
                @endif
                @endif
                <a class="btn btn-outline-dark" href="javascript:history.go(-1)" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
