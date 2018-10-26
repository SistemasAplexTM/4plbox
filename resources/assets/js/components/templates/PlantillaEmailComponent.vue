<style type="text/css">
    .panel-info {
        border-color: #bce8f1;
    }
    .panel-info>.panel-heading {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }
</style>
<template>
    <div class="row">   
        <div class="col-lg-12">
            <div class="mail-box">
                <div class="mail-body">
                    <div class="form-group" :class="{'has-error': listErrors.nombre}">
                        <label class="col-sm-2 control-label" for="nombre">Nombre:</label>
                        <div class="col-sm-10">
                            <input type="text" v-model="nombre" placeholder="Identificación del mensaje" class="form-control" value="" id="nombre" name="nombre" @click="deleteError('nombre')">
                            <small id="msn1" class="help-block result-nombre" v-show="listErrors.nombre"></small>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': listErrors.descripcion_plantilla}">
                        <label class="col-sm-2 control-label">Descripción:</label>
                        <div class="col-sm-10">
                            <input type="text" v-model="descripcion_plantilla" placeholder="Descripción general del mensaje" class="form-control" value="" id="descripcion_plantilla" name="descripcion_plantilla" @click="deleteError('descripcion_plantilla')">
                            <small id="msn1" class="help-block result-descripcion_plantilla" v-show="listErrors.descripcion_plantilla"></small>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': listErrors.subject}">
                        <label class="col-sm-2 control-label">Subject:</label>
                        <div class="col-sm-10">
                            <input type="text" v-model="subject" placeholder="" class="form-control" value=""  id="subject" name="subject" @click="deleteError('subject')">
                            <small id="msn1" class="help-block result-subject" v-show="listErrors.subject"></small>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': listErrors.otros_destinatarios}">
                        <label class="col-sm-2 control-label">Destinatarios:</label>
                        <div class="col-sm-10">
                            <input type="text" v-model="otros_destinatarios" placeholder="Otros destinatarios" class="form-control" value="" id="otros_destinatarios" name="otros_destinatarios" @click="deleteError('otros_destinatarios')">
                            <small id="msn1" class="help-block result-otros_destinatarios" v-show="listErrors.otros_destinatarios"></small>
                        </div>
                    </div>
                </div>
                <div class="mail-text h-200">
                    <textarea style="max-width: 100%;" placeholder="Ingrese Texto" class="form-control summernote" id="mensaje" name="mensaje" @click="deleteError('mensaje')"></textarea>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <small id="msn1" class="help-block result-mensaje" v-show="listErrors.mensaje"></small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data () {
            return {
                agencia_id: '',
        nombre: '',
        subject: '',
        mensaje: '',
        descripcion_plantilla: '',
        otros_destinatarios: '',
        editar: 0,
        formErrors: {},
        listErrors: {},
            }
        },
    }
</script>