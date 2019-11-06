<!-- estilos -->
<style type="text/css">
#tbl-modalconsignee_wrapper {
  padding-bottom: 0 !important;
}
.toggle.ios,
.toggle-on.ios,
.toggle-off.ios {
  border-radius: 20px;
}
.toggle.ios .toggle-handle {
  border-radius: 20px;
}
.modal-dialog {
  width: 60% !important;
}
</style>
<template>
  <!-- modal consignee -->
  <div
    class="modal fade bs-example-modal-lg"
    id="modalConsignee"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    data-keyboard="false"
    data-backdrop="static"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" @click="closeModal()">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">
            Destinatario (Consignee) ->
            <label for="buscarF" class="control-label gcore-label-top">
              Mostrar Todos:
              <input
                type="checkbox"
                data-toggle="toggle"
                id="show-all-c"
                data-size="mini"
                data-on="Si"
                data-off="No"
                data-width="50"
                data-style="ios"
                data-onstyle="primary"
                data-offstyle="danger"
              />
            </label>
          </h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table
              id="tbl-modalconsignee"
              class="table table-striped table-hover table-bordered"
              style="width: 100%;"
            >
              <thead>
                <tr>
                  <th>Acción</th>
                  <th>Nombre</th>
                  <th>Teléfono</th>
                  <th>Ciudad</th>
                  <th>Correo</th>
                  <th>Agencia</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" @click="closeModal()">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    closeModal() {
      $("#show-all-c").bootstrapToggle("off");
      //   setTimeout(() => {
      $("#modalConsignee").modal("hide");
      //   }, 200);
    }
  }
};
</script>
