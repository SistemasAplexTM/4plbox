@extends('layouts.app')
@section('title', 'Consignee')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('general.consignee')</h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">@lang('general.home')</a>
      </li>
      <li class="active">
        <strong>@lang('general.consignee')</strong>
      </li>
    </ol>
  </div>
</div>
@endsection

@section('content')
{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQTpXj82d8UpCi97wzo_nKXL7nYrd4G70"></script> --}}
<div class="row" id="consignee">
  <form id="formconsignee" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
    <div class="col-lg-4">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>@lang('general.record_of_consignee')</h5>
          <div class="ibox-tools">

          </div>
        </div>
        <div class="ibox-content">
          <!--***** contenido ******-->
          <form-csc :payload="payload" @updatetable="updateTable"
            @cancel="consignee_id = null; payload.field_id = null"></form-csc>
          <div class="row">
            <contactos-component :table="'consignee'" :parametro="parametro"></contactos-component>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>@lang('general.consignee')</h5>
        </div>
        <div class="ibox-content">
          <!--***** contenido ******-->
          <div class="table-responsive">
            <table id="tbl-consignee" class="table table-striped table-hover table-bordered" style="width: 100%;">
              <thead>
                <tr>
                  <th>PO BOX</th>
                  <th>@lang('general.name')</th>
                  <th>@lang('general.phone')</th>
                  <th>@lang('general.city')</th>
                  <th>@lang('general.client')</th>
                  <th>@lang('general.actions')</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>PO BOX</th>
                  <th>@lang('general.name')</th>
                  <th>@lang('general.phone')</th>
                  <th>@lang('general.city')</th>
                  <th>@lang('general.client')</th>
                  <th>@lang('general.actions')</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/consignee.js') }}"></script>
{{-- <script src="{{ asset('js/templates/documento/postalCode.js') }}"></script> --}}
@endsection