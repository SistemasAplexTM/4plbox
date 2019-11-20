<template>
  <div>
    <h3>Datos generales y dirección de envío</h3>
    <hr />
    <el-form
      :model="ruleForm"
      status-icon
      :rules="rules"
      ref="ruleForm"
      label-position="top"
      label-width="120px"
      class="demo-ruleForm"
      size="small"
    >
      <el-form-item prop="corporativo">
        <el-switch
          v-model="ruleForm.corporativo"
          active-text="Corporativo"
          inactive-text="Personal"
          active-icon-class="el-icon-office-building"
          inactive-icon-class="el-icon-user"
        ></el-switch>
      </el-form-item>

      <el-row :gutter="20">
        <el-col :span="number_name">
          <el-form-item prop="first_name">
            <label
              class="label-class"
            >{{ !ruleForm.corporativo ? 'Primer Nombre' : 'Razón social' }}</label>
            <el-input
              type="text"
              v-model="ruleForm.first_name"
              autocomplete="off"
              :placeholder="[ruleForm.corporativo ? 'Razón social' : 'Primer nombre']"
            ></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="number_name">
          <el-form-item prop="last_name" v-if="!ruleForm.corporativo">
            <label class="label-class">Primer Apellido</label>
            <el-input
              type="text"
              v-model="ruleForm.last_name"
              autocomplete="off"
              placeholder="Primer Apellido"
            ></el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item prop="city">
            <label class="label-class">
              Ciudad
              <small>(seleccione una ciudad de la lista)</small>
            </label>
            <city-component @get="ruleForm.city = $event.id" :reset="reset_city" autocomplete="off"></city-component>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item prop="address">
            <label class="label-class">Dirección</label>
            <el-input
              type="text"
              v-model="ruleForm.address"
              autocomplete="off"
              placeholder="Dirección"
            ></el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item prop="postal_code">
            <label class="label-class">Código Postal</label>
            <el-input
              type="text"
              v-model="ruleForm.postal_code"
              autocomplete="off"
              placeholder="Código Postal"
            ></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item prop="phone">
            <label class="label-class">Celular</label>
            <el-input type="text" v-model="ruleForm.phone" autocomplete="off" placeholder="Celular"></el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item prop="email">
            <label class="label-class">E-mail</label>
            <el-input
              type="email"
              v-model="ruleForm.email"
              autocomplete="off"
              placeholder="E-mail (será tu usuario)"
            ></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item prop="email_confirm">
            <label class="label-class">Confirmar E-mail</label>
            <el-input
              type="email"
              v-model="ruleForm.email_confirm"
              autocomplete="off"
              placeholder="Confirmar E-mail"
            ></el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-form-item prop="terms">
        <el-checkbox v-model="ruleForm.terms">He leído los Términos y condiciones generales.</el-checkbox>
      </el-form-item>
      <el-form-item prop="info">
        <el-checkbox
          v-model="ruleForm.info"
        >Deseo recibir información de mi casillero y de mi interés.</el-checkbox>
      </el-form-item>

      <el-form-item class="btn-form">
        <el-button type="success" @click="submitForm('ruleForm')" :loading="loading">
          <i class="fal fa-user" aria-hidden="true"></i> Crear Casillero
        </el-button>
        <!-- <el-button @click="resetForm('ruleForm')">Reset</el-button> -->
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
// import DisableAutocomplete from "vue-disable-autocomplete";
// Vue.use(DisableAutocomplete);
export default {
  props: ["agencia_id"],
  data() {
    var mailformat = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    var validate_email = (rule, value, callback) => {
      if (value === null) {
        callback(new Error("Campo obligatorio"));
      } else if (!mailformat.test(value)) {
        callback(new Error("No es un E-mail valido"));
      } else {
        let me = this;
        axios
          .post("validar/validar_email", {
            element: value,
            agencia_id: me.agencia_id
          })
          .then(response => {
            if (!response.data.valid) {
              callback(new Error(response.data.message));
            }
            if (me.ruleForm.email_confirm !== null) {
              me.$refs.ruleForm.validateField("email_confirm");
            }
            callback();
          });
      }
    };
    var validate_email_confirm = (rule, value, callback) => {
      if (value === null) {
        callback(new Error("Campo obligatorio"));
      } else if (value !== this.ruleForm.email) {
        callback(new Error("Los E-mails no coinciden!"));
      } else {
        callback();
      }
    };
    return {
      number_name: 12,
      loading: false,
      reset_city: false,
      ruleForm: {
        corporativo: false,
        first_name: null,
        last_name: null,
        city: null,
        address: null,
        postal_code: null,
        phone: null,
        email: null,
        email_confirm: null,
        terms: "",
        info: false
      },
      rules: {
        first_name: [
          { required: true, message: "Campo obligatorio", trigger: "blur" }
        ],
        last_name: [
          { required: true, message: "Campo obligatorio", trigger: "blur" }
        ],
        city: [
          { required: true, message: "Campo obligatorio", trigger: "change" }
        ],
        address: [
          { required: true, message: "Campo obligatorio", trigger: "blur" }
        ],
        postal_code: [
          { required: true, message: "Campo obligatorio", trigger: "blur" }
        ],
        phone: [
          { required: true, message: "Campo obligatorio", trigger: "blur" }
        ],
        email: [{ validator: validate_email, trigger: "blur" }],
        email_confirm: [{ validator: validate_email_confirm, trigger: "blur" }],
        terms: [
          {
            required: true,
            message: "Debe aceptar las condiciones para poder continuar.",
            trigger: "blur"
          }
        ]
      }
    };
  },
  watch: {
    "ruleForm.corporativo": {
      handler(newVal, oldVal) {
        if (newVal) {
          this.number_name = 24;
        } else {
          this.number_name = 12;
        }
      },
      deep: true
    },
    "ruleForm.city": {
      handler(newVal, oldVal) {
        if (this.reset_city) {
          this.reset_city = false;
        }
      },
      deep: true
    },
    ruleForm: {
      handler(nv, ov) {
        if (nv.first_name !== null) {
          this.ruleForm.first_name = nv.first_name.toUpperCase();
        }
        if (nv.last_name !== null) {
          this.ruleForm.last_name = nv.last_name.toUpperCase();
        }
        if (nv.address !== null) {
          this.ruleForm.address = nv.address.toUpperCase();
        }
        if (nv.email !== null) {
          this.ruleForm.email = nv.email.toLowerCase().trim();
        }
        if (nv.email_confirm !== null) {
          this.ruleForm.email_confirm = nv.email_confirm.toLowerCase().trim();
        }
      },
      deep: true
    }
  },
  methods: {
    submitForm(formName) {
      this.loading = true;
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.create();
        } else {
          this.loading = false;
          console.log("error submit!!");
          return false;
        }
      });
    },
    create() {
      let me = this;
      axios
        .post("../registro", {
          agencia_id: this.agencia_id,
          // 'listId': this.listId.id_list,
          localizacion_id: this.ruleForm.city,
          primer_nombre: this.ruleForm.first_name,
          primer_apellido: this.ruleForm.last_name,
          direccion: this.ruleForm.address,
          telefono: this.ruleForm.phone,
          celular: this.ruleForm.phone,
          correo: this.ruleForm.email,
          zip: this.ruleForm.postal_code,
          // tarifa: this.ruleForm.tarifa,
          estatus: 1,
          casillero: 0,
          acepta_condiciones: this.ruleForm.terms,
          recibir_info: this.ruleForm.info
        })
        .then(function(response) {
          if (response.data["code"] == 200) {
            toastr.success("Registro creado correctamente.");
            toastr.options.closeButton = true;
            window.location = response.data["url"];
            me.resetForm("ruleForm");
          } else {
            toastr.warning(
              response.data["error"] + " - " + response.data["message"]
            );
            toastr.options.closeButton = true;
          }
          me.loading = false;
        })
        .catch(function(error) {
          me.loading = false;
          toastr.warning(
            response.data["error"] + " - " + response.data["message"]
          );
          console.log(error);
        });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
      setTimeout(() => {
        this.reset_city = true;
      }, 200);
    }
  }
};
</script>

<style lang="css" scoped>
.label-class {
  margin-bottom: 0px;
}
.el-form-item {
  margin-bottom: 15px;
}
.btn-form {
  margin-top: 20px;
}
</style>
<style lang="css">
.el-icon-circle-check {
  color: #1cc529;
}
</style>