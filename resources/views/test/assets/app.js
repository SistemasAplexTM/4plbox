var sp = new SilentPrint();
sp.init(initSuccess, initFail);
function initSuccess() {
  sp.getMachineId(getMachineIdSuccess);
console.log('Todo mostro');
}
function initFail() {
 console.log('Paila');
}
function getMachineIdSuccess(machineId) {
  console.log('Maquina id: ', machineId);
}
