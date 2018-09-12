@extends('layouts.app')
@section('title', 'Bill of lading')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Bill of lading</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Bill of lading</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    #tbl-master_wrapper{
        padding-bottom: 120px;
    }
</style>
<div class="row" id="bill">
	<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    Bill of lading
                </h5>
                <div class="ibox-tools">
                    <a href="{{ url('bill/create') }}" data-toggle="tooltip" title="Crear bill of lading" class="btn btn-primary" >Nuevo <i class="fa fa-plus" style="font-size: small;"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <!--***** contenido ******-->
                <div class="table-responsive">
                    <table id="tbl-bill" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Bill of lading</th>
                                <th>Fecha</th>
                                <th>Punto de origen</th>
                                <th>Muelle de carga</th>
                                <th>Puerto extranjero de descarga</th>
                                <th>Peso Kl</th>
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
<script src="{{ asset('js/templates/bill/index.js') }}"></script>
@endsection