<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@lang('general.register_locker') | 4plbox</title>

    <!-- Styles -->
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/main.css') }}" rel="stylesheet"> --}}
    <style>
    body{
            background-color: #ffffff;
        }
        .asterisco{
            color: red !important;
        }
    </style>
</head>
<body>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQTpXj82d8UpCi97wzo_nKXL7nYrd4G70"></script>
    <div id="wrapper">
        <div id="">
            <div class="wrapper wrapper-content animated fadeInRight" id="casillero">
                <div class="row">
                    <div class="col-lg-6 col-lg-offset-3">
                        {{-- <h1>Registrese</h1>
                        <p>Obtenga su código de casillero en los Estados Unidos con nosotros y reciba sus compras en su domicilio.</p> --}}
                        <form id="formCasillero" enctype="multipart/form-data" class="form-horizontal casillero_form" role="form" action="#" method="post">          
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    
                                    <!--***** contenido ******-->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="pull-right">
                                                <input type='checkbox' data-toggle="toggle" id='corporativo' data-size='mini' data-on="Corporativo" data-off="Personal" data-width="100" data-style="ios" data-onstyle="warning" data-offstyle="primary">
                                            </div>
                                            <h3>
                                                @lang('general.general_shipping_data')
                                            </h3>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12" :class="[corporativo ? 'col-lg-12' : 'col-lg-6']">
                                            <div class="form-group" :class="{'has-error': errors.has('primer_nombre') }">
                                                <label class="control-label" for="primer_nombre">
                                                        @{{ !corporativo ? 'Primer Nombre' : 'Razón social' }} <span class="asterisco">*</span>
                                                </label> 
                                                <input 
                                                    v-model="primer_nombre"
                                                    v-validate="'required'"
                                                    type="text" required="" :placeholder="[corporativo ? 'Razón social' : 'Primer nombre']" class="form-control" id="primer_nombre" name="primer_nombre" value="">
                                                <label v-show="errors.has('primer_nombre')" class="error">@{{ errors.first('primer_nombre') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12" v-show="!corporativo">
                                            <div class="form-group" :class="{'has-error': errors.has('primer_apellido') }">
                                                <label class="control-label" for="primer_apellido">@lang('general.surnames')<span class="asterisco">*</span></label> 
                                                <input 
                                                    v-model="primer_apellido" 
                                                    v-validate="corporativo ? '' : 'required'" 
                                                    type="text" required="" placeholder="@lang('general.surnames')" class="form-control" id="primer_apellido" name="primer_apellido" value="">
                                                <label v-show="errors.has('primer_apellido')" class="error">@{{ errors.first('primer_apellido') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group" :class="{'has-error': errors.has('localizacion_id') }">
                                                <label class="control-label">Ciudad: <span class="asterisco">*</span></label>
                                                <v-select autocomplete="off"  name="localizacion_id" v-model="localizacion_id" label="name" :filterable="false" :options="ciudades" @search="onSearch" v-validate="'required'" :on-change="setPhoneCode">
                                                    <template slot="no-options">
                                                  @lang('general.there_are_no_results')
                                                    </template>
                                                    <template slot="option" slot-scope="option">
                                                        <div>
                                                            @{{ option.name }}
                                                        </div>
                                                        <small>@{{ option.depto }} - @{{ option.pais }}</small>
                                                    </template>
                                                    <template slot="selected-option" slot-scope="option">
                                                        <div>
                                                            @{{ option.name }}
                                                        </div>&nbsp;
                                                        <small>@{{ option.depto }} - @{{ option.pais }}</small>
                                                    </template>
                                                </v-select>
                                                <label v-show="errors.has('localizacion_id')" class="error">@{{ errors.first('localizacion_id') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group" :class="{'has-error': errors.has('direccion') }">
                                                <label class="control-label" for="direccion">@lang('general.address') <span class="asterisco">*</span></label>
                                                <input 
                                                    v-model="direccion"
                                                    v-validate="'required'" 
                                                    type="text" required="" placeholder="@lang('general.address')" class="form-control" id="direccion" name="direccion" value="">
                                                <label v-show="errors.has('direccion')" class="error">@{{ errors.first('direccion') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group" :class="{'has-error': errors.has('zip') }">
                                                <label class="control-label" for="zip">@lang('general.postal_code')  <span class="asterisco">*</span></label>
                                                <div class="input-group">
                                                    <input 
                                                    v-model="zip"
                                                    v-validate="'required'" 
                                                    type="number" placeholder="Zip" id="zip" name="zip" class="form-control" value="">
                                                    <span class="input-group-addon" @click="setZip" style="cursor: pointer;"><i class="fa fa-map-marker"></i> @lang('general.calculate')</span>
                                                </div>
                                                <label v-show="errors.has('zip')" class="error">@{{ errors.first('zip') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group" :class="{'has-error': errors.has('celular') }">
                                                <label class="control-label" for="celular">@lang('general.cell_phone') <span class="asterisco">*</span></label> 
                                                <div class="input-group">
                                                    <span class="input-group-addon">(+@{{ phone_code }})</span>
                                                    <input 
                                                        v-model="celular"
                                                        v-validate="'required'"
                                                        type="tel" required="" placeholder="999-9999" class="form-control" id="celular" name="celular" value="">
                                                </div>
                                                <label v-show="errors.has('celular')" class="error">@{{ errors.first('celular') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group" :class="{'has-error': errors.has('email') }">
                                                <label class="control-label" for="email">@lang('general.email') <span class="asterisco">*</span></label> 
                                                <input
                                                 v-model="email" 
                                                 v-validate="'required|email|unique'"
                                                 type="email" 
                                                 ref="email"
                                                 required="" placeholder="@lang('general.email_be_your_user')" class="form-control" name="email">
                                                <label v-show="errors.has('email')" class="error">@{{ errors.first('email') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group" :class="{'has-error': errors.has('email') }">
                                                <label class="control-label" for="email_confirmation">@lang('general.confirm_email')<span class="asterisco">*</span></label>
                                                <input
                                                v-validate="'required|confirmed:email'"
                                                 type="email" required="" placeholder="@lang('general.repeat_email')" class="form-control" name="email_confirmation" id="email_confirmation" v-model="email_confirmation">
                                                <label v-show="errors.has('email_confirmation')" class="error">@{{ errors.first('email_confirmation') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group" :class="{'has-error': errors.has('acepta_condiciones') }">
                                                <div class="checkbox checkbox-success checkbox-inline">
                                                        <input 
                                                    v-model="acepta_condiciones" 
                                                    v-validate="'required'" 
                                                    type="checkbox" id="acepta_condiciones" name="acepta_condiciones" value="f">
                                                    <label for="acepta_condiciones">@lang('general.i_have_read_the') <strong><a href="#" data-toggle="modal" data-target="#modalTerminosCondiciones" data-original-title="" title="">@lang('general.terms_and_conditions')</a></strong></label>
                                                </div>
                                                <div>
                                                    <label v-show="errors.has('acepta_condiciones')" class="error">@{{ errors.first('acepta_condiciones') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                               <div class="checkbox checkbox-success checkbox-inline">
                                                    <input 
                                                    v-model="recibir_info" 
                                                    type="checkbox" id="recibir_info" name="recibir_info" value="f" style="">
                                                    <label for="recibir_info"> @lang('general.i_wish_to_receive_information')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                               
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <div class="form-group">
                                                <button @click.prevent="create" type="button" class="ladda-button btn btn-primary hvr-float-shadow" data-style="zoom-in" title="">
                                                    <span class="ladda-label"><i class="fa fa-user" aria-hidden="true"></i>@lang('general.create_locker')</span>
                                                    <span class="ladda-spinner"></span>
                                                </button>
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
    <script src="{{ asset('js/templates/documento/postalCode.js') }}"></script>
    <script src="{{ asset('js/templates/casillero.js') }}"></script>
</body>
</html>
