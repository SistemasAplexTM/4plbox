@extends('layouts.app')
@section('title', 'Agencia')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Agencias</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">
                <strong>Agencias</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
    <div class="row" id="agencia">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Agencias</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    @if(Auth::user()->email === 'jhonnyalejo2212@gmail.com')
                	<a href="{{ route('agencia.create') }}">
					    <button type="button" class="btn btn-primary btn-sm">
					        <span class="fa fa-plus" aria-hidden="true"></span> Nuevo
					    </button>
					</a>
                    @endif
                    <!--***** contenido ******-->
                    <div class="table-responsive">
                        <table id="tbl-agencia" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Responsable</th>
                                    <th>Dirección</th>
                                    <th>Ciudad</th>
                                    <th>Estado</th>
                                    <th>País</th>
                                    <th>Teléfono</th>
                                    <th>Logo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Responsable</th>
                                    <th>Dirección</th>
                                    <th>Ciudad</th>
                                    <th>Estado</th>
                                    <th>País</th>
                                    <th>Teléfono</th>
                                    <th>Logo</th>
                                    <th>Acciones</th>
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
<script src="{{ asset('js/templates/agencia.js') }}"></script>
@endsection