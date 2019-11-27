const wcppGetPrintersTimeout_ms = 100000; //5 sec
const wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
var clientPrinters = "";

function wcpGetPrintersOnSuccess() {
  // Display client installed printers
  if (arguments[0].length > 0) {
    if (JSON) {
      try {
        clientPrinters = JSON.parse(arguments[0]);
        objVue.printers = arguments[0];
      } catch (e) {
        alert(e.message);
      }
    }
  } else {
    // objVue.loading_prints = false;
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
  //   objVue.getPrintersSaved();
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
  el: "#setup",
  data: {
    printers: []
  },
  methods: {
    getPrints() {
      this.loading_prints = true;
      javascript: jsWebClientPrint.getPrintersInfo();
    }
  }
});
