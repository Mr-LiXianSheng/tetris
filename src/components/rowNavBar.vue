<template>
  <div class="row-nav-bar">
    <div class="nav-bar"
      v-for="(button, index) in rowNavBar.buttons"
      :key="button.value + index"
      :class="{'active': rowNavBar.activeIndex === index}"
      @click="handleClick(index)">
      {{ button.label }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'RowNavBar',
  props: ['rowNavBar', 'value'],
  data () {
    return {
    }
  },
  methods: {
    /**
     * @description             处理按钮点击事件
     * @return     {undefined}  无返回值
     */
    handleClick (index) {
      const { rowNavBar, rowNavBar: { activeIndex } } = this

      if (index === activeIndex) return

      rowNavBar.activeIndex = index

      const { emitInput, emitChange } = this

      emitInput(rowNavBar.buttons[index].value)

      emitChange(rowNavBar.buttons[index])
    },
    /**
     * @description        抛出input事件
     * @return     {this}  vue实例
     */
    emitInput (value) {
      return this.$emit('input', value)
    },
    /**
     * @description        抛出change事件
     * @return     {this}  vue实例
     */
    emitChange (change) {
      return this.$emit('change', change)
    }
  },
  created () {
    const { value, rowNavBar, rowNavBar: { buttons, activeIndex } } = this

    const { emitInput, emitChange } = this

    /**
     * check weather set activeIndex
     * if set activeIndex and value is also exist
     * and activeIndex's value doesn't equal value emit change
     */
    if (typeof activeIndex === 'number') {
      buttons[activeIndex] && emitInput(buttons[activeIndex].value) &&
        value && (buttons[activeIndex].value !== value) &&
        emitChange(buttons[activeIndex])
    } else if (value) {
      /**
       * if only set value
       * refresh activeIndex
       */
      rowNavBar.activeIndex = buttons.findIndex(({value: tmpValue}) => tmpValue === value)
    }
  }
}
</script>

<style lang="less">
@import '../assets/style/color.less';

.row-nav-bar {
  display: flex;
  flex-direction: row;
  height: 40px;
  line-height: 40px;
  box-shadow: 0 0 3px @main-color;
  font-weight: bold;
  text-align: center;
  font-size: 16px;
  overflow: hidden;
  color: @main-color;

  .nav-bar {
    flex: 1;
    cursor: pointer;
    box-shadow: 0 0 3px @main-color;
    transition: all 0.3s;
    box-sizing: border-box;

    &:hover {
      background-color: @main-color;
      color: #fff;
    }

    &:active {
      border: 1px solid darken(@main-color, 10);
    }

    &.active {
      background-color: @main-color;
      color: #fff;
    }
  }
}
</style>
