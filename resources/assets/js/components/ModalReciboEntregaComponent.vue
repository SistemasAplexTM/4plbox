<template lang="html">
  <div class="modal fade bs-example-modal-lg" id="modalEntrega" tabindex="-1" role="dialog" aria-labelledby="mymodalEntrega" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="generarReciboWare" method="POST" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="mymodalEntrega"><i class="fal fa-barcode"></i> Recibo de Entrega WRH</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="control-label">Nombre del cliente</label>
                                <a id="input_name" class=" pull-right" data-toggle="tooltip" data-placement="top" title="Manual"><i class="fal fa-pencil" style="color: #f7a54a"></i></a>
                                <div class="input-group" style="width: 100%;">
                                    <select id="" name="" class="form-control chosen-select" style="width:350px;" tabindex="2">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control" id="cliente" name="cliente" value="" style="display: none;">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="">Dirección</label>
                                <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese la dirección">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="">Telefono</label>
                                <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese el Telefono">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="">Ciudad</label>
                                <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese la ciudad">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="">Transportador</label>
                                <input type="text" id="" name="" value="" class="form-control" placeholder="Ingrese el nombre del transportador">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="num_warehouse_guia" class="">Warehouse / Guia</label>
                                <input type="text" style="background-color: lightcyan" onkeyup="if (event.keyCode == 13)
                                            addDocumentoToRecibo();" id="num_warehouse_guia" name="num_warehouse_guia" value="" class="form-control" placeholder="Ingrese el Numero de Warehouse o Guia">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group" id="div_wrh_guia_r" style="display: none">
                                <label for="num_warehouse_guia_r" class="">Warehouse/Guia revisar - entregar</label>
                                <input type="text" style="background-color: #e0ffe6" id="num_warehouse_guia_r" name="num_warehouse_guia_r" value="" class="form-control" placeholder="Warehouse o guia a revisar y entregar." onkeyup="if (event.keyCode == 13)
                                            checkDocument($(this).val());">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group" id="div_status" style="display: none">
                                <label for="status" class="">Observación para el Estatus</label>
                                <input type="text" style="background-color: #e0ffe6" id="status" name="status" value="" class="form-control" placeholder="Observacion para el Estatus." onkeyup="if (event.keyCode == 13)
                                            checkDocument($('#num_warehouse_guia_r').val());">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="entregado" class="control-label col-lg-12">&nbsp;</label>
                                <input style="display: none;" name="entregado" id="entregado" type="checkbox" data-toggle="toggle" data-size='small' data-on="Entregar/Revisado" data-off="Consolidadar sin entregar" data-width="200" data-style="ios" data-onstyle="primary" data-offstyle="warning" >
                            </div>
                        </div>
                    </div>
                    <div class="row"><div class="mensage"></div></div>
                    <div class="row" style="padding-top: 10px;">
                        <table class="table table-striped table-hover" id="tbl_entregaWare">
                            <thead>
                                <tr>
                                    <th>Wareouses</th>
                                    <th>Tracking</th>
                                    <th style="width: 100px;">Cantidad</th>
                                    <th style="width: 100px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="crearRecibo" class="ladda-button btn btn-primary pull-left" data-style="expand-right"><i class="fal fa-save"></i> Generar Recibo</button>
                    <button type="button" id="nuevo" class="ladda-button btn btn-success pull-left" data-style="expand-right"><i class="fal fa-refresh"></i> Nuevo Recibo</button>

                    <a href="#" target="_blank" id="btn-imprimirR" type="button" class="ladda-button btn btn-warning" style="display: none" data-style="expand-right"><i class="fal fa-print"></i> Imprimir</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
export default {
}
</script>

<style lang="css" scoped>
</style>
