@extends('layouts.app')
@section('title', 'Mantenimiento')
@section('breadcrumb')
{{-- bread crumbs --}}
<style type="text/css">
    .dataTables_wrapper{
        padding-bottom: 200px;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Mantenimiento</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Mantenimiento</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    .ibox-content{
        background-color: #ffffff;
    }
</style>
    <div class="row" id="mantenimientoIndex">
        <div class="row">
            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-content mailbox-content">
                        <div class="file-manager">
                            <div class="space-25"></div>
                            <ul class="folder-list m-b-md" style="padding: 0">
                                <li><a href="#tab-1" data-toggle="tab"> <i class="fal fa-envelope "></i> Plantillas email</a></li>
                                <li><a href="#tab-2" data-toggle="tab"> <i class="fal fa-paypal "></i> Forma de pago</a></li>
                                <li><a href="#tab-3" data-toggle="tab"> <i class="fal fa-credit-card "></i> Tipo de pago</a></li>
                                <li><a href="#tab-4" data-toggle="tab"> <i class="fal fa-sitemap "></i> Grupos</a></li>
                                <li><a href="#tab-5" data-toggle="tab"> <i class="fal fa-sitemap "></i> Cargos</a></li>
                                <li><a href="#tab-6" data-toggle="tab"> <i class="fal fa-plane "></i> Aerolineas</a></li>
                                <li><a href="#tab-7" data-toggle="tab"> <i class="fal fa-plane "></i> Inventario erolineas</a></li>
                                <li><a href="#tab-8" data-toggle="tab"> <i class="fal fa-road "></i> Aeropuertos</a></li>
                                <li><a href="#tab-9" data-toggle="tab"> <i class="fal fa-share-alt "></i> Servicios</a></li>
                                <li><a href="#tab-10" data-toggle="tab"> <i class="fal fa-mail-reply-all "></i> Tipo embarque</a></li>
                                <li><a href="#tab-11" data-toggle="tab"> <i class="fal fa-shopping-bag "></i> Tipo empaque</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 animated fadeInRight">
                <div class="mail-box">
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <plantillaemail-component></plantillaemail-component>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            asfd
                        </div>
                        <div id="tab-3" class="tab-pane">
                            23423
                        </div>
                        <div id="tab-4" class="tab-pane">
                            fgar34534
                        </div>
                        <div id="tab-5" class="tab-pane">
                            asdfgbw35tbw546n
                        </div>
                        <div id="tab-6" class="tab-pane">
                            fftttfsdf356b456456v456nfffff
                        </div>
                        <div id="tab-7" class="tab-pane">
                            fftttfsd453534ffffff
                        </div>
                        <div id="tab-8" class="tab-pane">
                            2547373673
                        </div>
                        <div id="tab-9" class="tab-pane">
                            ybv2 3y345gvy
                        </div>
                        <div id="tab-10" class="tab-pane">
                            34564v5v345yh78
                        </div>
                        <div id="tab-11" class="tab-pane">
                            78978n78789n67m
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/templates/documento/main.js') }}"></script> --}}
<script type="text/javascript">
    var objVue = new Vue({
        el: '#mantenimientoIndex',
        mounted: function() {
        },
        data: {

        },
        methods: {

        }
    })
</script>
@endsection
