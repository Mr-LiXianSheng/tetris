function overLoadNotify (Vue, fn) {
  return (title = 'Warn', message = 'Abnormal data!', type = 'warning') => {
    const params = {
      title,
      message,
      type
    }

    fn.call(Vue, params)
  }
}

function overLoadMessage (Vue, fn) {
  return (message = 'Abnormal data!', type = 'warning') => {
    const params = {
      message,
      type
    }

    fn.call(Vue, params)
  }
}

export default function (Vue) {
  Vue.prototype.$notify = overLoadNotify(Vue, Vue.prototype.$notify)
  Vue.prototype.$message = overLoadMessage(Vue, Vue.prototype.$message)
}
