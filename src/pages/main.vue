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
    const { $http: { post } } = this

    post('/api/registration/register').then(res => {
      console.error(res)
    })
  }
}
</script>

<style lang="less">
#main-container {
}
</style>
