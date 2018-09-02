<template>
  <div id="login-board">

    <div class="top-title">login board</div>

    <!-- nav bar -->
    <row-nav-bar v-model="currentBoard" class="login-register-choose" :rowNavBar="rowNavBar" />

    <template v-if="currentBoard === 'login'">
      <login />
    </template>

    <template v-else>
      <register />
    </template>

  </div>
</template>

<script>
import login from './components/login'
import register from './components/register'

export default {
  name: 'LoginBoard',
  components: {
    login,
    register
  },
  data () {
    return {
      // current board type
      currentBoard: '',
      // row nav bar data
      rowNavBar: {
        buttons: [
          {
            label: 'Login',
            value: 'login'
          },
          {
            label: 'Register',
            value: 'register'
          }
        ],
        activeIndex: 0
      }
    }
  },
  methods: {
  },
  created () {
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

#login-board {
  width: 400px;
  box-shadow: 0 0 3px @addi-color;

  .top-title {
    color: @addi-color;
  }

  .login-register-choose {
    margin-bottom: 10px;
    box-shadow: 0 0 3px @addi-color;
    color: @addi-color;

    .nav-bar {
      box-shadow: 0 0 3px @addi-color;

      &:hover {
        background-color: @addi-color;
      }
    }

    .active {
      background-color: @addi-color;
    }
  }
}
</style>
