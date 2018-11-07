require('./bootstrap');

window.Vue 	= require('vue');
window.swal = require('sweetalert2');

import vSelect 						from 'vue-select'

import es 							from 'vee-validate/dist/locale/es';
import VeeValidate, { Validator } 	from 'vee-validate';
import Spinner 						from 'vue-spinkit'
// Localize takes the locale object as the second argument (optional) and merges it.
Validator.localize('es', es);
// Install the Plugin.
Vue.use(VeeValidate);

Vue.component('Spinner', Spinner)
Vue.component('v-select', vSelect)
Vue.component('autocomplete-component', 	require('./components/AutocompleteComponent.vue'));
Vue.component('contactos-component', 		require('./components/ContactosComponent.vue'));
Vue.component('prueba-component',			require('./components/prueba.vue'));
Vue.component('master-component', 			require('./components/MasterComponent.vue'));
Vue.component('master2-component', 			require('./components/Master2Component.vue'));
Vue.component('modalshipper-component', 	require('./components/ModalShipperComponent.vue'));
Vue.component('modalconsignee-component', 	require('./components/ModalConsigneeComponent.vue'));
Vue.component('modalarancel-component', 	require('./components/ModalArancelComponent.vue'));
Vue.component('modalcargosadd-component', 	require('./components/ModalCargosAddComponent.vue'));
Vue.component('formconsolidado-component', 	require('./components/FormConsolidadoComponent.vue'));
Vue.component('modalguias-component', 		require('./components/ModalGuiasComponent.vue'));
Vue.component('modaltagdocument-component', require('./components/ModalTagDocumentComponent.vue'));
Vue.component('rigthsidebar-component', 	require('./components/RigthSidebarComponent.vue'));
Vue.component('consol_bodega-component', 	require('./components/ConsolBodegaComponent.vue'));
Vue.component('index-config', 				require('./components/config/Index.vue'));
