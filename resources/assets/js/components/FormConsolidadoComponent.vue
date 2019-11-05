<!-- estilos -->
<style type="text/css">
#tbl-modalguiasconsolidado_wrapper {
  padding-bottom: 0 !important;
}
#tbl-consolidado_wrapper {
  padding-bottom: 0px !important;
  padding-right: 0px !important;
}
.d-center {
  display: flex;
  align-items: center;
}
.danger,
.danger .dropdown-toggle,
.danger .selected-tag {
  color: red;
  border-color: red;
}
a.badge:focus,
a.badge:hover {
  text-decoration: none;
}
.editable {
  font-weight: bold;
  /*color: orangered;*/
}
.text-danger {
  color: #ed5565;
}
.table.table td a {
  margin: 0;
}
.btn-group > .btn + .dropdown-toggle {
  height: 34px;
}
.center-content {
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h5>{{ documento.tipo_nombre }}</h5>
            <div class="ibox-tools">
              <button
                type="button"
                class="btn btn-xs btn-danger"
                onclick="closeDocument()"
                v-if="!close"
              >
                <i class="fal fa-lock"></i> Cerrar consolidado
              </button>
            </div>
          </div>
          <div class="form-horizontal">
            <div class="ibox-content col-lg-12">
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="col-sm-12">
                      <div class="form-group" :class="{ danger: errors.has('central_destino') }">
                        <label for="central_destino_id">Central destino (agencia)</label>
                        <el-select
                          autocomplete="off"
                          name="central_destino"
                          v-model="central_destino_id"
                          filterable
                          placeholder="Buscar"
                          v-validate.disable="'required'"
                          size="medium"
                          value-key="id"
                          :disabled="disabled_agencia"
                        >
                          <el-option
                            v-for="item in branchs"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                          ></el-option>
                        </el-select>
                      </div>
                      <span class="danger">{{ errors.first('central_destino') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-sm-12">
                      <div class="form-group" :class="{ danger: errors.has('pais') }">
                        <label for="localizacion_id">Ciudad destino</label>
                        <city-component
                          @get="setCity($event)"
                          :disabled="disabled_city"
                          :selected="city_selected_s"
                        ></city-component>
                      </div>
                      <span class="danger">{{ errors.first('pais') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="col-sm-12">
                      <div class="form-group" :class="{ danger: errors.has('transporte_id') }">
                        <label for="transporte_id">Transporte</label>
                        <el-select
                          autocomplete="off"
                          name="transporte_id"
                          v-model="transporte_id"
                          filterable
                          placeholder="Buscar"
                          v-validate.disable="'required'"
                          size="medium"
                          :disabled="disabled_transporte"
                        >
                          <el-option
                            v-for="item in transportes"
                            :key="item.id"
                            :label="item.nombre"
                            :value="item.id"
                          ></el-option>
                        </el-select>
                      </div>
                      <span class="danger">{{ errors.first('transporte_id') }}</span>
                    </div>
                  </div>
                  <!-- BOTONES DE IMPRESION -->
                  <div class="col-sm-4">
                    <div class="col-sm-12" v-show="show_buttons">
                      <label class="control-label col-lg-12">&nbsp;</label>
                      <a
                        target="blank_"
                        class="btn btn-info btn-sm printDocument"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Imprimir manifiesto"
                      >
                        <i class="fal fa-print"></i> Manifiesto
                      </a>
                      <!-- <a target="blank_" class="btn btn-info btn-sm printDocumentGuiasCuba" data-toggle="tooltip" data-placement="top" title="Imprimir guias hijas" ><i class="fal fa-print"></i> Guias hijas Cuba</a> -->
                      <a
                        target="blank_"
                        class="btn btn-info btn-sm printDocumentGuias"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Imprimir guias hijas"
                      >
                        <i class="fal fa-print"></i> Guias hijas
                      </a>
                      <div class="btn-group">
                        <a
                          class="btn btn-info btn-sm"
                          href="getDataPrintBagsConsolidate"
                          target="blank_"
                        >
                          <i class="fal fa-print"></i> Bolsas / Tulas
                        </a>
                        <button
                          type="button"
                          class="btn btn-info btn-sm dropdown-toggle"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li>
                            <a @click="printLabelBagModal">
                              <i class="fal fa-print"></i> Mas
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div
                      class="col-sm-12"
                      v-show="!show_buttons && show_msn"
                      style="color: #E34724"
                    >
                      <span>Hay valores declarados en cero (0), valores que superan lo permitido para COURIER o no hay documentos ingresados</span>
                      <a @click="show_buttons = true" class="btn btn-info btn-sm">Deseo continuar</a>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="observacion">Observación</label>
                        <input
                          type="text"
                          v-model="observacion"
                          name="observacion"
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="observacion">Tipo consolidado</label>
                        <input
                          type="text"
                          v-model="tipo_consolidado"
                          name="tipo_consolidado"
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4" v-if="show_buttons && pais_id == pais_id_config">
                    <div class="col-lg-12">
                      <!-- <div class="col-sm-5"> -->
                      <!-- <div class="form-group"> -->
                      <label for style="width: 100%;">&nbsp;</label>
                      <!-- <button
                        class="btn btn-primary btn-sm"
                        type="button"
                        data-toggle="tooltip"
                        title="Descargar Manifiesto Interno"
                        @click="exportInternalManifest()"
                      >
                        <i class="fal fa-cloud-download-alt"></i> Manifiesto Interno
                      </button>-->
                      <!-- </div> -->
                      <!-- </div> -->
                      <!-- <div class="col-sm-4"> -->
                      <!-- <div class="form-group">
                      <label for style="width: 100%;">&nbsp;</label>-->
                      <button
                        class="btn btn-primary btn-sm"
                        type="button"
                        data-toggle="tooltip"
                        title="Descargar Excel Liquimp"
                        @click="exportLiquimp()"
                      >
                        <i class="fal fa-cloud-download-alt"></i> Excel Liquimp
                      </button>
                      <!-- </div> -->
                      <!-- </div>
                      <div class="col-sm-3">-->
                      <!-- <div class="form-group">
                      <label for style="width: 100%;">&nbsp;</label>-->
                      <button
                        class="btn btn-primary btn-sm"
                        type="button"
                        data-toggle="tooltip"
                        title="Descargar Excel Bodega"
                        @click="exportCellar()"
                      >
                        <i class="fal fa-cloud-download-alt"></i> Excel Bodega
                      </button>
                      <!-- </div> -->
                      <!-- </div> -->
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="hr-line-dashed"></div>
                </div>
                <div class="row" v-if="!close">
                  <div class="col-sm-2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="num_bolsa" class>N° Bolsa</label>
                        <div class="input-group">
                          <span class="input-group-btn">
                            <button
                              @click="increaseBoxes()"
                              class="btn btn-info"
                              type="button"
                              data-toggle="tooltip"
                              title="Agregar bolsa"
                              style="padding: 8px 12px;"
                            >
                              <li class="fal fa-cubes"></li>
                            </button>
                          </span>
                          <input
                            type="number"
                            min="1"
                            class="form-control"
                            style
                            v-model="num_bolsa"
                            name="num_bolsa"
                            id="num_bolsa"
                            value="1"
                          />
                        </div>
                        <!-- /input-group -->
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="num_guia" class>Número de Warehouse</label>
                        <div class="input-group">
                          <input
                            type="text"
                            class="form-control"
                            v-model="num_guia"
                            @keyup.enter="addGuiasToConsolidado()"
                            name="num_guia"
                          />
                          <span class="input-group-btn">
                            <button
                              class="btn btn-info"
                              @click="addGuiasToConsolidado()"
                              type="button"
                              id="agregarBolsa"
                              data-toggle="tooltip"
                              title="Agregar guia"
                              style="padding: 8px 12px;"
                            >
                              <li class="fal fa-plus"></li>
                            </button>
                          </span>
                        </div>
                        <!-- /input-group -->
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label class="control-label col-lg-12">&nbsp;</label>
                        <button
                          class="btn btn-primary btn-sm"
                          type="button"
                          data-toggle="tooltip"
                          data-placement="top"
                          title="Documentos Disponobles"
                          id="btn_buscarGuias"
                          @click="getModalGuias()"
                        >
                          <i class="fal fa-search-plus"></i> Buscar
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="col-sm-12">
                      <div class="form-group" style="padding-top: 15px;margin-bottom: -15px;">
                        <label class="control-label col-lg-12">Rango Declarado</label>
                        <div class="col-lg-12">
                          <el-slider
                            v-model="range_value"
                            range
                            show-stops
                            :step="10"
                            :min="10"
                            :max="200"
                          ></el-slider>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table
                      id="tbl-consolidado"
                      class="table table-striped table-hover table-bordered dataTable"
                      style="width: 100%;margin-top: 30px;"
                    >
                      <thead>
                        <tr>
                          <th style="width: 20px;">
                            <i class="fal fa-cubes"></i>
                          </th>
                          <th>#Guia/WRH</th>
                          <th>Remitente</th>
                          <th>Destinatario</th>
                          <th>P.A</th>
                          <th>Descripción</th>
                          <th style="width: 40px;">Dec.</th>
                          <th style="width: 40px;">Lb</th>
                          <th style="width: 40px;">Lb R</th>
                          <th style="width: 20px;"></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th
                            style="text-align:right;font-size: 25px;"
                            colspan="6"
                          >Totales de esta página:</th>
                          <th id="Tdeclarado"></th>
                          <th id="Tpeso"></th>
                          <th id="TpesoR" colspan="2"></th>
                          <!-- <th id="TpesoK"></th> -->
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group">
                    <div class="col-sm-12 col-sm-offset-0 guardar">
                      <button
                        type="button"
                        id="saveForm"
                        class="ladda-button btn btn-success"
                        data-style="expand-right"
                        @click="saveConsolidado()"
                      >
                        <i class="fal fa-save fa-fw"></i> Guardar Cambios
                      </button>

                      <div class="btn-group dropup" v-show="show_buttons">
                        <button
                          type="button"
                          class="btn btn-info dropdown-toggle"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="fal fa-print"></i> Imprmir
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li>
                            <a
                              href
                              id="printDocument"
                              class="printDocument"
                              data-style="expand-right"
                              target="blank_"
                            >
                              <i class="fal fa-print fa-fw"></i> Imprimir Manifiesto
                            </a>
                          </li>
                          <li>
                            <a
                              href
                              id="printDocumentGuias"
                              class="printDocumentGuias"
                              data-style="expand-right"
                              target="blank_"
                            >
                              <i class="fal fa-print fa-fw"></i> Imprimir Guias
                            </a>
                          </li>
                          <li>
                            <a href="#" id class>
                              <i class="fal fa-print fa-fw"></i> Instrucciones
                            </a>
                          </li>
                        </ul>
                      </div>
                      <a @click="cancelDocument()" type="button" class="btn btn-white">
                        <i class="fal fa-times fa-fw"></i> Cancelar
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade bs-example-modal-lg"
      id="modalguiasconsolidado"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!--Este input es para cuando se haga el llamado desde el consolidado..
          se llenara con el valor del contador del detalle o el id del campo-->
          <input type="hidden" id="op" value />
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Warehouses pendientes por consolidar</h4>
          </div>
          <div class="modal-body">
            <form id="formGuiasConsolidado" name="formGuiasConsolidado" method="POST" action>
              <p>
                Seleccione los documentos que desea ingresar al consolidado y acontinuación de click en el botón
                <strong>Agregar</strong>
              </p>
              <div class="table-responsive">
                <table
                  id="tbl-modalguiasconsolidado"
                  class="table table-striped table-hover table-bordered"
                  style="width: 100%;"
                >
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 20px;"></th>
                      <th>Creación</th>
                      <th>Número warehouse</th>
                      <th>Peso lb</th>
                      <th>Declarado</th>
                      <th style="width:20%">Consignee</th>
                      <th>Agencia</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              id
              @click="addGuiasToConsolidadoModal()"
              class="btn btn-primary"
              data-dismiss="modal"
            >Agregar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- MODAL CONSIGNEES -->
    <div
      class="modal fade bs-example"
      id="modalShipperConsigneeConsolidado"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" style="width: 50%!important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">
              <i class="fal fa-user-circle"></i>
              {{ tituloModal }}
            </h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table
                id="tbl-consolidado_sc"
                class="table table-striped table-hover"
                style="width: 100%;"
              >
                <thead>
                  <tr>
                    <th>Acción</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Zip</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="contactos_fields != null" v-for="contacto in contactos_fields">
                    <td v-if="contacto.nombre != null" style="width: 150px;">
                      <button
                        @click="selectedShipperConsignee(contacto)"
                        class="btn-primary btn-xs"
                        data-toggle="tooltip"
                        title="Seleccionar"
                      >
                        Seleccionar
                        <i class="fal fa-check"></i>
                      </button>
                    </td>
                    <td v-if="contacto.nombre != null">{{ contacto.nombre }}</td>
                    <td v-if="contacto.nombre != null">{{ contacto.telefono }}</td>
                    <td v-if="contacto.nombre != null">{{ contacto.ciudad }}</td>
                    <td v-if="contacto.nombre != null">{{ contacto.zip }}</td>
                  </tr>
                  <tr v-else>
                    <td colspan="5">No hay datos</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- MODAL AGRUPAR GUIAS -->
    <div
      class="modal fade bs-example-modal-lg"
      id="modalagrupar"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" style="width: 40%!important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">
              <i class="fal fa-cubes"></i> Guias disponibles para agrupar
            </h4>
          </div>
          <div class="modal-body">
            <form id="formGuiasAgrupar">
              <p>Selecione las guias que desea agrupar en este registro.</p>
              <div class="table-responsive">
                <table
                  id="tbl-modalagrupar"
                  class="table table-striped table-hover"
                  style="width: 100%;"
                >
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 20px;"></th>
                      <th>Numero Guia</th>
                      <th>Peso lb</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              id
              @click="agruparGuiasConsolidado()"
              class="btn btn-primary"
              data-dismiss="modal"
            >Agregar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL IMPRIMIR LABELS POR BOLSA -->
    <div
      class="modal fade bs-example"
      id="modalPrintLabels"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" style="width: 40%!important">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">
              <i class="fal fa-barcode"></i>Imprimir
            </h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="col-sm-6">
                  <h3>Seleccione el numero de bolsa</h3>
                  <div class="form-group">
                    <!-- <select class="form-control" v-model="num_bolsa_selected">
																<option value="0">Todas</option>
																<option value="1">1</option>
																<option value="2">2</option>
                    </select>-->
                    <el-select v-model="num_bolsa_selected">
                      <el-option
                        v-for="item in bags"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      ></el-option>
                    </el-select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <h3>&nbsp;</h3>
                    <a
                      @click="printGroup('label')"
                      class="btn btn-info btn-sm"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Imprimir Labels Bolsa"
                    >
                      <i class="fal fa-print"></i> Lables
                    </a>
                    <a
                      hfer="#"
                      class="btn btn-info btn-sm"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Imprimir"
                    >
                      <i class="fal fa-print"></i> Factura Proforma
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <!-- <button type="button" id="" @click="printLabelBag()" class="btn btn-primary" data-dismiss="modal">Agregar</button> -->
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <modal-change :open_modal="openModalChange"></modal-change>
  </div>
</template>

<script>
export default {
  props: {
    documento: {
      type: Object,
      required: true
    },
    contactos: {
      type: Object,
      required: false
    },
    restore: {
      type: Object,
      required: false
    },
    agrupar: {
      type: Object,
      required: false
    },
    removeragrupado: {
      type: Object,
      required: false
    },
    permission: {
      type: Object,
      required: false
    },
    app_type: {
      type: String,
      required: true
    },
    app_client: {
      type: String,
      required: true
    },
    pais_id_config: [String, Number],
    close_document: {
      type: Boolean,
      required: false
    }
  },
  watch: {
    close_document: function(val) {
      this.close = val;
      this.updateTableDetail();
    },
    permission: function(values) {
      this.permissions = values;
    },
    contactos: function(option) {
      this.openModalChange = {
        id: option.id,
        id_data: option.idShipCons,
        option: option.opcion,
        open: true
      };
      // this.contactos_fields = null;
      // if (option.opcion === "shipper") {
      //   var id = option.idShipCons;
      //   $("#modalShipperConsigneeConsolidado").modal("show");
      //   this.tituloModal = "Remitente (Shipper)";
      //   var contact = this.shipper_contactos[id];
      //   if (contact != null) {
      //     this.contactos_fields = JSON.parse(
      //       contact.replace(/&quot;/g, '"')
      //     ).campos;
      //   }
      // }
      // if (option.opcion === "consignee") {
      //   var id = option.idShipCons;
      //   $("#modalShipperConsigneeConsolidado").modal("show");
      //   this.tituloModal = "Destinatario (Consignee)";
      //   var contact = this.consignee_contactos[id];
      //   if (contact != null) {
      //     this.contactos_fields = JSON.parse(
      //       contact.replace(/&quot;/g, '"')
      //     ).campos;
      //   }
      // }
    },
    restore: function(option) {
      let me = this;
      axios
        .get("restoreShipperConsignee/" + option.id + "/" + option.table)
        .then(response => {
          toastr.success("Registro original restaurado.");
          me.updateTableDetail();
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    },
    agrupar: function(option) {
      let me = this;
      if ($.fn.DataTable.isDataTable("#tbl-modalagrupar")) {
        $("#tbl-modalagrupar tbody").empty();
        $("#tbl-modalagrupar")
          .dataTable()
          .fnDestroy();
      }
      var table = $("#tbl-modalagrupar").DataTable({
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
        ajax: "getGuiasAgrupar/" + option.id,
        columns: [
          {
            render: function(data, type, full, meta) {
              return (
                '<div class="checkbox checkbox-success"><input type="checkbox" data-id_guia="' +
                full.documento_detalle_id +
                '" id="chk' +
                full.id +
                '" name="chk[]" value="' +
                full.id +
                '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' +
                full.id +
                '"></label></div>'
              );
            }
          },
          {
            data: "codigo",
            name: "codigo"
          },
          {
            data: "peso2",
            name: "peso2"
          }
        ]
      });
      $("#modalagrupar").modal("show");
    },
    removeragrupado: function(option) {
      let me = this;
      axios
        .get("removerGuiaAgrupada/" + option.id + "/" + option.id_guia_detalle)
        .then(response => {
          toastr.success("Registro quitado correctamente.");
          me.updateTableDetail();
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    }
  },
  mounted() {
    this.getSelectBranch();
    this.getDataDetail();
    this.getTransportes();
    this.close = this.documento.close_document;
    $("#document_type").val("consolidado");
    $(".printDocument").attr(
      "href",
      "../../impresion-documento/" + $("#id_documento").val() + "/consolidado"
    );
    $(".printDocumentGuias").attr(
      "href",
      "../../impresion-documento/" +
        $("#id_documento").val() +
        "/consolidado_guias"
    );
    $(".printDocumentGuiasCuba").attr(
      "href",
      "../../impresion-documento/" +
        $("#id_documento").val() +
        "/consolidado_guias_cuba"
    );
    let me = this;
    setTimeout(function() {
      if (me.documento.ciudad_id != null) {
        me.city_selected_s = me.documento.ciudad;
        me.localizacion_id = me.documento.ciudad_id;
        me.pais_id = me.documento.pais_id;
        me.disabled_city = true;
      }
      if (me.documento.central_destino_id != null) {
        me.central_destino_id = me.documento.central_destino_id;
        me.disabled_agencia = true;
      }
      if (me.documento.transporte_id != null) {
        me.transporte_id = me.documento.transporte_id;
        me.disabled_transporte = true;
      }
      if (me.documento.observaciones != null) {
        me.observacion = me.documento.observaciones;
      }
      if (me.documento.observaciones != null) {
        me.tipo_consolidado = me.documento.tipo_consolidado;
      }
    }, 100);
  },
  created() {
    /* CUSTOM MESSAGES VE-VALIDATOR*/
    const dict = {
      custom: {
        transporte_id: {
          required: "El Transporte es obligatorio"
        },
        localizacion_id: {
          required: "La ciudad es obligatorio"
        },
        central_destino_id: {
          required: "La Central destino es obligatoria"
        }
      }
    };
    this.$validator.localize("es", dict);
  },
  data() {
    return {
      openModalChange: {},
      transporte_id: null,
      transportes: [],
      countries: [],
      branchs: [],
      services: [],
      details: [],
      contactos_fields: [],
      shipper_contactos: {},
      consignee_contactos: {},
      permissions: {},
      num_bolsa: 1,
      pais_id: null,
      central_destino_id: null,
      observacion: null,
      num_guia: null,
      disabled_transporte: false,
      disabled_agencia: false,
      disabled_city: false,
      tituloModal: "",
      show_buttons: false,
      show_msn: false,
      close: false,
      localizacion_id: null,
      city_selected_s: null,
      ciudades: [],
      tipo_consolidado: "COURIER",
      num_bolsa_selected: 0,
      bags: [],
      cant_bags: 0,
      range_value: [30, 90]
    };
  },
  methods: {
    setCity(data) {
      this.localizacion_id = data.id;
      this.pais_id = data.pais_id;
    },
    getSelectBranch: function() {
      axios.get("/agencia/getSelectBranch").then(response => {
        this.branchs = response.data;
      });
    },
    printGroup(param) {
      window.open(
        "/impresion-group/pdfConsolidadoGroup/" +
          this.documento.id +
          "/guia/" +
          this.num_bolsa_selected,
        "_blank"
      );
    },
    exportInternalManifest() {
      window.open("/exportInternalManifest/" + this.documento.id, "_blank");
    },
    exportLiquimp() {
      window.open("/exportLiquimp/" + this.documento.id, "_blank");
    },
    exportCellar() {
      window.open("/exportCellar/" + this.documento.id, "_blank");
    },
    printLabelBagModal() {
      $("#modalPrintLabels").modal("show");
    },
    agruparGuiasConsolidado: function() {
      $("#modalagrupar").modal("hide");
      let me = this;
      var datos = $("#formGuiasAgrupar").serializeArray();
      var ids = {};
      $.each(datos, function(i, field) {
        if (field.name === "chk[]") {
          ids[i] = $("#chk" + field.value).data("id_guia");
        }
      });
      axios
        .post("agruparGuiasConsolidadoCreate", {
          id_detalle: me.agrupar.id,
          ids_guias: ids
        })
        .then(function(response) {
          toastr.success("Se agrupo correctamente.");
          me.updateTableDetail();
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
        });
    },
    selectedShipperConsignee: function(campos) {
      let me = this;
      axios
        .post("createContactsConsolidadoDetalle", {
          campos: campos,
          data: me.contactos
        })
        .then(function(response) {
          toastr.success("Cambio Exitoso.");
          $("#modalShipperConsigneeConsolidado").modal("hide");
          me.updateTableDetail();
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
        });
    },
    getTransportes: function() {
      let me = this;
      axios
        .get("../../administracion/Tipo_Embarque/getSelect")
        .then(function(response) {
          me.transportes = response.data;
        })
        .catch(function(error) {
          console.log(error);
          toastr.warning("Error.");
          toastr.options.closeButton = true;
        });
    },
    addGuiasToConsolidadoModal: function() {
      let me = this;
      var datos = $("#formGuiasConsolidado").serializeArray();
      $.each(datos, function(i, field) {
        if (field.name === "chk[]") {
          me.addGuiasToConsolidado($("#chk" + field.value).data("numguia"));
        }
      });
    },
    getModalGuias: function() {
      var me = this;
      if (
        me.localizacion_id != null &&
        me.central_destino_id != null &&
        me.transporte_id != null
      ) {
        var codigoGW = "";
        $("#modalguiasconsolidado").modal("show");
        if ($.fn.DataTable.isDataTable("#tbl-modalguiasconsolidado")) {
          $("#tbl-modalguiasconsolidado tbody").empty();
          $("#tbl-modalguiasconsolidado")
            .dataTable()
            .fnDestroy();
        }
        var table = $("#tbl-modalguiasconsolidado").DataTable({
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
          order: [[1, "desc"]],
          ajax: "getAllGuiasDisponibles/" + me.pais_id + "/" + me.transporte_id,
          columns: [
            {
              render: function(data, type, full, meta) {
                return (
                  '<div class="checkbox checkbox-success"><input type="checkbox" data-numguia="' +
                  full.num_guia +
                  '" id="chk' +
                  full.id +
                  '" name="chk[]" value="' +
                  full.id +
                  '" aria-label="Single checkbox One" style="right: 50px;"><label for="chk' +
                  full.id +
                  '"></label></div>'
                );
              },
              searchable: false,
              sortable: false
            },
            {
              data: "created_at",
              name: "created_at"
            },
            {
              render: function(data, type, full, meta) {
                return full.num_warehouse;
              }
            },
            {
              data: "peso2",
              name: "peso2",
              searchable: false,
              sortable: false
            },
            {
              render: function(data, type, full, meta) {
                return "$ " + full.declarado2;
              },
              searchable: false,
              sortable: false
            },
            {
              data: "consignee",
              name: "consignee",
              width: 100
            },
            {
              data: "agencia",
              name: "agencia",
              width: 100
            }
          ]
        });
      } else {
        toastr.info(
          "Porfavor, selecciona una central destino, un pais y un transporte para poder continuar."
        );
      }
    },
    saveConsolidado: function() {
      if (this.validateForm()) {
        var me = this;
        var rowData = {
          document_type: $("#document_type").val(),
          ciudad_id: me.localizacion_id,
          central_destino_id: me.central_destino_id,
          transporte_id: me.transporte_id,
          observacion: me.observacion,
          tipo_consolidado: me.tipo_consolidado
        };
        axios
          .put("../" + $("#id_documento").val(), rowData)
          .then(function(response) {
            toastr.success("Registro actualizado correctamente.");
            toastr.options.closeButton = true;
            me.disabled_agencia = true;
            me.disabled_city = true;
            me.disabled_transporte = true;
          })
          .catch(function(error) {
            toastr.warning("Error.");
            toastr.options.closeButton = true;
          });
      }
    },
    validateForm: function() {
      let me = this;
      if (me.central_destino_id == null) {
        me.showAlert(
          "warning",
          "Es necesario seleccionar una central destino para continuar."
        );
        return false;
      }
      if (me.localizacion_id == null) {
        me.showAlert(
          "warning",
          "Es necesario seleccionar una ciudad para continuar."
        );
        return false;
      }
      if (me.transporte_id == null) {
        me.showAlert(
          "warning",
          "Es necesario seleccionar un transporte para continuar."
        );
        return false;
      }
      return true;
    },
    cancelDocument: function() {
      window.location.href = "../";
    },
    updateDataDetail(rowData) {
      var me = this;
      axios
        .put("updateDetailConsolidado", { rowData })
        .then(function(response) {
          toastr.success("Registro actualizado correctamente.");
          toastr.options.closeButton = true;
          me.updateTableDetail();
        })
        .catch(function(error) {
          toastr.success("Error.");
          toastr.options.closeButton = true;
        });
    },
    addGuiasToConsolidado: function(num_guia) {
      if (num_guia) {
        this.num_guia = num_guia;
      }
      let me = this;
      if (this.num_guia == "") {
        toastr.warning(
          "Debe ingresar un numero de guia o warehouse para continuar."
        );
        toastr.options.closeButton = true;
      } else {
        if (this.validateForm()) {
          axios
            .get(
              "buscarGuias/" +
                this.num_guia +
                "/" +
                this.num_bolsa +
                "/" +
                this.pais_id +
                "/" +
                this.range_value
            )
            .then(response => {
              if (response.data.code === 200) {
                var table = $("#tbl-consolidado").DataTable();
                if (!table.data().count()) {
                  this.saveConsolidado();
                  this.disabled_city = true;
                  this.disabled_agencia = true;
                  this.disabled_transporte = true;
                }
                me.updateTableDetail();
                toastr.success("Registro agregado correctamente.");
                toastr.options.closeButton = true;
                this.num_guia = "";
              } else {
                if (response.data.code === 600) {
                  me.showAlert("warning", response.data.data);
                  this.num_guia = "";
                }
              }
            })
            .catch(function(error) {
              console.log(error);
              toastr.error("Error.", {
                timeOut: 50000
              });
            });
        }
      }
    },
    updateTableDetail() {
      var table = $("#tbl-consolidado").DataTable();
      table.ajax.reload();
    },
    increaseBoxes() {
      this.num_bolsa = parseInt(this.num_bolsa) + 1;
    },
    getDataDetail() {
      let me = this;
      var href_print_label = "";
      /* SOLO SI ES EL DOCUMNTO CONSOLIDADO*/
      var table = $("#tbl-consolidado")
        .DataTable({
          // keys: true,
          processing: true,
          serverSide: true,
          responsive: true,
          lengthMenu: [
            [40, 50, 80, 100, 200, -1],
            [40, 50, 80, 100, 200, "All"]
          ],
          ajax: "getAllConsolidadoDetalle",
          columns: [
            { data: "num_bolsa", name: "num_bolsa" },
            {
              render: function(data, type, full, meta) {
                var groupGuias = full.guias_agrupadas;
                var btn_delete =
                  "<a style='float: right;cursor:pointer;''><i class='fal fa-times'></i></a>";
                if (
                  groupGuias != null &&
                  groupGuias != "null" &&
                  groupGuias != ""
                ) {
                  groupGuias = groupGuias.replace(/,/g, "<br>");
                  groupGuias = groupGuias.replace(/@/g, ","); //SEPARADOR AL CREAR EL ONCLIC EN EL CONTROLADOR
                } else {
                  groupGuias = "";
                }
                var color = "default";
                if (parseInt(full.agrupadas) > 0) {
                  color = "primary";
                }
                let group = ' onclick="agruparGuias(' + full.id + ')"';
                if (me.close) {
                  group = "";
                }
                var error = "";
                if (
                  me.pais_id == pais_id_config &&
                  full.flag_declarado != 0 &&
                  (full.flag_declarado != null ||
                    parseFloat(full.declarado2) === 0 ||
                    (full.flag_peso != null && full.flag_peso != 0) ||
                    parseFloat(full.peso2) === 0)
                ) {
                  error = "text-danger";
                }
                if (me.app_type === "courier") {
                  if (me.pais_id != pais_id_config) {
                    return (
                      '<span id="num_guia' +
                      full.id +
                      '" class="' +
                      error +
                      '">' +
                      full.num_warehouse +
                      '</span><a style="float: right;cursor:pointer;" class="badge badge-' +
                      color +
                      ' pop" role="button" \n\
			                                            data-html="true" \n\
			                                            data-toggle="popover" \n\
			                                            data-trigger="hover" \n\
			                                            title="<b>Guias agrupadas</b>" \n\
			                                            data-content="' +
                      groupGuias +
                      '" ' +
                      group +
                      ">" +
                      full.agrupadas +
                      "</a>"
                    );
                  } else {
                    return (
                      '<span id="num_guia' +
                      full.id +
                      '" class="' +
                      error +
                      '">' +
                      full.num_guia +
                      '</span><a style="float: right;cursor:pointer;" class="badge badge-' +
                      color +
                      ' pop" \n\
		                                          role="button" \n\
		                                          data-html="true" \n\
		                                          data-toggle="popover" \n\
		                                          data-trigger="hover" \n\
		                                          title="<b>Guias agrupadas</b>" \n\
		                                          data-content="' +
                      groupGuias +
                      '" ' +
                      group +
                      ">" +
                      full.agrupadas +
                      "</a>"
                    );
                  }
                } else {
                  return (
                    '<span id="num_guia' +
                    full.id +
                    '" class="' +
                    error +
                    '">' +
                    full.num_warehouse +
                    '</span><a style="float: right;cursor:pointer;" class="badge badge-' +
                    color +
                    ' pop" role="button" \n\
			                                            data-html="true" \n\
			                                            data-toggle="popover" \n\
			                                            data-trigger="hover" \n\
			                                            title="<b>Guias agrupadas</b>" \n\
			                                            data-content="' +
                    groupGuias +
                    '" ' +
                    group +
                    ">" +
                    full.agrupadas +
                    "</a>"
                  );
                }
              }
            },
            {
              render: function(data, type, full, meta) {
                var nom_ship = full.shipper;
                var json = "";
                if (full.shipper == null) {
                  nom_ship = "";
                }
                if (full.shipper_json != null) {
                  nom_ship = full.shipper_json;
                }
                me.shipper_contactos[full.shipper_id] = full.shipper_contactos;
                return (
                  '<div class="center-content"><div style="width:80%;float: left;">' +
                  nom_ship +
                  '</div> <div style="width:20%;float: right;"><a  data-toggle="tooltip" title="Cambiar" class="edit" style="color:#FFC107;" onclick="showModalShipperConsigneeConsolidado(' +
                  full.id +
                  ", '" +
                  full.shipper_id +
                  '\', \'shipper\')"><i class="fal fa-pencil"></i></a> <a onclick="restoreShipperConsignee(' +
                  full.id +
                  ', \'shipper\')" class="delete" title="Restaurar original" data-toggle="tooltip" style="float:right;color:#2196F3;margin-right: 5px;"><i class="fal fa-sync-alt"></i></a></div></div> '
                );
              },
              visible: app_client === "worldcargo" ? false : true
            },
            {
              render: function(data, type, full, meta) {
                var nom_cons = full.consignee;
                var json = "";
                if (full.consignee == null) {
                  nom_cons = "";
                }
                if (full.consignee_json != null) {
                  nom_cons = full.consignee_json;
                }
                return (
                  '<div class="center-content"><div style="width:80%;float: left;">' +
                  nom_cons +
                  '</div> <div style="width:20%;float: right;"> <a  data-toggle="tooltip" title="Cambiar" class="edit" style="color:#FFC107;" onclick="showModalShipperConsigneeConsolidado(' +
                  full.id +
                  ", '" +
                  full.consignee_id +
                  '\',\'consignee\')"><i class="fal fa-pencil"></i></a> <a onclick="restoreShipperConsignee(' +
                  full.id +
                  ',\'consignee\')" class="delete" title="Restaurar original" data-toggle="tooltip" style="float:right;color:#2196F3;margin-right: 5px;"><i class="fal fa-sync-alt"></i></a></div></div>'
                );
              }
            },
            {
              render: function(data, type, full, meta) {
                var pa = full.pa == null ? "" : full.pa;
                return (
                  '<span id="pa' +
                  full.id +
                  '">' +
                  pa +
                  "</span>" +
                  '<a  data-toggle="tooltip" title="Cambiar" class="edit" style="float:right;color:#FFC107;" onclick="showModalArancel(' +
                  full.documento_detalle_id +
                  ', \'tbl-consolidado\')"><i class="fal fa-pencil"></i></a>'
                );
              },
              visible: app_client === "worldcargo" ? false : true
            },
            {
              render: function(data, type, full, meta) {
                var piezas =
                  '<div style="margin-top:5px;color:#a7a7a7"><small><i class="fal fa-box-full"></i> Piezas: ' +
                  full.piezas +
                  "<small></div>";
                return (
                  '<a data-name="contenido2" data-pk="' +
                  full.documento_detalle_id +
                  '" class="td_edit" data-placement="left" data-type="textarea" data-title="Contenido">' +
                  full.contenido2 +
                  "</a> " +
                  piezas
                );
              }
            },
            {
              render: function(data, type, full, meta) {
                return (
                  '<a data-name="declarado2" data-pk="' +
                  full.documento_detalle_id +
                  '" class="td_edit ' +
                  ((full.flag_declarado != null && full.flag_declarado != 0) ||
                  parseFloat(full.declarado2) === 0
                    ? me.pais_id == pais_id_config
                      ? "text-danger"
                      : ""
                    : "") +
                  '" data-type="text" data-placement="left" data-title="Declarado">' +
                  full.declarado2 +
                  "</a>"
                );
              }
            },
            {
              render: function(data, type, full, meta) {
                return (
                  '<a id="peso' +
                  full.consignee_id +
                  '" data-name="peso2" data-pk="' +
                  full.documento_detalle_id +
                  '" class="td_edit ' +
                  ((full.flag_peso != null && full.flag_peso != 0) ||
                  parseFloat(full.peso2) === 0
                    ? "text-danger"
                    : "") +
                  '" data-type="text" data-placement="left" data-title="Peso">' +
                  full.peso2 +
                  "</a>"
                );
              }
            },
            { data: "peso", name: "peso" },
            {
              sortable: false,
              render: function(data, type, full, meta) {
                var btn_delete = "";
                var document_print = "";
                if (me.pais_id != pais_id_config) {
                  href_print_label =
                    "../../impresion-documento-label/" +
                    full.documento_id +
                    "/warehouse/" +
                    full.documento_detalle_id +
                    "/consolidado";
                  document_print = "warehouse";
                } else {
                  href_print_label =
                    "../../impresion-documento-label/" +
                    full.documento_id +
                    "/guia/" +
                    full.documento_detalle_id +
                    "/consolidado/" +
                    full.id;
                  document_print = "guia";
                }

                var btn_proforma =
                  "<a href='../../impresion-documento/" +
                  full.documento_id +
                  "/invoice/" +
                  full.documento_detalle_id +
                  "' target='blank_' class=''><i class='fal fa-file' ></i> Factura Proforma</a> ";
                if (me.permissions.pdfLabel) {
                  var btn_label =
                    "<a href='" +
                    href_print_label +
                    "' target='blank_' class=''><i class='fal fa-barcode'></i> Imprimir label</a> ";
                  var name = "Nitro PDF Creator (Pro 10)";
                  var format = "PDF";
                  // href_print_label = 'onclick="javascript:jsWebClientPrint.print(\'useDefaultPrinter=false&printerName=' + name + '&filetype='+ format
                  +"&id=" +
                    full.documento_id +
                    "&agency_id=" +
                    full.agencia_id +
                    "&document=" +
                    document_print +
                    "&id_detail=" +
                    full.documento_detalle_id +
                    "&id_detail_consol=" +
                    full.id +
                    "&consolidado=" +
                    true +
                    "&document=guia&label=true')\"";
                }
                if (me.permissions.deleteDetailConsolidado && !me.close) {
                  var btn_delete =
                    ' <a onclick="eliminarConsolidado(' +
                    full.id +
                    "," +
                    false +
                    ")\" class='delete_btn' style='color:#E34724;'><i class='fal fa-trash-alt'></i> Eliminar</a> ";
                }
                var btn_group =
                  '<div class="btn-group" data-toggle="tooltip" title="Acciones">' +
                  '<button type="button" class="btn btn-success dropdown-toggle btn-circle-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                  '<i class="fal fa-ellipsis-v"></i>' +
                  "</button>" +
                  '<ul class="dropdown-menu dropdown-menu-right pull-right" style="font-size: 15px!important;">' +
                  "<li>" +
                  btn_proforma +
                  "</li>" +
                  // '<li><a '+href_print_label+'><i class="fal fa-barcode"></i> Imprimir label</a></li>'+
                  "<li>" +
                  btn_label +
                  "</li>" +
                  '<li role="separator" class="divider"></li>' +
                  "<li>" +
                  btn_delete +
                  "</li>" +
                  "</ul>" +
                  "</div>";
                // return btn_proforma + btn_label + btn_delete;
                return btn_group;
              },
              className: "text-center"
            },
            { data: "contenido2", name: "contenido2" },
            { data: "peso2", name: "peso2" },
            { data: "declarado2", name: "declarado2" }
          ],
          columnDefs: [
            { className: "text-center", targets: [0], width: "20px" },
            { targets: [1], width: "10%" },
            { targets: [2, 3] },
            { className: "text-center", targets: [4], width: 80 },
            // { "targets": [ 5 ],  width: '20%'},
            { targets: [6, 7, 8], width: "40px" },
            // { className: "text-center", "targets": [ 9 ],  },
            { targets: [10, 11, 12], visible: false }
          ],
          drawCallback: function() {
            var api = this.api();
            let datos = api.rows({ page: "current" }).data();
            let cont = 0;

            // SABER LA CANTIDAD DE BOLSAS DEL CONSOLIDADO
            if (datos.length > 0) {
              for (var i = 0; i < datos.length; i++) {
                me.cant_bags =
                  parseInt(me.cant_bags) < parseInt(datos[i].num_bolsa)
                    ? datos[i].num_bolsa
                    : me.cant_bags;
              }
              me.bags = [];
              for (var i = 0; i < parseInt(me.cant_bags) + 1; i++) {
                if (i === 0) {
                  me.bags.push({ value: i, label: "Todas" });
                } else {
                  me.bags.push({ value: i, label: "Bolsa " + i });
                }
              }
            }
            if (app_type === "courier" && me.pais_id == pais_id_config) {
              for (var i = 0; i < datos.length; i++) {
                // VALIDACION POSICION ARANCELARIA
                if (datos[i].pa == null) {
                  $("#num_guia" + datos[i].id).addClass("text-danger");
                  $("#pa" + datos[i].id)
                    .html("No Datos")
                    .addClass("text-danger");
                  cont++;
                } else {
                  $("#pa" + datos[i].id).removeClass("text-danger");
                  if (
                    datos[i].flag_peso == null &&
                    datos[i].flag_declarado == null
                  ) {
                    if (
                      parseFloat(datos[i].declarado2) == 0 ||
                      parseFloat(datos[i].peso2) == 0
                    ) {
                      $("#num_guia" + datos[i].id).addClass("text-danger");
                      cont++;
                    } else {
                      $("#num_guia" + datos[i].id).removeClass("text-danger");
                    }
                  } else {
                    cont++;
                  }
                }
              }
            }
            if (datos.length > 0) {
              me.show_msn = true;
            }
            if (cont === 0 && datos.length > 0) {
              me.show_buttons = true;
            } else {
              me.show_buttons = false;
            }

            if (!me.close) {
              $(".edit, .delete").css("opacity", "0");
            } else {
              $(".edit, .delete").css("opacity", "0");
            }
            /* EDITABLE FIELD */
            if (me.permissions.editDetail && !me.close) {
              $(".td_edit").editable({
                ajaxOptions: {
                  type: "post",
                  dataType: "json"
                },
                url: "updateDetailConsolidado",
                validate: function(value) {
                  if ($.trim(value) == "") {
                    return "Este campo es obligatorio!";
                  }
                },
                success: function(response, newValue) {
                  me.updateTableDetail();
                }
              });
            }
            /* POPOVER PARA LAS GUIAS AGRUPADAS (BADGED) */
            $(".pop")
              .popover({ trigger: "manual", html: true })
              .on("mouseenter", function() {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function() {
                  $(_this).popover("hide");
                });
              })
              .on("mouseleave", function() {
                var _this = this;
                setTimeout(function() {
                  if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                  }
                }, 300);
              });
          },
          footerCallback: function(row, data, start, end, display) {
            var api = this.api(),
              data;
            /*Remove the formatting to get integer data for summation*/
            var intVal = function(i) {
              return typeof i === "string"
                ? i.replace(/[\$,]/g, "") * 1
                : typeof i === "number"
                ? i
                : 0;
            };
            /*Total over all pages*/
            var total_cantidad = api
              .column(8)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);
            var librasR = api
              .column(8, { page: "current" })
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);
            var libras = api
              .column(11, { page: "current" })
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);
            var declarado = api
              .column(12, { page: "current" })
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);
            var pesoK = libras * 0.453592;
            var pesoKR = librasR * 0.453592;

            var diferenciaL = librasR - libras;
            var color1 = "rgb(203, 23, 30)";
            if (diferenciaL === 0) {
              color1 = "#4caf50";
            }
            var color2 = "rgb(203, 23, 30)";
            var diferenciaK = pesoKR - pesoK;
            if (diferenciaK === 0) {
              color2 = "#4caf50";
            }

            /*Update footer formatCurrency()*/
            $(api.column(6).footer()).html(
              '<spam id="totalDeclarado">' + declarado + "</spam><br>USD"
            );
            $(api.column(7).footer()).html(
              '<spam id="totalPeso">' +
                libras +
                ' (Lbs)<br><spam id="totalPesoK">' +
                isInteger(pesoK) +
                " (Kl)</spam></spam>"
            );
            $(api.column(8).footer()).html(
              '<spam id="totalPesoR">' +
                librasR +
                ' (Lbs)<br><spam id="totalPesoKR">' +
                isInteger(pesoKR) +
                " (Kl)</spam>"
            );
            // $(api.column(9).footer()).html('<spam id="diferenciaL" style="color:'+color1+'">Dif: ' + isInteger(diferenciaL) + ' (Lbs)</spam><br><spam id="diferenciaK" style="color:'+color2+'">Dif: ' + isInteger(diferenciaK) + ' (Kl)</spam>');
          }
        })
        .on("xhr.dt", function(e, settings, json, xhr) {});
      table.on("key", function(e, datatable, key, cell, originalEvent) {
        if (key == 13) {
          cell.data($(cell.node()).html()).draw();
          var rowData = datatable.row(cell.index().row).data();
          me.updateDataDetail(rowData);
        }
      });
    },
    showAlert(type, message) {
      this.$message({
        message: message,
        type: type,
        offset: 70
      });
    }
  }
};
</script>
