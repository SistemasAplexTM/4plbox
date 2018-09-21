@extends('layouts.app')
@section('title', 'Plantillas Email')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.email_templates')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.email_templates')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
	.panel-info {
	    border-color: #bce8f1;
	}
	.panel-info>.panel-heading {
	    color: #31708f;
	    background-color: #d9edf7;
	    border-color: #bce8f1;
	}
</style>
    <div class="row" id="emailTemplate">
        <form id="formemailTemplate" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.email_templates_registration')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                    	<input type="hidden" id="agencia_id" name="agencia_id" value="1">
                        <!--***** contenido ******-->
                        <div class="row">   
                            <div class="col-lg-12">
                                <div class="mail-box">
                                    <div class="mail-body">
                                        <div class="form-group" :class="{'has-error': listErrors.nombre}">
                                        	<label class="col-sm-3 control-label" for="nombre">@lang('general.name'):</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="nombre" placeholder="@lang('general.identification_of_the_message')" class="form-control" value="" id="nombre" name="nombre" @click="deleteError('nombre')">
                                                <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                                            </div>
                                        </div>
                                        <div class="form-group" :class="{'has-error': listErrors.descripcion_plantilla}">
                                        	<label class="col-sm-3 control-label">@lang('general.description'):</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="descripcion_plantilla" placeholder="@lang('general.message_overview')" class="form-control" value="" id="descripcion_plantilla" name="descripcion_plantilla" @click="deleteError('descripcion_plantilla')">
                                                <small id="msn1" class="help-block result-descripcion_plantilla" v-show="listErrors.descripcion_plantilla"></small>
                                            </div>
                                        </div>
                                        <div class="form-group" :class="{'has-error': listErrors.subject}">
                                        	<label class="col-sm-3 control-label">@lang('general.subject'):</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="subject" placeholder="@lang('general.subject')" class="form-control" value=""  id="subject" name="subject" @click="deleteError('subject')">
                                                <small id="msn1" class="help-block result-subject" v-show="listErrors.subject"></small>
                                            </div>
                                        </div>
                                        <div class="form-group" :class="{'has-error': listErrors.otros_destinatarios}">
                                        	<label class="col-sm-3 control-label">@lang('general.recipients'):</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="otros_destinatarios" placeholder="@lang('general.other_recipients')" class="form-control" value="" id="otros_destinatarios" name="otros_destinatarios" @click="deleteError('otros_destinatarios')">
                                                <small id="msn1" class="help-block result-otros_destinatarios" v-show="listErrors.otros_destinatarios"></small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        	<label class="col-sm-3 control-label"></label>
                                        	<div class="col-sm-9">
		                                        <div class="checkbox checkbox-success checkbox-inline">
		                                            <input v-model="email_file" type="checkbox" id="email_file" name="email_file">
		                                            <label for="email_file">@lang('general.send_attached_document')</label>
		                                        </div>
                                        	</div>
                                        </div>
                                    </div>
                                    <div class="mail-text h-200">
                                        <textarea style="max-width: 100%;" placeholder="Ingrese Texto" class="form-control summernote" id="mensaje" name="mensaje" @click="deleteError('mensaje')"></textarea>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <small id="msn1" class="help-block result-mensaje" v-show="listErrors.mensaje"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @include('layouts.buttons')
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.email_templates')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                	<div>
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="plantillas" class="active"><a href="#table" aria-controls="table" role="tab" data-toggle="tab">@lang('general.table')</a></li>
							<li role="plantillas"><a href="#variables" aria-controls="variables" role="tab" data-toggle="tab">Variables</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane fade in active" id="table" style="margin-top: 20px;">
								<div class="table-responsive">
			                        <table id="tbl-emailTemplate" class="table table-striped table-hover table-bordered" style="width: 100%;">
			                            <thead>
			                                <tr>
			                                    <th>@lang('general.name')</th>
			                                    <th>@lang('general.description')</th>
			                                    <th style="width: 80px;">@lang('general.actions')</th>
			                                </tr>
			                            </thead>
			                            <tfoot>
			                                <tr>
												<th>@lang('general.name')</th>
			                                    <th>@lang('general.description')</th>
			                                    <th>@lang('general.actions')</th>
			                                </tr>
			                            </tfoot>
			                        </table>
			                    </div>  
							</div>
							<div role="tabpanel" class="tab-pane fade" id="variables">
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-12" style="margin-top: 20px;">
											<div class="panel panel-info">
												<div class="panel-heading">@lang('general.document_data')</div>
												<!-- List group -->
												  <ul class="list-group">
												    <li class="list-group-item">{num_guia}</li>
												    <li class="list-group-item">{num_warehouse}</li>
												    <li class="list-group-item">{flete_impuesto}</li>
												    <li class="list-group-item">{piezas}</li>
												    <li class="list-group-item">{seguro}</li>
												    <li class="list-group-item">{descuento}</li>
												    <li class="list-group-item">{cargos_add}</li>
												    <li class="list-group-item">{total}</li>
												  </ul>
											</div>
											<div class="panel panel-info">
												<div class="panel-heading">@lang('general.data_shipper')</div>
												<!-- List group -->
												  <ul class="list-group">
												    <li class="list-group-item">{nom_shipper}</li>
												  </ul>
											</div>
											<div class="panel panel-info">
												<div class="panel-heading">@lang('general.data_consignee')</div>
												<!-- List group -->
												  <ul class="list-group">
												    <li class="list-group-item">{nom_consignee}</li>
												    <li class="list-group-item">{dir_consignee}</li>
												    <li class="list-group-item">{dir2_consignee}</li>
												    <li class="list-group-item">{ciu_consignee}</li>
												    <li class="list-group-item">{depto_consignee}</li>
												    <li class="list-group-item">{zip_consignee}</li>
												    <li class="list-group-item">{pais_consignee}</li>
												    <li class="list-group-item">{pass_consignee}</li>
												    <li class="list-group-item">{email_consignee}</li>
												    <li class="list-group-item">{tel_consignee}</li>
												    <li class="list-group-item">{zip_consignee}</li>
												    <li class="list-group-item">{cel_consignee}</li>
												  </ul>
											</div>
											<div class="panel panel-info">
												<div class="panel-heading">@lang('general.signature_data_agency')</div>
												<!-- List group -->
												  <ul class="list-group">
												    <li class="list-group-item">{id_agencia}</li>
												    <li class="list-group-item">{nom_agencia}</li>
												    <li class="list-group-item">{tel_agencia}</li>
												    <li class="list-group-item">{email_agencia}</li>
												    <li class="list-group-item">{dir_agencia}</li>
												    <li class="list-group-item">{zip_agencia}</li>
												    <li class="list-group-item">{ciudad_agencia}</li>
												    <li class="list-group-item">{estado_agencia}</li>
												  </ul>
											</div>
											<div class="panel panel-info">
												<div class="panel-heading">@lang('general.data_detail_message')</div>
												<!-- List group -->
												  <ul class="list-group">
												    <li class="list-group-item">{datos_detalle}</li>
												    <li class="list-group-item">{tracking}</li>
												  </ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
                    <!--***** contenido ******-->
                               
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/emailTemplate.js') }}"></script>
@endsection