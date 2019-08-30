<!-- estilos -->
<style type="text/css">
    #tbl-modalArancel_wrapper{
        padding-bottom:0 !important;
    }
    .modal-dialog {
        width: 60%!important;
    }
</style>
<template>
    <div class="modal fade bs-example-modal-lg" id="modalArancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Posicion Arancelaria (P.A)</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbl-modalArancel" class="table table-striped table-hover table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Codigo P.A</th>
                                    <th>Descripcion</th>
                                    <th><i class="fal fa-percent"></i>Iva</th>
                                    <th><i class="fal fa-percent"></i>Arancel</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Component shipper mounted.')
        },
    }
</script>