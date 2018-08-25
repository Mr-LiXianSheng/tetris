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
  props: ['rowNavBar'],
  data () {
    return {
    }
  },
  methods: {
    handleClick (index) {
      const { rowNavBar } = this

      rowNavBar.activeIndex = index

      this.$emit('change', rowNavBar.buttons[index])
    }
  },
  created () {
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
