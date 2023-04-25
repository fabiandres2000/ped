@extends('Plantilla.Principal')
@section('title','Editar Asignatura')
@section('Contenido')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{Session::get('des')}}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                    </li>
                    <li  class="breadcrumb-item active">Editar Asignatura
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <section id="number-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Editar Asignatura.</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">
                                    @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible show" role="alert" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h6 style="font: 16px EXODO;">Por favor corrige los siguientes errores:</h6>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                            <strong style="font: 15px EXODO;"><li>{{ $error }}</li></strong>                                        
                                            @endforeach
                                        </ul>
                                    </div>        
                                    @endif                    
                                </div>
                            </div>

                            <p class="px-1"></p>

                            <!--begin::Form-->
                            @include('Asignaturas.FormArea',
                            ['url'=>'/Asignaturas/ModificarArea/'.$Area->id,
                            'method'=>'put'
                            ])
                            <!--end::Form-->
                            <p class="px-1"></p>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </section>
</div>


@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $("#Men_Inicio").removeClass("active");
        $("#Men_Asignaturas").addClass("has-sub open");
        $("#Men_Asignaturas_addAsignatura").addClass("active");

    });

  
</script>
@endsection

