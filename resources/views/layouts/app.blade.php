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
                            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                                <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
                                    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
                                        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
                                            <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css" rel="stylesheet">
                                                <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
                                                <!-- Latest compiled and minified CSS -->
                                                <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" rel="stylesheet">
                                                </link>
                                            </link>
                                        </link>
                                    </link>
                                </link>
                            </link>
                        </meta>
                    </link>
                </meta>
            </meta>
        </meta>
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
        </div>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}">
        </script>
        <script src="{{ asset('js/plantilla.js') }}">
        </script>
        <script src="{{ asset('js/main.js') }}">
        </script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js">
        </script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
        </script>
        <script type="text/javascript">
            document.body.style.zoom="90%";
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
                    var url = 'commandBackup';
                    axios.get(url).then(response => {
                        toastr.success('Backup generado.');
                    });
                });
            });
        </script>
    </body>
</html>
@yield('scripts')
