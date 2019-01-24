@extends('layouts.app')
@section('title', 'Recibo de ebtrega')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('layouts.receipt')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('layouts.receipt')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">

</style>
    <div class="row" id="receipt">
        <form id="formreceipt" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Recibo de entrega</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="" class="control-label">Nombre del cliente</label>
                                    <a id="input_name" class=" pull-right" data-toggle="tooltip" data-placement="top" title="Manual"><i class="fa fa-pencil" style="color: #f7a54a"></i></a>
                                    <div class="input-group" style="width: 100%;">
                                        <select id="" name="" class="form-control chosen-select" style="width:100%;" tabindex="2">
                                            <option value="">Seleccione</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" id="cliente" name="cliente" value="" style="display: none;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="">Dirección</label>
                                    <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese la dirección">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="">Telefono</label>
                                    <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese el Telefono">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="">Ciudad</label>
                                    <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese la ciudad">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="">Transportador</label>
                                    <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese el nombre del transportador">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="num_warehouse_guia" class="">Warehouse / Guia</label>
                                    <input type="text" style="background-color: lightcyan" onkeyup="if (event.keyCode == 13)
                                                addDocumentoToRecibo();" id="num_warehouse_guia" name="num_warehouse_guia" value="" class="form-control" placeholder="Ingrese el Numero de Warehouse o Guia">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="entregado" class="control-label col-lg-12">&nbsp;</label>
                                    <input style="display: none;" name="entregado" id="entregado" type="checkbox" data-toggle="toggle" data-size='small' data-on="Entregar/Revisado" data-off="Consolidadar sin entregar" data-width="100%" data-style="ios" data-onstyle="primary" data-offstyle="warning" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group" id="div_wrh_guia_r" style="">
                                    <label for="num_warehouse_guia_r" class="">Revisar - entregar</label>
                                    <input type="text" style="background-color: #e0ffe6" id="num_warehouse_guia_r" name="num_warehouse_guia_r" value="" class="form-control" placeholder="Documento a revisar y entregar." onkeyup="if (event.keyCode == 13)
                                                checkDocument($(this).val());">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group" id="div_status" style="">
                                    <label for="status" class="">Observación para el Estatus</label>
                                    <input type="text" style="background-color: #e0ffe6" id="status" name="status" value="" class="form-control" placeholder="Observacion para el Estatus." onkeyup="if (event.keyCode == 13)
                                                checkDocument($('#num_warehouse_guia_r').val());">
                                </div>
                            </div>

                        </div>
                        <div class="row"><div class="mensage"></div></div>
                        <div class="row" style="padding-top: 10px;">
                            <table class="table table-striped table-hover" id="tbl_entregaWare">
                                <thead>
                                    <tr>
                                        <th>Wareouses</th>
                                        <th>Tracking</th>
                                        <th style="width: 100px;">Cantidad</th>
                                        <th style="width: 100px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            @include('layouts.buttons')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Recibos registrados</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <!--***** contenido ******-->
                        <div class="table-responsive">
                            <table id="tbl-receipt" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th># Recibo</th>
                                        <th>Consignee</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/receipt.js') }}"></script>
@endsection
