{{-- MODAL AGREGAR TRACKINGS2 --}}
<div class="modal fade bs-example" id="modalTrackingsAdd2" tabindex="" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" id="trackings">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
            class="sr-only">@lang('documents.close')</span></button>
        <h2 class="modal-title" id="myModalLabel"><i class="fal fa-truck"></i> @lang('documents.add_trackings')
        </h2>
      </div>
      <div class="modal-body">
        <div class="row" id="window-load">
          <div id="loading">
            <Spinner name="circle" color="#66bf33" />
          </div>
        </div>
        <div class="row">
          <div class="col-lg-8">
            <label class="control-label">Ingrese el número de tracking</label>
            <input type="text" placeholder="Tracking" id="tracking_number" class="form-control"
              v-model="tracking_number" @keyup.enter="addTrackingToDocument('create')">
          </div>
          <div class="col-lg-4">
            <label class="control-label" style="width: 100%;">&nbsp;</label>
            <el-button type="success" id="tracking_save" :loading="loading_add_tracking"
              @click="addTrackingToDocument('create')"><i class="fal fa-plus"></i> Agregar</el-button>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <h2>@lang('documents.associated_trackings')</h2>
            <div class="form-group">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tbl-trackings-used"
                  style="width: 100%">
                  <thead>
                    <tr>
                      <th style="width: 50%">@lang('documents.tracking')</th>
                      <th>@lang('documents.content')</th>
                      <th>@lang('general.actions')</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fal fa-times"></i>
          @lang('documents.close')</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL AGREGAR TRACKINGS --}}
<div class="modal fade bs-example" id="modalTrackingsAdd" tabindex="" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" id="trackings">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
            class="sr-only">@lang('documents.close')</span></button>
        <h2 class="modal-title" id="myModalLabel"><i class="fal fa-truck"></i> @lang('documents.add_trackings')
        </h2>
      </div>
      <div class="modal-body">
        <form id="formSearchTracking" name="formSearchTracking" method="POST" action="">
          <div class="row" id="window-load">
            <div id="loading">
              <Spinner name="circle" color="#66bf33" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-8">
              <h3>Seleccione los trackings del cliente registrado.</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover" id="tbl-trackings" style="width: 100%">
                    <thead>
                      <tr>
                        <th></th>
                        <th style="width: 50%">@lang('documents.tracking')</th>
                        <th>@lang('documents.content')</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="addTrackingsToDocument()">
          <i class="fal fa-plus"></i> @lang('documents.add')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <i class="fal fa-times"></i> @lang('documents.close')</button>
      </div>
    </div>
  </div>
</div>