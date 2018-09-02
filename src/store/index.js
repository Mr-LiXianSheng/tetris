import Vue from 'vue'
import Vuex from 'vuex'

// const $http = Vue.prototype.$http

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // interactive token, get it by login response
    userBaseInfo: {}
  },
  mutations: {
    // set user base info, get it by login response
    setUserBaseInfo (state, token) {
      state.userBaseInfo = token
    }
  },
  actions: {
  }
})
