@extends('layouts.app')
@section('title', 'Bill of lading')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.bill_of_lading')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li>
                <a href="{{ route('bill.index') }}">@lang('general.bill_of_lading')</a>
            </li>
            <li class="active">
                <strong>{{ (isset($bill) and $bill) ? 'Editar Bill of lading' : 'Registro de Bill of lading' }}</strong>
            </li>
        </ol>
    </div>
</div>
<style>
        *{
            font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif"
        }
        .bill{
            width: 100%;
        }
        .content{
            width: 100%;
            //border: 1px solid #000000;
        }
        .title{
            font-size: 8px;
            padding-bottom: 5px;
            padding-top: 2px;
            padding-left: 2px
        }
        .b-top{
            border-top: 1px solid #000000;
        }
        .b-bottom{
            border-bottom: 1px solid #000000;
        }
        .b-left{
            border-left: 1px solid #000000;
        }
        .b-right{
            border-right: 1px solid #000000;
        }
        .p-left{
            padding-left: 10px;
        }
        .var{
            font-size: 17px;
        /* font-weight: bold;*/
        }
        .detail .title{
            padding: 5px;
        }

        /* ESTILOS DE LOS CAMPOS FORMULARIO */
        .search{
            font-size: 9px!important;float: right;margin-right: 5px;
        }
        .addOther{
            font-size: 9px !important;float: left;margin-left: 5px;margin-bottom: 5px !important;
        }
        .delete{
            font-size: 9px!important;
        }
        .deleteOther{
            font-size: 9px!important;margin-top: 10px;
        }
        .txt-shipper {
            width:65%;
            resize:none;
        }
        .var{
            padding: 3px;
        }
        .txt-export {
            width:100%;
            resize:none;
        }
        .txt-consignee {
            width:65%;
            margin-bottom: 5px;
            resize:none;
        }
        .txt-forwarding_agent, .txt-notify_party, .txt-domestic_routing{
           resize:none; 
        }
        table.table td a{
            margin: 0;
        }
        .delete_, .delete_c{
            display: none;
        }
    </style>
@endsection

@section('content')
<div class="row" id="billForm">
    <form id="formBill" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
        <input type="hidden" id="bill_id" value="{{ $bill }}">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('general.register_of_bill_of_lading')</h5>
                    <div class="ibox-tools">
                        
                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    @include('templates.bill.formBill')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-sm-12 col-sm-offset-0 guardar">
                                    <button type="button" class="ladda-button btn btn-primary" @click.prevent="store()" v-if="editar==0">
                                        <i class="fa fa-save"></i>  @lang('layouts.save') 
                                    </button>
                                    <template v-else>
                                        <button type="button" class="ladda-button btn btn-warning" @click.prevent="update()">
                                            <i class="fa fa-edit"></i> @lang('layouts.update') 
                                        </button>
                                        <button type="button" class="ladda-button btn btn-info" @click.prevent="print()">
                                            <i class="fa fa-print"></i> @lang('layouts.print') 
                                        </button>
                                    </template>
                                    <button type="button" class="btn btn-white" @click.prevent="cancel()">
                                        <i class="fa fa-remove"></i>  @lang('layouts.cancel') 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL REGISTER PARTIES --}}
                <div class="modal fade bs-example-modal-lg" id="modalParties" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="width: 40%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i> @{{ name_partie }}</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_partie" id="id_partie" v-model="id_partie" class="form-control">
                                <div class="row">
                                    <div class="panel" style="margin-bottom: 0px;">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                          <h4 class="panel-title">
                                            <a id="open_collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: cornflowerblue;">
                                              <i class="fa fa-plus"></i> @lang('general.create_new')
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                          <div class="panel-body" style="padding: 0;">
                                            <div class="form-group">
                                                <div class="col-lg-5">
                                                    <label>@lang('general.display_name')</label>
                                                    <input type="text" name="display_name" id="display_name" v-model="display_name" class="form-control">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>@lang('general.account_number')</label>
                                                    <input type="text" name="account_number" id="account_number" v-model="account_number" class="form-control">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Zip</label>
                                                    <input type="text" name="zip" id="zip" class="form-control" v-model="zip_partie">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-9">
                                                    <label>@lang('general.data')</label>
                                                    <textarea name="text_exporter" id="text_exporter" v-model="text_exporter" class="form-control" rows="4"></textarea>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label style="width: 100%;" class="control-label">&nbsp;</label>
                                                    <a class="btn btn-primary" data-toggle="tooltip" title="Crear" @click="addPartie" v-if="!edit_p"><i class="fa fa-plus"></i> Crear</a>
                                                    <a class="btn btn-warning" data-toggle="tooltip" title="Editar" @click="editPartie" v-else="edit_p"><i class="fa fa-edit"></i>
                                                     @lang('general.edit')</a>
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-12" style="margin-top: 15px;">
                                        <div class="table-responsive">
                                            <table id="tbl-modalParties" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('general.name')</th>
                                                        <th>@lang('general.account_number')</th>
                                                        <th>Zip</th>
                                                        <th>@lang('general.actions')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('general.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- MODAL REGISTER PARTIES --}}
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/bill/bill.js') }}"></script>
@endsection