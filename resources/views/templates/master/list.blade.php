@extends('layouts.app')
@section('title', 'Guía master')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('master.master_guide')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('master.home')</a>
            </li>
            <li class="active">
                <strong>@lang('master.master_guide')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    #tbl-master_wrapper{
        padding-bottom: 230px;
    }
</style>
<div class="row" id="master_list">
	<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                @lang('master.registered_guides')
            </h5>
            <div class="ibox-tools">
                <a class="btn btn-primary" data-toggle="tooltip" title="Crear nueva master"><span data-toggle="modal" data-target="#modalCreateMaster">@lang('master.new') <i class="fal fa-plus" style="font-size: small;"></i></span></a>
            </div>
        </div>
        <div class="ibox-content">
            <!--***** contenido ******-->
            <div class="table-responsive">
                <table id="tbl-master" class="table table-striped table-hover table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th><center><i class="fal fa-file-check"></i> AWB</center></th>
                            <th><center><i class="fal fa-plane-departure"></i> @lang('master.airline')</center></th>
                            <th><center><i class="fal fa-calendar-day"></i> @lang('master.date')</center></th>
                            <th><center><i class="fal fa-dollar-sign"></i> @lang('master.rate')</center></th>
                            <th><center><i class="fal fa-forklift"></i> @lang('master.weight') Lb</center></th>
                            <th><center><i class="fal fa-dolly-flatbed"></i> @lang('master.weight') Kl</center></th>
                            <th><center><i class="fal fa-warehouse-alt"></i> @lang('master.consignee')</center></th>
                            <th><center><i class="fal fa-globe-americas"></i> @lang('master.destination')</center></th>
                            <th><center><i class="fal fa-file-spreadsheet"></i> @lang('master.manifest')</center></th>
                            <th><center>@lang('master.actions')</center></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
  </div>

    <!-- MODAL ASOCIAR CONSOLIDADO -->
    <div class="modal fade bs-example" id="modalCreateMaster" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fal fa-file" style="font-size: 20px;"></i> Creación de Guias Master
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="formGuiasAgrupar">
                        <p>Si desea asociar un consolidado a esta master, por favor seleccionelo.</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="status_id">Consolidado</label>
                                    <el-select
                                      clearable
                                      v-model="consolidado_id"
                                      filterable
                                      placeholder="Buscar Consolidado"
                                      :loading="loading"
                                      loading-text="Cargando..."
                                      no-data-text="No hay datos"
                                      value-key="id">
                                      <el-option
                                        v-for="item in options"
                                        :key="item.id"
                                        :label="item.consolidado"
                                        :value="item">
                                        <span style="float: left">Consolidado # @{{ item.consolidado }}</span>
                                        <span style="float: right; color: #8492a6; font-size: 13px"><i class="fal fa-calendar-alt"></i> @{{ item.fecha }} <i class="fal fa-map-marker-alt"></i> @{{ item.ciudad }}</span>
                                      </el-option>
                                    </el-select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i> Cerrar</button>
                    <a @click="createMaster()" class="btn btn-primary"><i class="fal fa-plus"></i> @lang('master.create')</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL IMPRIMIR LABELS POR BOLSA -->
		<div class="modal fade bs-example" id="modalPrintLabelsMaster" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" style="width: 30%!important">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<h4 class="modal-title" id="myModalLabel">
												<i class="fal fa-barcode"></i> Labels Bolsas
										</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-sm-12">
                      <el-input
                        name="type"
                        placeholder="Tipo de servicio"
                        prefix-icon="el-icon-edit-outlin"
                        v-model="type"
                        v-validate.disable="'required'" size="medium">
                      </el-input>
										</div>
									</div>

								</div>
								<div class="modal-footer">
										<button type="button" id="" @click="createLabelBags()" class="btn btn-primary" data-dismiss="modal">Imprimir</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								</div>
						</div>
				</div>
		</div>

    <!-- MODAL INGRESAR IMPUESTOS MASTER -->
		<div class="modal fade bs-example" id="modalMasterTax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" style="width: 35%!important">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<h4 class="modal-title" id="myModalLabel">
												<i class="fal fa-file-invoice-dollar"></i> Impuestos Master @{{ master }}
										</h4>
								</div>
								<div class="modal-body">
                  <div class="form-group">
  									<div class="row">
  										<div class="col-sm-6">
                        <label for="">Fecha liquidación</label>
                        <el-date-picker
                          v-model="tax_date"
                          type="date"
                          placeholder=""
                          value-format="yyyy-MM-dd"
                          name="tax_date"
                          size="medium">
                        </el-date-picker>
  										</div>
                      <div class="col-sm-6">
                        <label for="">TRM</label>
                        <el-input
                          name="type"
                          placeholder="TRM actual"
                          prefix-icon="el-icon-edit"
                          v-model="tax_trm"
                          size="medium">
                        </el-input>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-6">
                        <label for="">Peso</label>
                        <div>
                          @{{ tax_weight }} Kl -
                          @{{ tax_weight_lb }} Lb
                        </div>
  										</div>
                      <div class="col-sm-6">
                        <label for="">Rate</label>
                        <div>@{{ tax_rate }}</div>
  										</div>
                    </div>
									</div>
								</div>
								<div class="modal-footer">
                    <el-button type="primary" @click="saveTax()" :loading="text_loading" size="medium">@{{ text_save }}</el-button>
                    <el-button size="medium" data-dismiss="modal">Cerrar</el-button>
								</div>
						</div>
				</div>
		</div>

    <!-- MODAL COSTOS MASTER -->
		<div class="modal fade bs-example" id="modalMasterCost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog"  style="width:50%">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<h4 class="modal-title" id="myModalLabel">
												<i class="fal fa-file-invoice-dollar"></i> Costos y Gastos Master @{{ master }}
										</h4>
								</div>
								<div class="modal-body">
                  <div class="row" style="margin-top:10px;">
                    <div class="col-sm-5">
                      <div class="form-group">
                        <div class="row">
      										<div class="col-sm-12">
                            <label for="">@{{ text_cost }}</label>
                            <a style="float:right;color:dodgerblue;" data-toggle="tooltip"
                            :title="icon_title" :data-original-title="icon_title" @click="write = !write">
                              <i :class="icon_cost"></i>
                            </a>
                            <el-select v-model="cost_costo_id" filterable
                            clearable placeholder="Costos y Gastos" size="medium"
                            v-show="!write">
                              <el-option
                                v-for="item in costos"
                                :key="item.id"
                                :label="item.nombre"
                                :value="item.id">
                              </el-option>
                            </el-select>
                            <el-input
                              placeholder="Descripción"
                              prefix-icon="el-icon-edit"
                              v-model="cost_descripcion"
                              size="medium"
                              v-show="write">
                            </el-input>
      										</div>
                          <div class="col-sm-12">
                            <label for="">Seleccionar Moneda</label>
                            <el-select v-model="cost_moneda_id" filterable
                            clearable placeholder="Monedas" size="medium" value-key="id"
                            @change="setMoneda">
                              <el-option
                                v-for="item in monedas"
                                :key="item.id"
                                :label="item.descripcion"
                                :value="item">
                                <span style="float: left">@{{ item.descripcion }}</span>
                                <span style="float: right; color: #8492a6; font-size: 13px">@{{ item.moneda }} @{{ item.simbolo }}</span>
                              </el-option>
                            </el-select>
                          </div>
                          <div class="col-sm-12">
                            <label for="">Valor @{{ moneda }}</label>
                            <el-input
                              placeholder="Valor"
                              prefix-icon="el-icon-edit"
                              v-model="cost_valor"
                              size="medium">
                            </el-input>
      										</div>
                          <div class="col-sm-12">
                            <label for="">TRM</label>
                            <el-input
                              placeholder="TRM"
                              prefix-icon="el-icon-edit"
                              v-model="cost_trm"
                              size="medium">
                            </el-input>
      										</div>
                          <div class="col-sm-12">
                            <label for="">Seleccionar si es Costo o Gasto</label>
                          </div>
                          <div class="col-sm-12">
                            <el-radio v-model="costo_gasto" label="0" border size="medium">Costo</el-radio>
                            <el-radio v-model="costo_gasto" label="1" border size="medium">Gasto</el-radio>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-7">
                      <div class="table-responsive" style="padding-top:10px;">
                        <table id="tbl-cost" class="table table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Descripción</th>
                              <th>Valor</th>
                              <th>TRM</th>
                              <th></th>
                            </tr>
                          <thead>
                            <tbody>
                              <tr v-for="item in costs">
                                <td>
                                    <el-tag :type="(item.costo_gasto == '1') ? 'warning' : ''" size="mini">@{{ (item.costo_gasto == '1') ? 'Gasto' : 'Costo' }}</el-tag>
                                    @{{ (item.descripcion != null) ? item.descripcion : item.nombre }}
                                </td>
                                <td><small style="color: #adadad;">@{{ item.moneda }} @{{ item.simbolo }}</small> @{{ formatNumber(item.valor) }}</td>
                                <td>$ @{{ formatNumber(item.trm) }}</td>
                                <td style="width: 10%;">
                                  <a @click="deleteCost(item.id)" style="color:#E34724;" data-toggle="tooltip" title="Eliminar">
                                  <i class="fal fa-trash-alt fa-lg"></i>
                                </a>
                              </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
								</div>
								<div class="modal-footer">
                    <el-button type="primary" @click="saveCost()" :loading="text_loading" size="medium">@{{ text_save }}</el-button>
                    <el-button size="medium" data-dismiss="modal">Cerrar</el-button>
								</div>
						</div>
				</div>
		</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/master/master_list.js') }}"></script>
@endsection
