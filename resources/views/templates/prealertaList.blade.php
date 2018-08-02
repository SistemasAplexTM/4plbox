@extends('layouts.app')
@section('title', 'Prealerta')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Prealerta</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Prealerta</strong>
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
                    <h5>Prealertas</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-prealerta" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Tracking</th>
                                    <th>Despachar</th>
                                    <th>Consignee</th>
                                    <th>Agencia</th>
                                    <th>Contenido</th>
                                    <th>Instrucción</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Tracking</th>
                                    <th>Despachar</th>
                                    <th>Consignee</th>
                                    <th>Agencia</th>
                                    <th>Contenido</th>
                                    <th>Instrucción</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
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
<script src="{{ asset('js/templates/prealerta.js') }}"></script>
@endsection