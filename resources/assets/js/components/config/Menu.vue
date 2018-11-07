<template>
    <div class="ibox ">
        <div class="ibox-content bg">
            <div class="tab-content">                    
                <div class="tab-pane active" id="contact-1">
                    <div class="row m-b-lg">
                        <div class="col-lg-4 text-center">
                            <div class="m-b-sm">
                                <i class="fa fa-cogs fa-5x">
                                </i>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <strong>
                                @lang('general.configuration')
                            </strong>
                            <p>
                                Selecciona de la lista el modulo al que deseas agregar una configuración personalizada.
                            </p>
                        </div>
                    </div>
                    <div class="client-detail">
                        <div class="full-height-scroll">
                            <strong>
                                Lista de configuración
                            </strong>
                            <ul class="list-group clear-list">
                                <li class="list-group-item fist-item">
                                    <span class="pull-right">
                                        09:00 pm
                                    </span>
                                    Agencia
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right">
                                        10:16 am
                                    </span>
                                    Documentos
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>