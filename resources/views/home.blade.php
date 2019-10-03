@extends('layouts.app')
@section('title', 'Inicio')
@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>
            @lang('general.home')
        </h2>
    </div>
</div>
@endsection
@section('content')
@if(Auth::user()->isRole('admin'))

<div class="row" id="homeIndex">
    <div class="col-lg-12">
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top: 0px;">
            <div class="row">
                <div class="col-lg-12" style="margin-bottom: 10px;">
                    <div class="col-lg-6">
                        <div class=" feed-activity-list">
                            <div class="feed-element">
                                <h1>
                                    Principal
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class=" feed-activity-list">
                            <div class="feed-element">
                                <h1>
                                    @lang('general.users')
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-warning dim btn-large-dim btn-outline btn-inicio" type="button"
                            id="documento">
                            <i class="fal fa-box-open">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.warehouse')
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-primary dim btn-large-dim btn-outline btn-inicio" type="button"
                            id="master">
                            <i class="fal fa-paste">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            Master
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-default dim btn-large-dim btn-outline btn-inicio" type="button"
                            id="tracking">
                            <i class="fal fa-cubes">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.tracking')
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-success dim btn-large-dim btn-outline btn-inicio" type="button"
                            id="shipper">
                            <i class="fal fa-user-circle">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.shipper')
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-info dim btn-large-dim btn-outline btn-inicio" type="button"
                            id="consignee">
                            <i class="fal fa-user-circle">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.consignee')
                        </div>
                    </div>

                    <div class="col-lg-3 text-center">
                        <button class="btn btn-primary dim btn-large-dim btn-outline btn-inicio" type="button"
                            id="backup">
                            <i class="fal fa-cloud-download-alt">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.backup')
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-lg-12" style="margin-bottom: 10px;margin-top: 30px;">
                    <div class="col-lg-6">
                        <div class=" feed-activity-list">
                            <div class="feed-element">
                                <h1>
                                    Ficheros
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-default dim btn-large-dim btn-outline btn-inicio" type="button" id="mantenimiento">
                            <i class="fal fa-wrench">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            Mantenimiento
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-danger dim btn-large-dim btn-outline btn-inicio" type="button" id="administracion">
                            <i class="fal fa-cogs">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            Administración
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endif
@endsection
@section('scripts')

@endsection