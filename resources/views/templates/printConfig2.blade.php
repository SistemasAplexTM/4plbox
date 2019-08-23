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

            <div class="col-md-3">
                <a onclick="javascript:jsWebClientPrint.getPrintersInfo(); $('#spinner').css('visibility', 'visible');" class="btn btn-success">Get Printers Info...</a>
            </div>
            <div class="col-md-9">
                <h3 id="spinner" style="visibility: hidden"><span class="label label-info"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>Please wait a few seconds...</span></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label for="lstPrinters">Printers:</label>
                <select name="lstPrinters" id="lstPrinters" onchange="showSelectedPrinterInfo();" class="form-control"></select>
            </div>
            <div class="col-md-4">
                <label for="lstPrinterTrays">Supported Trays:</label>
                <select name="lstPrinterTrays" id="lstPrinterTrays" class="form-control"></select>
            </div>
            <div class="col-md-4">
                <label for="lstPrinterPapers">Supported Papers:</label>
                <select name="lstPrinterPapers" id="lstPrinterPapers" class="form-control"></select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <br />
                <br />
                <div class="input-group">
                    <span class="input-group-addon" id="portName">Port Name:</span>
                    <input type="text" id="txtPortName" class="form-control" placeholder=" " aria-describedby="portName">
                </div>
                <br />
                <div class="input-group">
                    <span class="input-group-addon" id="hRes">Horiz Res (dpi):</span>
                    <input type="text" id="txtHRes" class="form-control" placeholder=" " aria-describedby="hRes">
                </div>
                <br />
                <div class="input-group">
                    <span class="input-group-addon" id="vRes">Vert Res (dpi):</span>
                    <input type="text" id="txtVRes" class="form-control" placeholder=" " aria-describedby="vRes">
                </div>
            </div>
            <div class="col-md-4">
                <br />
                <br />
                <h4><span id="isConnected" class="label label-default glyphicon glyphicon-minus">&nbsp;</span> Is Connected?</h4>
                <hr />
                <h4><span id="isDefault" class="label label-default glyphicon glyphicon-minus">&nbsp;</span> Is Default?</h4>
                <hr />
                <h4><span id="isBIDIEnabled" class="label label-default glyphicon glyphicon-minus">&nbsp;</span> Is BIDI Enabled?</h4>
            </div>
            <div class="col-md-4">
                <br />
                <br />
                <h4><span id="isLocal" class="label label-default glyphicon glyphicon-minus">&nbsp;</span> Is Local?</h4>
                <hr />
                <h4><span id="isNetwork" class="label label-default glyphicon glyphicon-minus">&nbsp;</span> Is Network?</h4>
                <hr />
                <h4><span id="isShared" class="label label-default glyphicon glyphicon-minus">&nbsp;</span> Is Shared?</h4>
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
