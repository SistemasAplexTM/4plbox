@extends('layouts.app')
@section('title','Agencia')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('layouts.print_config')</h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">@lang('general.home')</a>
      </li>
      <li class="active">
        <strong>@lang('layouts.print_config')</strong>
        <!--No se puede traducir-->
      </li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row" id="printConfig">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>@lang('layouts.print_config')
          <small>
            (En este módulo, usted podrá configurar las impresoras para Labels y
            para documentos, por favor siga las
            instrucciones.)
          </small>
        </h5>
      </div>
      <div class="ibox-content">
        <div id="msgInProgress">
          <div id="mySpinner" style="width:32px;height:32px"></div>
          <br />
          <h3>Detecting WCPP utility at client side...</h3>
          <h3><span class="label label-info"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
              Please wait a few seconds...</span></h3>
          <br />
        </div>
        <div id="msgInstallWCPP" style="display:none;">
          <div class="row">
            <div class="col-lg-12">
              <ul>
                <li>
                  1. Descargue el archivo web Client
                  <br>
                  <a style="margin-right: 15px; margin-left: 10px" class="b-r"
                    href="https://www.neodynamic.com/downloads/wcpp/wcpp-4.0.18.719-win.exe"><i
                      class="fab fa-windows fa-2x"></i></a>
                  <a style="margin-right: 15px; margin-left: 10px" class="b-r"
                    href="https://www.neodynamic.com/downloads/wcpp/wcpp-4.0.18.601-intel-macosx.dmg"><i
                      class="fab fa-apple fa-2x"></i></a>
                  <a style=" margin-left: 10px"
                    href="https://www.neodynamic.com/downloads/wcpp/wcpp-4.0.18.601-i386.deb"><i
                      class="fab fa-linux fa-2x"></i></a>
                </li>
                Sí ya instaló el programa haga<a href="javascript:location.reload(true);" class="btn btn-default"> Click
                  para recargar </a>
              </ul>
            </div>
          </div>
        </div>


        <div id="detected" style="display:none;">
          <div class="row">
            <div class="col-lg-6">
              <div class="col-md-12">
                <el-button type="primary" size="medium" @click="getPrints()" :loading="loading_prints">Cargar
                  impresoras...
                </el-button>
              </div>
              <div class="col-lg-12" style="margin-top: 10px;">
                <div class="form-group">
                  <div>
                    <label for="installedPrinterName1">@lang('general.print_default'):</label>
                    <select disabled name="installedPrinterName1" id="installedPrinterName1"
                      class="form-control"></select>
                  </div>
                </div>
                <div class="form-group">
                  <div>
                    <label for="installedPrinterName">@lang('general.print_label'):</label>
                    <select name="installedPrinterName" id="installedPrinterName" class="form-control"></select>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <el-button type="success" size="medium" @click="savePrint()" :loading="loading"><i
                    class="fal fa-save"></i>
                  @lang('general.save')
                </el-button>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="table-responsive">
                <table id="tbl-mintic" class="table table-striped table-hover" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Impresora Labels</th>
                      <th>Impresora Por Defecto</th>
                      <th>@lang('general.actions')</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in printers">
                      <td>@{{ index + 1 }}</td>
                      <td>@{{ item.label }}</td>
                      <td>@{{ item.default }}</td>
                      <td><a @click="deletePrint(index)" class="delete_btn" data-toggle="tooltip" data-placement="top"
                          title="" data-original-title="Eliminar"><i class="fal fa-trash-alt fa-lg"></i></a></td>
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
@endsection

@section('scripts')
{!! $wcppScriptDetect !!}
{!! $wcpScript !!}
<script src="{{ asset('js/templates/printConfig.js') }}"></script>
@endsection