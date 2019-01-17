setTimeout(function () {
  Javascript:jsWebClientPrint.getPrinters();
}, 300);
//var wcppGetPrintersDelay_ms = 0;
var wcppGetPrintersTimeout_ms = 10000; //10 sec
var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
function wcpGetPrintersOnSuccess() {
  // Display client installed printers
  if (arguments[0].length > 0) {
    var p = arguments[0].split("|");
    var options = '';
    for (var i = 0; i < p.length; i++) {
        options += '<option value="'+p[i]+'">' + p[i] + '</option>';
    }
    $('#installedPrinterName').html(options);
    $('#installedPrinterName1').html(options);

    objVue.getPrint();

  } else {
    alert("No printers are installed in your system.");
  }
}
function wcpGetPrintersOnFailure() {
  // Do something if printers cannot be got from the client
  alert("No printers are installed in your system.");
}

var objVue = new Vue({
    el: '#printConfig',
    data: {

    },
    created(){
      // this.getPrint();
    },
    methods: {
      savePrint: function() {
        var data = {
          labels: $('#installedPrinterName').val(),
          default: $('#installedPrinterName1').val()
        }
        axios.post('printConfig', { data }).then(response => {
          toastr.success("<div><p>Registrado exitosamente.</p></div>");
          toastr.options.closeButton = true;
        }).catch(error => console.log(error))
      },
      getPrint: function() {
        axios.get('getConfig/print_' + agency_id).then(({data}) => {
          var printers = JSON.parse(data.value);
          $('#installedPrinterName').val(printers.prints.labels)
          $('#installedPrinterName1').val(printers.prints.default)
          console.log(printers.prints.labels);
        }).catch(error => console.log(error))
      }
    },
});
