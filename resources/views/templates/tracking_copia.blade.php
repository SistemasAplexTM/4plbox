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
    .v-select{
        background-color:#FFFFFF;
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

    .v-select .dropdown-menu .active > a {
      color: #fff;
    }
    .dropdown-toggle>input[type="search"] {
    width: 100px !important;
    }
    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 100% !important;
    }
</style>
    <div class="row" id="tracking">
        <form id="formtracking" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.track_record')</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <form-trackingreceipt></form-trackingreceipt>
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
                                          <th>@lang('general.client')</th>
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
                          <li role="trackings" class="active"><a href="#recibido" aria-controls="recibido" role="tab" data-toggle="tab"><i class="fal fa-barcode"></i> Recibido</a></li>
                          <li role="trackings"><a href="#bodega" aria-controls="bodega" role="tab" data-toggle="tab"><i class="fal fa-box-open"></i> En Bodega</a></li>
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
                              <table id="tbl-tracking-bodega" class="table table-striped table-hover table-bordered" style="width: 100%;">
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
          <div class="modal fade bs-example" id="modalCreateReceipt" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog" style="width: 60%;">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">@lang('documents.close')</span></button>
                          <h2 class="modal-title" id="myModalLabel"><i class="fal fa-file-signature"></i> Crear documento</h2>
                      </div>
                      <div class="modal-body">
                        <form id="formTrackingClient" name="formSearchTracking" method="POST" action="">
                          <div class="row">
                              <div class="col-lg-4">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <h3>Datos de envio</h3>
                                    <hr>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label for="shipper_id" class="control-label gcore-label-top">@lang('documents.sender_shipper'):</label>
                                      <v-select name="shipper_id" :options="shippers" placeholder="@lang('documents.sender_shipper')" label="name" v-model="shipper_id">
                                      </v-select>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label for="peso" class="control-label gcore-label-top">@lang('documents.weight'):</label>
                                      <input class="form-control" name="peso" placeholder="peso" type="number" min="0" v-model="peso">
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label for="piezas" class="control-label gcore-label-top">@lang('documents.pieces'):</label>
                                      <input class="form-control" name="piezas" placeholder="piezas" type="number" min="1" v-model="piezas">
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label for="piezas" class="control-label gcore-label-top">@lang('documents.dimensions') (L x W x H):</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control" placeholder="L" maxlength="4" id="largo" name="largo" v-model="largo">
                                          <span class="input-group-addon">x</span>
                                          <input type="text" class="form-control" placeholder="W" maxlength="4" id="ancho" name="ancho" v-model="ancho">
                                          <span class="input-group-addon">x</span>
                                          <input type="text" class="form-control" placeholder="H" maxlength="4" id="alto" name="alto" v-model="alto">
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
                                        <table class="table table-striped table-bordered table-hover" id="tbl-trackings-client" style="width: 100%">
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
                          <button type="button" class="btn btn-primary" id="saveDoc" @click="createDocument()"><i class="fal fa-file-signature"></i> @lang('documents.create')</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i> @lang('documents.close')</button>
                      </div>
                  </div>
              </div>
          </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/tracking.js') }}"></script>
@endsection
