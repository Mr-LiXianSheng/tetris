function overLoadNotify (Vue, fn) {
  return (title = 'Warn', message = 'Abnormal data!', type = 'error') => {
    const params = {
      title,
      message,
      type
    }

    fn.call(Vue, params)
  }
}

export default function (Vue) {
  Vue.prototype.$notify = overLoadNotify(Vue, Vue.prototype.$notify)
}
