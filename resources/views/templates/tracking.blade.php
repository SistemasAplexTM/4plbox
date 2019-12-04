@extends('layouts.app')
@section('title', 'Tracking')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('layouts.trackings_receipt')</h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">@lang('general.home')</a>
      </li>
      <li class="active">
        <strong>@lang('layouts.trackings_receipt')</strong>
      </li>
    </ol>
  </div>
</div>

@endsection

@section('content')
<style type="text/css">
  .v-select {
    background-color: #FFFFFF;
  }

  .v-select .dropdown li {
    border-bottom: 1px solid rgba(112, 128, 144, 0.1);
  }

  .v-select .dropdown li:last-child {
    border-bottom: none;
  }

  .v-select .dropdown li a {
    padding: 10px 20px;
    width: 100%;
    font-size: 1.25em;
    color: #3c3c3c;
  }

  .v-select .dropdown-menu .active>a {
    color: #fff;
  }

  .dropdown-toggle>input[type="search"] {
    width: 100px !important;
  }

  .dropdown-toggle>input[type="search"]:focus:valid {
    width: 100% !important;
  }

  .help-block {
    color: #f56c6c;
    font-size: 11;
    position: absolute;
    margin-top: 0px;
    margin-bottom: 0px;
  }
</style>
<div class="row" id="tracking">
  <form id="formtracking" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
    <div class="col-lg-4">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>@lang('general.track_record')</h5>
          <div class="fr">
            @foreach($agencias as $agencia)
            @if(Auth::user()->agencia_id == $agencia['id'])
            <change_agency :agency="{{ $agencia }}" :role="{{ $role_admin }}"
              :style_="'font-size:17px;float: left;font-weight: bold;'">
            </change_agency>

            <input type="hidden" id="agencia_id" class="form-control" value="{{ $agencia['id'] }}" readonly="">
            @endif
            @endforeach
          </div>
        </div>
        <div class="ibox-content">
          <!--***** contenido ******-->
          {{-- <button type="button" class="btn btn-primary" data-target="#modalPrint" data-toggle="modal">Imprimir
            recibo</button> --}}
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group" :class="{ 'has-error': errors.has('tracking') }">
                <div class="col-sm-4">
                  <label for="tracking" class="control-label gcore-label-top">@lang('general.tracking'): <samp
                      id="require">*</samp></label>
                </div>
                <div class="col-sm-8">
                  <input class="form-control" name="tracking" placeholder="@lang('general.number_of_tracking')"
                    type="text" v-model="tracking" v-validate.disable="'required|unique'"
                    v-on:keyup.enter="focusContent('contenido')">
                  <small class="help-block error" v-show="errors.has('tracking')">
                    @{{ errors.first('tracking') }}
                  </small>
                </div>
              </div>
            </div>
          </div>

          @if(env('APP_CLIENT') != 'worldcargo')
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group" :class="{ 'has-error': errors.has('contenido') }">
                <div class="col-sm-4">
                  <label for="contenido" class="control-label gcore-label-top">@lang('general.content'):</label>
                </div>
                <div class="col-sm-8">
                  <input class="form-control" id="contenido" name="contenido" placeholder="@lang('general.content')"
                    type="text" v-model="contenido">
                  <small class="help-block error" v-show="errors.has('contenido')">
                    @{{ errors.first('contenido') }}
                  </small>
                </div>
              </div>
            </div>
          </div>
          @endif
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group" :class="{ 'has-error': errors.has('consignee_id') }">
                <div class="col-sm-4">
                  <label for="consignee_id" class="control-label gcore-label-top">@lang('general.consignee'):

                  </label>
                </div>
                <div class="col-sm-8">
                  <el-autocomplete clearable class="inline-input" v-model="consignee_name"
                    :fetch-suggestions="querySearchConsignee" :trigger-on-focus="false"
                    placeholder="@lang('general.consignee')" size="small" @clear="deleteSelected"
                    @select="handleSelect">
                    <template slot-scope="{ item }">
                      <div class="content-select">
                        <i class="fal fa-user icon"></i> @{{ item.name }}
                      </div>
                    </template>
                    <template slot="append">
                      <i @click="openNew" class="pointer fal fa-user-plus"></i>
                    </template>
                  </el-autocomplete>
                  <small class="help-block error" v-show="errors.has('consignee_id')">
                    @{{ errors.first('consignee_id') }}
                  </small>
                </div>
              </div>
            </div>
          </div>

          <div class="row" v-show="instruccion || email">
            <div class="col-lg-12">
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="alert alert-info alert-dismissible" role="alert" id="msn_sendmail"
                    style="margin-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="icon_close"><span
                        aria-hidden="true">&times;</span></button>
                    <strong>@lang('general.instruction'):</strong> @{{ instruccion }}<br>
                    <strong>@lang('general.pack_off'):</strong> @{{ (confirmedSend) ? 'Sí' : 'No' }}<br>
                    <strong>@lang('general.email'):</strong> @{{ email }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            @include('layouts.buttons')
          </div>
        </div>
      </div>


      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>@lang('general.create_document')</h5>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <!--***** contenido ******-->
          <div class="row">
            <div class="table-responsive" style="margin-top: 10px">
              <table id="tbl-tracking-group" class="table table-hover" style="width: 100%;">
                <thead>
                  <tr>
                    <th>item</th>
                    <th>@lang('general.consignee')</th>
                    <th>Inventario</th>
                    <th>@lang('general.actions')</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>@lang('general.registered_tracking')</h5>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <!--***** contenido ******-->
          <ul class="nav nav-tabs" role="tablist">
            <li role="trackings" class="active"><a href="#recibido" aria-controls="recibido" role="tab"
                data-toggle="tab"><i class="fal fa-barcode"></i> Recibido</a></li>
            <li role="trackings"><a href="#bodega" aria-controls="bodega" role="tab" data-toggle="tab"><i
                  class="fal fa-box-open"></i> En Bodega</a></li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="recibido">
              <div class="table-responsive" style="margin-top: 10px">
                <table id="tbl-tracking" class="table table-striped table-hover table-bordered" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>@lang('general.date')</th>
                      <th>@lang('general.client')</th>

                      {{-- <th>@lang('general.email')</th> --}}
                      <th>@lang('general.tracking')</th>
                      <th>Warehouse</th>
                      <th>@lang('general.content')</th>
                      <th>@lang('general.actions')</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>@lang('general.date')</th>
                      <th>@lang('general.client')</th>
                      {{-- <th>@lang('general.email')</th> --}}
                      <th>@lang('general.tracking')</th>
                      <th>Warehouse</th>
                      <th>@lang('general.content')</th>
                      <th>@lang('general.actions')</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="bodega">
              <div class="table-responsive" style="margin-top: 10px">
                <table id="tbl-tracking-bodega" class="table table-striped table-hover table-bordered"
                  style="width: 100%;">
                  <thead>
                    <tr>
                      <th>@lang('general.date')</th>
                      <th>@lang('general.client')</th>
                      {{-- <th>@lang('general.email')</th> --}}
                      <th>@lang('general.tracking')</th>
                      <th>Warehouse</th>
                      <th>@lang('general.content')</th>
                      {{-- <th>@lang('general.office')</th> --}}
                      <th>@lang('general.actions')</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>@lang('general.date')</th>
                      <th>@lang('general.client')</th>
                      {{-- <th>@lang('general.email')</th> --}}
                      <th>@lang('general.tracking')</th>
                      <th>Warehouse</th>
                      <th>@lang('general.content')</th>
                      {{-- <th>@lang('general.office')</th> --}}
                      <th>@lang('general.actions')</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  {{-- MODAL CREAR RECIBO --}}
  <div class="modal fade bs-example" id="modalCreateReceipt" tabindex="" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
              class="sr-only">@lang('documents.close')</span></button>
          <h2 class="modal-title" id="myModalLabel"><i class="fal fa-file-signature"></i> Crear documento</h2>
        </div>
        <div class="modal-body">
          <form id="formTrackingClient" name="formSearchTracking" method="POST" action="">
            <input type="hidden" id="agencia_id_receipt">
            <div class="row">
              <div class="col-lg-4">
                <div class="row">
                  <div class="col-lg-12">
                    <h3>Datos de envío</h3>
                    <hr>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="shipper_id"
                        class="control-label gcore-label-top">@lang('documents.sender_shipper'):</label>
                      <v-select name="shipper_id" :options="shippers" placeholder="@lang('documents.sender_shipper')"
                        label="name" v-model="shipper_id">
                      </v-select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="peso" class="control-label gcore-label-top">@lang('documents.weight') (Lb):</label>
                      <input class="form-control" name="peso" placeholder="peso" type="number" min="0" v-model="peso">
                    </div>
                  </div>
                  {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                      <label for="piezas" class="control-label gcore-label-top">@lang('documents.pieces'):</label>
                                      <input class="form-control" name="piezas" placeholder="piezas" type="number" min="1" v-model="piezas">
                                    </div>
                                  </div> --}}
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="piezas" class="control-label gcore-label-top">@lang('documents.dimensions') (L x W x
                        H):</label>
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="L" maxlength="4" id="largo" name="largo"
                          v-model="largo">
                        <span class="input-group-addon">x</span>
                        <input type="text" class="form-control" placeholder="W" maxlength="4" id="ancho" name="ancho"
                          v-model="ancho">
                        <span class="input-group-addon">x</span>
                        <input type="text" class="form-control" placeholder="H" maxlength="4" id="alto" name="alto"
                          v-model="alto">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="col-lg-12">
                  <h3>Trackings registrados del cliente '<span id='client-tracking'></span>'</h3>
                  <hr>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover" id="tbl-trackings-client"
                        style="width: 100%">
                        <thead>
                          <tr>
                            <th></th>
                            <th>@lang('documents.tracking')</th>
                            <th>@lang('documents.content')</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="saveDoc" @click="createDocument()"><i
              class="fal fa-file-signature"></i> @lang('documents.create')</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i>
            @lang('documents.close')</button>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL CAMBIAR CONSIGNEE --}}
  <div class="modal fade bs-example" id="modalEditConsignee" tabindex="" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="width: 20%;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
              class="sr-only">@lang('documents.close')</span></button>
          <h2 class="modal-title" id="myModalLabel"><i class="fal fa-user"></i> @lang('general.consignee')</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <el-autocomplete clearable class="inline-input" v-model="consignee_name_change"
                :fetch-suggestions="querySearchConsignee" :trigger-on-focus="false"
                placeholder="@lang('general.consignee')" size="small" @clear="deleteSelected"
                @select="handleSelectChange">
                <template slot-scope="{ item }">
                  <div class="content-select">
                    <div style="">
                      <i class="fal fa-user icon"></i> @{{ item.name }}
                    </div>
                  </div>
                </template>
              </el-autocomplete>
              <small class="help-block" v-show="errors_data">Campo obligatorio</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <el-button type="primary" :loading="loading" @click="editConsignee"><i class="fal fa-edit"></i>
            @lang('layouts.save')</el-button>
          <el-button data-dismiss="modal"><i class="fal fa-times"></i> @lang('documents.close')</el-button>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL PRINT DOCUMENT --}}
  <div class="modal fade bs-example" id="modalPrint" tabindex="1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 20%;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
              class="sr-only">@lang('documents.close')</span></button>
          <h2 class="modal-title" id="myModalLabel"><i class="fal fa-print"></i> @lang('documents.print_document')</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <h2 style="text-align: center;padding-bottom: 20px;">
                <i class="fal fa-cubes"></i> @{{ print_warehouse }}
              </h2>
              <el-button type="primary" :loading="loading" @click="printDocument(true)" v-if="print_direct"><i
                  class="fal fa-print"></i>
                @lang('general.direct_print')</el-button>

              <el-button type="success" :loading="loading" @click="printDocument(false)"><i class="fal fa-print"></i>
                @lang('layouts.print')</el-button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <el-button data-dismiss="modal"><i class="fal fa-times"></i> @lang('documents.close')</el-button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
{!! $wcpScript !!}
<script src="{{ asset('js/templates/tracking.js') }}"></script>
@endsection