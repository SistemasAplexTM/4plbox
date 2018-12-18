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
    .lb_status{
        cursor: pointer;
    }
    .dropdown-toggle>input[type="search"] {
    width: 100px !important;
    }
    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 5% !important;
    }
    .actions_btn{
        text-align: center;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('documents.documents')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('documents.home')</a>
            </li>
            <li class="active">
                <strong>@lang('documents.documents')</strong>
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
                    <h5>@lang('documents.documents')</h5>
                </div>
                <div class="ibox-content">
                    <modaltagdocument-component :params='params' :id_status='id_status' :table_delete="tableDelete"></modaltagdocument-component>
                    <div class="row">
                        <div class="row">

                            <div id="msn-documento" class="col-lg-12" style="text-align: left;display: none;">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger alert-dismissible" role="alert" id="msnP">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>@lang('documents.attention')</strong> <i id="msn"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2" style="text-align: left;" id="ajaxCreate">
                                <div class="col-lg-12">
                                    <button type="button"  style="" class="btn btn-primary btn-lg btn-block" id="crearDoc" onclick="createNewDocument_(1)"><i class="fa fa-plus"></i> @lang('documents.create_document')</button>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="col-lg-8" style="font-size: 30px; font-weight:800;border-bottom: 1px solid #CDCDCD;">
                                    <span id="icono_doc"></span>&nbsp;
                                    <div style="display:inline;" id="nombre_doc">
                                       @lang('documents.warehouse')
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <v-select name="status_id" v-model="status_id" label="descripcion" :filterable="false" :options="status" placeholder="Status"></v-select>
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
                                      @lang('documents.types_of_documents')
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
                                                    <th><i class="fa fa-file" aria-hidden="true" id="icono-doc-table"></i> #@lang('documents.documents')</th>
                                                    <th><i class="fa fa-calendar" aria-hidden="true"></i> @lang('documents.date')</th>
                                                    <th><i class="fa fa-user" aria-hidden="true"></i> @lang('documents.client_consignee')</th>
                                                    <th><i class="fa fa-dollar-sign" aria-hidden="true"></i> @lang('general.rate')</th>
                                                    <th><i class="fa fa-balance-scale" aria-hidden="true"></i> @lang('documents.weight')</th>
                                                    <th><i class="fa fa-cubes" aria-hidden="true"></i> @lang('documents.volume')</th>
                                                    <th><i class="fa fa-building" aria-hidden="true"></i> @lang('documents.agency')</th>
                                                    <th><i class="fa fa-bolt" aria-hidden="true"></i> @lang('documents.actions')</th>
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
