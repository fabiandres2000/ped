{!! Form::open(['url'=>$url,'method'=>$method,'class'=>'','id'=>'formProf','files'=>true]) !!}
{{ csrf_field() }}
<input type="hidden" class="form-control" id="id_foro" value="{{ $Foro->id }}" />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="titulo">Título:</label>
            {!!
            Form::text('titulo',old('titulo',$Foro->titulo),['class'=>'form-control','placeholder'=>'Titulo','id'=>'titulo'])!!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label">Contenido:</label>
                <textarea cols="80" id="summernoteCont" name="contenido" rows="10"></textarea>
                <br>
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
                <a class="btn btn-outline-dark" href="{{ url('/Foro/Gestion') }}" title="Volver">
                    <i class="fa fa-angle-double-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}