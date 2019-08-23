var clientPrinters = null;

var wcppGetPrintersTimeout_ms = 10000; //10 sec
var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

var wcppPingDelay_ms = 5000;

function wcppDetectOnSuccess(){
    // WCPP utility is installed at the client side
    // redirect to WebClientPrint sample page

    // get WCPP version
    var wcppVer = arguments[0];
    if(wcppVer.substring(0, 1) == "4")
    console.log('ok WCP');
        // window.location.href = '{{action('HomeController@samples')}}';
    else //force to install WCPP v4.0
        wcppDetectOnFailure();
}

function wcppDetectOnFailure() {
    // It seems WCPP is not installed at the client side
    // ask the user to install it
    $('#msgInProgress').hide();
    $('#msgInstallWCPP').show();
}

function wcpGetPrintersOnSuccess() {
    $('#spinner').css('visibility', 'hidden');
    // Display client installed printers
    if (arguments[0].length > 0) {
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
                    $('#lstPrinters').html(options);
                    $('#lstPrinters').focus();

                    showSelectedPrinterInfo();
                }
            } catch (e) {
                alert(e.message)
            }
        }


    } else {
        alert("No printers are installed in your system.");
    }
}
function wcpGetPrintersOnFailure() {
    $('#spinner').css('visibility', 'hidden');
    // Do something if printers cannot be got from the client
    alert("No printers are installed in your system.");
}


function showSelectedPrinterInfo() {
    // get selected printer index
    var idx = $("#lstPrinters")[0].selectedIndex;
    // get supported trays
    var options = '';
    for (var i = 0; i < clientPrinters[idx].trays.length; i++) {
        options += '<option>' + clientPrinters[idx].trays[i] + '</option>';
    }
    $('#lstPrinterTrays').html(options);
    // get supported papers
    options = '';
    for (var i = 0; i < clientPrinters[idx].papers.length; i++) {
        options += '<option>' + clientPrinters[idx].papers[i] + '</option>';
    }
    $('#lstPrinterPapers').html(options);
    // additional info...
    $('#txtPortName').val(clientPrinters[idx].portName);
    $('#txtHRes').val(clientPrinters[idx].hRes);
    $('#txtVRes').val(clientPrinters[idx].vRes);
    $('#isConnected').attr('class', (clientPrinters[idx].isConnected ? 'label label-info glyphicon glyphicon-ok' : 'label label-danger glyphicon glyphicon-remove'));
    $('#isDefault').attr('class', (clientPrinters[idx].isDefault ? 'label label-info glyphicon glyphicon-ok' : 'label label-danger glyphicon glyphicon-remove'));
    $('#isBIDIEnabled').attr('class', (clientPrinters[idx].isBIDIEnabled ? 'label label-info glyphicon glyphicon-ok' : 'label label-danger glyphicon glyphicon-remove'));
    $('#isLocal').attr('class', (clientPrinters[idx].isLocal ? 'label label-info glyphicon glyphicon-ok' : 'label label-danger glyphicon glyphicon-remove'));
    $('#isNetwork').attr('class', (clientPrinters[idx].isNetwork ? 'label label-info glyphicon glyphicon-ok' : 'label label-danger glyphicon glyphicon-remove'));
    $('#isShared').attr('class', (clientPrinters[idx].isShared ? 'label label-info glyphicon glyphicon-ok' : 'label label-danger glyphicon glyphicon-remove'));

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
