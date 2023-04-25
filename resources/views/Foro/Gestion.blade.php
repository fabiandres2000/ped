@extends('Plantilla.Principal')
@section('title','Gestión de Foros')
@section('Contenido')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Gestión de Foros</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">Gestión de Foro
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
                        <h4 class="card-title">Gestión de Foro</h4>
                    </div>

                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">

                                    <div class="col-12">
                                        <div class="row">
                                            @if(Auth::user()->tipo_usuario=="Administrador" ||
                                            Auth::user()->tipo_usuario=="Profesor")
                                            <div class="col-md-8 col-lg-6">
                                                <div class="btn-list">
                                                    <a class="btn btn-outline-primary" href="{{url('/Foro/Nuevo')}}"
                                                       title="Nuevo Foro">
                                                        <i class="ft-message-square"></i> Nuevo Foro
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-4 col-lg-6 float-md-right">
                                                {!!
                                                Form::model(Request::all(),['url'=>'/Foro/Gestion','method'=>'GET','autocomplete'=>'off','role'=>'search','class'=>''])
                                                !!}
                                                <div class="input-group">
                                                    {!! Form::text('txtbusqueda',null,['class'=>'form-control
                                                    form-control-sm','placeholder'=>'BUSQUEDA..'])!!}
                                                    <span class="input-group-append">
                                                        <button type="submit" class="btn btn-primary "> <i
                                                                class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="px-1"></p>
                            <div class="row">
                                <div class="col-md-12">
                                    @if(Session::has('error'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-icon-right alert-warning alert-dismissible mb-2"
                                                 role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Alerta!</strong> {!! session('error') !!}

                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                    @if(Session::has('success'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-icon-right alert-info alert-dismissible mb-2"
                                                 role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>{!! session('success') !!}</strong>
                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    @foreach($Foros as $Alu)

                                    <div class="bs-callout-success callout-transparent callout-bordered mt-1"
                                         id='Foro{{$Alu->id}}'>
                                        <div class="media align-items-stretch">
                                            <div
                                                class="d-flex align-items-center bg-success position-relative callout-arrow-left p-2">
                                                <i class="ft-message-square white font-medium-5"></i>
                                            </div>
                                            <div class="media-body p-1">
                                                <a data-id='{{ $Alu->id }}'
                                                   href='{{url('Foro/Comentarios/'.$Alu->id)}}'>
                                                    <strong>{!! $Alu->titulo !!}</strong>
                                                    <p>{!! $Alu->contenido !!}</p>
                                                </a>
                                                @if(Auth::user()->tipo_usuario=="Administrador" ||
                                                Auth::user()->tipo_usuario=="Profesor")
                                                <a href='{{url('Foro/Editar/'.$Alu->id)}}' data-toggle="tooltip"
                                                   title="Editar"
                                                   class="btn btn-icon btn-outline-success btn-social-icon btn-sm"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href='#' data-id="{{ $Alu->id }}" data-toggle="tooltip"
                                                   title="Eliminar"
                                                   class="btn btn-icon btn-outline-warning btn-social-icon btn-sm btnEliminar"><i
                                                        class="fa fa-trash"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <p class="px-1"></p>
                                    {{-- @include('Foro.paginacion') --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</div>

{!! Form::open(['url'=>'/Foro/Eliminar'
,'id'=>'formAuxiliar'])!!}
{!! Form::close() !!}

@endsection
@section('scripts')
<script>
    $(document).ready(function () {

        $("#Men_Inicio").removeClass("active");
        $("#Men_Presentacion").removeClass("active");
        $("#Men_Foros").addClass("active open");

        $(".btnEliminar").on({
            click: function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var form = $("#formAuxiliar");
                $("#idAuxiliar").remove();
                form.append("<input type='hidden' name='id' id='idAuxiliar' value='" + id + "'>");
                var url = form.attr("action");
                var datos = form.serialize();
                mensaje = "¿Desea Elimninar este Foro?";

                Swal.fire({
                    title: 'Gestionar Foros',
                    text: mensaje,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "post",
                            url: url,
                            data: datos,
                            success: function (respuesta) {
                                Swal.fire({
                                    title: "",
                                    text: respuesta.mensaje,
                                    icon: "success",
                                    button: "Aceptar",
                                });

                                if (respuesta.estado === "ELIMINADO") {
                                    $("#Foro" + id).hide();
                                }

                            },
                            error: function () {

                                mensaje = "El Foro no pudo ser Eliminado";

                                Swal.fire(
                                        'Gestionar Foros',
                                        mensaje,
                                        'success'
                                    )
                            }
                        });
                    }
                });
            }
        });

    });
</script>
@endsection