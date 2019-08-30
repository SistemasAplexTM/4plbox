@extends('layouts.app')
@section('title', 'Recibo de ebtrega')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('layouts.receipt')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('layouts.receipt')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="receipt">
        <form id="formreceipt" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Recibo de entrega</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="consignee_id" class="control-label">Nombre del cliente</label>
                                    <a id="input_name" class=" pull-right" data-toggle="tooltip" data-placement="top" title="Manual"><i class="fal fa-pencil" style="color: #f7a54a"></i></a>
                                    <div id="group-consignee" class="input-group" style="width: 100%;">
                                        <select id="consignee_id" name="consignee_id" class="form-control chosen-select" style="width:100%;" tabindex="2">
                                            <option value="">Seleccione</option>
                                            @if(count($consignees) != null)
                                              @foreach ($consignees as $val)
                                                <option value="{{ $val->id }}" data-direccion="{{ $val->direccion }}" data-telefono="{{ $val->telefono }}" data-ciudad="{{ $val->ciudad }}">{{ $val->name }}</option>
                                              @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" id="cliente" v-model="client" name="cliente" style="display: none;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="direccion" class="">Dirección</label>
                                    <input type="text" id="direccion" name="" value="" class="form-control" placeholder="Ingrese la dirección">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="telefono" class="">Telefono</label>
                                    <input type="text" id="telefono" name="telefono" value="" class="form-control" placeholder="Ingrese el Telefono">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ciudad" class="">Ciudad</label>
                                    <input type="text" id="ciudad" name="ciudad" value="" class="form-control" placeholder="Ingrese la ciudad">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="">Transportador</label>
                                    <input type="text" id="transportador" v-model="transportador" name="" class="form-control" placeholder="Ingrese el nombre del transportador">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="warehouse" class="">Warehouse</label>
                                    <input type="text" style="background-color: lightcyan" @keyup.enter="addDocumentToReceipt" id="warehouse" v-model="warehouse" name="warehouse" class="form-control" placeholder="Ingrese el Numero de Warehouse">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="entregado" class="control-label col-lg-12">&nbsp;</label>
                                    <input style="display: none;" name="entregado" id="entregado" type="checkbox" data-toggle="toggle" data-size='small' data-on="Entregar/Revisado" data-off="Consolidadar sin entregar" data-width="100%" data-style="ios" data-onstyle="primary" data-offstyle="warning" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group" id="div_status" style="display: none">
                                <label for="status" class="">Observación para el Estatus</label>
                                <input type="text" style="background-color: #e0ffe6" id="status" v-model="status" class="form-control" placeholder="Observacion para el Estatus." onkeyup="if (event.keyCode == 13)
                                checkDocument($('#num_warehouse_guia_r').val());">
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group" id="div_wrh_guia_r" style="display: none">
                                <label for="num_warehouse_guia_r" class="">Revisar - entregar</label>
                                <input type="text"
                                id="num_warehouse_guia_r"
                                class="form-control"
                                v-model="num_warehouse_guia_r"
                                @keyup.enter="checkDocument"
                                placeholder="Documento a revisar y entregar."
                                style="background-color: #e0ffe6">
                              </div>
                            </div>
                        </div>
                        <div class="row"><div class="mensage"></div></div>
                        <div class="row" style="padding-top: 10px;">
                            <table class="table table-striped table-hover" id="tbl_entregaWare">
                                <thead>
                                    <tr>
                                        <th>Wareouses</th>
                                        <th>Tracking</th>
                                        <th style="width: 100px;">Cantidad</th>
                                        <th style="width: 100px;" v-if="editar==0">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="(value, index) in detail">
                                    <td>
                                      <span v-show="editar!=0" :class="(value.entregado == 0) ? 'badge badge-warning' : 'badge badge-primary'" :id="(value.id) ? value.id : null">@{{ (value.entregado == 0) ? 'Sin entregar' : 'Revisado' }}</span>
                                      @{{ value.warehouse }}
                                    </td>
                                    <td>@{{ value.trackings }}</td>
                                    <td style="width: 100px;">1</td>
                                    <td style="width: 100px;" v-if="editar==0">
                                      <a href="#" class="text-danger" @click="detail.splice(index, 1)">
                                        <i class="fal fa-trash"></i>
                                      </a>
                                    </td>
                                  </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group">
                                  <div class="col-sm-12 col-sm-offset-0 guardar">
                                      <button type="button" class="ladda-button btn btn-primary" data-style="expand-right" @click.prevent="saveDetail()" v-if="editar==0">
                                          <i class="fal fa-save"></i>  @lang('layouts.save')
                                      </button>
                                      <template v-else>
                                          {{-- <button type="button" class="ladda-button btn btn-warning" data-style="expand-right" @click.prevent="update()">
                                              <i class="fal fa-edit"></i> @lang('layouts.update')
                                          </button> --}}
                                          <button type="button" class="btn btn-white" @click.prevent="cancel()">
                                              <i class="fal fa-times"></i>  @lang('layouts.cancel')
                                          </button>
                                      </template>
                                  </div>
                              </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Recibos registrados</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-receipt" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="10">Recibo</th>
                                        <th>Consignee</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th style="width: 90px;">Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/receipt.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}
@endsection
