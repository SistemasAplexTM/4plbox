<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | 4plbox</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    
</head>
<body class="fixed-sidebar fixed-nav fixed-nav-basic">
    <div id="wrapper">
        {{-- Sidebar --}}
        @include('layouts.sidebar')
        <div id="page-wrapper" class="gray-bg">
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plantilla.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script type="text/javascript">
        document.body.style.zoom="90%";
        var objVue = new Vue({
            el: '#navbar',
            mounted: function() {
               this.getNameAgencia();
            },
            data:{
                datos: null
            },
            methods:{
                showRigthSidebar: function(data){
                    this.datos = data;
                },
                getNameAgencia: function() {
                    // axios.get('/user/getNameAgenciaUser').then(response => {
                    axios.get('/4plbox/public/user/getNameAgenciaUser').then(response => {
                        var logo = $('#imgProfile').attr('src');
                        $('#imgProfile').attr('src', logo +'/'+ response.data.data['logo'])
                        $('#_agencia').html(response.data.data['descripcion']);
                    });
                },
            },
        });
    </script>
    @yield('scripts')
</body>
</html>
