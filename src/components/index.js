import inputWithButton from './inputWithButton.vue'

import rowNavBar from './rowNavBar.vue'

import tableWithSlot from './table/tableWithSlot.vue'

import tableColumn from './table/tableColumn.vue'

export default {
  install: (Vue) => {
    Vue.component('inputWithButton', inputWithButton)
    Vue.component('rowNavBar', rowNavBar)
    Vue.component('tableWithSlot', tableWithSlot)
    Vue.component('tableColumn', tableColumn)
  }
}
