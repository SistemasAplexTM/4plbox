@extends('layouts.app')
@section('title', 'Guía master')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Guía master</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Guía master</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    #tbl-master_wrapper{
        padding-bottom: 100px;
    }
</style>
<div class="row" id="master">
	<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    Guías registradas
                </h5>
                <div class="ibox-tools">
                    <a href="{{ url('master/create') }}" data-toggle="tooltip" title="Crear nueva master" class="btn btn-primary" >Nuevo <i class="fa fa-plus" style="font-size: small;"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-master" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Master AWB</th>
                                <th>Aerolinea</th>
                                <th>Fecha</th>
                                <th>Tarifa</th>
                                <th>Peso Lb</th>
                                <th>Peso Kl</th>
                                <th>Consignee</th>
                                <th>Destino</th>
                                <th>Consolidado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>             
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/master/master_list.js') }}"></script>
@endsection