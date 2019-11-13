require('./bootstrap');

window.Vue = require('vue');

window.swal = require('sweetalert2');

import vSelect from 'vue-select'

import es from 'vee-validate/dist/locale/es';
import VeeValidate, {
  Validator
} from 'vee-validate';
const config = {
  fieldsBagName: 'fieldBags'
}
import Element from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'
import Spinner from 'vue-spinkit'
import 'element-ui/lib/theme-chalk/index.css';
// Localize takes the locale object as the second argument (optional) and merges it.
Validator.localize('es', es);
// Install the Plugin.
Vue.use(VeeValidate, config);

Vue.use(Element, {
  locale
})

import VueGoodWizard from 'vue-good-wizard';

Vue.use(VueGoodWizard)

import ElInputTag from 'el-input-tag'
Vue.use(ElInputTag)

Vue.component('Spinner', Spinner)
Vue.component('v-select', vSelect)
Vue.component('autocomplete-component', require('./components/AutocompleteComponent.vue'));
Vue.component('contactos-component', require('./components/ContactosComponent.vue'));
Vue.component('search-result', require('./components/SearchResult.vue'));
Vue.component('master-component', require('./components/MasterComponent.vue'));
Vue.component('master2-component', require('./components/Master2Component.vue'));
Vue.component('modalshipper-component', require('./components/ModalShipperComponent.vue'));
Vue.component('modalconsignee-component', require('./components/ModalConsigneeComponent.vue'));
Vue.component('modalarancel-component', require('./components/ModalArancelComponent.vue'));
Vue.component('modalcargosadd-component', require('./components/ModalCargosAddComponent.vue'));
Vue.component('formconsolidado-component', require('./components/FormConsolidadoComponent.vue'));
Vue.component('modalguias-component', require('./components/ModalGuiasComponent.vue'));
Vue.component('modaltagdocument-component', require('./components/ModalTagDocumentComponent.vue'));
Vue.component('modalreciboentrega-component', require('./components/ModalReciboEntregaComponent.vue'));
Vue.component('rigthsidebar-component', require('./components/RigthSidebarComponent.vue'));
Vue.component('right-sidebar', require('./components/RightSidebar.vue'));
Vue.component('consol_bodega-component', require('./components/ConsolBodegaComponent.vue'));
Vue.component('config-component', require('./components/config/Index.vue'));
Vue.component('agency-integrations-component', require('./components/agency_integrations/Index.vue'));
Vue.component('points-component', require('./components/ModalPointsComponent.vue'));
Vue.component('city-component', require('./components/CityComponent.vue'));
Vue.component('cuba-component', require('./components/cuba/Index.vue'));
Vue.component('products-cuba-component', require('./components/cuba/ModalProductsComponent.vue'));
Vue.component('invoice-component', require('./components/invoice/Index.vue'));
Vue.component('modal-cambiar-status-consolidado', require('./components/ModalCambiarStatusConsolidado.vue'));
Vue.component('status-component', require('./components/Status.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('shipper-consignee-select', require('./components/ShipperConsigneeSelect.vue'));
//
Vue.component('form-csc', require('./components/FormConsigneeShipperClient.vue'));
Vue.component('menu-component', require('./components/menu/Index.vue'));
Vue.component('sidebar-component', require('./components/menu/Sidebar.vue'));
Vue.component('right-menu', require('./components/RightMenu/RightMenu.vue'));
Vue.component('agency-form-url-component', require('./components/agency_url/FormURL.vue'));
Vue.component('agency-list-url-component', require('./components/agency_url/ListURL.vue'));
Vue.component('report-agency-component', require('./components/report_agency/Index.vue'));

//documento
Vue.component('document-filter', require('./components/DocumentFilter.vue'));
Vue.component('document-upload', require('./components/DocumentUpload.vue'));
Vue.component('document-group', require('./components/document/index/GroupDocuments.vue'));
Vue.component('add-contact', require('./components/AddContactComponent.vue'));
Vue.component('modal-change', require('./components/consolidado/ModalChangeShipperConsignee.vue'));
Vue.component('change_agency', require('./components/document/form/ChangeAgency.vue'));
Vue.component('casillero', require('./components/Casillero.vue'));