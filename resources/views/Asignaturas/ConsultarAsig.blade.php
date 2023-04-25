@extends('Plantilla.Principal')
@section('title','Consulta de Asignatura')
@section('Contenido')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{Session::get('des')}}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                    </li>
                    <li  class="breadcrumb-item active">Consulta de Asignatura
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
                        <h4 class="card-title">Consulta de Asignatura</h4>                    
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
                            @include('Asignaturas.FormAsignatura',
                            ['url'=>'/Asignaturas/ModificarAsig/'.$Asig->id,
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

{!! Form::open(['url'=>'/cambiar/Presetancion'
,'id'=>'formAuxiliar'])!!}
{!! Form::close() !!}

@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $("#Men_Inicio").removeClass("active");
        $("#Men_Asignaturas").addClass("has-sub open");
        $("#Men_Asignaturas_addAdig").addClass("active");

        $.extend({
        DelPer: function (id_fila) {
        $('#tr_' + id_fila).remove();     
       
        }
                });

        //======================EVENTO AGREGAR PERIODOS=======================\\
        $("#AddPeriodo").on({
            click: function (e) {


          

        e.preventDefault();
                var perio = $("#periodo_modulo").val();
                var porc = $("#porc_avance").val();
                var ConsPer = $("#ConsPer").val();
                var flag = "ok";
                if (perio === "") {
                    swal('Error!', 'Seleccione un Periodo...', 'warning');
                    return false;
                }
                if (porc === "") {
                    swal('Error!', 'Ingrese el Porcentaje...', 'warning');
                    return false;
                }

                $('#tr_periodos input').each(function () {
                    if ($(this).val() === perio) {
                        swal('Error!', 'Este Periodo ya ha sido Asignado...', 'warning');
                        flag = "no";
                    }

                });

                if (flag === "no") {
                    return;
                }

                    var style = 'text-transform: uppercase;background-color:white;';
                    var clase = 'form-control form-control-sm';
                    var campo = "";
                    campo += "<tr id='tr_"+ConsPer+"' style='font-size: 11px;'>";
                    campo += "<td>";
                    campo += "<div class='controls'>";
                    campo += "<input type='hidden' id='txtperi"+ConsPer+"' name='txtperi[]'  class='" + clase + "' readonly style='" + style + "' value='" + perio + "'>";
                    campo += perio;
                    campo += "</div>";
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<div class='controls text-center'>";
                    campo += "<input type='hidden' id='txtporc' name='txtporc[]' class='" + clase + "' readonly style='" + style + "' value='" + porc + "'>";
                    campo += porc;
                    campo += "</div>";
                    campo += "</td>";
                    campo += "<td>";
                    campo += "<div class='controls'>";
                    campo += "<a onclick='$.DelPer(" + ConsPer + ")' class='btn btn-danger btn-sm btnQuitar text-white'  title='Remover'><i class='fa fa-trash-o font-medium-3' aria-hidden='true'></i></a>&nbsp;";
                    campo += "</div>";
                    campo += "</td>";
                    campo += "</tr>";
                    $("#tr_periodos").append(campo);
                    
                    $('#periodo_modulo').val('').trigger('change.select2');
                    $("#porc_avance").val("");
                    $("#ConsPer").val(parseFloat(ConsPer) + 1);
               
//                }

            }
        });
//======================EVENTO AGREGAR PERIODOS=======================\\


    });

    function validartxtnum(e) {
        tecla = e.which || e.keyCode;
        patron = /[0-9]+$/;
        te = String.fromCharCode(tecla);
        //    if(e.which==46 || e.keyCode==46) {
        //        tecla = 44;
        //    }
        return (patron.test(te) || tecla == 9 || tecla == 8 || tecla == 37 || tecla == 39 || tecla == 44);
    }
</script>
@endsection

