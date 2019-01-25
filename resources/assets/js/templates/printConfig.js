// setTimeout(function () {
//   Javascript:jsWebClientPrint.getPrinters();
// }, 300);
//var wcppGetPrintersDelay_ms = 0;
const wcppGetPrintersTimeout_ms = 10000; //10 sec
const wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
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

const wcppPingDelay_ms = 1000;

function wcppDetectOnSuccess(){
    // WCPP utility is installed at the client side
    // redirect to WebClientPrint sample page

    // get WCPP version
    var wcppVer = arguments[0];
    if(wcppVer.substring(0, 1) == "4"){
      // window.reload;
      // $('#msgInProgress').hide();
      Javascript:jsWebClientPrint.getPrinters();
      $('#msgInProgress').hide();
      $('#detected').css('display', 'block');

    }else{
      console.log('ELSE');
      wcppDetectOnFailure();
    } //force to install WCPP v4.0
}

function wcppDetectOnFailure() {
  console.log('sadasdas');
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
      loading: true
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
        }).catch(error => console.log(error))
      }
    },
});
