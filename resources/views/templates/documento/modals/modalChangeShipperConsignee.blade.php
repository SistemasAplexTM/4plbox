<div class="modal fade bs-example" id="modalChangeShipperConsignee" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="points" style="width: 50%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">@lang('documents.close')</span></button>
                <h2 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i> Shipper - Consignee</h2>
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
                        <div class="form-group">
                          <div class="col-lg-12">
                            <h2>Shipper</h2>
                            <hr>
                          </div>
                          <div class="col-lg-10">
                            <shipper-consignee-select :data="dataSelectShipper" :option="'shipper'"></shipper-consignee-select>
                          </div>
                          <div class="col-lg-2">
                            <button type="button" class="ladda-button ladda-button-demo btn btn-primary"  data-style="zoom-in" title="Cambiar"><i class="fal fa-exchange"></i></button>
                          </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                          <div class="col-lg-12">
                            <h2>Consignee</h2>
                            <hr>
                          </div>
                          <div class="col-lg-10">
                            <shipper-consignee-select :data="dataSelectConsignee" :option="'consignee'"></shipper-consignee-select>
                          </div>
                          <div class="col-lg-2">
                            <button type="button" class="ladda-button ladda-button-demo btn btn-primary"  data-style="zoom-in" title="Cambiar"><i class="fal fa-exchange"></i></button>
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
