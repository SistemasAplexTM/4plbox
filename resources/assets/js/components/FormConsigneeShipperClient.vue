<template lang="html">
  <div class="" v-loading="loading">
    <el-row :gutter="24" v-if="hidde && showItem">
      <el-col :span="8">
        <label for="" class="control-label gcore-label-top">&nbsp;</label>
      </el-col>
      <el-col :span="16">
        <el-tooltip :content="(form.corporativo) ? 'Persona' : 'Compañia'" placement="top">
          <el-switch
            v-model="form.corporativo"
            active-text="Compañia"
            inactive-text="Persona natural"
            active-icon-class="el-icon-office-building"
            inactive-icon-class="el-icon-user">
          </el-switch>
        </el-tooltip>
      </el-col>
    </el-row>

    <el-row :gutter="24" v-if="showId && showItem">
      <el-col :span="8">
        <label for="tipo_identificacion_id" class="control-label gcore-label-top">Tipo identificación:<samp id="require">*</samp></label>
      </el-col>
      <el-col :span="16">
        <el-select v-model="form.tipo_identificacion_id" size="medium" filterable placeholder="Select">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
        <small class="help-block" v-show="errors_data">Campo obligatorio</small>
      </el-col>
    </el-row>

    <el-row :gutter="24" v-if="hidde && showItem">
      <el-col :span="8">
        <label for="documento" class="control-label gcore-label-top" v-if="!form.corporativo">Documento:</label>
        <label for="documento" class="control-label gcore-label-top" v-if="form.corporativo">Nit:</label>
      </el-col>
      <el-col :span="16">
        <el-input  :placeholder="(form.corporativo) ? 'Nit' : 'Documento'" v-model="form.documento" size="medium" clearable></el-input>
      </el-col>
    </el-row>

    <el-row :gutter="24">
      <el-col :span="8">
        <label for="primer_nombre" class="control-label gcore-label-top" v-if="!form.corporativo">Primer Nombre:<samp id="require">*</samp></label>
        <label for="primer_nombre" class="control-label gcore-label-top" v-if="form.corporativo && hidde">Razon social:<samp id="require">*</samp></label>
        <label for="primer_nombre" class="control-label gcore-label-top" v-if="form.corporativo && !hidde">Nombre:<samp id="require">*</samp></label>
      </el-col>
      <el-col :span="16">
        <el-input  :placeholder="(form.corporativo) ? ((hidde) ? 'Razon social' : 'Nombre') : 'Primer Nombre'"
        v-model="form.primer_nombre" size="medium" clearable
        @blur="validateFields('primer_nombre')"
        :class="{ 'error_field': errors_data.primer_nombre }"></el-input>
        <small class="help-block" v-show="errors_data.primer_nombre">Campo obligatorio</small>
      </el-col>
    </el-row>

    <div v-if="hidde">
      <transition name="fade">
        <el-row :gutter="24" v-show="!form.corporativo">
          <el-col :span="8">
            <label for="segundo_nombre" class="control-label gcore-label-top">Segundo Nombre:</label>
          </el-col>
          <el-col :span="16">
            <el-input  placeholder="Segundo Nombre" v-model="form.segundo_nombre" size="medium" clearable></el-input>
          </el-col>
        </el-row>
      </transition>
      <transition name="fade">
        <el-row :gutter="24" v-show="!form.corporativo">
          <el-col :span="8">
            <label for="primer_apellido" class="control-label gcore-label-top">Primer Apellido:<samp id="require">*</samp></label>
          </el-col>
          <el-col :span="16">
            <el-input  placeholder="Primer Apellido" v-model="form.primer_apellido" size="medium" clearable
            @blur="validateFields('primer_apellido')"
            :class="{ 'error_field': errors_data.primer_apellido }"></el-input>
            <small class="help-block" v-show="errors_data.primer_apellido">Campo obligatorio</small>
          </el-col>
        </el-row>
      </transition>
      <transition name="fade">
        <el-row :gutter="24" v-show="!form.corporativo">
          <el-col :span="8">
            <label for="segundo_apellido" class="control-label gcore-label-top">Segundo Apellido:</label>
          </el-col>
          <el-col :span="16">
            <el-input  placeholder="Segundo Apellido" v-model="form.segundo_apellido" size="medium" clearable></el-input>
          </el-col>
        </el-row>
      </transition>
    </div>

    <el-row :gutter="24">
      <el-col :span="8">
        <label for="direccion" class="control-label gcore-label-top">Dirección:<samp id="require">*</samp></label>
      </el-col>
      <el-col :span="16">
        <el-input  placeholder="Dirección" v-model="form.direccion" size="medium" clearable
        @blur="validateFields('direccion')"
        :class="{ 'error_field': errors_data.direccion }"></el-input>
        <small class="help-block" v-show="errors_data.direccion">Campo obligatorio</small>
      </el-col>
    </el-row>
    <el-row :gutter="24">
      <el-col :span="8">
        <label for="localizacion_id" class="control-label gcore-label-top">Ciudad:<samp id="require">*</samp></label>
      </el-col>
      <el-col :span="16">
        <city-component @get="setCity($event)" :selected="city_selected_s" clearable
        @blur="validateFields('localizacion_id')"
        :class="{ 'error_field': errors_data.localizacion_id }"></city-component>
        <small class="help-block" v-show="errors_data.localizacion_id">Campo obligatorio</small>
      </el-col>
    </el-row>
    <el-row :gutter="24">
      <el-col :span="8">
        <label for="telefono" class="control-label gcore-label-top">Teléfono:</label>
      </el-col>
      <el-col :span="16">
        <el-input  placeholder="Teléfono" v-model="form.telefono" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="hidde && showItem">
      <el-col :span="8">
        <label for="whatsapp" class="control-label gcore-label-top">Whatsapp:</label>
      </el-col>
      <el-col :span="16">
        <el-input  placeholder="Whatsapp" v-model="form.whatsapp" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="showItem">
      <el-col :span="8">
        <label for="correo" class="control-label gcore-label-top">Email:</label>
      </el-col>
      <el-col :span="16">
        <el-input type="email"  placeholder="Email" v-model="form.correo" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="hidde && showItem">
      <el-col :span="8">
        <label for="emails_cc" class="control-label gcore-label-top">Emails CC:</label>
      </el-col>
      <el-col :span="16">
        <el-input-tag placeholder="Ingrese correos" v-model="form.emails_cc" size="medium"></el-input-tag>
      </el-col>
    </el-row>
    
    <el-row :gutter="24" v-if="!hidde && showItem">
      <el-col :span="8">
        <label for="zona" class="control-label gcore-label-top">Zona:</label>
      </el-col>
      <el-col :span="16">
        <el-input  placeholder="Zona" v-model="form.zona" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="hidde">
      <el-col :span="8">
        <label for="zip" class="control-label gcore-label-top">Zip Code:</label>
      </el-col>
      <el-col :span="16">
        <el-input  placeholder="Zip Code" v-model="form.zip" size="medium" clearable></el-input>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="payload.table === 'consignee' && showItem">
      <el-col :span="8">
        <label for="cliente_id" class="control-label gcore-label-top">Cliente:</label>
      </el-col>
      <el-col :span="16">
        <el-select v-model="form.cliente_id" size="medium" filterable clearable placeholder="Selecione" value-key="id">
          <el-option
            v-for="item in clientes"
            :key="item.id"
            :label="item.nombre"
            :value="item.id">
          </el-option>
        </el-select>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="payload.table === 'clientes' && showItem">
      <el-col :span="8">
        <label for="notify_client" class="control-label gcore-label-top">&nbsp;</label>
      </el-col>
      <el-col :span="16">
        <el-popover
          placement="top-start"
          title="Notificar Recibos de Trackings de Consignees"
          width="410"
          trigger="hover"
          content="Se enviara email con copia oculta a este cliente de la carga recibida de los consignees asociado a el.">
          <el-checkbox slot="reference" v-model="form.notify_client"><i class="fal fa-envelope"></i> Notificar Recibos de Trackings de Consignees.</el-checkbox>
        </el-popover>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="payload.table === 'clientes' && showItem">
      <el-col :span="8">
        <label for="invoice_all" class="control-label gcore-label-top">&nbsp;</label>
      </el-col>
      <el-col :span="16">
        <el-popover
          placement="top-start"
          title="Factura Agrupada"
          width="410"
          trigger="hover"
          content="Se generará una sola factura agrupando en ella las de sus clientes.">
          <el-checkbox slot="reference" v-model="form.invoice_all"><i class="fal fa-file-invoice-dollar"></i> Generar Factura Agrupada.</el-checkbox>
        </el-popover>
      </el-col>
    </el-row>
    <transition name="fade">
      <el-row :gutter="24" v-if="payload.table !== 'clientes' && form.cliente_id !== null && form.cliente_id !== '' && showItem">
        <el-col :span="8">
          <label for="notify_client" class="control-label gcore-label-top">&nbsp;</label>
        </el-col>
        <el-col :span="16">
          <el-popover
            title="Notificar al Cliente"
            width="410"
            trigger="hover"
            content="Se le enviara email de la carga recibida al cliente asociado de este consignee.">
            <el-checkbox slot="reference" v-model="form.notify_client"><i class="fal fa-envelope"></i> Notificar al cliente.</el-checkbox>
          </el-popover>
        </el-col>
      </el-row>
    </transition>
    <el-row :gutter="24" v-if="payload.table === 'consignee' && showItem">
      <el-col :span="8">
        <label for="tarifa" class="control-label gcore-label-top">Tarifa:</label>
      </el-col>
      <el-col :span="16">
        <el-input-number size="medium" v-model="form.tarifa" :min="0" :precision="2" :step="0.1" placeholder="Tarifa 0.00"></el-input-number>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="payload.table === 'consignee' && showItem">
      <el-col :span="8">
        <label for="emailsend" class="control-label gcore-label-top">&nbsp;</label>
      </el-col>
      <el-col :span="16">
        <el-checkbox v-model="form.emailsend"><i class="fal fa-envelope"></i> Enviar email con datos de su casillero.</el-checkbox>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="payload.table !== 'clientes' && edit === false && showItem">
      <el-col :span="8">
        <label for="" class="control-label gcore-label-top">Registrar en:</label>
      </el-col>
      <el-col :span="16" class="checkbox_create">
        <el-checkbox-group v-model="check_create">
          <el-checkbox label="Shipper" v-if="payload.table != 'shipper'"></el-checkbox>
          <el-checkbox label="Consignee" v-if="payload.table != 'consignee'"></el-checkbox>
          <el-checkbox label="Clientes"></el-checkbox>
        </el-checkbox-group>
      </el-col>
    </el-row>
    <el-row :gutter="24" v-if="!payload.hidden_btn">
      <el-col :span="24">
        <el-button type="success" :loading="loading" @click="beforeSend()" v-if="!edit"><i class="fal fa-save"></i> Guardar</el-button>
        <el-button type="primary" :loading="loading" @click="beforeSend(true)" v-if="edit"><i class="fal fa-edit"></i> Actualizar</el-button>
        <el-button @click="resetForm()" v-if="edit"><i class="fal fa-times"></i> Cancelar</el-button>
      </el-col>
    </el-row>

  </div>
</template>

<script>
export default {
  props: ["payload", "parent"],
  data() {
    return {
      loading: false,
      edit: false,
      hidde: true,
      showId: false,
      errors_data: {
        agencia_id: false,
        primer_nombre: false,
        primer_apellido: false,
        direccion: false,
        localizacion_id: false
      },
      form: {
        corporativo: false,
        // agencia_id: null,
        tipo_identificacion_id: null,
        documento: null,
        primer_nombre: null,
        segundo_nombre: null,
        primer_apellido: null,
        segundo_apellido: null,
        direccion: null,
        telefono: null,
        whatsapp: null,
        correo: null,
        emails_cc: [],
        localizacion_id: null,
        zip: null,
        cliente_id: null,
        tarifa: 0,
        emailsend: false,
        notify_client: false,
        invoice_all: false,
        zona: null
      },
      check_create: [],
      // branchs: [],
      clientes: null,
      city_selected_s: "",
      table: null
    };
  },
  computed: {
    showItem() {
      return this.parent ? false : true;
    }
  },
  created() {
    let me = this;
    bus.$on("save", function(payload) {
      me.beforeSend();
    });
    bus.$on("update", function(payload) {
      me.beforeSend(true);
    });
    bus.$on("cancel", function(payload) {
      me.resetForm();
    });
  },
  watch: {
    "payload.field_id": {
      handler(newVal, oldVal) {
        if (!this.payload.id_c) {
          if (newVal !== "null" && newVal !== null && newVal !== "") {
            this.getDataById(newVal);
          }
        }
      },
      deep: true
    },
    "payload.table": {
      handler(newVal, oldVal) {
        this.table = newVal;
      },
      deep: true
    }
  },
  mounted() {
    if (this.payload) {
      if (
        this.payload.field_id != null &&
        this.payload.field_id != "null" &&
        this.payload.field_id != ""
      ) {
        this.getDataById(this.payload.field_id);
      }
    }
    this.getSelectClient();
    if (this.payload.table === "clientes") {
      let me = this;
      me.hidde = false;
      me.form.corporativo = true;
    }
    this.table = this.payload.table;
  },
  methods: {
    beforeSend(edit) {
      this.loading = true;
      if (this.validateFields(false)) {
        // VALIDA SI ES EL FORMULARIO PARA CLIENTE Y ASIGNA LAS VARIABLES
        // CORRESPONDIENTES
        this.setFormToClient();
        if (edit) {
          this.update();
        } else {
          this.store();
        }
      }
      this.loading = false;
    },
    store() {
      let me = this;
      if (this.parent) {
        this.form.parent_id = this.parent;
      }
      axios
        .post("/" + this.table.toLowerCase(), this.form)
        .then(function(response) {
          if (response.data["code"] == 200) {
            toastr.success("Registro creado correctamente.");
            me.loading = false;
            if (me.check_create.length > 0) {
              me.table = me.check_create[0];
              me.check_create = removeItemFromArr(me.check_create, me.table);
              me.beforeSend();
            } else {
              me.resetForm();
              me.$emit("updatetable");
              bus.$emit("getData", response.data["datos"]);
            }
          } else {
            me.loading = false;
            toastr.warning(response.data["error"]);
          }
        })
        .catch(function(error) {
          console.log(error);
          me.loading = false;
          toastr.error("Error." + error, {
            timeOut: 50000
          });
        });

      var removeItemFromArr = (arr, item) => {
        return arr.filter(e => e !== item);
      };
    },
    update() {
      let me = this;
      axios
        .put("/" + this.table + "/" + this.payload.field_id, this.form)
        .then(function(response) {
          if (response.data["code"] == 200) {
            toastr.success("Registro Actualizado correctamente.");
            me.loading = false;
            me.resetForm();
            me.$emit("updatetable");
            bus.$emit("getData", response.data["datos"]);
          } else {
            me.loading = false;
            toastr.warning(response.data["error"]);
          }
        })
        .catch(function(error) {
          console.log(error);
          me.loading = false;
          toastr.error("Error." + error, {
            timeOut: 50000
          });
        });
    },
    validateFields(field) {
      let save = true;
      if (this.form.primer_nombre === null || this.form.primer_nombre === "") {
        if (!field || field === "primer_nombre") {
          this.errors_data.primer_nombre = true;
          save = false;
        }
      } else {
        this.errors_data.primer_nombre = false;
      }
      if (this.form.direccion === null || this.form.direccion === "") {
        if (!field || field === "direccion") {
          this.errors_data.direccion = true;
          save = false;
        }
      } else {
        this.errors_data.direccion = false;
      }
      if (
        this.form.localizacion_id === null ||
        this.form.localizacion_id === ""
      ) {
        if (!field || field === "localizacion_id") {
          this.errors_data.localizacion_id = true;
          save = false;
        }
      } else {
        this.errors_data.localizacion_id = false;
      }
      if (!this.form.corporativo) {
        if (
          this.form.primer_apellido === null ||
          this.form.primer_apellido === ""
        ) {
          if (!field || field === "primer_apellido") {
            this.errors_data.primer_apellido = true;
            save = false;
          }
        } else {
          this.errors_data.primer_apellido = false;
        }
      } else {
        this.errors_data.primer_apellido = false;
      }

      return save;
    },
    resetForm() {
      this.errors_data = {
        agencia_id: false,
        primer_nombre: false,
        primer_apellido: false,
        direccion: false,
        localizacion_id: false
      };
      this.form.corporativo = false;
      this.form.tipo_identificacion_id = null;
      this.form.documento = null;
      this.form.primer_nombre = null;
      this.form.segundo_nombre = null;
      this.form.primer_apellido = null;
      this.form.segundo_apellido = null;
      this.form.direccion = null;
      this.form.telefono = null;
      this.form.whatsapp = null;
      this.form.correo = null;
      this.form.emails_cc = [];
      this.form.localizacion_id = null;
      this.form.zip = null;
      this.form.cliente_id = null;
      this.form.tarifa = 0;
      this.form.emailsend = false;
      this.form.notify_client = false;
      this.form.invoice_all = false;
      this.form.zona = null;
      this.city_selected_s = this.city_selected_s === null ? "" : null;
      this.edit = false;
      this.$emit("cancel");
      if (this.parent_id) {
        bus.$emit("close");
      }
    },
    getSelectBranch: function() {
      axios.get("/agencia/getAgencies").then(response => {
        this.branchs = response.data;
        this.form.agencia_id = this.agency.id;
      });
    },
    getSelectClient: function() {
      axios.get("/clientes/all").then(response => {
        this.clientes = response.data.data;
      });
    },
    setCity(data) {
      this.form.localizacion_id = data.id;
      this.form.zona = data.name;
    },
    getDataById(id) {
      let me = this;
      me.loading = true;
      axios
        .get("/" + this.payload.table + "/getDataById/" + id)
        .then(response => {
          me.form = response.data;
          me.form.cliente_id = me.form.cliente_id + "";
          if (me.form.notify_client == 1) {
            me.form.notify_client = true;
          } else {
            me.form.notify_client = false;
          }
          if (me.form.invoice_all == 1) {
            me.form.invoice_all = true;
          } else {
            me.form.invoice_all = false;
          }
          if (this.payload.table !== "clientes") {
            if (
              response.data.cliente_id == "null" ||
              response.data.cliente_id == "" ||
              response.data.cliente_id == "undefined"
            ) {
              me.form.cliente_id = null;
            }
            if (me.form.corporativo == 1) {
              me.form.corporativo = true;
            } else {
              me.form.corporativo = false;
            }
            if (me.form.email_cc !== null) {
              let emails = me.form.email_cc;
              me.form.emails_cc = emails.split(",");
            }
          }
          me.city_selected_s = response.data.ciudad;
          me.edit = true;
          me.loading = false;
        });
    },
    setFormToClient() {
      if (this.table.toLowerCase() === "clientes") {
        this.form = {
          localizacion_id: this.form.localizacion_id,
          nombre:
            this.form.primer_nombre +
            " " +
            this.verifyNull(this.form.segundo_nombre) +
            " " +
            this.verifyNull(this.form.primer_apellido) +
            " " +
            this.verifyNull(this.form.segundo_apellido) +
            " ",
          direccion: this.form.direccion,
          telefono: this.form.telefono,
          email: this.form.correo,
          zona: this.form.zona,
          email_bcc: this.form.notify_client,
          factura_agrupada: this.form.invoice_all
        };
      }
    },
    verifyNull(field) {
      if (field == null) {
        return "";
      } else {
        return field;
      }
    }
  }
};
</script>

<style lang="css">
[class*=" el-icon-"],
[class^="el-icon-"] {
  line-height: inherit;
}
.error_field > .el-input > input,
.error_field > .el-input__inner {
  border-color: #f56c6c;
}
</style>
<style lang="css" scoped>
.checkbox_create {
  margin-top: 9px;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.help-block {
  color: #f56c6c;
  font-size: 11;
  position: absolute;
  margin-top: 0px;
  margin-bottom: 0px;
}
.el-row {
  padding-bottom: 15px;
}
</style>
