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
@endsection

@section('content')
<div class="row" id="bill">
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
                    
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/bill/bill.js') }}"></script>
@endsection