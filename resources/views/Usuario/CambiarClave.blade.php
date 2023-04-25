@extends('Plantilla.Principal')
@section('title','Cambiar Clave')
@section('Contenido')
<section class="flexbox-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h6 style="font: 16px EXODO;">Por favor corrige los siguientes errores:</h6>
                <ul>
                    @foreach($errors->all() as $error)
                    <strong style="font: 15px EXODO;">
                        <li>{{ $error }}</li>
                    </strong>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            @if(Session::has('error'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{!! session('error') !!}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-5 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                <div class="card-header border-0 text-center">
                    {{-- <img src="{{ asset('app-assets/images/portrait/small/avatar-s-1.png') }}" alt="unlock-user"
                    class="rounded-circle img-fluid center-block"> --}}
                    <h5 class="card-title mt-1">{{ Auth::user()->nombre_usuario }}</h5>
                </div>
                <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2">
                    <span>Cambiar Clave</span>
                </p>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ url('/camcla') }}" method="post">
                            {{ csrf_field() }}
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="password" class="form-control form-control-md" name="passact"
                                    placeholder="Clave Actual" required>
                                <div class="form-control-position">
                                    <i class="fa fa-key"></i>
                                </div>
                            </fieldset>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="password" class="form-control form-control-md" name="password"
                                    placeholder="Nueva Clave" required>
                                <div class="form-control-position">
                                    <i class="fa fa-key"></i>
                                </div>
                            </fieldset>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="password" class="form-control form-control-md" name="password_confirmation"
                                    placeholder="Confirmar Clave" required>
                                <div class="form-control-position">
                                    <i class="fa fa-key"></i>
                                </div>
                            </fieldset>
                            <button type="submit" class="btn btn-outline-success btn-lg btn-block"><i class="
                                ft-check-circle"></i> Cambiar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection