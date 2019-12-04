$(document).ready(function () {
  $.fn.editable.defaults.mode = "popup";
  $.fn.editable.defaults.params = function (params) {
    params._token = $('meta[name="csrf-token"]').attr("content");
    return params;
  };
  loadTable("tbl-tracking", false);
  loadTable("tbl-tracking-bodega", true);
  loadTableCreateReceipt();

  jQuery("#tbl-tracking")
    .on("mouseover", "tr", function () {
      jQuery(this)
        .find(".edit")
        .css("opacity", "1");
    })
    .on("mouseout", "tr", function () {
      jQuery(this)
        .find(".edit")
        .css("opacity", "0");
    });
});

function loadTable(name, bodega) {
  var table = $("#" + name).DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    lengthChange: false,
    order: [
      [0, "DESC"]
    ],
    ajax: "tracking/all/" +
      true +
      "/" +
      false +
      "/" +
      false +
      "/" +
      false +
      "/" +
      bodega,
    columns: [{
      data: "fecha",
      name: "fecha",
      render: function (data, type, full, meta) {
        return full.fecha + '<div style="color:#c6c9cb;">' + full.agencia + '</div>';
      }
    },
    {
      data: "cliente",
      name: "cliente",
      render: function (data, type, full, meta) {
        if (full.cliente === null) {
          return "";
        } else {
          return (
            '<div style="width:80%;float: left;">' +
            full.cliente +
            '</div> <div style="width:20%;float: right;"><a  data-toggle="tooltip" title="Cambiar" class="edit" style="color:#FFC107;" onclick="editConsignee(' +
            full.id +
            ')"><i class="fal fa-pencil"></i></a></div>'
          );
        }
      }
    },
    // {
    //     data: "cliente_email",
    //     name: 'cliente_email'
    // },
    {
      data: "codigo",
      name: "codigo",
      render: function (data, type, full, meta) {
        return (
          '<a data-name="codigo" data-pk="' +
          full.id +
          '" class="td_edit" data-type="text" data-placement="top" data-title="Tracking">' +
          full.codigo +
          "</a>"
        );
      }
    },
    {
      render: function (data, type, full, meta) {
        return (
          "<div>" +
          (full.num_warehouse === null ? "" : full.num_warehouse) +
          '</div><small style="color:#2196F3">' +
          (full.estatus === null ? "" : full.estatus) +
          "</small>"
        );
      },
      visible: bodega
    },
    {
      render: function (data, type, full, meta) {
        return (
          '<a data-name="contenido" data-pk="' +
          full.id +
          '" class="td_edit" data-type="textarea" data-placement="left" data-title="Contenido">' +
          (full.contenido !== null ? full.contenido : "No hay datos") +
          "</a>"
        );
      }
    },
    // {
    //     sortable: false,
    //     "render": function(data, type, full, meta) {
    //         var color = '#ccc';
    //         var label = 'Sin acción';
    //         if (full.confirmed_send == 1) {
    //             color = '#4caf50';
    //             label = 'Despachar';
    //         }
    //         return '<div style="color:' + color + '" class="text-center" data-toggle="tooltip" title="' + label + '"><i class="fal fa-flag"></i></div>';
    //     }
    // },
    {
      sortable: false,
      render: function (data, type, full, meta) {
        var btn_delete = "";
        if (permission_delete) {
          var btn_delete =
            ' <a onclick="eliminar(' +
            full.id +
            "," +
            false +
            ")\" class='delete_btn' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fal fa-trash-alt fa-lg'></i></a> ";
        }
        var btn_recall_email =
          ' <a onclick="reenviarEmail(' +
          full.consignee_id +
          ",'" +
          full.codigo +
          "')\" class='reply_btn' data-toggle='tooltip' data-placement='top' title='Reenviar Email'><i class='fal fa-reply-all'></i></a> ";
        return btn_delete;
      }
    }
    ],
    drawCallback: function () {
      $(".edit").css("opacity", "0");
      $(".td_edit").editable({
        ajaxOptions: {
          type: "post",
          dataType: "json"
        },
        url: "tracking/updateTrackingReceipt",
        validate: function (value) {
          if ($.trim(value) == "") {
            return "Este campo es obligatorio!";
          }
        },
        success: function (response, newValue) {
          objVue.updateTable();
        }
      });
    }
  });
}

function editConsignee(id) {
  objVue.tracking_id = id;
  $("#modalEditConsignee").modal("show");
}

function reenviarEmail(consignee_id, tracking) {
  objVue.reenviarEmail(consignee_id, tracking);
}

function loadTableCreateReceipt() {
  var table = $("#tbl-tracking-group").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    order: [
      [0, "DESC"]
    ],
    ajax: "tracking/getTrackingByCreateReceipt/",
    columns: [{
      data: "confirmed_send",
      name: "confirmed_send",
      render: function (data, type, full, meta) {
        return parseInt(full.confirmed_send);
      },
      visible: false
    },
    {
      data: "cliente",
      name: "cliente",
      render: function (data, type, full, meta) {
        return "<label>" + full.cliente + "</label>";
      }
    },
    {
      sortable: false,
      class: "text-center",
      render: function (data, type, full, meta) {
        var color = "success";
        // var dateObj = new Date();
        // var month = (dateObj.getMonth() + 1).toString(); //months from 1-12
        // var day = dateObj.getDate().toString();
        // var year = dateObj.getUTCFullYear();
        //
        // var mmChars = month.split('');
        // var ddChars = day.split('');
        //
        // month = (mmChars[1]?month:"0"+mmChars[0]);
        // day = (ddChars[1]?day:"0"+ddChars[0]);
        //
        // var today = year + "-" + month + "-" + day;
        if (parseInt(full.confirmed_send) !== 0) {
          color = "primary";
        }
        return (
          '<label class="badge badge-' +
          color +
          '" style="font-size: 15px;">' +
          full.cantidad +
          "</label> "
        );
      }
    },
    {
      sortable: false,
      render: function (data, type, full, meta) {
        var btn_recall_email =
          ' <a onclick="reenviarEmail(' +
          full.consignee_id +
          ",'" +
          full.trackings +
          "')\" class='reply_btn' data-toggle='tooltip' data-placement='top' title='Reenviar Email'><i class='fal fa-reply-all'></i></a> ";
        var btn_create =
          ' <a onclick="showDataToCreateReceipt(' +
          full.consignee_id +
          ", '" +
          full.cliente +
          "', " +
          full.agencia_id +
          ")\" class='btn btn-outline btn-primary btn-xs' data-toggle='tooltip' title='Crear recibo'><i class='fal fa-file-signature'></i> </a> ";
        return btn_create + " " + btn_recall_email;
      }
    }
    ]
  });
}

function showDataToCreateReceipt(consignee_id, client, agencia_id) {
  $('#agencia_id_receipt').val(agencia_id);
  if ($.fn.DataTable.isDataTable("#tbl-trackings-client")) {
    $("#tbl-trackings-client" + " tbody").empty();
    $("#tbl-trackings-client")
      .dataTable()
      .fnDestroy();
  }
  var table = $("#tbl-trackings-client").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    paging: false,
    ajax: "tracking/getTrackingByIdConsignee/" + consignee_id,
    columns: [{
      render: function (data, type, full, meta) {
        return (
          '<div class="checkbox checkbox-success"><input type="checkbox" checked="true" data-contenido="' +
          full.contenido +
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
      render: function (data, type, full, meta) {
        return (
          '<a data-name="contenido" data-pk="' +
          full.id +
          '" class="td_edit" data-type="text" data-placement="right" data-title="Contenido">' +
          (full.contenido !== null ? full.contenido : "No hay datos") +
          "</a>"
        );
      }
    }
    ],
    drawCallback: function () {
      $(".td_edit").editable({
        ajaxOptions: {
          type: "post",
          dataType: "json"
        },
        url: "tracking/updateTrackingReceipt",
        validate: function (value) {
          if ($.trim(value) == "") {
            return "Este campo es obligatorio!";
          }
        },
        success: function (response, newValue) {
          objVue.updateTable();
          refreshTable("tbl-trackings-client");
        }
      });
    }
  });
  objVue.consignee_id_doc = consignee_id;
  $("#client-tracking").html(client.toUpperCase());
  $("#modalCreateReceipt").modal("show");
}

var objVue = new Vue({
  el: "#tracking",
  created: function () {
    // this.getConsignee();
    this.getShipper();
    let me = this;
    bus.$on("getData", function (payload) {
      // me.consignee_data = payload;
      me.consignee_name = payload.nombre_full;
      me.consignee_id = {
        id: payload.id,
        name: payload.nombre_full
      };
    });
    /* CUSTOM MESSAGES VE-VALIDATOR*/
    const dict = {
      custom: {
        // consignee_id: {
        //     required: 'El cliente es obligatorio.'
        // },
        tracking: {
          required: "El tracking es obligatorio."
        }
      }
    };
    me.$validator.localize("es", dict);
  },
  data: {
    loading: false,
    consignee_id_doc: null,
    consignee_id: null,
    consignee_name: null,
    shipper_id: null,
    contenido: null,
    tracking: null,
    email: null,
    peso: null,
    piezas: 1,
    largo: 0,
    ancho: 0,
    alto: 0,
    instruccion: false,
    confirmedSend: false,
    editar: 0,
    consignees: [],
    shippers: [],
    ids_tracking: [],
    contenido_tracking: [],
    contenido_detail: null,
    consignee_name_change: null,
    errors_data: false,
    tracking_id: null,
    print_direct: (print_labels != '' && print_documents != '') ? true : false,
    print_warehouse: null,
    print_id_document: null
  },
  methods: {
    focusContent(el) {
      $('#' + el).focus();
    },
    printDocument: function (direct) {
      let me = this;
      if (direct) {
        javascript: jsWebClientPrint.print("useDefaultPrinter=false&printerName=" + print_labels + "&filetype=" + print_format + "&id=" + me.print_id_document + "&agency_id=" + agency_id + "&document=warehouse&label=true")
        setTimeout(function () {
          javascript: jsWebClientPrint.print("useDefaultPrinter=false&printerName=" + print_documents + "&filetype=" + print_format + "&id=" + me.print_id_document + "&agency_id=" + agency_id + "&document=warehouse")
        }, 1000);
      } else {
        window.open('impresion-documento-label/' + this.print_id_document + '/warehouse', '_blank');
        window.open('impresion-documento/' + this.print_id_document + '/warehouse', '_blank');
      }
    },
    openNew() {
      var data = {
        component: "form-csc",
        title: "Consignee",
        icon: "fal fa-user",
        field_id: null,
        table: "consignee",
        hidden_btn: true,
        edit: false
      };
      bus.$emit("open", data);
    },
    editConsignee() {
      this.loading = true;
      this.errors_data = false;
      if (this.consignee_id !== null) {
        let me = this;
        axios
          .post("tracking/updateTrackingReceipt", {
            pk: me.tracking_id,
            value: me.consignee_id.id,
            name: "consignee_id"
          })
          .then(function (response) {
            var res = response.data;
            if (response.data["code"] == 200) {
              $("#modalEditConsignee").modal("hide");
              toastr.success("Actualización Exitosa");
              me.updateTable();
            } else {
              toastr.warning(response.data["error"]);
            }
            me.loading = false;
          })
          .catch(function (error) {
            me.loading = false;
            console.log(error);
            toastr.error("Error." + error, {
              timeOut: 5000
            });
          });
      } else {
        this.errors_data = true;
        this.loading = false;
        toastr.warning("Seleccione un Destinatario");
      }
    },
    querySearchConsignee(queryString, cb) {
      var me = this;
      axios
        .get("/consignee/vueSelect/" + queryString)
        .then(function (response) {
          me.consignees = response.data.items;
          cb(me.consignees);
        })
        .catch(function (error) {
          console.log(error);
          toastr.warning("Error: -" + error);
        });
    },
    handleSelect(item) {
      this.consignee_id = item;
      this.consignee_name = item.name;
    },
    handleSelectChange(item) {
      this.consignee_id = item;
      this.consignee_name_change = item.name;
      this.errors_data = false;
    },
    deleteSelected() {
      this.consignee_id = null;
    },
    reenviarEmail: function (id, track) {
      axios
        .get("tracking/reenviarEmail/trackingRecibido/" + id + "/" + track)
        .then(response => {
          if (response.data.code === 200) {
            toastr.success("Email en proceso de envio....");
            this.updateTable();
          } else {
            toastr.error(response.data.error);
          }
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Error.", {
            timeOut: 50000
          });
        });
    },
    resetForm: function () {
      this.consignee_id = null;
      this.consignee_name = null;
      this.tracking = null;
      this.contenido = null;
      this.email = null;
      this.instruccion = false;
      this.confirmedSend = false;
      this.editar = 0;
    },
    validation: function (data) {
      if (data.contenido != "") {
        if (data.peso != "" && parseFloat(data.peso) > 0) {
          if (data.shipper != null) {
            return true;
          } else {
            toastr.options = {
              positionClass: "toast-top-center"
            };
            toastr.error("Selecciona un shippper para continuar.");
            return false;
          }
        } else {
          toastr.options = {
            positionClass: "toast-top-center"
          };
          toastr.error("Ingresa el peso del paquete.");
          return false;
        }
      } else {
        toastr.options = {
          positionClass: "toast-top-center"
        };
        toastr.error(
          "Ingresa el contenido en alguno de los registros de tracking seleccionados."
        );
        return false;
      }
    },
    createDocument: function () {
      var l = $("#saveDoc").ladda();
      l.ladda("start");
      let me = this;
      var datos = $("#formTrackingClient").serializeArray();
      me.ids_tracking = [];
      me.contenido_tracking = [];
      $.each(datos, function (i, field) {
        if (field.name === "chk[]") {
          if ($("#chk" + field.value).val() != "") {
            me.ids_tracking.push($("#chk" + field.value).val());
            me.contenido_tracking.push(
              $("#chk" + field.value).data("contenido")
            );
          }
        }
      });
      if (me.contenido_tracking.length > 0) {
        var myStr = me.contenido_tracking.toString();
        me.contenido_detail = myStr.replace(/,/g, ", ");
      }
      var datosForm = {
        contenido: me.contenido_detail,
        peso: me.peso,
        shipper: me.shipper_id
      };
      if (me.validation(datosForm)) {
        axios
          .post("documento/ajaxCreate/" + 1, {
            tipo_documento_id: 1,
            type_id: 1, //COURIER
            created_at: this.getTime(),
            shipper_id: me.shipper_id.id ? me.shipper_id.id : false,
            consignee_id: me.consignee_id_doc,
            agencia_id: $('#agencia_id_receipt').val()
          })
          .then(function (response) {
            var res = response.data;
            if (response.data["code"] == 200) {
              me.print_id_document = res.datos["id"];
              me.print_warehouse = res.datos["num_warehouse"];
              toastr.success(
                "Registro creado correctamente. Recibo N°: " +
                res.datos["num_warehouse"]
              );
              setTimeout(() => {
                $('#modalPrint').modal('show');
              }, 1500);
              me.createDocumentDetail(res.datos["id"]);
            } else {
              toastr.warning(response.data["error"]);
            }
            l.ladda("stop");
          })
          .catch(function (error) {
            console.log(error);
            if (error.response.status === 422) {
              me.formErrors = error.response.data; //guardo los errores
              me.listErrors = me.formErrors.errors; //genero lista de errores
            }
            $.each(me.formErrors.errors, function (key, value) {
              $(".result-" + key).html(value);
            });
            toastr.error("Porfavor completa los campos obligatorios.", {
              timeOut: 50000
            });
            l.ladda("stop");
          });
      }
      l.ladda("stop");
    },
    createDocumentDetail: function (id_document) {
      let me = this;
      axios
        .post("documento/insertDetail", {
          documento_id: id_document,
          contador: 1,
          dimensiones: me.peso + " Vol=" + me.largo + "x" + me.ancho + "x" + me.alto,
          peso: me.peso,
          peso2: me.peso,
          contenido: me.contenido_detail,
          contenido2: me.contenido_detail,
          largo: me.largo,
          ancho: me.ancho,
          alto: me.alto,
          volumen: ((me.largo * me.ancho * me.alto) / 166).toFixed(2),
          tipo_empaque_id: 3,
          posicion_arancelaria_id: 234,
          arancel_id2: 234,
          created_at: this.getTime(),
          ids_tracking: this.ids_tracking,
          shipper_id: me.shipper_id.id ? me.shipper_id.id : null,
          consignee_id: me.consignee_id_doc
        })
        .then(function (response) {
          var res = response.data;
          if (response.data["code"] == 200) {
            $("#modalCreateReceipt").modal("hide");
            me.shipper_id = null;
            me.peso = null;
            me.piezas = 1;
            me.largo = 0;
            me.ancho = 0;
            me.alto = 0;
            // toastr.success('Registro creado correctamente.');
            me.updateTable();
          } else {
            toastr.warning(response.data["error"]);
          }
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Error.", {
            timeOut: 50000
          });
        });
    },
    searchTracking: function () {
      let me = this;
      axios.get("tracking/searchTracking/" + me.tracking).then(response => {
        var datos = response.data;
        if (datos.data != null) {
          if (datos.data["consignee_id"]) {
            me.consignee_id = {
              id: datos.data["consignee_id"],
              name: datos.data["nombre_full"]
            };
            me.consignee_name = datos.data["nombre_full"];
          } else {
            me.consignee_id = null;
          }
          me.contenido = datos.data["contenido"];
          me.instruccion = datos.data["instruccion"];
          me.email = datos.data["correo"];
          me.confirmedSend = datos.data["despachar"] == 1 ? true : false;
          if (me.instruccion !== null || me.confirmedSend) {
            $(".alert-dismissible")
              .removeClass("alert-info")
              .addClass("alert-danger");
          } else {
            $(".alert-dismissible")
              .removeClass("alert-danger")
              .addClass("alert-info");
          }
        } else {
          me.create();
        }
      });
    },
    getConsignee: function () {
      let me = this;
      axios.get("tracking/getAllShipperConsignee/consignee").then(response => {
        me.consignees = response.data.data;
      });
    },
    getShipper: function () {
      let me = this;
      axios.get("tracking/getAllShipperConsignee/shipper").then(response => {
        me.shippers = response.data.data;
      });
    },
    updateTable: function () {
      refreshTable("tbl-tracking");
      refreshTable("tbl-tracking-bodega");
      refreshTable("tbl-tracking-group");
    },
    delete: function (data) {
      swal({
        title: "Seguro que desea eliminar este registro?",
        text: "No lo podras recuperar despues!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No, cancelar!"
      }).then(result => {
        if (result.value) {
          axios.delete("tracking/" + data.id).then(response => {
            this.updateTable();
            toastr.success("Registro eliminado correctamente.");
            toastr.options.closeButton = true;
          });
        }
      });
    },
    create: function () {
      const isUnique = value => {
        return axios
          .post("tracking/validar_tracking", {
            element: value
          })
          .then(response => {
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
      let me = this;
      this.$validator
        .validateAll()
        .then(result => {
          if (result) {
            axios
              .post("tracking", {
                consignee_id: this.consignee_id != null ? this.consignee_id.id : null,
                codigo: this.tracking,
                contenido: this.contenido,
                confirmed_send: this.confirmedSend,
                agencia_id: $("#agencia_id").val()
              })
              .then(function (response) {
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
              .catch(function (error) {
                console.log(error);
                toastr.warning(
                  "Error: porfavor veifica la informacion ingresada.", {
                  timeOut: 50000
                }
                );
              });
          }
        })
        .catch(function (error) {
          toastr.warning("Error: " + error);
        });
    },
    cancel: function () {
      var me = this;
      me.resetForm();
    }
  }
});