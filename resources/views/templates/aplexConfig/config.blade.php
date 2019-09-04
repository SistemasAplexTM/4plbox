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
<style media="screen">
  .content_cards{
    margin: 0 auto;
    float: none;
  }
  .content_row{
    text-align: center;
  }
  .bg-warning{
    background-color: #f8ac5a;
    color: white;
  }
  .bg-danger{
    background-color: #ed5666;
    color: white;
  }
  h4{
    font-weight: 100!important;
  }
</style>
@endsection

@section('content')
  <div class="row content_row" id="Setup">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 content_cards">
      <div class="widget bg-primary text-center">
        <h2>
          <i class="fal fa-tools"></i> @lang('layouts.maintenances')
        </h2>
      </div>
      @foreach ($menu as $key => $value)
        <div class="col-lg-2" style="padding: 15px">
          @can($value['perm'])
            @if ($value['url'])
              <a href="{{ url($value['route']) }}">
            @else
              <a href="{{ route($value['route']) }}">
            @endif
              <div class="widget white-bg text-center">
                <div class="m-b-md">
                  {!! '<i class="fal fa-' . $value['icon'] . ' fa-4x"></i>' !!}
                  <br>
                  <br>
                  <h4 class="no-margins">
                    @lang($value['desc'])
                  </h4>
                </div>
              </div>
            </a>
          @endcan
        </div>
      @endforeach
    </div>
  </div>
  @if(Auth::user()->isRole('admin'))
    <div class="row content_row" id="Setup">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 content_cards">
        <div class="widget bg-warning text-center">
          <h2>
            <i class="fal fa-cogs"></i> @lang('layouts.administration')
          </h2>
        </div>
        @foreach ($menu2 as $key => $value)
          <div class="col-lg-2" style="padding: 15px">
          @can($value['perm'])
            @if ($value['url'])
              <a href="{{ url($value['route']) }}">
              @else
                <a href="{{ route($value['route']) }}">
                @endif
                <div class="widget white-bg text-center text-warning">
                  <div class="m-b-md">
                    {!! '<i class="fal fa-' . $value['icon'] . ' fa-4x"></i>' !!}
                    <br>
                    <br>
                    <h4 class="no-margins">
                      @lang($value['desc'])
                    </h4>
                  </div>
                </div>
              </a>
            @endcan
          </div>
        @endforeach
      </div>
    </div>

    <div class="row content_row" id="Setup">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 content_cards">
        <div class="widget bg-danger text-center">
          <h2>
            <i class="fal fa-users-cog"></i> @lang('layouts.security')
          </h2>
        </div>
        @foreach ($menu3 as $key => $value)
          <div class="col-lg-2" style="padding: 15px">
          @can($value['perm'])
            @if ($value['url'])
              <a href="{{ url($value['route']) }}">
              @else
                <a href="{{ route($value['route']) }}">
                @endif
                <div class="widget white-bg text-center text-danger">
                  <div class="m-b-md">
                    {!! '<i class="fal fa-' . $value['icon'] . ' fa-4x"></i>' !!}
                    <br>
                    <br>
                    <h4 class="no-margins">
                      @lang($value['desc'])
                    </h4>
                  </div>
                </div>
              </a>
            @endcan
          </div>
        @endforeach
      </div>
    </div>
  @endif
@endsection

@section('scripts')
<script src="{{ asset('js/templates/setup.js') }}"></script>
@endsection
