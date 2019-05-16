@extends('layouts.app')
@section('title', 'Guía master')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('master.master_guide')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('master.start')</a>
            </li>
            <li>
                <a href="{{ route('master.index') }}">@lang('master.masters')</a>
            </li>
            <li class="active">
                <strong>{{ (isset($master) and $master) ? 'Editar agencia' : 'Registro de guía master' }}</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row" id="master">
    <form id="formciudad" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@lang('master.master_guide_record')</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <!--***** contenido ******-->
                    @if($master)
                        <master-component :master="{!! (($master) ? $master : false) !!}" :consol="{!! (($consolidado_id) ? $consolidado_id : 0) !!}" :peso_consolidado="{!! $peso !!}" :piezas_consolidado="{!! $piezas !!}"></master-component>
                    @else
                        <master-component :master="null" :consol="{!! (($consolidado_id) ? $consolidado_id : 0) !!}" :peso_consolidado="{!! $peso !!}" :piezas_consolidado="{!! $piezas !!}"></master-component>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/master/master_create.js') }}"></script>
@endsection
