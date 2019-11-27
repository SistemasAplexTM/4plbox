@extends('layouts.app')
@section('title', 'Plantillas Email')
@section('breadcrumb')
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
  .tox .tox-dialog__content-js {
    display: inline-block;
    height: auto;
  }

  .tox .tox-dialog__body-content {
    height: 200px !importnat;
  }

  .panel-info {
    border-color: #bce8f1;
  }

  .panel-info>.panel-heading {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
  }

  .copy-btn {
    padding: 0;
    width: 88px;
    height: 25px;
    line-height: 23px;
    border-radius: 50px;
    float: right;
  }
</style>
<div class="row" id="emailTemplate">
  <form id="formemailTemplate" enctype="multipart/form-data" class="form-horizontal" role="form" action=""
    method="post">
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
                      <input type="text" v-model="nombre" placeholder="@lang('general.identification_of_the_message')"
                        class="form-control" value="" id="nombre" name="nombre" @click="deleteError('nombre')">
                      <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                    </div>
                  </div>
                  <div class="form-group" :class="{'has-error': listErrors.descripcion_plantilla}">
                    <label class="col-sm-3 control-label">@lang('general.description'):</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="descripcion_plantilla" placeholder="@lang('general.message_overview')"
                        class="form-control" value="" id="descripcion_plantilla" name="descripcion_plantilla"
                        @click="deleteError('descripcion_plantilla')">
                      <small id="msn1" class="help-block result-descripcion_plantilla"
                        v-show="listErrors.descripcion_plantilla"></small>
                    </div>
                  </div>
                  <div class="form-group" :class="{'has-error': listErrors.subject}">
                    <label class="col-sm-3 control-label">@lang('general.subject'):</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="subject" placeholder="@lang('general.subject')" class="form-control"
                        value="" id="subject" name="subject" @click="deleteError('subject')">
                      <small id="msn1" class="help-block result-subject" v-show="listErrors.subject"></small>
                    </div>
                  </div>
                  <div class="form-group" :class="{'has-error': listErrors.otros_destinatarios}">
                    <label class="col-sm-3 control-label">@lang('general.recipients'):</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="otros_destinatarios" placeholder="@lang('general.other_recipients')"
                        class="form-control" value="" id="otros_destinatarios" name="otros_destinatarios"
                        @click="deleteError('otros_destinatarios')">
                      <small id="msn1" class="help-block result-otros_destinatarios"
                        v-show="listErrors.otros_destinatarios"></small>
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
                  <textarea style="max-width: 100%;" placeholder="Ingrese Texto" class="form-control" id="mensaje"
                    name="mensaje" @click="deleteError('mensaje')"></textarea>
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
    <div class="col-lg-5">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>@lang('general.email_templates')</h5>
        </div>
        <div class="ibox-content">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="plantillas" class="active"><a href="#table" aria-controls="table" role="tab"
                data-toggle="tab">@lang('general.table')</a></li>
            <li role="plantillas"><a href="#variables" aria-controls="variables" role="tab"
                data-toggle="tab">Variables</a></li>
          </ul>
          {{-- input para coiar en clipboard --}}
          <input type="hidden" id="testing-code" :value="testingCode">
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="table" style="margin-top: 20px;">
              <div class="table-responsive">
                <table id="tbl-emailTemplate" class="table table-striped table-hover table-bordered"
                  style="width: 100%;">
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
                    <div class="panel-group" id="accordion">
                      <div class="panel panel-info">
                        <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion"
                          href="#collapse1">
                          <h4 class="panel-title">
                            @lang('general.document_data') <i class="fal fa-angle-down fr"></i>
                          </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapse1">
                          <div class="panel-body">
                            <ul class="list-group">
                              <li class="list-group-item">{num_guia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{num_guia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{num_warehouse}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{num_warehouse}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{flete_impuesto}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{flete_impuesto}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{piezas}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{piezas}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{seguro}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{seguro}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{descuento}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{descuento}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{cargos_add}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{cargos_add}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{total}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{total}')">
                                  Copy
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-info ">
                        <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion"
                          href="#collapse2">
                          <h4 class="panel-title">
                            @lang('general.data_shipper') <i class="fal fa-angle-down fr"></i>
                          </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapse2">
                          <div class="panl-body">
                            <ul class="list-group">
                              <li class="list-group-item">{nom_shipper}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{nom_shipper}')">
                                  Copy
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-info ">
                        <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion"
                          href="#collapse3">
                          <h4 class="panel-title">
                            @lang('general.data_consignee') <i class="fal fa-angle-down fr"></i>
                          </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapse3">
                          <div class="panel-body">
                            <ul class="list-group">
                              <li class="list-group-item">{nom_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{nom_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{dir_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{dir_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{dir2_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{dir2_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{ciu_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{ciu_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{depto_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{depto_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{zip_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{zip_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{pais_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{pais_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{user_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{user_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{pass_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{pass_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{email_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{email_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{tel_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{tel_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{zip_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{zip_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{cel_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{cel_consignee}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{pobox_consignee}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{pobox_consignee}')">
                                  Copy
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-info ">
                        <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion"
                          href="#collapse4">
                          <h4 class="panel-title">
                            @lang('general.signature_data_agency') <i class="fal fa-angle-down fr"></i>
                          </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapse4">
                          <div class="panel-body">
                            <ul class="list-group">
                              <li class="list-group-item">{id_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{id_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{logo_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{logo_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{nom_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{nom_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{tel_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{tel_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{email_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{email_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{dir_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{dir_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{zip_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{zip_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{ciudad_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{ciudad_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{estado_agencia}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{estado_agencia}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_principal}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_terms}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url_terms}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_casillero}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url_casillero}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_registro}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url_registro}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_rastreo}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url_rastreo}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_prealerta}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url_prealerta}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{url_registro_casillero}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{url_registro_casillero}')">
                                  Copy
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-info ">
                        <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion"
                          href="#collapse5">
                          <h4 class="panel-title">
                            @lang('general.data_detail_message') <i class="fal fa-angle-down fr"></i>
                          </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapse5">
                          <div class="panel-body">
                            <ul class="list-group">
                              <li class="list-group-item">{datos_detalle}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{datos_detalle}')">
                                  Copy
                                </a>
                              </li>
                              <li class="list-group-item">{tracking}
                                <a class="btn btn-default copy-btn btn-xs" data-toggle="tooltip" title="Copiar"
                                  @click.stop.prevent="copyTestingCode('{tracking}')">
                                  Copy
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/emailTemplate.js') }}"></script>
<script src="{{ asset('js/plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('js/plugins/tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>
<script>
  tinymce.init({
    selector: '#mensaje',
    plugins: [
      'autoresize',
      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'save table directionality emoticons template paste'
    ],
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    image_advtab: true,
  });
</script>
@endsection