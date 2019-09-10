<nav id="sidebar" class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
      <nav id="sidebar" class="navbar-default navbar-static-side" role="navigation">
<div class="sidebar-collapse">
{{-- <sidebar-component></sidebar-component> --}}
<nav class="navbar-default navbar-static-side" role="navigation">
<div class="sidebar-collapse">
<ul class="nav metismenu" id="side-menu">
<li class="nav-header">
<div class="dropdown profile-element">
<span>
{{-- <el-image id="imgProfile" src="{{ asset('storage/') }}/{{ Session::get('logo') }}" style="width: 170px;height: 60px;background-color: #fff">
<div slot="error" class="image-slot text-center" style="width: 170px;height: 60px;background-color: #fff; opacity: 0.6">
<i class="fal fa-image fa-4x"></i>
</div>
</el-image> --}}
<img alt="image" class="" id="imgProfile" src="{{ asset('storage/') }}/{{ Session::get('logo') }}" style="width: 170px;height: 60px;background-color: #fff"/>
</span>
<a class="dropdown-toggle" data-toggle="dropdown" href="#">
<span class="clear">
<span class="block m-t-xs">
<strong class="font-bold">
{{ Auth::user()->name }}
</strong>
<br>
<strong class="font-bold" id="_agencia">
{{ Session::get('agencia') }}
</strong>
</br>
</span>
<span class="text-muted text-xs block">
@lang('layouts.welcome')
<b class="caret">
</b>
</span>
</span>
</a>
<ul class="dropdown-menu animated fadeInRight m-t-xs">
<li>
<a href="{{ route('home') }}">
<i class="fal fa-home">
</i>
@lang('layouts.home')

</a>
</li>
<li>
<a href="#">
<i class="fal fa-user">
</i>
@lang('layouts.profile')

</a>
</li>
</ul>
</div>
<div class="logo-element">
4plbox
</div>
</li>
<li class="active" id="firstMenu">
<a href="" style="background-color: #BA55D3; color: white;">
<i class="fal fa-th-large">
</i>
<span class="nav-label">
@lang('layouts.load')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level">
@can('documento.index')
<li>
<a href="{{ route('documento.index') }}">
<span class="fal fa-clipboard">
</span>
@lang('layouts.documents')
</a>
</li>
@endcan
@can('documento.index')
{{-- <li>
<a href="{{ url('mintic') }}">
<span class="fal fa-clipboard">
</span>
Mintic
</a>
</li> --}}
@endcan

@can('master.index')
<li>
<a href="{{ route('master.index') }}">
<span class="fal fa-plane">
</span>
@lang('layouts.master_guide')

</a>
</li>
@endcan
@can('bill.index')
<li>
<a href="{{ route('bill.index') }}">
<span class="fal fa-ship">
</span>
@lang('layouts.bill_of_lading')
</a>
</li>
@endcan
</ul>
</li>
<li class="active">
<a href="" style="background-color: #5cb85c; color: white;">
<i class="fal fa-user">
</i>
<span class="nav-label">
@lang('layouts.account')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level">
@can('shipper.index')
<li>
<a href="{{ route('shipper.index') }}">
<span class="fal fa-plane-departure"></span>
</span>
@lang('layouts.shipper')
</a>
</li>
@endcan
@can('consignee.index')
<li>
<a href="{{ route('consignee.index') }}">
<span class="fal fa-plane-arrival"></span>
@lang('layouts.consignee')

</a>
</li>
@endcan
@can('clientes.index')
<li>
<a href="{{ route('clientes.index') }}">
<span class="fal fa-users">
</span>
@lang('layouts.clients')
</a>
</li>
@endcan
</ul>
</li>
@if(env('APP_TYPE') === 'courier')
<li class="active">
<a href="" style="background-color: brown; color: white;">
<i class="fal fa-address-card">
</i>
<span class="nav-label">
@lang('layouts.lockens')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level">
<li>
<a href="{{ route('prealerta.list') }}">
<span class="fal fa-exclamation-triangle">
</span>
@lang('layouts.alerts')
</a>
</li>
@can('tracking.index')
<li>
<a href="{{ route('tracking.index') }}">
<span class="fal fa-cubes">
</span>
@lang('layouts.trackings_receipt')
</a>
</li>
@endcan
</ul>
</li>
@endif
@can('master.index')
{{-- <li class="active">
<a href="" style="background-color: #d6c600; color: white;">
<i class="fal fa-puzzle-piece">
</i>
<span class="nav-label">
@lang('layouts.reports')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level">
<li>
<a href="{{ route('report.index') }}">
<span class="fal fa-file">
</span>
@lang('layouts.load_reports')
</a>
</li>
</ul>
</li> --}}
@endcan


{{-- <li class="active">
<a href="" style="background-color: #2a3fa5; color: white;">
<i class="fal fa-address-card">
</i>
<span class="nav-label">
@lang('layouts.distribution_warehouse')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level">
@can('documento.index')
<li>
<a href="{{ route('receipt.index') }}">
<span class="fal fa-file-signature">
</span>
@lang('layouts.receipt')
</a>
</li>
<li>
<a href="{{ route('radicado.index') }}">
<span class="fal fa-file-contract">
</span>
Radicados
</a>
</li>
<li>
<a href="{{ route('radicado_clientes.index') }}">
<span class="fal fa-users">
</span>
@lang('layouts.clients')
</a>
</li>
@endcan
</ul>
</li> --}}
{{-- <li class="active">
<a href="" style="background-color: #0eb1ff;; color: white;">
<i class="fal fa-dollar-sign">
</i>
<span class="nav-label">
@lang('layouts.accounting')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level">
<li>
<a href="{{ route('invoce.index') }}">
<span class="fal fa-file-invoice">
</span>
@lang('layouts.invoices')
</a>
</li>
<li>
<a href="{{ route('receipt.index') }}">
<span class="fal fa-file-chart-pie">
</span>
@lang('layouts.report')
</a>
</li>
</ul>
</li> --}}
{{-- SOLO LO VE ADMINISTRADOR Y GESTION --}}
@if(Auth::user()->isRole('admin'))
<li class="active" style="">
<a href="#" style="background-color: #017767; color: white;">
<i class="fal fa-cogs">
</i>
<span class="nav-label">
@lang('layouts.administration')
</span>
<span class="arrow">
<i class="fal fa-angle-down"></i>
</span>
</a>
<ul class="nav nav-second-level collapse">
<li>
<a href="{{ route('config.index') }}">
<span class="fal fa-cogs">
</span>
@lang('general.configuration')
</a>
</li>
@if(Auth::user()->email === 'jhonnyalejo2212@gmail.com')
<li>
<a href="{{ url('administracion/7') }}">
<span class="fal fa-code-fork">
</span>
@lang('layouts.functions')
</a>
</li>
<li>
<a href="{{ route('modulo.index') }}">
<span class="fal fa-window-restore">
</span>
@lang('layouts.modules')
</a>
</li>
@endif
</ul>
</li>
@endif
</ul>
</div>
</nav>
</div>
</nav>
    </div>
</nav>
