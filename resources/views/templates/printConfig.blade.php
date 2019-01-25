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
                <strong>@lang('layouts.print_config')</strong> <!--No se puede traducir-->
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
            <h5>@lang('layouts.print_config')</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-lg-12">
              <p>
                En este módulo, usted podrá configurar lasimpresoras para Labels y para documentos, por favis siga las instrucciones.
              </p>
              <ul>
                <li>
                  1. Descargue el archivo web Client
                  <br>
                  <a style="margin-right: 15px; margin-left: 10px" class="b-r" href="https://www.neodynamic.com/downloads/wcpp/wcpp-4.0.18.719-win.exe"><i class="fab fa-windows fa-2x"></i></a>
                  <a style="margin-right: 15px; margin-left: 10px" class="b-r" href="https://www.neodynamic.com/downloads/wcpp/wcpp-4.0.18.601-intel-macosx.dmg"><i class="fab fa-apple fa-2x"></i></a>
                  <a style=" margin-left: 10px" href="https://www.neodynamic.com/downloads/wcpp/wcpp-4.0.18.601-i386.deb"><i class="fab fa-linux fa-2x"></i></a>
                </li>
                <li>
                  Sí la instalación del programa tuvo exito, podrá seleccionar las impresoras de su sistema
                </li>
              </ul>
            </div>
          </div>
          <div class="row">
              <div class="col-lg-12" style="margin-top: 10px;">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div>
                        <label for="installedPrinterName">3. @lang('general.print_label'):</label>
                        <select name="installedPrinterName" id="installedPrinterName" class="form-control"></select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div>
                        <label for="installedPrinterName1">4. @lang('general.print_default'):</label>
                        <select name="installedPrinterName1" id="installedPrinterName1" class="form-control"></select>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-6">
                <a class="ladda-button btn btn-primary" @click="savePrint()">
                  <i class="fa fa-save"></i> @lang('general.save')
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  {!! $wcpScript !!}
{{-- <script src="{{ asset('js/templates/printConfig.js') }}"></script> --}}
@endsection
