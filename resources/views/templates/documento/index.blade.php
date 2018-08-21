@extends('layouts.app')
@section('title', 'Documentos')
@section('breadcrumb')
{{-- bread crumbs --}}
<style type="text/css">
    .dataTables_wrapper{
        padding-bottom: 200px;
        padding-right: 30px;
    }
    #msn_sendmail{
        width: 50%;
        font-size: 15px;
        padding: 5px;
        float: right;
        margin-bottom: 0px;
    }
    #icon_close{
        right: 0px;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Documentos</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Documentos</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="documentoIndex" data-id_print="{{Session('print_document')['id']}}" data-doc_print="{{Session('print_document')['document']}}">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Documentos</h5>
                </div>
                <div class="ibox-content">
                    <modaltagdocument-component :params='params' :id_status='id_status' :table_delete="tableDelete"></modaltagdocument-component>
                    <div class="row">
                        <div class="row">
                            
                            <div id="msn-documento" class="col-lg-12" style="text-align: left;display: none;">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger alert-dismissible" role="alert" id="msnP">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Atención!</strong> <i id="msn"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2" style="text-align: left;" id="ajaxCreate">
                                <div class="col-lg-12">
                                    <button type="button"  style="" class="btn btn-primary btn-lg btn-block" id="crearDoc" onclick="createNewDocument_(1)"><i class="fa fa-plus"></i> Crear Documento</button>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="col-lg-12" style="font-size: 30px; font-weight:800;border-bottom: 1px solid #CDCDCD;"><i aria-hidden="true" id="icono_doc" class="fa fa-file-text-o"></i>&nbsp; 
                                    <div style="display:inline;" id="nombre_doc">
                                        Warehouse
                                    </div>
                                     @if (session()->has('sendemail'))
                                            {{-- <div class="alert alert-success alert-dismissible" role="alert" id="msn_sendmail">
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="icon_close"><span aria-hidden="true">&times;</span></button>
                                              <strong>Perfecto!</strong> {{ session()->get('sendemail') }}
                                            </div> --}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-lg-2" >
                                <div class="col-lg-12">
                                    <a href="#" class="list-group-item active" style="text-align: center; background-color:#2196f3;border-color: #2196f3 ">
                                        Tipos de Documentos
                                    </a>
                                    <div class="btn-group-vertical" id="listaDocumentos" style="width: 100%;">
                                        <!--Listar documentos-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table id="tbl-documento" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th><i class="fa fa-file-text-o" aria-hidden="true" id="icono-doc-table"></i> #Documento</th>
                                                    <th><i class="fa fa-calendar" aria-hidden="true"></i> Fecha</th>
                                                    <th><i class="fa fa-user" aria-hidden="true"></i> Cliente / Consignee</th>
                                                    <th><i class="fa fa-balance-scale" aria-hidden="true"></i> Peso</th>
                                                    <th><i class="fa fa-cubes" aria-hidden="true"></i> Volumen</th>
                                                    <th><i class="fa fa-building" aria-hidden="true"></i> Agencia</th>
                                                    <th><i class="fa fa-bolt" aria-hidden="true"></i> Acciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/documento/main.js') }}"></script>
@endsection