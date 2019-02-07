@extends('layouts.app')
@section('title', trans('general.configuration'))

@section('content')
<div id="aplexConfig">
    <config-component></config-component>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/templates/config/index.js') }}"></script>
@endsection
