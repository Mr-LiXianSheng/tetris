<template>
  <div id="app">
    <canvas-b-g />
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="less">
#app {
  height: 100%;
  width: 1400px;
  margin: auto;
}
</style>
