<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" id="imgProfile" src="{{ asset('storage/') }}/{{ Session::get('logo') }}" style="width: 70px;height: 70px;background-color: #fff"/>
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
            <!--id='firstMenu'-->
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
                            <spam class="fal fa-clipboard">
                            </spam>
                             @lang('layouts.documents')
                        </a>
                    </li>
                    @endcan
                    @can('documento.index')
                    <li>
                        <a href="{{ url('mintic') }}">
                            <spam class="fal fa-clipboard">
                            </spam>
                             Mintic
                        </a>
                    </li>
                    @endcan

                    @can('master.index')
                    <li>
                        <a href="{{ route('master.index') }}">
                            <spam class="fal fa-plane">
                            </spam>
                            @lang('layouts.master_guide')

                        </a>
                    </li>
                    @endcan
                    @can('bill.index')
                    <li>
                        <a href="{{ route('bill.index') }}">
                            <spam class="fal fa-ship">
                            </spam>
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
                            <spam class="fal fa-plane-departure"></spam>
                            </spam>
                                 @lang('layouts.shipper')
                        </a>
                    </li>
                    @endcan
                    @can('consignee.index')
                    <li>
                        <a href="{{ route('consignee.index') }}">
                            <spam class="fal fa-plane-arrival"></spam>
                                @lang('layouts.consignee')

                        </a>
                    </li>
                    @endcan
                    @can('clientes.index')
                    <li>
                        <a href="{{ route('clientes.index') }}">
                            <spam class="fal fa-users">
                            </spam>
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
                        {{-- @can('prealerta.list') --}}
                        <li>
                            <a href="{{ route('prealerta.list') }}">
                                <spam class="fal fa-exclamation-triangle">
                                </spam>
                                @lang('layouts.alerts')
                            </a>
                        </li>
                        {{-- @endcan --}}
                        @can('tracking.index')
                        <li>
                            <a href="{{ route('tracking.index') }}">
                                <spam class="fal fa-cubes">
                                </spam>
                                  @lang('layouts.trackings_receipt')
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endif
            @can('master.index')
              <li class="active">
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
                              <spam class="fal fa-file">
                              </spam>
                                   @lang('layouts.load_reports')
                          </a>
                      </li>
                      {{-- <li>
                          <a href="{{ route('consulta.index') }}">
                              <spam class="fal fa-file">
                              </spam>
                               @lang('layouts.reports')
                          </a>
                      </li> --}}
                  </ul>
              </li>
            @endcan


            <li class="active">
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
                            <spam class="fal fa-file-signature">
                            </spam>
                             @lang('layouts.receipt')
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            <li class="active">
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
                            <spam class="fal fa-file-invoice">
                            </spam>
                             @lang('layouts.invoices')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('receipt.index') }}">
                            <spam class="fal fa-file-chart-pie">
                            </spam>
                             @lang('layouts.report')
                        </a>
                    </li>
                </ul>
            </li>
            @if(Auth::user()->isRole('admin'))
            {{-- <li class="active">
                <a href="" style="background-color: #0e9aef; color: white;">
                    <i class="fal fa-wrench">
                    </i>
                    <span class="nav-label">
                      @lang('layouts.maintenances')
                    </span>
                    <span class="arrow">
                        <i class="fal fa-angle-down"></i>
                    </span>
                </a>
                <ul class="nav nav-second-level"> --}}
                    {{-- @can('emailTemplate.index')
                    <li>
                        <a href="{{ route('emailTemplate.index') }}">
                            <spam class="fal fa-envelope">
                            </spam>
                                  @lang('layouts.email_templates')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('emailTemplate.index')
                    <li>
                        <a href="{{ route('printConfig') }}">
                            <spam class="fal fa-print">
                            </spam>
                            @lang('layouts.print_config')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('administracion.index')
                    <li>
                        <a href="{{ url('administracion/1') }}">
                            <spam class="fal fa-hand-holding-usd">
                            </spam>
                                @lang('layouts.payment_methods')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('administracion.index')
                    <li>
                        <a href="{{ url('administracion/2') }}">
                            <spam class="fal fa-credit-card">
                            </spam>
                                @lang('layouts.payment_types')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('administracion.index')
                    <li>
                        <a href="{{ url('administracion/3') }}">
                            <spam class="fal fa-sitemap">
                            </spam>
                               @lang('layouts.groups_of_receipts')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('aerolinea_inventario.index') --}}
                    {{-- <li>
                        <a href="{{ url('aerolinea_inventario') }}">
                            <spam class="fal fa-plane">
                            </spam>
                               @lang('layouts.inventory_airlines')

                        </a>
                    </li> --}}
                    {{-- @endcan --}}
                    {{-- @can('transport.index')
                    <li>
                        <a href="{{ url('transport/aerolineas') }}">
                            <spam class="fal fa-plane">
                            </spam>
                                @lang('layouts.airlines')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('transport.index')
                    <li>
                        <a href="{{ url('transport/aeropuertos') }}">
                            <spam class="fal fa-road">
                            </spam>
                                @lang('layouts.airports')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('servicios.index')
                    <li>
                        <a href="{{ route('servicios.index') }}">
                            <spam class="fal fa-share-alt">
                            </spam>
                                 @lang('layouts.services')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('administracion.index')
                    <li>
                        <a href="{{ url('administracion/5') }}">
                            <spam class="fal fa-reply-all">
                            </spam>
                                 @lang('layouts.type_boardings')
                        </a>
                    </li>
                    @endcan --}}
                    {{-- @can('administracion.index')
                    <li>
                        <a href="{{ url('administracion/6') }}">
                            <spam class="fal fa-shopping-bag">
                            </spam>
                                @lang('layouts.type_packagings')
                        </a>
                    </li>
                    @endcan --}}
                {{-- </ul>
            </li> --}}
            @endif
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
                              <spam class="fal fa-cogs">
                              </spam>
                               @lang('general.configuration')
                          </a>
                      </li>
                      {{-- @can('arancel.index')
                      <li>
                          <a href="{{ route('arancel.index') }}">
                              <spam class="fal fa-money-bill">
                              </spam>
                                      @lang('layouts.tariffs')
                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('status.index')
                      <li>
                          <a href="{{ route('status.index') }}">
                              <spam class="fal fa-history">
                              </spam>
                                @lang('layouts.status')
                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('transportador.index')
                      <li>
                          <a href="{{ route('transportador.index') }}">
                              <spam class="fal fa-truck">
                              </spam>
                                 @lang('layouts.transporters')
                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('ciudad.index')
                      <li>
                          <a href="{{ route('ciudad.index') }}">
                              <spam class="fal fa-street-view">
                              </spam>
                                 @lang('layouts.cities')
                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('departamento.index')
                      <li>
                          <a href="{{ route('departamento.index') }}">
                              <spam class="fal fa-globe">
                              </spam>
                                @lang('layouts.dptos_states')

                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('pais.index')
                      <li>
                          <a href="{{ route('pais.index') }}">
                              <spam class="fal fa-globe">
                              </spam>
                                @lang('layouts.countrieses')

                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('tipoDocumento.index')
                      <li>
                          <a href="{{ route('tipoDocumento.index') }}">
                              <spam class="fal fa-file">
                              </spam>
                                        @lang('layouts.document_types')
                          </a>
                      </li>
                      @endcan --}}
                      {{-- @can('logActivity.index')
                      <li>
                          <a href="{{ route('logActivity.index') }}">
                              <spam class="fal fa-history">
                              </spam>
                               @lang('layouts.logs')

                          </a>
                      </li>
                      @endcan --}}
                      @if(Auth::user()->email === 'jhonnyalejo2212@gmail.com')
                      <li>
                          <a href="{{ url('administracion/7') }}">
                              <spam class="fal fa-code-fork">
                              </spam>
                                    @lang('layouts.functions')

                          </a>
                      </li>
                      <li>
                          <a href="{{ route('modulo.index') }}">
                              <spam class="fal fa-window-restore">
                              </spam>
                             @lang('layouts.modules')
                          </a>
                      </li>
                      @endif
                  </ul>
              </li>
            @endif
            <li class="active" style="">
                <a href="#" style="background-color: #ff1d1d; color: white;">
                    <i class="fal fa-key">
                    </i>
                    <span class="nav-label">
                    @lang('layouts.security')

                    </span>
                    <span class="arrow">
                        <i class="fal fa-angle-down"></i>
                    </span>
                </a>
                <ul class="nav nav-second-level collapse">
                    @can('user.index')
                    <li>
                        <a href="{{ route('user.index') }}">
                            <spam class="fal fa-user">
                            </spam>
                                     @lang('layouts.users')
                        </a>
                    </li>
                    @endcan
                    @can('rol.index')
                    <li>
                        <a href="{{ route('rol.index') }}">
                            <spam class="fal fa-sitemap">
                            </spam>
                             @lang('layouts.roles')
                        </a>
                    </li>
                    @endcan
                    @can('rol.index')
                    {{-- @can('accessControl.index') --}}
                    <li>
                        <a href="{{ route('accessControl.index') }}">
                            <spam class="fal fa-address-book">
                            </spam>
                                @lang('layouts.access_controls')
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('config.index') }}">
                            <spam class="fal fa-cogs">
                            </spam>
                                @lang('general.configuration')
                        </a>
                    </li> --}}
                    {{-- @endcan --}}
                    @endcan
                </ul>
            </li>
        </ul>
    </div>
</nav>
