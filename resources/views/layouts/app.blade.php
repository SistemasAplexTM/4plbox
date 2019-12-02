<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="{{ asset('img/favicon.ico') }}" rel="icon" type="image/x-icon">
    <!-- CSRF Token -->
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>
        @yield('title') | 4plbox
    </title>
    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css"
        rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
        rel="stylesheet" />
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css"
        rel="stylesheet">
    <!-- ICONOS DE FONT AWESOME 5 PRO -->
    {{-- <script src="https://kit.fontawesome.com/a3e351735a.js"></script> --}}
    @yield('scripts_head')
</head>

<body class="fixed-sidebar fixed-nav fixed-nav-basic">
    <div id="wrapper">
        {{-- Sidebar --}}
        @include('layouts.sidebar')
        <div class="gray-bg" id="page-wrapper">
            {{-- Navbar --}}
            @include('layouts.navbar')
            @yield('breadcrumb')
            {{-- contenido --}}
            <div class="wrapper wrapper-content animated fadeInRight" id="app">
                @yield('content')
            </div>
        </div>
        @include('layouts.sidebarRight')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/plantilla.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    {{-- <script src="{{ asset('js/plugins/silentPrint/silentPrint.min.js') }}"></script> --}}
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js">
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
    </script>
    <script type="text/javascript">
        window.bus = new Vue();
          var objVue = new Vue({
            el: '#right-sidebar'
          })
          var objVue = new Vue({
            el: '#sidebar'
          })
          var objVueNav = new Vue({
            el: '#navbar_index',
          })
            // document.body.style.zoom="90%";
            $(document).ready(function(){
                $('#in_documento').on('click', function(){
                    window.location.href = '{{ route('documento.index') }}';
                });
                $('#in_master').on('click', function(){
                    window.location.href = '{{ route('master.index') }}';
                });
                $('#in_tracking').on('click', function(){
                    window.location.href = '{{ route('tracking.index') }}';
                });
                $('#in_shipper').on('click', function(){
                    window.location.href = '{{ route('shipper.index') }}';
                });
                $('#in_consignee').on('click', function(){
                    window.location.href = '{{ route('consignee.index') }}';
                });
                $('#in_mantenimiento').on('click', function(){
                    {{-- window.location.href = '{{ route('mantenimiento.index') }}'; --}}
                });
                $('#in_administracion').on('click', function(){
                    {{-- window.location.href = '{{ route('administracion.index') }}'; --}}
                });
                $('#in_backup').on('click', function(){
                    var url = '/commandBackup';
                    axios.get(url).then(response => {
                        toastr.success('Backup generado.');
                    });
                });

                //SILENT PRINT
                // var sp = new SilentPrint();
                // sp.init(initSuccess, initFail);
                // function initSuccess() {
                //   console.log('silentPrint OK!');
                //   sp.getMachineId(getMachineIdSuccess);
                //   sp.getPrinterList(getPrinterListSuccess);
                // }
                // function initFail() {
                //   console.log('silentPrint ERROR!');
                // }
                //
                // function getMachineIdSuccess(machineId) {
                //   console.log('ID: '+ machineId);
                // }
                //
                // function getPrinterListSuccess(list) {
                //  console.log(list);
                //  var pathname = window.location.pathname;
                //  var url = pathname.split('/');
                //  console.log(url.pop());
                // }

            });
    </script>
</body>

</html>
@yield('scripts')