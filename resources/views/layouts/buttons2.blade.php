<div class="col-lg-12">
    <div class="form-group">
        <div class="col-sm-12 col-sm-offset-0 guardar">
            <button class="ladda-button btn btn-primary hvr-float-shadow" @click.prevent="create()" v-if="editar==0">
                <i class="fal fa-save"></i> Guardar
            </button>
            <button class="ladda-button btn btn-warning hvr-float-shadow" @click.prevent="update()" v-if="editar==1">
                <i class="fal fa-edit"></i> Actualizar
            </button>
            <button class="btn btn-white hvr-float-shadow" @click.prevent="cancel()" v-if="editar==1">
                <i class="fal fa-remove"></i> Cancelar
            </button>
            <button class="btn btn-white hvr-float-shadow pull-right" @click.prevent="returnView()">
                <i class="fal fa-mail-reply"></i> Volver
            </button>
        </div>
    </div>
</div>