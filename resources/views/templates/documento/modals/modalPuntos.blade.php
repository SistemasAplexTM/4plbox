<div class="modal fade bs-example" id="modalAddPoints" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="points" style="width: 40%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">@lang('documents.close')</span></button>
                <h2 class="modal-title" id="myModalLabel"><i class="fal fa-truck"></i> @lang('documents.add_points')</h2>
            </div>
            <div class="modal-body">
              <form id="formAddPoints" action="">
                <div class="row" id="window-load"><div id="loading"><Spinner name="circle" color="#66bf33"/></div></div>
                <div class="row">
                    <div class="col-lg-8">
                        <h3>Seleccione la categoria para y la cantidad del producto para registrar los puntos.</h3>
                    </div>
                    <button type="button" class="ladda-button ladda-button-demo btn btn-primary"  data-style="zoom-in">Submit</button>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                      <div class="form-group">
                        <select name="points_id" id="points_id" class="form-control js-data-example-ajax select2-container"></select>
                      </div>
                    </div>
                </div>
                {{-- <div class="row">
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
                </div> --}}
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="addTrackingsToDocument()">@lang('documents.add')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('documents.close')</button>
            </div>
        </div>
    </div>
</div>
