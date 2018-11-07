@extends('layouts.app')
@section('title', trans('general.configuration'))

@section('content')
<style type="text/css">
    .bg{
        background: none;
    }
    .view_content{
        padding: 10px;
        background: #ffffff;
    }
</style>
<div id="aplexConfig">
    <index-config></index-config>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/templates/config/index.js') }}"></script>
@endsection
