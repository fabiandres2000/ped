@php
use Illuminate\Support\Facades\Input;
@endphp
{!! Form::open(['url'=>$url,'method'=>$method,'class'=>'','id'=>'formAsig','files'=>true]) !!}
{{ csrf_field() }}
<input type="hidden" name="modulo_id"  value=""/>
<input type="hidden" data-id='id-dat' id="dat" data-ruta="{{asset('/app-assets/images/Img_Asinaturas')}}" />
<h4 class="form-section"><i class="ft-grid"></i> Información de Área</h4>
<div class="row">

   <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="nombre_area">Nombre Área:</label>
            {!! Form::text('nombre_area',old('nombre_area',$Area->nombre_area),['class'=>'form-control','placeholder'=>'Nombre de Área','id'=>'nombre_area'])!!}    
        </div>
    </div>
        <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label" for="presentacion_modulo">Descripción:</label>
                {!! Form::textarea('descripcion',old('descripcion',$Area->descripcion),['class'=>'form-control','placeholder'=>'Descripción','id'=>'descripcion','rows'=>5])!!}    
            </div>
        </div>
    </div>

</div><br>

<div class="form-actions right">
    <div class="row ">
        <div class="col-md-12 col-lg-12 ">
            <div class="btn-list">
                @if($opc!='Consulta')
                <button class="btn btn-outline-primary" href="#" title="Guardar" type="submit">
                    <i class="fa fa-save"></i> Guardar
                </button>
                <a class="btn btn-outline-warning" href="{{ url('/Asignaturas/NuevaArea') }}" title="Cancelar">
                    <i class="fa fa-close"></i> Cancelar
                </a>
                @endif
                <a class="btn btn-outline-dark" href="{{ url('/Asignaturas/GestionAreas') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

