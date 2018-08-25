<template>
  <div id="online-board">

    <div class="top-title">
      online-board
    </div>

    <!-- search-container -->
    <div class="search">
      <el-input placeholder="请输入用户名" v-model="userName">
        <el-button slot="append" icon="el-icon-search"></el-button>
      </el-input>
    </div>

  </div>
</template>

<script>
export default {
  name: 'OnlineBoard',
  data () {
    return {
      userName: ''
    }
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

#online-board {
  flex: 1;
  margin: 0 20px;
  box-shadow: 0 0 3px @deep-color;
}
</style>
