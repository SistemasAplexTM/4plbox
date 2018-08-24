<div  id="navbar">
<div class="row border-bottom">
    <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header" style="width: 60%;">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <a class="minimalize-styl-2" href="{{ route('home') }}" style="font-size: 30px;margin-top: 5px;margin-bottom: 0px;" data-toggle="tooltip" title="Inicio" data-placement="right"><i class="fa fa-home"></i> </a>
            <span class="minimalize-styl-2" style="font-size: 15px;font-weight: bold;">
                {{-- {{ session('agencia_name_global') }} --}}
            </span>
            <span class="minimalize-styl-2" style="font-size: 15px;font-weight: bold;width: 50%;">
                <autocomplete-component type="navbar" @change-select="showRigthSidebar" url="4plbox/public/documento/searchDataByNavbar"></autocomplete-component>
                {{-- <autocomplete-component type="navbar" @change-select="showRigthSidebar" url="documento/searchDataByNavbar"></autocomplete-component> --}}
            </span>
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
                    <i class="fa fa-bell" data-toggle='tooltip' data-placement='left' title='Prealertas'></i>  
                </a>

                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="#">
                            <div>
                                <i class="fa fa-user-circle fa-fw"></i>JHONNYS
                                <span class="pull-right text-muted small">MENSAJE</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div style="text-align: center; font-weight: bold;">
                                No hay registros
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <div class="text-center link-block">
                            <a href="#">
                                <strong>Ver Todas las alertas</strong>
                                <i class="fa fa-angle-double-right"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Log out
                </a>
                <li>
                    <a class="right-sidebar-toggle" id="sidebar-rigth">
                        <i class="fa fa-tasks"></i>
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            <li>
                
            </li>
        </ul>

    </nav>
</div>
<rigthsidebar-component :object="datos"></rigthsidebar-component>
</div>
