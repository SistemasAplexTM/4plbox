import Vue from 'vue'
import Vuex from 'vuex'
import 'es6-promise/auto'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    rightMenu: {
      active: false,
      component: null,
      title: null,
      icon: null
    },
  },
  mutations: {
    openRightMenu (state, payload) {
      state.rightMenu = payload
    },
    closeRightMenu (state, payload) {
      state.rightMenu = {
        active: false,
        component: null,
        title: null,
        icon: null
      }
    },
  },
  actions: {

  },
  getters: {
    rightMenu: state => state.rightMenu,
  },
})
