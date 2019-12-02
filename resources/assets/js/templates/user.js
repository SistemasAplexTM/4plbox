$(document).ready(function() {});
$(window).load(function() {
  getUsers(1, "user");
});

function getUsers(data, table) {
  $("#tbl-" + table).DataTable({
    ajax: "user/all/" + data,
    columns: [
      {
        data: "name",
        name: "name"
      },
      {
        data: "email",
        name: "email"
      },
      {
        data: "rol_name",
        name: "rol_name"
      },
      {
        data: "name_agencia",
        name: "name_agencia"
      },
      {
        sortable: false,
        render: function(data, type, full, meta) {
          var btn_edit = "";
          var btn_delete = "";
          var cred_id = full.rol_id;
          var age_id = full.agencia_id;
          if (full.rol_id == null) {
            cred_id = "null";
          }
          if (full.agencia_id == null) {
            age_id = "null";
          }
          if (permission_update) {
            var params = [
              full.id,
              "'" + full.name + "'",
              "'" + full.email + "'",
              cred_id,
              age_id,
              "'" + full.rol_name + "'",
              "'" + full.name_agencia + "'",
              full.actived
            ];
            var btn_edit =
              '<a onclick="edit(' +
              params +
              ")\" class='edit_btn' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fal fa-pencil fa-lg'></i></a> ";
          }
          if (permission_delete) {
            var btn_delete =
              ' <a onclick="eliminar(' +
              full.id +
              "," +
              true +
              ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
          }
          return btn_edit + btn_delete;
        }
      }
    ]
  });
}

function edit(
  id,
  name,
  email,
  rol_id,
  agencia_id,
  rol_name,
  name_agencia,
  actived
) {
  var data = {
    id: id,
    name: name,
    email: email,
    rol_id: rol_id,
    agencia_id: agencia_id,
    rol_name: rol_name,
    name_agencia: name_agencia,
    actived: actived
  };
  objVue.edit(data);
}
var objVue = new Vue({
  el: "#user",
  mounted: function() {
    this.getDataSelect();
    const dict = {
      custom: {
        name: {
          required: "El nombre es obligatorio."
        },
        email: {
          required: "El correo es obligatorio."
        },
        password: {
          required: "La contraseña es obligatoria.",
          min: (field, params) => `Debe contener minimo ${params[0]} caracteres`
        },
        password_confirm: {
          required: "La confirmación de la contraseña es obligatoria.",
          confirmed: "La confirmación de la contraseña no coincide."
        },
        rol_id: {
          required: "El rol es obligatorio."
        },
        agencia_id: {
          required: "La dirección es obligatoria."
        }
      }
    };
    this.$validator.localize("es", dict);
  },
  data: {
    name: null,
    email: null,
    password: null,
    password_confirm: null,
    rol_id: null,
    agencia_id: null,
    actived: false,
    editar: 0,
    mostrar_password: true,
    changue_password: false,
    agencias: [],
    roles: [],
    formErrors: {},
    listErrors: {}
  },
  methods: {
    getUsersTable(op, table) {
      if (!$.fn.DataTable.isDataTable("#tbl-" + table)) {
        getUsers(op, table);
      }
    },
    resetForm: function() {
      this.id = null;
      this.email = null;
      this.name = null;
      this.password = null;
      this.password_confirm = null;
      this.rol_id = null;
      this.agencia_id = null;
      this.actived = false;
      this.editar = 0;
      this.mostrar_password = true;
      this.changue_password = false;
      this.formErrors = {};
      this.listErrors = {};
    },
    getDataSelect: function() {
      axios.get("user/getDataSelect/agencia").then(response => {
        this.agencias = response.data.data;
        if (this.agencias.length == 1) {
          this.agencia_id = {
            id: this.agencias[0].id,
            name: this.agencias[0].name
          };
        }
      });
      axios.get("user/getDataSelect/roles").then(response => {
        this.roles = response.data.data;
      });
    },
    /* metodo para eliminar el error de los campos del formulario cuando dan clic sobre el */
    deleteError: function(element) {
      let me = this;
      $.each(me.listErrors, function(key, value) {
        if (key !== element) {
          me.listErrors[key] = value;
        } else {
          me.listErrors[key] = false;
        }
      });
    },
    rollBackDelete: function(data) {
      var urlRestaurar = "user/restaurar/" + data.id;
      axios.get(urlRestaurar).then(response => {
        toastr.success("Registro restaurado.");
        this.updateTable();
      });
    },
    updateTable: function() {
      refreshTable("tbl-user");
      if ($.fn.DataTable.isDataTable("#tbl-user_agency")) {
        refreshTable("tbl-user_agency");
      }
      if ($.fn.DataTable.isDataTable("#tbl-users_locker")) {
        refreshTable("tbl-users_locker");
      }
    },
    delete: function(data) {
      this.formErrors = {};
      this.listErrors = {};
      if (data.logical === true) {
        axios
          .get("user/delete/" + data.id + "/" + data.logical)
          .then(response => {
            this.updateTable();
            toastr.success(
              "<div><p>Registro eliminado exitosamente.</p><button type='button' onclick='deshacerEliminar(" +
                data.id +
                ")' id='okBtn' class='btn btn-xs btn-danger pull-right'><i class='fal fa-reply'></i> Restaurar</button></div>"
            );
            toastr.options.closeButton = true;
          })
          .catch(function(error) {
            console.log(error);
            if (error.response.status === 403) {
              toastr.error("No tienes permisos para realizar esta acción.", {
                timeOut: 50000
              });
            } else {
              toastr.error("Error." + error, {
                timeOut: 50000
              });
            }
          });
      } else {
        axios
          .delete("user/" + data.id)
          .then(response => {
            this.updateTable();
            toastr.success("Registro eliminado correctamente.");
            toastr.options.closeButton = true;
          })
          .catch(function(error) {
            console.log(error);
            if (error.response.status === 403) {
              toastr.error("No tienes permisos para realizar esta acción.", {
                timeOut: 50000
              });
            } else {
              toastr.error("Error." + error, {
                timeOut: 50000
              });
            }
          });
      }
    },
    create: function() {
      const isUnique = value => {
        return axios
          .post("user/validarUsername", {
            email: value
          })
          .then(response => {
            // Notice that we return an object containing both a valid property and a data property.
            return {
              valid: response.data.valid,
              data: {
                message: response.data.message
              }
            };
          });
      };
      // The messages getter may also accept a third parameter that includes the data we returned earlier.
      this.$validator.extend("unique", {
        validate: isUnique,
        getMessage: (field, params, data) => {
          return data.message;
        }
      });
      this.$validator
        .validateAll()
        .then(result => {
          if (result) {
            var activo = 0;
            if (this.actived) {
              activo = 1;
            }
            let me = this;
            axios
              .post("user", {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirm: this.password_confirm,
                rol_id: this.rol_id.id,
                agencia_id: this.agencia_id.id,
                actived: activo
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
                me.updateTable();
              })
              .catch(function(error) {
                if (error.response.status === 403) {
                  toastr.error(
                    "No tienes permisos para realizar esta acción.",
                    {
                      timeOut: 50000
                    }
                  );
                  return;
                }
                if (error.response.status === 422) {
                  console.log("test;: ", error.response.data);
                  me.formErrors = error.response.data; //guardo los errores
                  me.listErrors = me.formErrors.errors; //genero lista de errores
                }
                $.each(me.formErrors.errors, function(key, value) {
                  $(".result-" + key).html(value);
                });
                toastr.error("Error al registrar.", {
                  timeOut: 50000
                });
              });
          }
        })
        .catch(function(error) {
          toastr.warning("Error: Completa los campos obligatorios.");
        });
    },
    update: function() {
      this.$validator
        .validateAll(["agencia_id", "name", "email", "rol_id"])
        .then(result => {
          if (result) {
            var activo = 0;
            if (this.actived) {
              activo = 1;
            }
            var me = this;
            var data = {
              name: this.name,
              email: this.email,
              rol_id: this.rol_id.id,
              agencia_id: this.agencia_id.id,
              actived: activo
            };
            if (this.changue_password != null) {
              data["password"] = this.password;
              data["password_confirm"] = this.password_confirm;
            }
            console.log(data);
            axios
              .put("user/" + this.id, {
                data
              })
              .then(function(response) {
                if (response.data["code"] == 200) {
                  toastr.success("Registro Actualizado correctamente");
                  toastr.options.closeButton = true;
                  me.editar = 0;
                } else {
                  toastr.warning(response.data["error"]);
                  toastr.options.closeButton = true;
                  console.log(response.data);
                }
                me.resetForm();
                me.updateTable();
              })
              .catch(function(error) {
                if (error.response.status === 403) {
                  toastr.error(
                    "No tienes permisos para realizar esta acción.",
                    {
                      timeOut: 50000
                    }
                  );
                  return;
                }
                if (error.response.status === 422) {
                  me.formErrors = error.response.data;
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
          console.log(error);
          toastr.warning("Error: " + error);
        });
    },
    edit: function(data) {
      this.id = data["id"];
      this.name = data["name"];
      this.email = data["email"];
      if (data["agencia_id"] == null) {
        this.agencia_id = null;
      } else {
        this.agencia_id = {
          id: data["agencia_id"],
          name: data["name_agencia"]
        };
      }
      if (data["rol_id"] == null) {
        this.rol_id = null;
      } else {
        this.rol_id = {
          id: data["rol_id"],
          name: data["rol_name"]
        };
      }
      if (data["actived"] == 1) {
        this.actived = true;
      } else {
        this.actived = false;
      }
      this.editar = 1;
      this.mostrar_password = false;
      this.changue_password = true;
      this.formErrors = {};
      this.listErrors = {};
    },
    cancel: function() {
      var me = this;
      me.resetForm();
    },
    changePassword: function() {
      this.changue_password = false;
    }
  }
});
