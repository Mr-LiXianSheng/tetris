<template>
  <div id="leader-board">
    <div class="top-title">Leader Board</div>

    <!-- search-container -->
    <div class="search">
      <el-input placeholder="请输入用户名" v-model="userName">
        <el-button slot="append" icon="el-icon-search"></el-button>
      </el-input>
    </div>

    <row-nav-bar @change="getLeaderBoardTableData" class="leader-board-choose" :rowNavBar="rowNavBar" />

  </div>
</template>

<script>
import rowNavBar from '@/components/rowNavBar'

export default {
  name: 'leader-board',
  data () {
    return {
      // be searched userName on leader board
      userName: '',
      // row nav bar data
      rowNavBar: {
        buttons: [
          {
            label: 'History',
            value: 'history'
          },
          {
            label: 'Month',
            value: 'month'
          },
          {
            label: 'Week',
            value: 'week'
          }
        ],
        activeIndex: 0
      }
    }
  },
  components: {
    rowNavBar
  },
  methods: {
    getLeaderBoardTableData (leaderBoard) {
      console.error(leaderBoard)
    }
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

#leader-board {
  width: 400px;
  box-shadow: 0 0 3px @main-color;
  overflow: hidden;

  .leader-board-choose {
    margin-top: 10px;
  }
}
</style>
