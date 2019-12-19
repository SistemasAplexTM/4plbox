@extends('layouts.app')
@section('title', 'Documentos')
@section('breadcrumb')
{{-- bread crumbs --}}
<style type="text/css">
    .ligth {
        opacity: 0.5;
    }

    .dataTables_wrapper {
        padding-bottom: 200px;
        padding-right: 30px;
    }

    #msn_sendmail {
        width: 50%;
        font-size: 15px;
        padding: 5px;
        float: right;
        margin-bottom: 0px;
    }

    #icon_close {
        right: 0px;
    }

    .lb_status {
        cursor: pointer;
        font-family: 'Roboto', sans-serif;
    }

    .dropdown-toggle>input[type="search"] {
        width: 100px !important;
    }

    .dropdown-toggle>input[type="search"]:focus:valid {
        width: 5% !important;
    }

    .actions_btn {
        text-align: center;
    }

    #tbl-modalagrupar_wrapper {
        padding-bottom: 0px;
        padding-right: 0px;
    }

    .ui-group-buttons .or {
        position: relative;
        float: left;
        width: .3em;
        height: 1.3em;
        z-index: 3;
        font-size: 12px
    }

    .ui-group-buttons .or:before {
        position: absolute;
        top: 50%;
        left: 50%;
        content: ' ';
        background-color: #5a5a5a;
        margin-top: -.1em;
        margin-left: -.9em;
        width: 1.8em;
        height: 1.8em;
        line-height: 1.55;
        color: #fff;
        font-style: normal;
        font-weight: 400;
        text-align: center;
        border-radius: 500px;
        -webkit-box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        box-sizing: border-box
    }

    .ui-group-buttons .or:after {
        position: absolute;
        top: 0;
        left: 0;
        content: ' ';
        width: .3em;
        height: 2.9em;
        background-color: rgba(0, 0, 0, 0);
        border-top: .6em solid #5a5a5a;
        border-bottom: .6em solid #5a5a5a
    }

    .ui-group-buttons .or.or-lg {
        height: 1.3em;
        font-size: 16px
    }

    .ui-group-buttons .or.or-lg:after {
        height: 2.85em
    }

    .ui-group-buttons .or.or-sm {
        height: 1em
    }

    .ui-group-buttons .or.or-sm:after {
        height: 2.5em
    }

    .ui-group-buttons .or.or-xs {
        height: .25em
    }

    .ui-group-buttons .or.or-xs:after {
        height: 1.84em;
        z-index: -1000
    }

    .ui-group-buttons {
        display: inline-block;
        vertical-align: middle
    }

    .ui-group-buttons:after {
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden
    }

    .ui-group-buttons .btn {
        float: left;
        border-radius: 0
    }

    .ui-group-buttons .btn:first-child {
        margin-left: 0;
        border-top-left-radius: .25em;
        border-bottom-left-radius: .25em;
        padding-right: 15px
    }

    .ui-group-buttons .btn:last-child {
        border-top-right-radius: .25em;
        border-bottom-right-radius: .25em;
        padding-left: 15px
    }

    .ui-group-buttons {
        width: 100%;
    }

    .ui-group-buttons .btn {
        width: 48%
    }

    #crearDoc,
    #btns_group {
        display: none;
    }

    table.dataTable tbody tr.selected {
        background-color: #d4e4fb;
    }

    .btn_actions>.btn,
    .btn_actions>.btn-group>.btn {
        font-size: 12px !important;
    }

    .el-upload-list {
        height: 0;
    }

    .el-alert__content {
        width: 100%;
    }

    .downloadLink {
        font-weight: bold;
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

<div class="row" id="documentoIndex" data-id_print="{{Session('print_document')['id']}}"
    data-doc_print="{{Session('print_document')['document']}}">
    <document-group :open_group="openGroup" :id_document="idDocument" @set="resetVariables"></document-group>
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>@lang('documents.documents')</h5>
            </div>
            <div class="ibox-content">
                <modaltagdocument-component :params='params' :id_status='id_status' :table_delete="tableDelete">
                </modaltagdocument-component>
                <div class="row">
                    <div class="row">

                        <div id="msn-documento" class="col-lg-12" style="text-align: left;display: none;">
                            <div class="col-lg-12">
                                <div class="alert alert-danger alert-dismissible" role="alert" id="msnP">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                    <strong>@lang('documents.attention')</strong> <i id="msn"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2" style="text-align: left;" id="ajaxCreate">
                            @if(env('APP_CLIENT') === 'jyg')
                            <div class="" id="btns_group" style="width:100%;">
                                <button type="button" class="btn btn-primary" id="crearDoc2"
                                    style="width:100%;padding: 9px;"><i class="fal fa-box-open"></i>
                                    @lang('documents.create_document')</button>
                            </div>
                            @else
                            <div class="ui-group-buttons" id="btns_group">
                                <button type="button" class="btn btn-primary" id="crearDoc2"><i
                                        class="fal fa-box-open"></i> Courier</button>
                                <div class="or"></div>
                                <button type="button" class="button btn btn-warning" id="crearDoc3"><i
                                        class="fal fa-truck-moving"></i> Carga</button>
                            </div>
                            @endif
                            <button type="button" style="" class="btn btn-primary btn-lg btn-block" id="crearDoc"
                                onclick="createNewDocument_(1)"><i class="fal fa-plus"></i>
                                @lang('documents.create_document')</button>
                        </div>
                        <div class="col-lg-10">
                            <div class="col-lg-12"
                                style="font-size: 30px; font-weight:800;border-bottom: 1px solid #CDCDCD;">
                                <span id="icono_doc"></span>&nbsp;
                                <div style="display:inline;" id="nombre_doc">

                                </div>
                                <div class="btn_actions" style="display:inline;float:right;">
                                    <div class="btn-group print_document" style="display:none;">
                                        <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle"
                                            aria-expanded="false" style="padding-top: 3px;padding-bottom: 3px;"><i
                                                class='fal fa-print fa-lg'></i> <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right pull-right">

                                        </ul>
                                    </div>
                                    <a class="btn btn-warning btn-outline edit_document" title="Editar"
                                        data-toggle="tooltip" style="display:none;"><i
                                            class="fal fa-pencil fa-lg"></i></a>
                                    <a class="btn btn-success btn-outline tags_document" data-toggle="modal"
                                        data-target="#modalTagDocument" style="display:none;"><i
                                            class="fal fa-arrow-square-right fa-lg" data-toggle="tooltip" title=""
                                            data-original-title="Tareas"></i></a>
                                    <a class="btn btn-danger btn-outline delete_document" title="Eliminar"
                                        data-toggle="tooltip" style="display:none;"><i
                                            class="fal fa-trash-alt fa-lg"></i></a>
                                    <a class="btn btn-info filter_document" title="Filtrar" data-toggle="tooltip"
                                        @click="dialogVisible = true;" v-if="showFilter"><i
                                            class="fal fa-filter fa-lg"></i></a>
                                    <a class="btn btn-success upload_document" title="Subir archivo de status"
                                        data-toggle="tooltip" @click="dialogVisibleUpload = true" v-if="showFilter"><i
                                            class="fal fa-upload fa-lg"></i></a>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            &nbsp;
                        </div>
                    </div>
                    <div class="row" style="">
                        <div class="col-lg-2">
                            <div class="">
                                <a href="#" class="list-group-item active"
                                    style="text-align: center; background-color:#2196f3;border-color: #2196f3 ">
                                    @lang('documents.types_of_documents')
                                </a>
                                <div class="btn-group-vertical" id="listaDocumentos" style="width: 100%;">
                                    <!--Listar documentos-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-lg-12" id="tbl1">
                                    <div class="table-responsive">
                                        <table id="tbl-documento" class="table table-striped table-hover table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th><i class="fal fa-file" aria-hidden="true"
                                                            id="icono-doc-table"></i> #@lang('documents.documents')</th>
                                                    <th><i class="fal fa-calendar" aria-hidden="true"></i>
                                                        @lang('documents.date')</th>
                                                    <th><i class="fal fa-user" aria-hidden="true"></i>
                                                        @lang('documents.consignee')</th>
                                                    <th><i class="fal fa-map-marked-alt" aria-hidden="true"></i>
                                                        @lang('documents.city')</th>
                                                    <th><i class="fal fa-dollar-sign" aria-hidden="true"></i>
                                                        @lang('general.rate')</th>
                                                    <th><i class="fal fa-balance-scale" aria-hidden="true"></i>
                                                        @lang('documents.weight')</th>
                                                    <th><i class="fal fa-cubes" aria-hidden="true"></i>
                                                        @lang('documents.volume')</th>
                                                    <th><i class="fal fa-building" aria-hidden="true"></i>
                                                        @lang('documents.agency')</th>
                                                    <th><i class="fal fa-bolt" aria-hidden="true"></i>
                                                        @lang('documents.actions')</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-lg-12" id="tbl2" style="display:none">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="warehouses" id="default" :class="{ active: courier_carga }"
                                            @click="pendign">
                                            <a href="#courier" aria-controls="courier" role="tab" data-toggle="tab"><i
                                                    class="fal fa-box-open"></i> COURIER @{{ courier_today }}
                                            </a>
                                        </li>
                                        <li role="load" :class="{ active: !courier_carga }" id="li-load"
                                            @click="pendign">
                                            <a href="#load" aria-controls="load" role="tab" data-toggle="tab"><i
                                                    class="fal fa-truck-moving"></i> CARGA
                                            </a></li>
                                        <li role="pending" id="li-pending" @click="pendign"><a href="#pending"
                                                aria-controls="pending" role="tab" data-toggle="tab"><i
                                                    class="fal fa-box"></i> PENDIENTES <span
                                                    class="pending badge badge-primary ligth">{{ $pendientes->cantidad }}</span></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade"
                                            :class="{ active: courier_carga, in: courier_carga  }" id="courier">
                                            <div class="table-responsive" style="padding-top:10px;">
                                                <table id="tbl-documento2"
                                                    class="table table-striped table-hover table-bordered"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th><i class="fal fa-file" aria-hidden="true"
                                                                    id="icono-doc-table"></i>
                                                                #@lang('documents.documents')</th>
                                                            <th><i class="fal fa-calendar" aria-hidden="true"></i>
                                                                @lang('documents.date')</th>
                                                            <th><i class="fal fa-user" aria-hidden="true"></i>
                                                                @lang('documents.consignee')</th>
                                                            <th><i class="fal fa-map-marked-alt" aria-hidden="true"></i>
                                                                @lang('documents.city')</th>
                                                            <th><i class="fal fa-dollar-sign" aria-hidden="true"></i>
                                                                @lang('general.rate')</th>
                                                            <th>
                                                                <div style="float: left;"><i
                                                                        class="fal fa-balance-scale"
                                                                        aria-hidden="true"></i>
                                                                    @lang('documents.weight')</div>
                                                                <div style="float: right;"><i class="fal fa-box-check"
                                                                        aria-hidden="true"></i>
                                                                    @lang('documents.pieces')</div>
                                                            </th>
                                                            <th><i class="fal fa-cubes" aria-hidden="true"></i>
                                                                @lang('documents.volume')</th>
                                                            <th><i class="fal fa-building" aria-hidden="true"></i>
                                                                @lang('documents.agency')</th>
                                                            <th><i class="fal fa-bolt" aria-hidden="true"></i>
                                                                @lang('documents.actions')</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade"
                                            :class="{ active: !courier_carga, in: !courier_carga }" id="load">
                                            <div class="table-responsive" style="padding-top:10px;">
                                                <table id="tbl-documento3"
                                                    class="table table-striped table-hover table-bordered"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th><i class="fal fa-file" aria-hidden="true"
                                                                    id="icono-doc-table"></i>
                                                                #@lang('documents.documents')</th>
                                                            <th><i class="fal fa-calendar" aria-hidden="true"></i>
                                                                @lang('documents.date')</th>
                                                            <th><i class="fal fa-user" aria-hidden="true"></i>
                                                                @lang('documents.consignee')</th>
                                                            <th><i class="fal fa-map-marked-alt" aria-hidden="true"></i>
                                                                @lang('documents.city')</th>
                                                            <th><i class="fal fa-dollar-sign" aria-hidden="true"></i>
                                                                @lang('general.rate')</th>
                                                            <th>
                                                                <div style="float: left;"><i
                                                                        class="fal fa-balance-scale"
                                                                        aria-hidden="true"></i>
                                                                    @lang('documents.weight')</div>
                                                                <div style="float: right;"><i class="fal fa-box-check"
                                                                        aria-hidden="true"></i>
                                                                    @lang('documents.pieces')</div>
                                                            </th>
                                                            <th><i class="fal fa-cubes" aria-hidden="true"></i>
                                                                @lang('documents.volume')</th>
                                                            <th><i class="fal fa-building" aria-hidden="true"></i>
                                                                @lang('documents.agency')</th>
                                                            <th><i class="fal fa-bolt" aria-hidden="true"></i>
                                                                @lang('documents.actions')</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="pending">
                                            <div class="table-responsive" style="padding-top:10px;">
                                                <table id="tbl-documento4"
                                                    class="table table-striped table-hover table-bordered"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th><i class="fal fa-file" aria-hidden="true"
                                                                    id="icono-doc-table"></i>
                                                                #@lang('documents.documents')</th>
                                                            <th><i class="fal fa-calendar" aria-hidden="true"></i>
                                                                @lang('documents.date')</th>
                                                            <th><i class="fal fa-user" aria-hidden="true"></i>
                                                                @lang('documents.consignee')</th>
                                                            <th><i class="fal fa-map-marked-alt" aria-hidden="true"></i>
                                                                @lang('documents.city')</th>
                                                            <th><i class="fal fa-dollar-sign" aria-hidden="true"></i>
                                                                @lang('general.rate')</th>
                                                            <th>
                                                                <div style="float: left;"><i
                                                                        class="fal fa-balance-scale"
                                                                        aria-hidden="true"></i>
                                                                    @lang('documents.weight')</div>
                                                                <div style="float: right;"><i class="fal fa-box-check"
                                                                        aria-hidden="true"></i>
                                                                    @lang('documents.pieces')</div>
                                                            </th>
                                                            <th><i class="fal fa-cubes" aria-hidden="true"></i>
                                                                @lang('documents.volume')</th>
                                                            <th><i class="fal fa-building" aria-hidden="true"></i>
                                                                @lang('documents.agency')</th>
                                                            <th><i class="fal fa-bolt" aria-hidden="true"></i>
                                                                @lang('documents.actions')</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TABLA PARA CONSOLIDADO AGENCIA --}}
                                <div class="col-lg-12" id="tbl3" style="display:none">
                                    <div class="table-responsive">
                                        <table id="tbl-documento_agencia"
                                            class="table table-striped table-hover table-bordered" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th><i class="fal fa-file" aria-hidden="true"
                                                            id="icono-doc-table"></i> #@lang('documents.documents')</th>
                                                    <th><i class="fal fa-calendar" aria-hidden="true"></i>
                                                        @lang('documents.date')</th>
                                                    <th><i class="fal fa-user" aria-hidden="true"></i>
                                                        @lang('documents.consignee')</th>
                                                    <th><i class="fal fa-map-marked-alt" aria-hidden="true"></i>
                                                        @lang('documents.city')</th>
                                                    <th><i class="fal fa-dollar-sign" aria-hidden="true"></i>
                                                        @lang('general.rate')</th>
                                                    <th><i class="fal fa-balance-scale" aria-hidden="true"></i>
                                                        @lang('documents.weight')</th>
                                                    <th><i class="fal fa-cubes" aria-hidden="true"></i>
                                                        @lang('documents.volume')</th>
                                                    <th><i class="fal fa-building" aria-hidden="true"></i>
                                                        @lang('documents.agency')</th>
                                                    <th><i class="fal fa-bolt" aria-hidden="true"></i>
                                                        @lang('documents.actions')</th>
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

    <!-- MODAL AGRUPAR GUIAS -->
    <div class="modal fade bs-example-modal-lg" id="modalagrupar" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 40%!important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fal fa-cubes"></i> Documentos disponibles para agrupar
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="formGuiasAgrupar_2">
                        <p>Selecione los documentos que desea agrupar en este registro.</p>
                        <div class="table-responsive">
                            <table id="tbl-modalagrupar" class="table table-striped table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px;"></th>
                                        <th>Numero de documento</th>
                                        <th>Peso lb</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="" @click="agruparDocumentoDetalle()" class="btn btn-primary"
                        data-dismiss="modal">Agregar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CAMBIAR STATUS CONSOLIDADO -->
    <div class="modal fade bs-example" id="modalChangeStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fal fa-clock" style="font-size: 20px;"></i> Estatus actual del consolidado
                    </h4>
                </div>
                <div class="modal-body">
                    <modal-cambiar-status-consolidado :document_id="id_consolidado_selected"
                        :status="{{ json_encode($status_list) }}" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FILTRAR DOCUMENTO --}}
    <document-filter :dialogvisible="dialogVisible" :courier_carga="courier_carga" @get="filter($event)"
        @close="closeModal()"></document-filter>

    {{-- MODAL SUBIR ESTATUS DOCUMENTO --}}
    <document-upload :dialogvisibleupload="dialogVisibleUpload" @close="closeModal()"></document-upload>

</div>

@endsection

@section('scripts')
{!! $wcpScript !!}
<script src="{{ asset('js/templates/documento/documentoIndex/main.js') }}"></script>
<script src="{{ asset('js/templates/documento/documentoIndex/vue.js') }}"></script>
<script src="{{ asset('js/templates/documento/documentoIndex/index.js') }}"></script>
@endsection