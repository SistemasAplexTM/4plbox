@extends('layouts.app')
@section('title', 'Resultado')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.result_of_the_search')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="resultSearch">
        <form id="formResultSearch" enctype="multipart/form-data" class="form-horizontal" role="form" action="" method="post">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('general.result_of_the_search')</h5>
                        <div class="ibox-tools">
                            
                        </div>
                    </div>
                    <div class="ibox-content">
                    	{{ print_r($status) }}
                        <!--***** contenido ******-->
                        <div class="table-responsive">
	                        <table id="tbl-pais" class="table table-striped table-hover table-bordered" style="width: 100%;">
	                            <thead>
	                                <tr>
	                                    <th>@lang('general.description')</th>
	                                    <th>@lang('general.prefix')</th>
	                                    <th>@lang('general.actions')</th>
	                                </tr>
	                            </thead>
	                            <tbody>

	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>@lang('general.description')</th>
	                                    <th>@lang('general.prefix')</th>
	                                    <th>@lang('general.actions')</th>
	                                </tr>
	                            </tfoot>
	                        </table>
	                    </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/templates/pais.js') }}"></script> --}}
@endsection