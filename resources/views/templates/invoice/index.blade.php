@extends('layouts.app')

@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('layouts.invoices')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>@lang('layouts.invoices')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
  <style media="screen">
    #tbl-invoice_wrapper {
      padding-bottom: 200px !important;
    }
  </style>
  <div class="row" id="invoice">
    <invoice-component></invoice-component>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/templates/invoice/index.js') }}"></script>
@endsection
