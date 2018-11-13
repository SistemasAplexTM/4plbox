<div class="col-lg-12">
    <div class="form-group">
        <div class="col-sm-12 col-sm-offset-0 guardar">
            <button type="button" class="ladda-button btn btn-primary" data-style="expand-right" @click.prevent="create()" v-if="editar==0">
                <i class="fa fa-save"></i>  @lang('layouts.save') 
            </button>
            <template v-else>
                <button type="button" class="ladda-button btn btn-warning" data-style="expand-right" @click.prevent="update()">
                    <i class="fa fa-edit"></i> @lang('layouts.update') 
                </button>
                <button type="button" class="btn btn-white" @click.prevent="cancel()">
                    <i class="fa fa-times"></i>  @lang('layouts.cancel') 
                </button>
            </template>
        </div>
    </div>
</div>