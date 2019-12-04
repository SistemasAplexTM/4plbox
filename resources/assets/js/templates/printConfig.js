// setTimeout(function () {
//   Javascript:jsWebClientPrint.getPrinters();
// }, 300);
//var wcppGetPrintersDelay_ms = 0;
const wcppGetPrintersTimeout_ms = 100000; //5 sec
const wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
var clientPrinters = "";

function wcpGetPrintersOnSuccess() {
  $(".load_printer").attr("disabled");

  // Display client installed printers
  if (arguments[0].length > 0) {
    // var p = arguments[0].split("|");
    // var options = '';
    // for (var i = 0; i < p.length; i++) {
    //     options += '<option value="'+p[i]+'">' + p[i] + '</option>';
    // }
    // $('#installedPrinterName').html(options);
    // $('#installedPrinterName1').html(options);
    if (JSON) {
      try {
        clientPrinters = JSON.parse(arguments[0]);
        if (clientPrinters.error) {
          alert(clientPrinters.error);
        } else {
          var options = "";
          var optionDefault = "";
          for (var i = 0; i < clientPrinters.length; i++) {
            if (clientPrinters[i].isDefault) {
              optionDefault = "<option>" + clientPrinters[i].name + "</option>";
            }
            options += "<option>" + clientPrinters[i].name + "</option>";

          }
          // $('#lstPrinters').html(options);
          $("#installedPrinterName1").html(optionDefault);
          $("#installedPrinterName").html(options);
        }
      } catch (e) {
        alert(e.message);
      }
    }
    objVue.loading_prints = false;
    // $('.load_printer').attr('disabled', false);

    // objVue.getPrint();
  } else {
    objVue.loading_prints = false;
    alert("No printers are installed in your system.");
  }
}

function wcpGetPrintersOnFailure() {
  // Do something if printers cannot be got from the client
  alert("No printers are installed in your system.");
}

const wcppPingDelay_ms = 1000;

function wcppDetectOnSuccess() {
  javascript: jsWebClientPrint.getPrintersInfo();
  objVue.getPrintersSaved();
  // WCPP utility is installed at the client side
  // redirect to WebClientPrint sample page

  // get WCPP version
  var wcppVer = arguments[0];
  if (wcppVer.substring(0, 1) == "4") {
    // window.reload;
    // $('#msgInProgress').hide();
    $("#msgInProgress").hide();
    $("#detected").css("display", "block");
  } else {
    console.log("ELSE");
    wcppDetectOnFailure();
  } //force to install WCPP v4.0
}

function wcppDetectOnFailure() {
  // It seems WCPP is not installed at the client side
  // ask the user to install it
  $("#msgInProgress").hide();
  $("#msgInstallWCPP").show();
}

function wcppDetectOnFailure() {
  // It seems WCPP is not installed at the client side
  // ask the user to install it
  $("#msgInProgress").hide();
  $("#msgInstallWCPP").show();
}

var objVue = new Vue({
  el: "#printConfig",
  data: {
    detected: false,
    loading: false,
    loading_prints: false,
    printers: []
  },
  created() {
    // this.getPrint();
  },
  methods: {
    savePrint: function () {
      let me = this;
      me.loading = true;
      var data = {
        labels: $("#installedPrinterName").val(),
        default: $("#installedPrinterName1").val()
      };
      axios
        .post("printConfig", {
          data
        })
        .then(({ data }) => {
          me.loading = false;
          if (data.code == 200) {
            me.getPrintersSaved();
            toastr.success("<div><p>" + data.data + "</p></div>");
          } else {
            toastr.warning("<div><p>" + data.data + "</p></div>");
          }
        })
        .catch(error => console.log(error));
    },
    getPrints() {
      this.loading_prints = true;
      javascript: jsWebClientPrint.getPrintersInfo();
    },
    getPrint: function () {
      axios
        .get("getConfig/print_" + agency_id)
        .then(({ data }) => {
          var printers = JSON.parse(data.value);
          $("#installedPrinterName").val(printers.prints.labels);
          $("#installedPrinterName1").val(printers.prints.default);
        })
        .catch(error => console.log(error));
    },
    getPrintersSaved: function () {
      axios
        .get("printConfig/getPrintersSaved")
        .then(({ data }) => {
          this.printers = data;
        })
        .catch(error => console.log(error));
    },
    deletePrint(id) {
      let me = this;
      axios
        .get("printConfig/deletePrinter/" + id)
        .then(({ data }) => {
          if (data.code == 200) {
            me.getPrintersSaved();
            toastr.success("<div><p>Registrado Eliminado.</p></div>");
            toastr.options.closeButton = true;
          } else {
            toastr.danger("<div><p>Error.</p></div>");
            toastr.options.closeButton = true;
          }
        })
        .catch(error => console.log(error));
    }
  }
});
