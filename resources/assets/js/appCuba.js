require('./bootstrap');

window.Vue = require('vue');

import Element from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';
// Localize takes the locale object as the second argument (optional) and merges it.
Vue.use(Element, {
    locale
})

import VueGoodWizard from 'vue-good-wizard';

Vue.use(VueGoodWizard)

Vue.component('city-component', require('./components/CityComponent.vue'));
Vue.component('cuba-component', require('./components/cuba/Index.vue'));