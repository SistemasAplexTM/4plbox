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
<style>

	:root {
		/*
		#FF0000 (ROJO)
		#000099 (AZUL)
		#00CC00 (VERDE)
		#FF9900 (NARANJA)
		*/
	    --color: #FF0000;
		/*
		#FFDFDF (Rojo)
		#E1F0FF (Azul)
		*/
		--background-color: #FFDFDF;

	}
	.document_master {
		font-family:Tahoma, Geneva, sans-serif;
		font-size:12px;
		vertical-align:middle;
	}
	table, th, td {
		border-collapse: collapse;
		align: center;
	}
	table td.special {
	    border: 3px solid /*var(--color)*/;
	}
	div.r {
	    display: inline-block; /* the default for span */
	    border: 1px solid blue;
		float:left;
	}
	.main_table {
		border-collapse: collapse;
		align: center;
		border: 1px solid /*var(--color)*/;
		width:100%;
	}
	.main_table_2 {
		border-collapse: collapse;
		align: center;
		border-bottom: 1px solid /*var(--color)*/;
		border-left: 1px solid /*var(--color)*/;
		border-right: 1px solid /*var(--color)*/;
		width:100%;
	}
	.main_table_3 {
		border-collapse: collapse;
		align: center;
		border-left: 1px solid /*var(--color)*/;
		width:100%;
	}
	.left_line{
		border-left: 1px solid /*var(--color)*/;
	}
	.rigth_line {
		border-right: 1px solid /*var(--color)*/;
	}
	.left_bottom_line {
		border-left: 1px solid /*var(--color)*/;
		border-bottom: 1px solid /*var(--color)*/;
	}
	.left_top_line {
		border-left: 1px solid /*var(--color)*/;
		border-top: 1px solid /*var(--color)*/;
	}
	.top_line {
		border-top: 1px solid /*var(--color)*/;
	}
	.top_line_dasherd {
			border-style: dashed solid none none;
			border-color: /*var(--color)*/;
	}
	.bottom_line {
		border-bottom: 1px solid /*var(--color)*/;
	}

	.margin_div {
		margin-bottom:5px;
	}
	.text_titles {
		font-size:8px;
		text-align:left;
		padding-left:3px;
		color: /*var(--color)*/;
	}
	.text_titles_tl {
		font-size:8px;
		text-align:left;
		vertical-align:top;
		padding-left:3px;
		color: /*var(--color)*/;
	}
	.text_titles_tr {
		font-size:8px;
		text-align:right;
		vertical-align:top;
		padding-right:3px;
		color: /*var(--color)*/;
	}
	.text_titles_tc {
		font-size:8px;
		text-align:center;
		vertical-align:top;
		color: /*var(--color)*/;
	}
	.text_titles_c {
		font-size:8px;
		text-align:center;
		color: /*var(--color)*/;
	}
	.text_titles_j {
		font-size:9px;
		text-align:justify;
		padding: 2px;
		color: /*var(--color)*/;
	}
	.text_regular_l {
		font-family:'Courier New', Courier, monospace;
		text-align:left;
		padding-left: 3px;
	}
	.text_regular_r {
		font-family:'Courier New', Courier, monospace;
		text-align:right;
		padding-right: 3px;
	}
	.text_regular_c {
		font-family:'Courier New', Courier, monospace;
		text-align:center;
	}
	.text_total {
		font-family:'Courier New', Courier, monospace;
		text-align:right;
		padding-right: 3px;
	}
	.text_normal {
		color: /*var(--color)*/;
		font-size:12px;
	}
	.big_title {
		font-size:20px;
		font-weight:700;
		color: /*var(--color)*/;
	}
	.altura{
		height:25px;
	}
	.altura_2{
		height:30px;
	}
	.altura_interna{
		height:15px;
	}
	div.relative {
		position: relative;
		top: 0px;
		left: 0px;
	}
	.bg_azul {
		background-color:var(--background-color);

	}
	.line1{
		width: 17px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-11px)
			translateX(-8px)
			rotate(50deg);
		position: absolute;
	}
	.line2{
		width: 17px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-11px)
			translateX(-13px)
			rotate(-50deg);
		position: absolute;
	}
	.line3{
		width: 14px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-10px)
			translateX(-4px)
			rotate(58deg);
		position: absolute;
	}
	.line4{
		width: 14px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-10px)
			translateX(-15px)
			rotate(-52deg);
		position: absolute;
	}
	.line6{
		width: 16px;
		height: 17px;
		border-bottom: 1px solid /*var(--color)*/;
		-webkit-transform:
			translateY(-11px)
			translateX(-16px)
			rotate(-50deg);
		position: absolute;
	}
	.persons_data {
		font-family:'Courier New', Courier, monospace;
		text-align:left;
		padding-left: 3px;

		margin-top:-13px;
		margin-bottom:5px;
		padding-left:3px;
		font-size:13px
	}
	.persons_data_2 {
		font-family:'Courier New', Courier, monospace;
		text-align:left;
		padding-left: 3px;

		margin-top:3px;
		margin-bottom:5px;
		padding-left:3px;
		font-size:13px
	}
	@page{
		margin-left: 25px;
		margin-right: 15px;
		margin-top: 15px;
		margin-bottom: 15px;
	}
</style>
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
                    @include('templates/master/formMaster')
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/master/master_create.js') }}"></script>
@endsection
