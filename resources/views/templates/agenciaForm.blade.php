@extends('layouts.app')
@section('title','Agencia')
@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('general.agencies')</h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">@lang('general.home')</a>
      </li>
      <li>
        <a href="{{ route('agencia.index') }}">@lang('general.agencies')</a>
      </li>
      <li class="active">
        <strong>{{ (isset($agencia) and $agencia) ? 'Editar agencia' : 'Registro de agencias' }}</strong>
        <!--No se puede traducir-->
      </li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<style type="text/css">
  #heading_paypal {
    /*color: #fefefe;*/
    background: rgba(145, 195, 242, 1);
    background: -moz-radial-gradient(center, ellipse cover, rgba(145, 195, 242, 1) 0%, rgba(95, 161, 236, 1) 100%);
    background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(145, 195, 242, 1)), color-stop(100%, rgba(95, 161, 236, 1)));
    background: -webkit-radial-gradient(center, ellipse cover, rgba(145, 195, 242, 1) 0%, rgba(95, 161, 236, 1) 100%);
    background: -o-radial-gradient(center, ellipse cover, rgba(145, 195, 242, 1) 0%, rgba(95, 161, 236, 1) 100%);
    background: -ms-radial-gradient(center, ellipse cover, rgba(145, 195, 242, 1) 0%, rgba(95, 161, 236, 1) 100%);
    background: radial-gradient(ellipse at center, rgba(145, 195, 242, 1) 0%, rgba(95, 161, 236, 1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#91c3f2', endColorstr='#5fa1ec', GradientType=1);
  }

  #heading_mail {
    /*color: #282828;*/
    background: rgba(252, 250, 222, 1);
    background: -moz-radial-gradient(center, ellipse cover, rgba(252, 250, 222, 1) 0%, rgba(255, 231, 194, 1) 100%);
    background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(252, 250, 222, 1)), color-stop(100%, rgba(255, 231, 194, 1)));
    background: -webkit-radial-gradient(center, ellipse cover, rgba(252, 250, 222, 1) 0%, rgba(255, 231, 194, 1) 100%);
    background: -o-radial-gradient(center, ellipse cover, rgba(252, 250, 222, 1) 0%, rgba(255, 231, 194, 1) 100%);
    background: -ms-radial-gradient(center, ellipse cover, rgba(252, 250, 222, 1) 0%, rgba(255, 231, 194, 1) 100%);
    background: radial-gradient(ellipse at center, rgba(252, 250, 222, 1) 0%, rgba(255, 231, 194, 1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fcfade', endColorstr='#ffe7c2', GradientType=1);
  }

  #heading_zoopim {
    /*color: #fefefe;*/
    background: rgba(194, 227, 235, 1);
    background: -moz-radial-gradient(center, ellipse cover, rgba(194, 227, 235, 1) 0%, rgba(194, 227, 235, 1) 26%, rgba(0, 196, 240, 1) 100%);
    background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(194, 227, 235, 1)), color-stop(26%, rgba(194, 227, 235, 1)), color-stop(100%, rgba(0, 196, 240, 1)));
    background: -webkit-radial-gradient(center, ellipse cover, rgba(194, 227, 235, 1) 0%, rgba(194, 227, 235, 1) 26%, rgba(0, 196, 240, 1) 100%);
    background: -o-radial-gradient(center, ellipse cover, rgba(194, 227, 235, 1) 0%, rgba(194, 227, 235, 1) 26%, rgba(0, 196, 240, 1) 100%);
    background: -ms-radial-gradient(center, ellipse cover, rgba(194, 227, 235, 1) 0%, rgba(194, 227, 235, 1) 26%, rgba(0, 196, 240, 1) 100%);
    background: radial-gradient(ellipse at center, rgba(194, 227, 235, 1) 0%, rgba(194, 227, 235, 1) 26%, rgba(0, 196, 240, 1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c2e3eb', endColorstr='#00c4f0', GradientType=1);
  }
</style>
<div class="row" id="agenciaform">
  <form id="formaagencia" enctype="multipart/form-data" class="form-horizontal" role="form"
    action="{{ (isset($agencia) and $agencia) ? route('agencia.update', [$agencia->id]) : route('agencia.store') }}"
    method="{{ (isset($agencia) and $agencia) ? 'POST' : 'POST' }}">
    {{ csrf_field() }}
    {{ (isset($agencia) and $agencia) ? method_field('PUT') : method_field('POST') }}
    <input type="hidden" id="edit" value="{{ (isset($agencia) and $agencia) ? 'edit' : '' }}">
    <input type="hidden" id="id" value="{{ (isset($agencia) and $agencia) ? $agencia->id : '' }}">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{{ (isset($agencia) and $agencia) ? 'Editar agencia' : 'Registro de agencias' }}</h5>
        </div>
        <div class="ibox-content">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="agencia" class="active"><a href="#tab1" aria-controls="tab1" role="tab"
                data-toggle="tab">@lang('general.registration_data')</a></li>
            <li role="agencia"><a href="#tab2" aria-controls="tab2" role="tab"
                data-toggle="tab">@lang('general.integrations')</a></li>
            <li role="agencia"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">URL</a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="tab1" style="margin-top: 20px;">
              <!--***** contenido ******-->
              <div class="col-lg-4">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="descripcion" class="control-label">@lang('general.name')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->descripcion : old('descripcion') }}">
                        @if ($errors->has('descripcion'))
                        <small class="help-block">{{ $errors->first('descripcion') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('responsable') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="responsable" class="control-label">@lang('general.responsable')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="responsable" name="responsable"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->responsable : old('responsable') }}">
                        @if ($errors->has('responsable'))
                        <small class="help-block">{{ $errors->first('responsable') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('direccion') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="direccion" class="control-label">@lang('general.address')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="direccion" name="direccion"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->direccion : old('direccion') }}">
                        @if ($errors->has('direccion'))
                        <small class="help-block">{{ $errors->first('direccion') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('telefono') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="telefono" class="control-label">@lang('general.phone')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="tel" class="form-control" data-mask="(999) 999-9999" id="telefono" name="telefono"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->telefono : old('telefono') }}">
                        @if ($errors->has('telefono'))
                        <small class="help-block">{{ $errors->first('telefono') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('whatsapp') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="whatsapp" class="control-label">@lang('general.whatsapp')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="tel" class="form-control" data-mask="(999) 999-9999" id="whatsapp" name="whatsapp"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->whatsapp : old('whatsapp') }}">
                        @if ($errors->has('whatsapp'))
                        <small class="help-block">{{ $errors->first('whatsapp') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('localizacion_id') ? ' has-error' : '' }}">
                      <div class="col-sm-4">
                        <label for="localizacion_id" class="control-label gcore-label-top">@lang('general.city')</label>
                      </div>
                      <div class="col-sm-8">
                        <select name="localizacion_id" id="localizacion_id"
                          class="form-control js-data-example-ajax select2-container">
                          @if(isset($agencia) and $agencia)
                          <option value="{{ $agencia->ciudad_id }}" selected="">
                            {{ $agencia->ciudad }}</option>
                          @endif
                        </select>
                        @if ($errors->has('localizacion_id'))
                        <small class="help-block">{{ $errors->first('localizacion_id') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="zip" class="control-label">@lang('general.zip_code')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="zip" name="zip"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->zip : old('zip') }}">
                        @if ($errors->has('zip'))
                        <small class="help-block">{{ $errors->first('zip') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="email" class="control-label">@lang('general.email')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="mail" class="form-control" id="email" name="email"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->email : old('mail') }}">
                        @if ($errors->has('email'))
                        <small class="help-block">{{ $errors->first('email') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('email_cc') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="email_cc" class="control-label">@lang('general.email_cc')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="mail" class="form-control" id="email_cc" name="email_cc"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->email_cc : old('mail') }}">
                        @if ($errors->has('email_cc'))
                        <small class="help-block">{{ $errors->first('email_cc') }}</small>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-lg-4">
                        <label for="prefijo_pobox" class="control-label">
                          <div class="col-sm-12" data-trigger="hover" data-container="body" data-toggle="popover"
                            data-placement="rigth"
                            data-content="Si no se ingresa ningun dato aqui, el PoBox de los clientes se creara automaticamente."
                            style="padding-left: 0px; padding-right: 0px;">
                            @lang('general.pobox_prefix')
                            <i class="fal fa-question-circle" style="cursor: pointer; color: coral;"></i>
                          </div>
                        </label>
                      </div>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="prefijo_pobox" name="prefijo_pobox"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->prefijo_pobox : '' }}"
                          placeholder="Para el código de los clientes">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="url" class="control-label">URL:</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="url" class="form-control" id="url" name="url"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->url : old('url') }}"
                          placeholder="https://">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group {{ $errors->has('url_terms') ? ' has-error' : '' }}">
                      <div class="col-lg-4">
                        <label for="url_terms" class="control-label">@lang('general.url_terms')</label>
                      </div>
                      <div class="col-lg-8">
                        <input type="url" class="form-control" id="url_terms" name="url_terms"
                          value="{{ (isset($agencia) and $agencia) ? $agencia->url_terms : old('url_terms') }}"
                          placeholder="https://">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-lg-4">
                        <label for="logo" class="control-label">
                          <div class="col-sm-12" data-trigger="hover" data-container="body" data-toggle="popover"
                            data-placement="rigth"
                            data-content="Verifique que la imagen sea de un peso igual o menor a 1 Mb"
                            style="padding-left: 0px; padding-right: 0px;">
                            Logo:
                            <i class="fal fa-question-circle" style="cursor: pointer; color: coral;"></i>
                          </div>
                        </label>
                      </div>
                      <div class="col-lg-8">
                        <input type="file" class="form-control" id="logo" name="logo">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- DETALLE DE AGENCIA -->
              {{-- @if(isset($agencia) and $agencia->tipo_agencia == 1) --}}
              <div class="col-lg-8">
                <div class="col-lg-12">
                  <!--<div class="hr-line-dashed"></div>-->
                  <div class="form-group">
                    <label for="" class="col-lg-12"
                      style="text-align: center; font-size: 15px;">@lang('general.agency_detail')</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group" id="servicio">
                      <label for="servicios_id" class="">@lang('general.service')</label>
                      <select id="servicios_id" name="servi_id" class="form-control">
                        <option value="" data-tarifa="" data-seguro="">@lang('general.select')
                        </option>
                        {{-- llenar select de servicios --}}
                      </select>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group" id="classTarifaP">
                      <label for="tarifaP" class="">@lang('general.rate')</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" readonly="" class="form-control" id="tarifaP" name="tarifaP" placeholder="0">
                      </div>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group" id="classTarifaP">
                      <label for="seguroP" class="">@lang('general.insurance')</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" readonly="" class="form-control" id="seguroP" name="seguroP" placeholder="0">
                      </div>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group" id="classTarifaP">
                      <label for="seguroP" class="">@lang('general.minimum_fee')</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" readonly="" class="form-control" id="min" name="min" placeholder="0">
                      </div>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3">
                    &nbsp;
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group" id="classTarifaA">
                      <label for="tarifaA" class="">@lang('general.agency_rate')</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" id="tarifaA" name="tarifaA" placeholder="Ej: 0.1">
                      </div>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                  <div class="col-sm-2" data-container="body" data-toggle="popover" data-placement="top"
                    data-content="Por cada 100 USD se cobrara el valor ingresado en el seguro.">
                    <div class="form-group" id="classSeguro">
                      <label for="seguro" class="">@lang('general.insurance_agency')</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" id="seguro" name="seg" value="0">
                      </div>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group" id="classSeguro">
                      <label for="seguro" class="">@lang('general.minimum_fee_agency')</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" id="min_agency" name="min_agency" value="0">
                      </div>
                      <small id="msn1" class="help-block"></small>
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div class="form-group">
                      <div class="col-lg-12">
                        <label for="" class="">&nbsp;</label>
                      </div>
                      <div class="col-lg-12">
                        <a class="btn btn-primary btn-sm" type="button" id="btn_add_row" onclick="addRow()" value="1"
                          data-toggle="tooltip" title="Agregar"><i class="fal fa-plus"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="table-responsive form-group">
                    <div id="delete-ok"></div>
                    <table class="table table-striped table-bordered table-hover" id="detalleAgencia">
                      <thead>
                        <tr>
                          <th>@lang('general.service')</th>
                          <th>@lang('general.rate') $</th>
                          <th>@lang('general.rate_min') $</th>
                          <th>@lang('general.insurance')</th>
                          <th>@lang('general.agency_rate')</th>
                          <th>$ @lang('general.insurance')</th>
                          <th>$ @lang('general.minimum_fee')</th>
                          <th width="100px">@lang('general.actions')</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(isset($detalle))
                        <?php $count = 1; ?>
                        @foreach ($detalle as $data)
                        <tr id="fila{{ $count }}" class="rowServices">
                          <td><input type="hidden" id="servi" name="" value="{{ $data->servicios_id }}"
                              class="form-control" readonly><input type="text" id="serviN" name=""
                              value="{{ $data->tipo_embarque }} - {{ $data->servicio }}" class="form-control" readonly>
                          </td>
                          <td><input type="text" id="tariP" name="" value="{{ $data->tarifa_principal }}"
                              class="form-control" readonly></td>
                          <td><input type="text" id="tariPmin" name="" value="{{ $data->tarifa_minima }}"
                              class="form-control" readonly></td>
                          <td><input type="text" id="segup" name="" value="{{ $data->seguro_principal }}"
                              class="form-control" readonly></td>
                          <td><input type="text" id="tariA{{ $data->id }}" name="" value="{{ $data->tarifa_agencia }}"
                              class="form-control" readonly></td>
                          <td><input type="text" id="segu{{ $data->id }}" name="" value="{{ $data->seguro }}"
                              class="form-control" readonly>
                          </td>
                          <td><input type="text" id="min{{ $data->id }}" name=""
                              value="{{ $data->tarifa_minima_agencia }}" class="form-control" readonly></td>

                          <td>
                            <a onclick="editRowServices({{ $count }}, {{ $data->id }})" id="btn_edit{{ $data->id }}"
                              class='btn_edit edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i
                                class='fal fa-pencil fa-lg'></i></a>
                            <a class="delete_btn btn_remove" onclick="removeRowServices({{ $count }}, {{ $data->id }})"
                              id="{{ $data->id }}"><i class="fal fa-trash-alt fa-lg" data-toggle="tooltip"
                                data-placement="top" title="Eliminar"></i></a></td>
                        </tr>
                        <?php $count++; ?>
                        @endforeach
                        @endif
                      </tbody>
                      <tfoot>
                      </tfoot>
                    </table>
                    <div id="noEnviar" class="col-lg-12" style="text-align: center; color: red; display: none;">
                      @lang('general.service_in_the_detail')</div>
                  </div>
                </div>
                <!--**************** Fin Detalle Agencia  *******************************-->
              </div>
              {{-- @endif --}}
              <div class="col-lg-12">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="observacion" class="">@lang('general.observation')</label>
                    <textarea class="form-control" id="observacion" name="observacion"></textarea>
                  </div>
                </div>
              </div>
              <!--**************** Detalle Agencia  *******************************-->
              <!--****************Aquí*******************************-->
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <div class="col-sm-12 col-sm-offset-0 guardar">
                      <a class="btn btn-white" href="{{ route('agencia.index') }}"
                        style="display: {{ (isset($agencia) and $agencia) ? 'none' : 'inline-block' }}">
                        <i class="fal fa-mail-reply"></i> @lang('general.return')
                      </a>
                      <a class="btn btn-white" href="{{ route('agencia.index') }}"
                        style="display: {{ (isset($agencia) and $agencia) ? 'inline-block' : 'none' }}">
                        <i class="fal fa-times"></i> @lang('general.cancel')
                      </a>
                      <a class="ladda-button btn btn-primary" id="saveForm"
                        style="display: {{ (isset($agencia) and $agencia) ? 'none' : 'inline-block' }}">
                        <i class="fal fa-save"></i> @lang('general.save')
                      </a>
                      <button class="ladda-button btn btn-warning" id="updateForm"
                        style="display: {{ (isset($agencia) and $agencia) ? 'inline-block' : 'none' }}">
                        <i class="fal fa-edit"></i> @lang('general.update')
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab2">
              <div class="row">
                <div class="col-lg-12">
                  <agency-integrations-component :agency_id="{{ ((isset($agencia) and $agencia) ? $agencia->id : 0) }}">
                  </agency-integrations-component>
                </div>
              </div>
              <div class="row" style="margin-top: 20px;"></div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab3">
              <div class="row">
                <div class="col-lg-12" style="margin-top: 10px;">
                  <div class="col-lg-6">
                    <h3>Públicas</h3>
                    <div class="form-group">
                      <table class="table table-striped table-hover table-bordered" id="tbl-url" style="width: 100%;">
                        <thead>
                          <tr>
                            <th width="35%">@lang('general.description')</th>
                            <th>URL</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>@lang('general.tracking')</td>
                            <td><a target="_blank" href="{{ url('/').'/rastreo' }}">{{ url('/').'/rastreo' }}</a>
                            </td>
                          </tr>
                          <tr>
                            <td>@lang('general.locker_registration')</td>
                            <td><a target="_blank"
                                href="{{ url('/').'/registro/' }}{{ (isset($agencia) and $agencia) ? base64_encode($agencia->id) : '' }}">{{ url('/').'/registro/' }}{{ (isset($agencia) and $agencia) ? base64_encode($agencia->id) : '' }}</a>
                            </td>
                          </tr>
                          <tr>
                            <td>@lang('general.pre_alert')</td>
                            <td><a target="_blank"
                                href="{{ url('/').'/prealerta/' }}{{ (isset($agencia) and $agencia) ? base64_encode($agencia->id) : '' }}">{{ url('/').'/prealerta/' }}{{ (isset($agencia) and $agencia) ? base64_encode($agencia->id) : '' }}</a>
                            </td>
                          </tr>
                          <tr>
                            <td>Casillero</td>
                            <td>
                              @php
                              $arr = explode("//", url('/'));
                              @endphp
                              <a
                                href="{{ $arr[0] . '//casillero/' . $arr[1].'/login/' }}{{ (isset($agencia) and $agencia) ? base64_encode($agencia->id) : '' }}">
                                {{ $arr[0] . '//casillero/' . $arr[1].'/login/' }}{{ (isset($agencia) and $agencia) ? base64_encode($agencia->id) : '' }}
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>

                    </div>
                  </div>
                  <div class="col-lg-6">
                    <h3>Privadas</h3>
                    <table class="table table-striped table-hover table-bordered" id="tbl-url" style="width: 100%;">
                      <thead>
                        <tr>
                          <th width="35%">@lang('general.description')</th>
                          <th>URL</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>URL Principal</td>
                          <td>
                            <a class="editable_url" data-name="url" data-type="textarea"
                              data-pk="{{ (isset($agencia) and $agencia) ? $agencia->id : '' }}">{{ (isset($agencia) and $agencia) ? $agencia->url : '' }}</a>
                          </td>
                        </tr>
                        <tr>
                          <td>Términos y condiciones</td>
                          <td>
                            <a class="editable_url" data-name="url_terms" data-type="textarea"
                              data-pk="{{ (isset($agencia) and $agencia) ? $agencia->id : '' }}">{{ (isset($agencia) and $agencia) ? $agencia->url_terms : '' }}</a>
                          </td>
                        </tr>
                        <tr>
                          <td>Registro de casillero</td>
                          <td>
                            <a class="editable_url" data-name="url_registro_casillero" data-type="textarea"
                              data-pk="{{ (isset($agencia) and $agencia) ? $agencia->id : '' }}">{{ (isset($agencia) and $agencia) ? $agencia->url_registro_casillero : '' }}</a>
                          </td>
                        </tr>
                        <tr>
                          <td>Prealertar</td>
                          <td>
                            <a class="editable_url" data-name="url_prealerta" data-type="textarea"
                              data-pk="{{ (isset($agencia) and $agencia) ? $agencia->id : '' }}">{{ (isset($agencia) and $agencia) ? $agencia->url_prealerta : '' }}</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
{{-- {{ Form::close() }} --}}
</form>
</div>
@endsection

@section('scripts')

<script src="{{ asset('js/templates/agenciaForm.js') }}"></script>
<script>
  $(document).ready(function(){
  $.fn.editable.defaults.mode = 'popup';
  $.fn.editable.defaults.params = function(params) {
      params._token = $('meta[name="csrf-token"]').attr('content');
      return params;
  };

  $('.editable_url').editable({
    ajaxOptions: {
      type: 'post',
      dataType: 'json'
    },
    url: "/agencia/saveURL/" + {{ (isset($agencia) and $agencia) ? $agencia->id : 0 }},
    title: 'Digite URL',
    validate:function(value){
      if($.trim(value) == ''){
        return 'Este campo es obligatorio!';
      }
    },
    success: function(response, newValue) {
      // me.get();
    }
  });
})
</script>
@endsection