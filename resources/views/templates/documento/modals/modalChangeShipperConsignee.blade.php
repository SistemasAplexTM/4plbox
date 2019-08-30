<div class="modal fade bs-example" id="modalChangeShipperConsignee" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="points" style="width: 50%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">@lang('documents.close')</span></button>
                <h2 class="modal-title" id="myModalLabel"><i class="fal fa-users"></i> Shipper - Consignee</h2>
            </div>
            <div class="modal-body">
              <form id="formChangeSC" action="">
                <div class="row" id="window-load"><div id="loading"><Spinner name="circle" color="#66bf33"/></div></div>
                <div class="row">
                    <div class="col-lg-12">
                      <div class="col-lg-12">
                        <h3>Seleccione el shipper y/o consignee que desea cambiar para este documento.</h3>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                      <div class="row">
                          <div class="col-lg-12">
                            <h2>Shipper</h2>
                            <hr>
                          </div>
                          <div class="col-lg-9">
                            <shipper-consignee-select :shipper_id="shipper_id" :option="'shipper'" @get="setDataShipperConsignee($event, true)"></shipper-consignee-select>
                          </div>
                          <div class="col-lg-3">
                            <el-button type="success" class="btn-change" size="medium" :loading="loading_save_ship" @click="saveChange('shipper')"><i v-if="!loading_save_ship" class="fal fa-exchange"></i></el-button>
                          </div>
                          <div class="col-lg-12" style="margin-top: 15px;">
                            <p><i class="fal fa-user"></i> @{{ shipper.nombre_full }}</p>
                            <p><i class="fal fa-phone"></i> @{{ shipper.telefono }}</p>
                            <p><i class="fal fa-map-marker-alt"></i> @{{ shipper.direccion }} / @{{ shipper.ciudad }}</p>
                            <p>@{{ shipper.zip }}</p>
                          </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="row">
                          <div class="col-lg-12">
                            <h2>Consignee</h2>
                            <hr>
                          </div>
                          <div class="col-lg-9">
                            <shipper-consignee-select :consignee_id="consignee_id" :option="'consignee'" @get="setDataShipperConsignee($event, false)"></shipper-consignee-select>
                          </div>
                          <div class="col-lg-3">
                            <el-button type="success" class="btn-change" size="medium" :loading="loading_save_cons" @click="saveChange('consignee')"><i v-if="!loading_save_cons" class="fal fa-exchange"></i></el-button>
                          </div>
                          <div class="col-lg-12" style="margin-top: 15px;">
                            <p><i class="fal fa-user"></i> @{{ consignee.nombre_full }}</p>
                            <p><i class="fal fa-phone"></i> @{{ consignee.telefono }}</p>
                            <p><i class="fal fa-map-marker-alt"></i> @{{ consignee.direccion }} / @{{ shipper.ciudad }}</p>
                            <p>@{{ consignee.zip }}</p>
                          </div>
                      </div>
                    </div>
                  </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('documents.close')</button>
            </div>
        </div>
    </div>
</div>
