@php
    use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url' => $url, 'method' => $method, 'class' => '', 'id' => 'formSimu', 'files' => true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" id="Ruta" value="{{ url('/') }}/" />

<input type="hidden" name="competencia_id" value="" />
<input type="hidden" id="Tot_Porc" value="" />
<input type="hidden" id="comp_sel" value="" />
<input type="hidden" id="ConsComp" value="1" />
<input type="hidden" id="ConsSesiones" value="1" />
<input type="hidden" id="OpcSimul" name="OpcSimul" value="" />
<input type="hidden" id="Comp_sel" name="Comp_sel" value="" />
<input type="hidden" id="Tot_Porc" name="Tot_Porc" value="" />
<input type="hidden" id="OpcGuardado" name="OpcGuardado" value="" />
<input type="hidden" id="IdSesion" name="IdSesion" value="" />
<input type="hidden" id="IdSesionGen" name="IdSesionGen" value="" />

<input type="hidden" id="OpcSesion" name="OpcSesion" value="G" />
<input type="hidden" name="rutaimagen" value="{{ asset('app-assets/images/') }}" id="rutaimagen">


<input type="hidden" name="Id_Simu" id="Id_Simu" value="{{ $Simu->id }}" />
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

<h4 id="IdTit" class="form-section"><i class="ft-grid"></i> Información del Simulacro</h4>
<div class="row" id="Par">

    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="Decripción">Descripción:</label>
            {!! Form::text('nombre', old('nombre', $Simu->nombre), [
                'class' => 'form-control',
                'placeholder' => 'Descripción del Simulacro',
                'id' => 'nombre',
            ]) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">Prueba:</label>
                <select name="prueba" id="prueba" class="form-control select2">
                    <option value="">-- Seleccionar --</option>
                    <option value="3" @if (Input::old('prueba', $Simu->prueba) == '3') selected="selected" @endif>Grado 3°
                    </option>
                    <option value="5" @if (Input::old('prueba', $Simu->prueba) == '5') selected="selected" @endif>Grado 5°
                    </option>
                    <option value="7" @if (Input::old('prueba', $Simu->prueba) == '7') selected="selected" @endif>Grado 7°
                    </option>
                    <option value="9" @if (Input::old('prueba', $Simu->prueba) == '9') selected="selected" @endif>Grado 9°
                    </option>
                    <option value="11" @if (Input::old('prueba', $Simu->prueba) == '11') selected="selected" @endif>Grado 11°
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label " for="fecha">Fecha de Simulacro:</label>
            {!! Form::text('fecha', old('fecha', $Simu->fecha), [
                'class' => 'form-control',
                'placeholder' => 'Ingresar Fecha',
                'id' => 'fecha',
            ]) !!}

        </div>
    </div>
    <div class="col-md-2" id="Div_Crear">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">&nbsp;</label>
                <button class="btn btn-outline-primary" onclick="$.GuardarSimulacro('new');" id="Btn_Crear"
                    title="Guardar y Parametrizar" type="button">
                    <i class="fa fa-save"></i> Guardar y Parametrizar
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-2" id="Div_Update" style="display: none;">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">&nbsp;</label><br>
                <button class="btn btn-outline-primary" onclick="$.GuardarSimulacro('upd');" id="Btn_Update"
                    title="Actualizar" type="button">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
            </div>
        </div>
    </div>

</div>

<div class="form-actions center" style="display: none;" id="div-addSesion">
    <div class="heading-elements center">
        <div class="btn-group">
            <button type="button" onclick="$.AddSesion();" class="btn btn-success btn-min-width dropdown-toggle"
                aria-expanded="false"><i class="fa fa-plus"></i> Agregar
                y Parametrizar Sesión</button>
        </div>
    </div>
</div>



<div class="row" id="ParSesiones" style="display: block;">
    <div class="col-md-12 col-sm-12">
        <h4 class="form-section" id="Tit_ProceSimu"><i class="ft-grid"></i> Sesiones del Simulacro</h4>

    </diV>
    <div id="detalleSesion" style="display: none;" class="col-md-12 col-sm-12">
        <div class="row ">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Descripción:</label>
                    <input type="text" class="form-control" id="DescSesion2" value="" name="DescSesion" />
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label class="form-label" for="porc_modulo">Tiempo Sesión:</label>
                    <input type="text" id="TSesion2" placeholder="hh:mm" value="" class="form-control"
                        name="TSesion2">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label class="form-label" for="presentacion_modulo">&nbsp;</label><br />
                    <button class="btn btn-outline-primary" onclick="$.GuardarSesion('upd');" id="Btn_UpdateSesion"
                        title="Actualizar" type="button">
                        <i class="fa fa-refresh"></i> Actualizar
                    </button>
                </div>
            </div>

        </div>
    </div>


</div>


<div id="Div_Sesiones">


</div>


<div class="col-md-12 col-sm-12" style="display: none;" id="ConfSesion">
    <div class="card border-top-info box-shadow-0 border-bottom-info">
        <div class="card-header">
            <h4 class="card-title">Sesión 2</h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">


                </div>

                <div class="card-footer">
                    <div class="chart-title mb-1 text-center">
                        <h6>Duracion de la Sesión.</h6>
                    </div>
                    <div class="chart-stats text-center">
                        <a href="#" class="btn btn-sm btn-primary mr-1"><i class="ft-clock"> 4 Horas y 20
                                Minutos </i></a>
                    </div>
                </div>
                <div class="form-actions right">
                    <button type="button" onclick="$.ParSesion2();" class="btn btn-outline-primary">
                        <i class="ft-settings"></i> Parametrizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row" id="AreasAgregadas" style="display:none;">
    <div class="col-md-12">
        <h4 class="form-section"><i class="ft-grid"></i> Configurar Sesión de Simulacro</h4>

    </div>

    <div class="col-md-12">
        <h6 class="form-section"><strong>Áreas Agregadas</strong> </h6>
        <table class="table table-hover mb-0" id="table_areas">
            <thead>
                <tr>
                    <th>Area</th>
                    <th># Preguntas</th>
                    <th>Opcion</th>
                </tr>
            </thead>
            <tbody id="tr_areas">
            </tbody>
        </table>
        <div class="form-actions right">
            <div class="row ">
                <div class="col-md-12 col-lg-12 ">
                    <div class="btn-list">
                        <button id="AddAsig" onclick="$.AddArea();" type="button"
                            class="btn mr-1 mb-1 btn-success"><i class="fa fa-plus"></i> Agregar</button>
                        <button id="AddAsig" onclick="$.AtrasAreaSesion();" type="button"
                            class="btn mr-1 mb-1 btn-primary"><i class="fa fa-arrow-left"></i> Atras</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="DetaAreas" style="display: none;">
    <div class="col-md-8">
        <div class="form-group">
            <label class="form-label" for="modulo">Área:</label>
            <select class="form-control select2" style="width: 100%;"
                onchange="$.CargCompetenciasxComponentes(this.value);" data-placeholder="Seleccione" name="area"
                id="area">
                <option value="">Seleccione el Área</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="n_preguntas">Número de Preguntas por Área:</label>
            <input type="text" class="form-control" maxlength="2" onchange="$.ValCampArea();"
                onkeypress="return validartxtnum(event);" id="n_preguntas" name="n_preguntas" />
        </div>
    </div>
    <div class="col-md-12">
        <h4 class="card-title">Asignar numero de preguntas por Competencias y Componentes</h4>

    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="modulo">Competencia:</label>
            <select class="form-control select2" disabled style="width: 100%;" data-placeholder="Seleccione"
                name="Competencia" id="Competencia">
                <option value="">Seleccione una Competencia</option>
            </select>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="modulo">Componente:</label>
            <select class="form-control select2" disabled style="width: 100%;" data-placeholder="Seleccione"
                name="Componente" id="Componente">
                <option value="">Seleccione un Componente</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="PorPreguntas"># de Preguntas:</label>
            <input type="text" class="form-control" maxlength="3" disabled
                onkeypress="return validartxtnum(event);" id="PorcPreguntas" name="PorcPreguntas" />
        </div>
    </div>
    <div class="col-md-2">
        <label class="form-label" for="PorPreguntas">&nbsp;</label>
        <div class="form-group">
            <button id="AddCompe" onclick="$.AddComp();" type="button" class="btn mr-1 mb-1 btn-success"><i
                    class="fa fa-plus"></i> Agregar</button>
            <button id="UpdCompe" onclick="$.UpdatPorc();" type="button" style="display:none;"
                class="btn mr-1 mb-1 btn-success"><i class="fa fa-refresh"></i> Actualizar</button>
        </div>
    </div>
    <div class="col-md-12">
        <h6 class="form-section"><strong>Número de Preguntas por Competencia Y Componente</strong> </h6>
        <table class="table table-hover mb-0" id="table_areas">
            <thead>
                <tr>
                    <th>Competencia</th>
                    <th>Componente</th>
                    <th># Preguntas</th>
                    <th>Opcion</th>
                </tr>
            </thead>
            <tbody id="tr_competencias">
            </tbody>
            <tfoot>
                <tr>
                    <th colspan='1'></th>
                    <th colspan='1'></th>
                    <th colspan='1'><label id='gtotal' style='font-weight: bold;'></label></th>
                    <th colspan='1'></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-md-12 right pt-1">
        <div class="form-group">
            <button id="GenPreg" style="display: none;" onclick="$.GenPreg();" type="button"
                class="btn mr-1 mb-1 btn-grey-blue"><i class="fa fa-list-ul"></i> Generar Preguntas</button>
        </div>
    </div>
    <div class="col-md-12" style="display: none;" id="TablaPreg">
        <h6 class="form-section"><strong>Preguntas Generadas</strong> </h6>
        <div class="card-content">
            <div class="vertical-scroll scroll-example height-300">
                <div class="card-body pt-0" id="PreguntasxAreas">

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-12 right pt-1">
        <button id="GuarCofCompe" style="display:none;" onclick="$.GuarConfComp('Guardar');" type="button"
            class="btn mr-1 mb-1 btn-primary"><i class="fa fa-save"></i> Guardar
            Configuración</button>
        <button id="EditCofCompeEdit" style="display:none;" onclick="$.GuarConfComp('Editar');" type="button"
            class="btn mr-1 mb-1 btn-blue"><i class="fa fa-edit"></i> Actualizar
            Configuración</button>
        <button id="AddAsig" onclick="$.AtrasAreas();" type="button" class="btn mr-1 mb-1 btn-purple"><i
                class="fa fa-arrow-left"></i> Atras</button>
    </div>
</div>

<div class="modal fade text-left" id="selPreguntas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-body">
                <div class="modal-header bg-blue white">
                    <h4 class="modal-title">Seleccionar las Preguntas</h4>
                </div>

                <div id="SelPreg" class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="modulo">Compentencia y Componente :</label>
                                <select class="form-control select2" style="width: 100%;"
                                    onchange="$.selCompxComp(this.value);" data-placeholder="Seleccione"
                                    name="compxcompo" id="compxcompo">
                                    <option value="">Seleccione un Componente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="modulo">Total Preguntas:</label>
                                <input type="text" disabled id="nPregCompoxCompe" value=""
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="modulo">Preguntas Selecionadas.:</label>
                                <input type="text" disabled id="nPregCompoxCompeSel" value="0"
                                    class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <h6 class="form-section"><strong>Preguntas Disponibles</strong> </h6>
                        </div>
                        <div id="listPreguntas" style="height: 250px; overflow: auto;">

                        </div>

                    </div>

                    <div class="form-actions">
                        <div class="row  text-right">
                            <div class="col-md-12 col-lg-12 ">
                                <div class="btn-list">
                                    <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                                        data-dismiss="modal"><i class="ft-corner-up-left position-right"></i>
                                        Salir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="verPreguntas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-body">
                <div class="modal-header bg-blue white">
                    <h4 class="modal-title">Visualización de Preguntas</h4>
                </div>

                <div id="SelPreg" class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            
                            <label class="form-label" for="modulo"><strong>Competencia:</strong></label>  
                            <label id="textCompetencia" class="form-label" for="modulo"></label>  

                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="modulo"><strong>Componente:</strong></label>  
                            <label id="textComponente" class="form-label" for="modulo"></label>  
                        </div>
                        <div class="col-md-12">
                            <strong id="titParte"></strong>
                            <p id="desParte"></p>
                        </div>
                        <div class="col-md-12">
                            <strong>Enunciado:</strong>
                            <p id="desEnunciado"></p>
                        </div>
                        <div class="col-md-12">
                            <h6 class="form-section"><strong>Destalle de Preguntas</strong> </h6>
                        </div>
                        <div id="div-evaluaciones" style="height: 250px;  width:100%; overflow: auto;">
                      
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row  text-right">
                            <div class="col-md-12 col-lg-12 ">
                                <div class="btn-list">
                                    <button type="button" id="btn_salir" class="btn grey btn-outline-secondary"
                                        data-dismiss="modal"><i class="ft-corner-up-left position-right"></i>
                                        Salir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<br>

{{-- //////////////////////////MODAL /////////////////////////////////// --}}

<div class="modal fade text-left" id="ModnewSesion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
    aria-hidden="true">
    <div class="modal-dialog comenta" role="document">
        <div class="modal-content border-blue">
            <div class="modal-header bg-blue white">
                <h4 class="modal-title" id="titu_tema">Detalles de la Sesión</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="media">
                        <div class="row ">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-label" for="porc_modulo">Descripción:</label>
                                    <input type="text" class="form-control" id="DescSesion" value=""
                                        name="DescSesion" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="porc_modulo">Tiempo Sesión:</label>
                                    <input type="text" id="TSesion" placeholder="hh:mm" value=""
                                        class="form-control" name="TSesion">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="btn_GuarComent" onclick="$.GuarDatosSesion();"
                    class="btn grey btn-outline-success"><i class="ft-save position-right"></i>
                    Aceptar</button>
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
                <a class="btn btn-outline-dark" id="btn-volverInicio" href="{{ url('/ModuloE/GestionSimulacros') }}"
                    title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
