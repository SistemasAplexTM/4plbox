@extends('layouts.app')
@section('title', 'Inicio')
@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>
            @lang('general.home')
        </h2>
    </div>
</div>
@endsection
@section('content')
<style type="text/css">
    .btn-inicio{
        font-size: 45px!important;
        margin-right: 0px;
    }
    .feed-element, .feed-element .media{
        padding-bottom: 0px;
    }
</style>
<div class="row" id="homeIndex">
    <div class="col-lg-12">
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top: 0px;">
            <div class="row">
                <div class="col-lg-12" style="margin-bottom: 10px;">
                    <div class="col-lg-6">
                        <div class=" feed-activity-list">
                            <div class="feed-element">
                                <h1>
                                    Principal
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class=" feed-activity-list">
                            <div class="feed-element">
                                <h1>
                                    @lang('general.users')
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-warning dim btn-large-dim btn-outline btn-inicio" type="button" id="documento">
                            <i class="fa fa-dropbox">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.warehouse')
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-primary dim btn-large-dim btn-outline btn-inicio" type="button" id="master">
                            <i class="fa fa-paste">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            Master
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-default dim btn-large-dim btn-outline btn-inicio" type="button" id="tracking">
                            <i class="fa fa-cubes">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                           @lang('general.tracking')
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-success dim btn-large-dim btn-outline btn-inicio" type="button" id="shipper">
                            <i class="fa fa-user-circle-o">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                             @lang('general.shipper')
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-info dim btn-large-dim btn-outline btn-inicio" type="button" id="consignee">
                            <i class="fa fa-user-circle">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                             @lang('general.consignee')
                        </div>
                    </div>

                    <div class="col-lg-3 text-center">
                        <button class="btn btn-primary dim btn-large-dim btn-outline btn-inicio" type="button" id="backup">
                            <i class="fa fa-cloud-download">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            @lang('general.backup')
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-lg-12" style="margin-bottom: 10px;margin-top: 30px;">
                    <div class="col-lg-6">
                        <div class=" feed-activity-list">
                            <div class="feed-element">
                                <h1>
                                    Ficheros
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-default dim btn-large-dim btn-outline btn-inicio" type="button" id="mantenimiento">
                            <i class="fa fa-wrench">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            Mantenimiento
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="btn btn-danger dim btn-large-dim btn-outline btn-inicio" type="button" id="administracion">
                            <i class="fa fa-cogs">
                            </i>
                        </button>
                        <div style="font-size: 20px;">
                            Administración
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#documento').on('click', function(){
            window.location.href = '{{ route('documento.index') }}';
        });
        $('#master').on('click', function(){
            window.location.href = '{{ route('master.index') }}';
        });
        $('#tracking').on('click', function(){
            window.location.href = '{{ route('tracking.index') }}';
        });
        $('#shipper').on('click', function(){
            window.location.href = '{{ route('shipper.index') }}';
        });
        $('#consignee').on('click', function(){
            window.location.href = '{{ route('consignee.index') }}';
        });
        $('#mantenimiento').on('click', function(){
            {{-- window.location.href = '{{ route('mantenimiento.index') }}'; --}}
        });
        $('#administracion').on('click', function(){
            {{-- window.location.href = '{{ route('administracion.index') }}'; --}}
        });
        $('#backup').on('click', function(){
            objVue.generateBackup();
        });
    });

    function createNewDocument_(tipo_doc_id, name, functionalities) {
    var data = {
        tipo_doc_id: tipo_doc_id,
        name: name,
        functionalities: functionalities,
    };
    objVue.createNewDocument(data);
}
/* objetos VUE index */
var objVue = new Vue({
        el: '#homeIndex',
        data: {
            id_status: null,
            tableDelete: null,
            params: {},
        },
        methods: {
            createNewDocument: function(data) {
                swal({
                    title: "Se creará un(a) <spam style='color: rgb(212, 103, 82);'>" + data.name + ".</spam><br>\n¿Desea Continuar?.",
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Si, Crear",
                    cancelButtonText: "No, Cancelar!",
                }).then((result) => {
                    if (result.value) {
                        axios.post('documento/ajaxCreate/' + data.tipo_doc_id, {
                            'tipo_documento_id': data.tipo_doc_id,
                            'funcionalidaddes': data.functionalities
                        }).then(function(response) {
                            var res = response.data;
                            if (response.data['code'] == 200) {
                                toastr.success('Registro creado correctamente.');
                                window.location.href = 'documento/' + res.datos['id'] + '/edit';
                            } else {
                                toastr.warning(response.data['error']);
                            }
                        }).catch(function(error) {
                            console.log(error);
                            if (error.response.status === 422) {
                                me.formErrors = error.response.data; //guardo los errores
                                me.listErrors = me.formErrors.errors; //genero lista de errores
                            }
                            $.each(me.formErrors.errors, function(key, value) {
                                $('.result-' + key).html(value);
                            });
                            toastr.error("Porfavor completa los campos obligatorios.", {
                                timeOut: 50000
                            });
                        });
                    }
                })
            },
            generateBackup: function() {
                var url = 'commandBackup';
                axios.get(url).then(response => {
                    toastr.success('Backup generado.');
                });
            },
        }
    })
</script>
@endsection
