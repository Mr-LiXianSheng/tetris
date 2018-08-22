<template>
  <div id="main-container">
    this is multiplayer tetris game
  </div>
</template>

<script>
export default {
  data () {
    return {
    }
  },
  methods: {
  },
  created () {
    const { $http: { get } } = this

    get('/api/test.php').then(res => {
      console.error(res)
    })
  }
}
</script>

<style lang="less">
#main-container {
}
</style>
