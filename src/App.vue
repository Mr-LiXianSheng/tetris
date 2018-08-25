<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="less">
@import './assets/style/color.less';

#app {
  height: 100%;
  width: 1400px;
  margin: auto;
  box-shadow: 0 0 3px @main-color;
}
</style>
