<!-- estilos -->
<style type="text/css" scoped>
@import url("https://fonts.googleapis.com/css?family=Russo+One");
@import url("https://fonts.googleapis.com/css?family=Roboto+Condensed");

strong {
  font-weight: bold;
}
#tbl-statusReport_wrapper {
  padding-bottom: 0 !important;
}
#tbl-status_wrapper,
#tbl-notas_wrapper {
  padding-bottom: 0px !important;
  padding-right: 0px !important;
}
.modal-dialog {
  width: 40% !important;
}
#num_track {
  font-size: 25px;
}
#register {
  margin-top: 15px;
}
.v-select {
  background-color: #ffffff;
}
.v-select .dropdown li {
  border-bottom: 1px solid rgba(112, 128, 144, 0.1);
}

.v-select .dropdown li:last-child {
  border-bottom: none;
}

.v-select .dropdown li a {
  padding: 10px 20px;
  width: 100%;
  font-size: 1.25em;
  color: #3c3c3c;
}

.v-select .dropdown-menu .active > a {
  color: #fff;
}
.dropdown-toggle > input[type="search"] {
  width: 130px !important;
}
.dropdown-toggle > input[type="search"]:focus:valid {
  width: 100% !important;
}
button.dim {
  /*margin-bottom: 0px!important;*/
}
.button_print {
  margin-top: -5px;
  float: right;
}
.tracking {
  font-size: 20px;
  padding-bottom: 3px;
}
.cont-tracking {
  font-family: "courier", sans-serif;
  margin-top: 20px;
}
.el-select-dropdown {
  z-index: 9999 !important;
}
</style>
<template>
  <!-- modal shipper -->
  <div
    class="modal fade bs-example"
    id="modalTagDocument"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">
            <label id="num_track">
              <i class="fal fa-cubes"></i>
              {{ num_track }}
              <span
                class="badge badge-success"
                data-toggle="tooltip"
                data-placement="top"
                title
                data-original-title="Cantidad de cajas"
                style="font-size:17px;"
              >{{ cantidad }}</span>
            </label>
          </h4>
          <div class>
            <span>
              <i class="fal fa-user"></i>
              {{ cliente_cons }}
            </span>
            <button
              @click="openUrl(urlSendEmail)"
              data-style="expand-right"
              class="ladda-button btn btn-info dim button_print"
              data-toggle="tooltip"
              title="Enviar email"
              id="btn-sendEmail"
            >
              <i class="fal fa-envelope fa-lg" style></i>
            </button>
          </div>
          <div class>
            <span>
              <i class="fal fa-envelope"></i>
              {{ cliente_email }}
            </span>
          </div>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                  <a href="#tracking" aria-controls="tracking" role="tab" data-toggle="tab">
                    <i class="fal fa-truck"></i> Trackings
                  </a>
                </li>
                <li role="presentation" @click="is_active = true">
                  <a href="#status" aria-controls="status" role="tab" data-toggle="tab">
                    <i class="fal fa-clock"></i> Status
                  </a>
                </li>
                <li role="presentation">
                  <a href="#notas" aria-controls="notas" role="tab" data-toggle="tab">
                    <i class="fal fa-comments"></i> Notas
                  </a>
                </li>
              </ul>
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tracking">
                  <div class="form-group cont-tracking">
                    <div v-for="val in trackings" class="tracking">
                      <i class="fal fa-truck fa-xs"></i>
                      <strong>{{ val.codigo }}</strong>
                      <br />
                      <i class="fal fa-minus fa-xs"></i> Contenido:
                      <strong>{{ val.contenido }}</strong>
                      <!-- <br />
                      <i class="fal fa-minus fa-xs"></i> Peso:
                      <strong>{{ val.peso }} Lbs</strong>-->
                    </div>
                  </div>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="status">
                  <div class="row form-group" id="register">
                    <div class="col-lg-6" :class="{ 'has-error': errors.has('warehouse') }">
                      <el-select
                        v-model="warehouse_codigo"
                        name="warehouse"
                        multiple
                        filterable
                        placeholder="Warehouse/Guia"
                        value-key="id"
                        collapse-tags
                        v-validate.disable="'required'"
                        size="medium"
                        :disabled="disabled_w"
                      >
                        <el-option
                          v-for="item in warehouses"
                          :key="item.id"
                          :label="item.name"
                          :value="item"
                        ></el-option>
                      </el-select>
                      <small class="help-block">{{ errors.first('warehouse') }}</small>
                    </div>
                    <div class="col-lg-6" :class="{ 'has-error': errors.has('estatus') }">
                      <status-component :default="defaultStatus" @get="selectStatus($event)" />
                      <small class="help-block">{{ errors.first('estatus') }}</small>
                    </div>
                    <transition name="fade">
                      <div
                        class="col-lg-6 form-group"
                        :class="{ 'has-error': errors.has('transportadora') }"
                        v-if="show"
                      >
                        <el-select
                          name="transportadora"
                          v-model="transportadora_id"
                          filterable
                          placeholder="Transportadoras locales"
                          v-validate.disable="'required'"
                          size="medium"
                        >
                          <el-option
                            v-for="item in transportadora"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                          >
                            <span style="float: left">{{ item.name }}</span>
                            <span
                              style="float: right; color: #8492a6; font-size: 13px"
                            >{{ item.pais }}</span>
                          </el-option>
                        </el-select>
                        <small class="help-block">{{ errors.first('transportadora') }}</small>
                      </div>
                    </transition>
                    <transition name="fade">
                      <div
                        class="col-lg-6 form-group"
                        :class="{ 'has-error': errors.has('guia_transportadora') }"
                        v-if="show"
                      >
                        <el-input
                          name="guia_transportadora"
                          placeholder="Número de guia transportadora"
                          prefix-icon="el-icon-edit-outlin"
                          v-model="guia_transportadora"
                          v-validate.disable="'required'"
                          size="medium"
                        ></el-input>
                        <small class="help-block">{{ errors.first('guia_transportadora') }}</small>
                      </div>
                    </transition>
                    <div class="col-lg-10">
                      <input
                        type="text"
                        class="form-control"
                        v-model="observacion"
                        placeholder="Observación"
                      />
                    </div>
                    <div class="col-lg-2">
                      <button
                        class="btn btn-primary"
                        data-toggle="tooltip"
                        title="Agregar"
                        @click="createStatusReport()"
                      >
                        <i class="fal fa-plus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="table-responsive" v-show="is_active">
                    <table
                      id="tbl-statusReport"
                      class="table table-striped table-hover table-bordered"
                      style="width: 100%;"
                    >
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Estatus</th>
                          <th>Observación</th>
                          <th>Usuario</th>
                          <th>Accion</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="notas">
                  <div class="row form-group" id="register">
                    <div class="col-lg-10" :class="{ 'has-error': errors.has('nota_name') }">
                      <input
                        type="text"
                        name="nota_name"
                        class="form-control"
                        v-model="nota"
                        placeholder="Agregar nota"
                        v-validate.disable="'required'"
                      />
                      <small class="help-block">{{ errors.first('nota_name') }}</small>
                    </div>
                    <div class="col-lg-2">
                      <button
                        class="btn btn-primary"
                        data-toggle="tooltip"
                        title="Agregar"
                        @click="createNota()"
                      >
                        <i class="fal fa-plus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table
                      id="tbl-notas"
                      class="table table-striped table-hover table-bordered"
                      style="width: 100%;"
                    >
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Nota</th>
                          <th>Usuario</th>
                          <th>Accion</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    params: {
      type: Object
    },
    id_status: Number,
    table_delete: String
  },
  mounted() {
    const dict = {
      custom: {
        warehouse: {
          required: "Este campo es obligatorio."
        },
        estatus: {
          required: "Este campo es obligatorio."
        },
        transportadora: {
          required: "Este campo es obligatorio."
        },
        guia_transportadora: {
          required: "Este campo es obligatorio."
        }
      }
    };
    this.$validator.localize("es", dict);
  },
  data() {
    return {
      id_document: null,
      consignee_id: null,
      cliente_cons: null,
      cliente_email: null,
      num_track: null,
      cantidad: null,
      urlPrint: null,
      urlPrintLabel: null,
      urlSendEmail: null,
      estatus_id: null,
      nota: null,
      observacion: null,
      id_status_nota: null,
      warehouse_codigo: null,
      status: [],
      warehouses: [],
      trackings: [],
      is_active: false,
      transportadora: [],
      transportadora_id: null,
      guia_transportadora: null,
      show: false,
      disabled_w: false,
      defaultStatus: false
    };
  },
  watch: {
    params: function(val) {
      this.warehouse_codigo = [];
      this.disabled_w = false;
      this.id_document = val.id;
      this.cliente_cons = val.cliente;
      this.consignee_id = val.consignee_id;
      this.cliente_email = val.correo;
      this.num_track = val.codigo;
      this.cantidad = val.cantidad == "undefined" ? "" : val.cantidad;
      this.detalle_id = val.detalle_id == "undefined" ? "" : val.detalle_id;
      if (val.correo == "Sin correo" || val.correo == "") {
        $("#btn-sendEmail").attr("disabled", true);
      } else {
        $("#btn-sendEmail").attr("disabled", false);
      }
      if (val.liquidado == 1) {
        this.urlSendEmail = "documento/sendEmailDocument/" + this.id_document;
        this.urlPrint = "impresion-documento/" + val.id + "/guia";
        this.urlPrintLabel = "impresion-documento-label/" + val.id + "/guia";
      } else {
        this.urlSendEmail = "documento/sendEmailDocument/" + this.id_document;
        this.urlPrint = "impresion-documento/" + val.id + "/warehouse";
        this.urlPrintLabel =
          "impresion-documento-label/" + val.id + "/warehouse";
      }
      this.getSelectWarehouses();
      this.getDatas();
      this.getSelectStatus();
      this.getStatus();
      this.getNotas();
      this.getSelectTransportadoras();
      if (this.detalle_id !== "") {
        setTimeout(() => {
          this.warehouse_codigo = [
            {
              id: this.detalle_id,
              name: this.num_track
            }
          ];
          this.disabled_w = true;
        }, 1000);
      }
    },
    id_status: function(newQuestion) {
      if (newQuestion != null) {
        this.id_status_nota = newQuestion;
        this.deleteStatusNota();
      }
    },
    table_delete: function(newQuestion) {
      this.table_delete = newQuestion;
    }
  },
  methods: {
    selectStatus(val) {
      this.estatus_id = val;
      if (val.transportadora == 1) {
        this.show = true;
      } else {
        this.show = false;
      }
    },
    openUrl: function(url) {
      var l = Ladda.create(document.querySelector(".ladda-button"));
      l.start();
      axios.get(url).then(response => {
        l.stop();
        toastr.success("Email enviado.");
        toastr.options.closeButton = true;
      });
    },
    resetForm: function() {
      // this.estatus_id= null,
      // this.warehouse_codigo= [],
      (this.nota = null),
        (this.observacion = null),
        (this.transportadora_id = null),
        (this.guia_transportadora = null),
        (this.show = false);
    },
    updateTable: function(table) {
      $("#tbl-" + table)
        .dataTable()
        ._fnAjaxUpdate();
    },
    getSelectStatus: function() {
      axios.get("status/getDataSelectModalTagGuia").then(response => {
        this.status = response.data.data;
      });
    },
    getSelectTransportadoras: function() {
      axios
        .get("status/getDataSelectTransportadoras/" + this.params.id)
        .then(response => {
          this.transportadora = response.data.data;
        });
    },
    getSelectWarehouses: function() {
      axios
        .get(
          "documento/getDataSelectWarehousesModalTagGuia/" + this.id_document
        )
        .then(response => {
          this.warehouses = response.data.data;
        });
    },
    getStatus: function() {
      if ($.fn.DataTable.isDataTable("#tbl-statusReport")) {
        $("#tbl-statusReport tbody").empty();
        $("#tbl-statusReport")
          .dataTable()
          .fnDestroy();
      }
      var table = $("#tbl-statusReport").DataTable({
        language: {
          paginate: {
            previous: "Anterior",
            next: "Siguiente"
          },
          /*"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",*/
          info: "Registros del _START_ al _END_  de un total de _TOTAL_",
          search: "Buscar",
          lengthMenu: "Mostrar _MENU_ Registros",
          infoEmpty: "Mostrando registros del 0 al 0",
          emptyTable: "No hay datos disponibles en la tabla",
          infoFiltered: "(Filtrando para _MAX_ Registros totales)",
          zeroRecords: "No se encontraron registros coincidentes"
        },
        lengthMenu: [[5, 10, 15, 20, 50], [5, 10, 15, 20, 50]],
        ajax: "statusReport/getAllGrid/" + this.id_document,
        columns: [
          {
            data: "fecha_status",
            name: "fecha_status"
          },
          {
            sortable: false,
            render: function(data, type, full, meta) {
              return (
                '<span style="color:#439a46;font-weight: 900;">' +
                full.num_warehouse +
                '</span><div><i class="' +
                full.status_icon +
                '" style="color: ' +
                full.status_color +
                '"></i> ' +
                full.status_name +
                " " +
                (full.consolidado !== null
                  ? "(" + full.consolidado + ")"
                  : "") +
                " </div> "
              );
            }
          },
          {
            data: "observacion",
            name: "observacion"
          },
          {
            data: "name",
            name: "usuario_id"
          },
          {
            render: function(data, type, full, meta) {
              return (
                '<a class="delete_btn" data-toggle="tooltip" title="Eliminar" onclick="deleteStatusNota(' +
                full.id +
                ', \'statusReport\')"><i class="fal fa-trash-alt fa-lg"></i></a>'
              );
            }
          }
        ]
      });
    },
    createStatusReport: function() {
      let me = this;
      this.$validator
        .validateAll([
          "estatus",
          "warehouse",
          "transportadora",
          "guia_transportadora"
        ])
        .then(result => {
          if (result) {
            axios
              .post("statusReport", {
                consignee_id: this.consignee_id,
                status_id: this.estatus_id.id,
                codigo: this.warehouse_codigo,
                observacion: this.observacion,
                transportadora: this.transportadora_id,
                num_transportadora: this.guia_transportadora
              })
              .then(function(response) {
                if (response.data["code"] == 200) {
                  toastr.success("Registro creado correctamente.");
                  toastr.options.closeButton = true;
                  me.resetForm();
                  me.updateTable("statusReport");
                } else {
                  toastr.warning(response.data["error"]);
                  toastr.options.closeButton = true;
                }
              })
              .catch(function(error) {
                if (error.response.status === 422) {
                  me.formErrors = error.response.data; //guardo los errores
                  me.listErrors = me.formErrors.errors; //genero lista de errores
                }
                $.each(me.formErrors.errors, function(key, value) {
                  $(".result-" + key).html(value);
                });
                toastr.error("Porfavor completa los campos obligatorios.", {
                  timeOut: 50000
                });
              });
          }
        })
        .catch(function(error) {
          toastr.warning("Error: Completa los campos.");
        });
    },
    deleteStatusNota: function() {
      let me = this;
      deleteStatusNota(null, me.table_delete);
      swal({
        title: "¿Desea eliminar el registro seleccionado?",
        text: "No podras restaurarlo despues!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No, Cancelar!"
      }).then(result => {
        if (result.value) {
          axios
            .get(me.table_delete + "/delete/" + me.id_status_nota + "/" + false)
            .then(response => {
              toastr.success("Registro eliminado exitosamente.");
              toastr.options.closeButton = true;
              var table = $("#tbl-" + me.table_delete).DataTable();
              table.ajax.reload();
            });
        }
      });
    },
    /* NOTAS */
    getNotas: function() {
      if ($.fn.DataTable.isDataTable("#tbl-notas")) {
        $("#tbl-notas tbody").empty();
        $("#tbl-notas")
          .dataTable()
          .fnDestroy();
      }
      var table = $("#tbl-notas").DataTable({
        language: {
          paginate: {
            previous: "Anterior",
            next: "Siguiente"
          },
          /*"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",*/
          info: "Registros del _START_ al _END_  de un total de _TOTAL_",
          search: "Buscar",
          lengthMenu: "Mostrar _MENU_ Registros",
          infoEmpty: "Mostrando registros del 0 al 0",
          emptyTable: "No hay datos disponibles en la tabla",
          infoFiltered: "(Filtrando para _MAX_ Registros totales)",
          zeroRecords: "No se encontraron registros coincidentes"
        },
        processing: true,
        serverSide: true,
        searching: true,
        lengthMenu: [[5, 10, 15, 20, 50], [5, 10, 15, 20, 50]],
        ajax: "documento/getAllGridNotas/" + this.id_document,
        columns: [
          {
            data: "created_at",
            name: "created_at"
          },
          {
            data: "nota",
            name: "nota"
          },
          {
            data: "name",
            name: "name"
          },
          {
            render: function(data, type, full, meta) {
              return (
                '<a class="delete_btn" data-toggle="tooltip" title="Eliminar" onclick="deleteStatusNota(' +
                full.id +
                ', \'notas\')"><i class="fal fa-trash-alt fa-lg"></i></a>'
              );
            }
          }
        ]
      });
    },
    createNota: function() {
      let me = this;
      this.$validator
        .validateAll(["nota_name"])
        .then(result => {
          if (result) {
            axios
              .post("documento/ajaxCreateNota/" + this.id_document, {
                nota: this.nota
              })
              .then(function(response) {
                if (response.data["code"] == 200) {
                  toastr.success("Registro creado correctamente.");
                  toastr.options.closeButton = true;
                } else {
                  toastr.warning(response.data["error"]);
                  toastr.options.closeButton = true;
                }
                me.resetForm();
                me.updateTable("notas");
              })
              .catch(function(error) {
                console.log(error);
                toastr.error("Porfavor completa los campos obligatorios.", {
                  timeOut: 50000
                });
              });
          }
        })
        .catch(function(error) {
          toastr.warning("Error: Completa los campos.");
        });
    },
    getDatas: function() {
      axios
        .get("documento/getDataByDocument/" + this.id_document)
        .then(response => {
          let me = this;
          let track = response.data.trackings;
          let trackings = [];
          me.trackings = {};
          if (track.length > 0) {
            for (var i = 0; i < track.length; i++) {
              if (track[i].codigo != null) {
                trackings.push(track[i]);
              }
            }
            this.trackings = trackings;
          }
        });
    }
  }
};
</script>
