// setTimeout(function () {
//   Javascript:jsWebClientPrint.getPrinters();
// }, 300);
//var wcppGetPrintersDelay_ms = 0;
const wcppGetPrintersTimeout_ms = 100000; //5 sec
const wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
var clientPrinters = '';

function wcpGetPrintersOnSuccess() {
  var l = Ladda.create(document.querySelector('.ladda-button'));
  l.start();
  $('.load_printer').attr('disabled');

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
          alert(clientPrinters.error)
        } else {
          var options = '';
          for (var i = 0; i < clientPrinters.length; i++) {
            options += '<option>' + clientPrinters[i].name + '</option>';
          }
          // $('#lstPrinters').html(options);
          $('#installedPrinterName').html(options);
          $('#installedPrinterName1').html(options);
        }
      } catch (e) {
        alert(e.message)
      }
    }
    l.stop();
    // $('.load_printer').attr('disabled', false);

    // objVue.getPrint();

  } else {
    l.stop();
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
    $('#msgInProgress').hide();
    $('#detected').css('display', 'block');
  } else {
    console.log('ELSE');
    wcppDetectOnFailure();
  } //force to install WCPP v4.0
}

function wcppDetectOnFailure() {
  // It seems WCPP is not installed at the client side
  // ask the user to install it
  $('#msgInProgress').hide();
  $('#msgInstallWCPP').show();
}


function wcppDetectOnFailure() {
  // It seems WCPP is not installed at the client side
  // ask the user to install it
  $('#msgInProgress').hide();
  $('#msgInstallWCPP').show();
}

var objVue = new Vue({
  el: '#printConfig',
  data: {
    detected: false,
    loading: true,
    printers: []
  },
  created() {
    // this.getPrint();
  },
  methods: {
    savePrint: function () {
      var data = {
        labels: $('#installedPrinterName').val(),
        default: $('#installedPrinterName1').val()
      }
      axios.post('printConfig', {
        data
      }).then(response => {
        toastr.success("<div><p>Registrado exitosamente.</p></div>");
        toastr.options.closeButton = true;
      }).catch(error => console.log(error))
    },
    getPrint: function () {
      axios.get('getConfig/print_' + agency_id).then(({
        data
      }) => {
        var printers = JSON.parse(data.value);
        $('#installedPrinterName').val(printers.prints.labels)
        $('#installedPrinterName1').val(printers.prints.default)
      }).catch(error => console.log(error))
    },
    getPrintersSaved: function () {
      axios.get('printConfig/getPrintersSaved').then(({
        data
      }) => {
        this.printers = data
        console.log(data);

      }).catch(error => console.log(error))
    },
    deletePrint(id) {
      axios.get('printConfig/deletePrinter/' + id).then(({
        data
      }) => {
        toastr.success("<div><p>Registrado Eliminado.</p></div>");
        toastr.options.closeButton = true;

      }).catch(error => console.log(error))
    }
  },
});