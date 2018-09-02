// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

// Element-UI
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

// axiosExtend
import './plugins/axiosExtend'

// elementUI functions Extend
import elementUIExtend from './plugins/elementUIExtend'

// some global components
import GlobalComponents from './components/index'

// Vuex store
import store from './store'

// style
import './assets/style/common.less'
import './assets/style/class.less'

// icon
import './assets/font/iconfont.css'

Vue.use(ElementUI)

Vue.use(elementUIExtend)

Vue.use(GlobalComponents)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
