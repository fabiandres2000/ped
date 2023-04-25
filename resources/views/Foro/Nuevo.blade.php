@extends('Plantilla.Principal')
@section('title','Crear Nuevo Foro')
@section('Contenido')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Nuevo Foro</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/Foro/Gestion')}}">Gestion de Foros</a>
                    </li>
                    <li class="breadcrumb-item active">Nuevo Foro
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
                        <h4 class="card-title">Crear Nuevo Foro</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">
                                    @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">×</button>
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

                            <p class="px-1"></p>

                            <!--begin::Form-->
                            @include('Foro.Form',
                            ['url'=>'/Foro/guardar',
                            'method'=>'post'
                            ])
                            <!--end::Form-->
                            <div class="row">
                                <div class="col-md-12">
                                    @if(Session::has('error'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">×</button>
                                                <strong>{!! session('error') !!}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(Session::has('success'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-success" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">×</button>
                                                <strong>{!! session('success') !!}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
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
        $("#Men_Presentacion").removeClass("active");
        $("#Men_Foros").addClass("active open");

        
        CKEDITOR.editorConfig = function(config) {
            config.toolbarGroups = [{
                    name: 'styles',
                    groups: ['styles']
                },
                {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo']
                },
                {
                    name: 'editing',
                    groups: ['selection', 'find', 'spellchecker', 'editing']
                },
                {
                    name: 'forms',
                    groups: ['forms']
                },
                {
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
                },
                {
                    name: 'links',
                    groups: ['links']
                },
                {
                    name: 'insert',
                    groups: ['insert']
                },
                {
                    name: 'colors',
                    groups: ['colors']
                },
                {
                    name: 'tools',
                    groups: ['tools']
                },
                {
                    name: 'document',
                    groups: ['doctools', 'mode', 'document']
                },
                {
                    name: 'others',
                    groups: ['others']
                },
                {
                    name: 'about',
                    groups: ['about']
                }
            ];

            config.removeButtons =
                'Source,Save,NewPage,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,HorizontalRule,Smiley,PageBreak,Iframe,ShowBlocks,About,Styles,Format';
        };

        $.extend({
            HabilEdit: function(){
                CKEDITOR.replace('summernoteCont', {});

            }
        });

        $.HabilEdit();


        
    });    
</script>
@endsection