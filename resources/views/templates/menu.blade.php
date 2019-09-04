@extends('layouts.app')
@section('title', 'Menú')
@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Menú</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Inicio</a>
            </li>
            <li class="active">
                <strong>Menú</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    .ibox-content{
        background-color: #ffffff;
    }
</style>
    <div class="row" id="menu">
      <menu-component/>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    var objVue = new Vue({
        el: '#menu',
    })
</script>
@endsection
