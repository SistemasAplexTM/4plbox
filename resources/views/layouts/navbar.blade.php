<style type="text/css">
    .btn-inicio{
        font-size: 30px!important;
        margin-right: 0px;
        margin-bottom: 5px !important;
    }
</style>
<div  id="navbar">
<div class="row border-bottom" id="navbar_index">
    <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header" style="width: 60%;">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fal fa-bars"></i> </a>
            {{-- <a class="minimalize-styl-2" href="{{ route('home') }}" style="font-size: 30px;margin-top: 5px;margin-bottom: 0px;" data-toggle="tooltip" title="Inicio" data-placement="right"><i class="fal fa-home"></i> </a> --}}
        <ul class="nav metismenu" id="homeIndex">
            <li class="dropdown navbar minimalize-styl-2" style="margin-top: 0px;margin-left: 0px;">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #7b7171;"><i class="fal fa-home fa-lg"></i> <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu" style="left: auto;width: 600px;">
                    <li>
                        <div class="col-lg-12" style="margin-top:20px; margin-bottom: 10px;">
                            <div class="col-lg-2 text-center">
                                <button class="btn btn-warning dim btn-outline btn-inicio" type="button" id="in_documento" style="margin:auto;">
                                    <i class="fal fa-box-open">
                                    </i>
                                </button>
                                <div style="font-size: 12px; font-weight:bold;">
                                    @lang('general.warehouse')
                                </div>
                            </div>
                            <div class="col-lg-2 text-center">
                                <button class="btn btn-primary dim btn-outline btn-inicio" type="button" id="in_master" style="margin:auto;">
                                    <i class="fal fa-paste">
                                    </i>
                                </button>
                                <div style="font-size: 12px; font-weight:bold;">
                                    Master
                                </div>
                            </div>
                            <div class="col-lg-2 text-center">
                                <button class="btn btn-default dim btn-outline btn-inicio" type="button" id="in_tracking" style="margin:auto;">
                                    <i class="fal fa-cubes">
                                    </i>
                                </button>
                                <div style="font-size: 12px; font-weight:bold;">
                                   @lang('general.tracking')
                                </div>
                            </div>
                            <div class="col-lg-2 text-center">
                                <button class="btn btn-success dim btn-outline btn-inicio" type="button" id="in_shipper" style="margin:auto;">
                                    <i class="fal fa-user-circle">
                                    </i>
                                </button>
                                <div style="font-size: 12px; font-weight:bold;">
                                     @lang('general.shipper')
                                </div>
                            </div>
                            <div class="col-lg-2 text-center">
                                <button class="btn btn-info dim btn-outline btn-inicio" type="button" id="in_consignee" style="margin:auto;">
                                    <i class="fal fa-user-circle">
                                    </i>
                                </button>
                                <div style="font-size: 12px; font-weight:bold;">
                                     @lang('general.consignee')
                                </div>
                            </div>

                            <div class="col-lg-2 text-center">
                                <button class="btn btn-primary dim btn-outline btn-inicio" type="button" id="in_backup" style="margin:auto;">
                                    <i class="fal fa-cloud-download-alt">
                                    </i>
                                </button>
                                <div style="font-size: 12px; font-weight:bold;">
                                    @lang('general.backup')
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="dropdown navbar minimalize-styl-2" style="margin-top: 10px;margin-bottom:0px;width: 400px;">
              <search></search>
            </li>
        </ul>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li><span id="liveclock" style=""></span></li>
            <li>
                <a href="{{ route('change_lang', ['lang' => 'es']) }}">ES</a>
            </li>
            <li>
                <a href="{{ route('change_lang', ['lang' => 'en']) }}">EN</a>
            </li>
            <!--NOTIFICACIONES-->
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    <i class="fal fa-bell" data-toggle='tooltip' data-placement='left' title='Prealertas'></i>
                </a>

                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="#">
                            <div>
                                <i class="fal fa-user-circle fa-fw"></i>JHONNYS
                                <span class="pull-right text-muted small">@lang('layouts.message')</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div style="text-align: center; font-weight: bold;">
                               @lang('layouts.no_records')
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <div class="text-center link-block">
                            <a href="#">
                                <strong>@lang('layouts.see_all_alerts')</strong>
                                <i class="fal fa-angle-double-right"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fal fa-sign-out-alt"></i> @lang('layouts.log_out')
                </a>
                {{-- <li>
                    <a class="right-sidebar-toggle" id="sidebar-rigth">
                        <i class="fal fa-tasks"></i>
                    </a>
                </li> --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            <li>

            </li>
        </ul>

    </nav>
</div>
{{-- <rigthsidebar-component :object="datos"></rigthsidebar-component> --}}
</div>
