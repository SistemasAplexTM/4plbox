<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@lang('general.tracking') | 4plbox</title>

    <!-- Styles -->
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        body{
            background-color: #ffffff;
        }
        .help-block{
            color: #ed5565;
        }
         #formulario{
            float: none;
            margin:0px auto!important;
        }
        #codigo_label{
            font-weight: bold;
            font-size: 30px;
            margin: 5px;
        }
        #tracking_label, #peso_label, #fecha_label{
            font-weight: bold;
            color: black;
            font-size: 15px;
        }
        .year {
            position: absolute;
            -webkit-transform: rotate(-90deg);
            transform: rotate(-90deg);
            bottom: 20px;
            margin-left: 35px;
        }
        .day{
            font-size: 30px;
            font-weight: 900;
        }
        .mont{
            font-weight: 900;
        }
        .gris {
        width: 60%;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div>
            <div class="wrapper wrapper-content animated fadeInRight" id="rastreo">
                <div class="row" id="contenido">                    
                    <div class="col-lg-5 col-md-6" id="formulario">
                        <h1>@lang('general.track_your_merchandise')</h1>
                        <p>@lang('general.enter_the_tracking')</p>
                        <form id="formRastreo" enctype="multipart/form-data" class="form-horizontal casillero_form" role="form" action="#" method="post">          
                            <div class="ibox float-e-margins">
                                <div class="ibox-content gray-bg">
                                    <!--***** contenido ******-->
                                    <div class="row">   
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label" for="codigo">@lang('general.warehouse_guide_tracking')</label>
                                                <div class="input-group" :class="{ 'has-error': errors.has('codigo') }">
                                                    <input type="text" placeholder="@lang('general.track_your_package_here')" class="form-control" name="codigo" v-model="codigo" v-validate.disabled="'required'">
                                                    <span class="input-group-btn">
                                                    <button @click.prevent="getData()" type="button" class="ladda-button btn btn-primary" data-style="expand-right" title=""><span class="ladda-label"> Rastrear <i class="fa fa-map-marker" aria-hidden="true"></i></span><span class="ladda-spinner"></span></button>
                                                    </span>
                                                </div>
                                                <small class="help-block">@{{ errors.first('codigo') }}</small>
                                                <div id="codigo_label">@{{ codigo_label }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">   
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>@lang('general.weight'): <span id="peso_label">@{{ peso_label }} Lbs</span></label>
                                                <label>@lang('general.tracking'): <span id="tracking_label">@{{ tracking_label }}</span></label>
                                                <div>@lang('general.deliver_date'): <span id="fecha_label">@{{ fecha_entrega }}</span></div>
                                                <div class="hr-line-dashed" style="border-top: 1px dashed #898d90;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">               
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row" v-for="dato in datos">
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <h1 style="" class="day" id="estado_day1">@{{ dato.day_data }}</h1>
                                                            <h2 style=""  class="mont" id="estado_mont1">@{{ dato.mont_data }}</h2>
                                                            <span class="year">
                                                                <h2 id="estado_year1">@{{ dato.year_data }}</h2>
                                                            </span>
                                                        </div>                          
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0; padding-right: 0;">
                                                            <div style="text-align: center;"><img id="estado1" class="gris" v-bind:src="dato.img"></div>
                                                            <div style="padding: 15px 0px;"></div>
                                                            <div style="text-align: center;"><img id="estado_linea1" src="{{ asset('img/imagesRastreo/linea_puntos.png') }}"></div>
                                                            <div style="padding: 15px 0px;"></div>
                                                        </div>
                                                    </div>
                                                </div>                  
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
                                                    <h3 style="font-weight: 900;" id="estado_estado1">@{{ dato.estado }}</h3>
                                                    <span style="font-weight: 300;" id="estado_descripcion1">@{{ dato.descripcion }}</span><br>
                                                    <span style="font-weight: 900;color: black;" id="estado_procedente1">@{{ dato.procedencia }}</span>
                                                </div>
                                            </div>
                                            <div v-if="Object.keys(datos).length === 0">
                                                <h1>@{{ no_data }}</h1>
                                            </div>            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plantilla.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/templates/rastreo.js') }}"></script>
</body>
</html>
