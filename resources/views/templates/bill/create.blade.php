@extends('layouts.app')
@section('title', 'Bill of lading')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Bill of lading</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Inicio</a>
            </li>
            <li>
                <a href="{{ route('bill.index') }}">Mater</a>
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
/*          font-weight: bold;*/
        }
        .detail .title{
            padding: 5px;
        }

        /* ESTILOS DE LOS CAMPOS FORMULARIO */
        .search{
            font-size: 9px!important;float: right;margin-right: 5px;
        }
        .delete{
            font-size: 9px!important;
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
    </style>
@endsection

@section('content')
<div class="row" id="billForm">
    <form id="formBill" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Registro de Bill of lading</h5>
                    <div class="ibox-tools">
                        
                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    @include('templates.bill.formBill')
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/bill/bill.js') }}"></script>
@endsection