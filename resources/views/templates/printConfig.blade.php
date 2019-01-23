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
                (iOS)
              </p>
              <ul>
                <li>
                  1. Descargue el archivo web Client (Windows)
                </li>
                <li>
                  2. Compruebe la conexción del sistema
                </li>
                <li>
                  3. Seleccione impresora para Labels
                </li>
                <li>
                  4. Seleccione la impresora por defecto para los documentos en papel carta u oficio.
                </li>
                <li>
                  5. Ahora puede imprimir los documentos sin utilizar ventanas emergentes.
                </li>
              </ul>
            </div>
          </div>
          <div class="row">
              <div class="col-lg-12" style="margin-top: 10px;">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div>
                        <label for="installedPrinterName">@lang('general.print_label'):</label>
                        <select name="installedPrinterName" id="installedPrinterName" class="form-control"></select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div>
                        <label for="installedPrinterName1">@lang('general.print_default'):</label>
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
<script type="text/javascript">
  document.getElementById('testNumber').innerHTML = number_format(25.4);
</script>

{{-- <script src="{{ asset('js/templates/printConfig.js') }}"></script> --}}
@endsection
