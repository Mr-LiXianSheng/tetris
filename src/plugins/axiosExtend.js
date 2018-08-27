import Vue from 'vue'
import Axios from 'axios'

import { baseInfo } from '../config'

import { Message } from 'element-ui'

Vue.prototype.$http = Axios

const timeout = 3000

const warningMsg = 'Connection abnormality, Please try later!'

Axios.defaults.timeout = timeout
Axios.defaults.headers.common['ProductionInfo-Name'] = baseInfo.name
Axios.defaults.headers.common['ProductionInfo-Developer'] = baseInfo.developer
Axios.defaults.headers.common['ProductionInfo-Version'] = baseInfo.version

function interception (fn, methods) {
  return (...args) => {
    // Request params num
    const argsLen = args.length

    // GET Request
    if (methods === 'get' && argsLen > 1) {
      const requerParams = Object.entries(args[1]).map(([key, value]) => {
        return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`
      }).join('&')

      args[0] += `?${requerParams}`

      args.pop()

      // POST Request
    } else if (methods === 'post' && argsLen > 2 && args[argsLen - 1] === true) {
      const requerParams = new FormData()

      Object.entries(args[1]).forEach(([key, value]) => {
        requerParams.append(key, value)
      })

      args.pop()
    }

    return fn.apply(this, args)
      /**
       * do something
       */
      .then(res => res)
      /**
       * deal request error
       */
      .catch(e => {
        Message({
          message: warningMsg,
          type: 'warning'
        })

        return Promise.reject(new Error(warningMsg))
      })
  }
}

Vue.prototype.$http.get = interception(Vue.prototype.$http.get, 'get')
Vue.prototype.$http.post = interception(Vue.prototype.$http.post, 'post')
