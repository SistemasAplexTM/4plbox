@extends('layouts.app')
@section('title', 'Configuración')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>@lang('general.configuration')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('general.home')</a>
            </li>
            <li class="active">
                <strong>@lang('general.configuration')</strong>
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
  <div class="row" id="Setup">
    <h3>
      @lang('layouts.maintenances')
    </h3>
    @foreach ($menu as $key => $value)
      <div class="col-lg-2">
        @can($value['perm'])
          @if ($value['url'])
            <a href="{{ url($value['route']) }}">
          @else
            <a href="{{ route($value['route']) }}">
          @endif
            <div class="widget white-bg p-lg text-center">
              <div class="m-b-md">
                {!! '<i class="fa fa-' . $value['icon'] . ' fa-4x"></i>' !!}
                <h3 class="font-bold no-margins">
                  @lang($value['desc'])
                </h3>
              </div>
            </div>
          </a>
        @endcan
      </div>
    @endforeach
  </div>
  @if(Auth::user()->isRole('admin'))
    <div class="row" id="Setup">
      <h3>
        @lang('layouts.administration')
      </h3>
      @foreach ($menu2 as $key => $value)
        <div class="col-lg-2">
          @can($value['perm'])
            @if ($value['url'])
              <a href="{{ url($value['route']) }}">
              @else
                <a href="{{ route($value['route']) }}">
                @endif
                <div class="widget white-bg p-lg text-center">
                  <div class="m-b-md">
                    {!! '<i class="fa fa-' . $value['icon'] . ' fa-4x"></i>' !!}
                    <h3 class="font-bold no-margins">
                      @lang($value['desc'])
                    </h3>
                  </div>
                </div>
              </a>
            @endcan
          </div>
        @endforeach
      </div>
  @endif
@endsection

@section('scripts')
<script src="{{ asset('js/templates/setup.js') }}"></script>
@endsection
