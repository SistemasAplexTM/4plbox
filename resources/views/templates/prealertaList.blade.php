@extends('layouts.app')
@section('title', 'Prealerta')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.pre_alert')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.pre_alert')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row" id="prealerta" data-id_agencia="{{ $id_age }}">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>@lang('general.pre_alert')</h5>
                <div class="ibox-tools">

                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-prealerta" class="table table-striped table-hover table-bordered"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('general.tracking')</th>
                                <th>@lang('general.pack_off')</th>
                                <th>@lang('general.consignee')</th>
                                <th>@lang('general.agency')</th>
                                <th>@lang('general.content')</th>
                                <th>@lang('general.instruction')</th>
                                <th>@lang('general.email')</th>
                                <th>@lang('general.phone')</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('general.tracking')</th>
                                <th>@lang('general.pack_off')</th>
                                <th>@lang('general.consignee')</th>
                                <th>@lang('general.agency')</th>
                                <th>@lang('general.content')</th>
                                <th>@lang('general.instruction')</th>
                                <th>@lang('general.email')</th>
                                <th>@lang('general.phone')</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/plugins/zip.js-master/scripts/JSPrintManager.js') }}"></script>
<script src="{{ asset('js/plugins/zip.js-master/scripts/zip.js') }}"></script>
<script src="{{ asset('js/plugins/zip.js-master/scripts/zip-ext.js') }}"></script>
<script src="{{ asset('js/plugins/zip.js-master/scripts/deflate.js') }}"></script> --}}

{{-- <script src="{{ asset('js/plugins/QZ-Print/dependencies/rsvp-3.1.0.min.js') }}"></script>
<script src="{{ asset('js/plugins/QZ-Print/dependencies/sha-256.min.js') }}"></script>
<script src="{{ asset('js/plugins/QZ-Print/qz-tray.js') }}"></script> --}}

<script src="{{ asset('js/templates/prealerta.js') }}"></script>
@endsection