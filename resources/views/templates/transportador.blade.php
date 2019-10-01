@extends('layouts.app')
@section('title', 'Transportador')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.transported')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.transported')r</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
  <style>
    .el-upload{
      text-align: initial;
    }
    .el-upload__input{
      position: absolute;
    }
  </style>
    <div class="row" id="transportador">
        <form id="formtransportador" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.registration_of_transported')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        <div class="col-sm-4">
                                            <label for="nombre" class="control-label gcore-label-top">@lang('general.name'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" name="nombre" id="nombre" value="" placeholder="@lang('general.name_of_the_transporter')" class="form-control" type="text" @click="deleteError('nombre')" @focus="deleteError('nombre')" />
                                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.direccion}">
                                        <div class="col-sm-4">
                                            <label for="direccion" class="control-label gcore-label-top">@lang('general.address'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="direccion" name="direccion" id="direccion" value="" placeholder="@lang('general.transporter_address')" class="form-control" type="text" @click="deleteError('direccion')" @focus="deleteError('direccion')" />
		                                    <small id="msn1" class="help-block result-direccion" v-show="listErrors.direccion"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.telefono}">
                                        <div class="col-sm-4">
                                            <label for="telefono" class="control-label gcore-label-top">@lang('general.phone'):</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input v-model="telefono" name="telefono" id="telefono" value="" placeholder="@lang('general.enter_the_phone')" class="form-control" type="text" @click="deleteError('telefono')" @focus="deleteError('telefono')" />
                                            <small id="msn1" class="help-block result-telefono" v-show="listErrors.telefono"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.email}">
                                        <div class="col-sm-4">
                                            <label for="email" class="control-label gcore-label-top">@lang('general.email'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="email" name="email" id="email" value="" placeholder="@lang('general.enter_the_email')" class="form-control" type="emial" @click="deleteError('email')" @focus="deleteError('email')" />
		                                    <small id="msn1" class="help-block result-email" v-show="listErrors.email"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.contacto}">
                                        <div class="col-sm-4">
                                            <label for="contacto" class="control-label gcore-label-top">@lang('general.contact'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="contacto" name="contacto" id="contacto" value="" placeholder="@lang('general.enter_the_name_of_the_contact')" class="form-control" type="text" @click="deleteError('contacto')" @focus="deleteError('contacto')" />
		                                    <small id="msn1" class="help-block result-contacto" v-show="listErrors.contacto"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.ciudad}">
                                        <div class="col-sm-4">
                                            <label for="ciudad" class="control-label gcore-label-top">@lang('general.city'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="ciudad" name="ciudad" id="ciudad" value="" placeholder="@lang('general.enter_the_city_of_the_transporter')" class="form-control" type="text" @click="deleteError('ciudad')" @focus="deleteError('ciudad')"/>
		                                    <small id="msn1" class="help-block result-ciudad" v-show="listErrors.ciudad"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.estado}">
                                        <div class="col-sm-4">
                                            <label for="estado" class="control-label gcore-label-top">@lang('general.state_dpto'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="estado" name="estado" id="estado" value="" placeholder="@lang('general.enter_the_status_of_the_transporter')" class="form-control" type="text" @click="deleteError('estado')" @focus="deleteError('estado')" />
		                                    <small id="msn1" class="help-block result-estado" v-show="listErrors.estado"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.pais}">
                                        <div class="col-sm-4">
                                            <label for="pais" class="control-label gcore-label-top">@lang('general.country'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="pais" name="pais" id="pais" value="" placeholder="@lang('general.enter_the_carrier_country')" class="form-control" type="text" @click="deleteError('pais')" @focus="deleteError('pais')" />
		                                    <small id="msn1" class="help-block result-pais" v-show="listErrors.pais"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group" :class="{'has-error': listErrors.zip}">
                                        <div class="col-sm-4">
                                            <label for="zip" class="control-label gcore-label-top">@lang('general.code_zip'):</label>
                                        </div>
                                        <div class="col-sm-8">
		                                    <input v-model="zip" name="zip" id="zip" value="" placeholder="@lang('general.enter_the_transporter_zip_code')" class="form-control" type="text" @click="deleteError('zip')" @focus="deleteError('zip')" />
		                                    <small id="msn1" class="help-block result-zip" v-show="listErrors.zip"></small>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                              <div class="col-sm-4">
                                  <label for="zip" class="control-label gcore-label-top"></label>
                              </div>
                              <div class="col-lg-8">
                                  <div class="form-group"  :class="{'has-error': listErrors.zip}">
                                      <div class="checkbox checkbox-success checkbox-inline">
                                          <input type="checkbox" id="shipper" name="shipper" v-model="shipper">
                                          <label for="shipper"><i class="fal fa-user"></i> @lang('general.shipper')</label>
                                      </div>
                                     <div class="checkbox checkbox-success checkbox-inline">
                                          <input type="checkbox" id="consignee" name="consignee" v-model="consignee">
                                          <label for="consignee"><i class="fal fa-user"></i> @lang('general.consignee')</label>
                                      </div>
                                     <div class="checkbox checkbox-success checkbox-inline">
                                          <input type="checkbox" id="carrier" name="carrier" v-model="carrier">
                                          <label for="carrier"><i class="fal fa-plane"></i>  @lang('general.carrier') </label>
                                      </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <transition name="fade">
                        <div class="row" v-show="carrier">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                  <div class="form-group">
                                      <div class="col-sm-4">
                                          <label for="zip" class="control-label gcore-label-top"></label>
                                      </div>
                                      <div class="col-sm-8">
                                        <el-upload
                                          class="upload-demo"
                                          ref="upload"
                                          action="#"
                                          :http-request="addAttachment"
                                          :auto-upload="false"
                                          :headers="headerFile"
                                          :on-remove="handleRemove"
                                          :file-list="fileList"
                                          :limit="1"
                                          accept="image/png, image/jpeg"
                                          list-type="picture">
                                          <el-button size="small" type="primary"><i class="fal fa-upload"></i> Clic para subir Logo</el-button>
                                          <div slot="tip" class="el-upload__tip">Solo archivos jpg/png con un tamaño menor de 500kb</div>
                                        </el-upload>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                      </transition>


                        <div class="row">
                            @include('layouts.buttons')
                        </div>
                    </div>
                </div>
            </div>

        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.transporters')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-transportador" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.address')</th>
                                    <th>@lang('general.city')</th>
                                    <th>Zip</th>
                                    <th>@lang('general.contact')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.address')</th>
                                    <th>@lang('general.city')</th>
                                    <th>Zip</th>
                                    <th>@lang('general.contact')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/transportador.js') }}"></script>
@endsection
