<template>
  <div id="main-container">
    <leader-board />
    <online-board />
    <login-board />
  </div>
</template>

<script>
import leaderBoard from './mainContent/leaderBoard'
import onlineBoard from './mainContent/onlineBoard'
import loginBoard from './mainContent/loginBoard'

export default {
  name: 'Main',
  data () {
    return {}
  },
  components: {
    leaderBoard,
    onlineBoard,
    loginBoard
  },
  methods: {
  },
  created () {
  }
}
</script>

<style lang="less">
#main-container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: row;
}
</style>
