<template>
  <div id="leader-board">
    <div class="top-title">Leader Board</div>

    <!-- search input -->
    <input-with-button class="search-input" placeholder="Please input userName" v-model="search.userName" @enter="getLeaderBoardData">
      <template slot="button-content">
        <i class="el-icon-search" />
      </template>
    </input-with-button>

    <!-- nav bar -->
    <row-nav-bar v-model="search.leaderBoard" @change="getLeaderBoardData" class="leader-board-choose" :rowNavBar="rowNavBar" />

    <!-- leader board table -->
    <table-with-slot
    class="leader-board-table"
    :tableData="leaderBoard">

      <!-- ranking -->
      <table-column
        prop="index"
        label="Ranking"
        width="50">
      </table-column>

      <!-- userName -->
      <table-column
        prop="userName"
        label="UserName">
      </table-column>

      <!-- score -->
      <table-column
        prop="score"
        label="Score">
      </table-column>

      <table-column
        label="Score">
        <template slot-scope="scope">
          {{ scope.row.time }}
        </template>
      </table-column>

    </table-with-slot>
  </div>
</template>

<script>
import inputWithButton from '@/components/inputWithButton'
import rowNavBar from '@/components/rowNavBar'
import tableWithSlot from '@/components/table/tableWithSlot'
import tableColumn from '@/components/table/tableColumn'

export default {
  name: 'leader-board',
  components: {
    inputWithButton,
    rowNavBar,
    tableWithSlot,
    tableColumn
  },
  data () {
    return {
      // request path
      path: {
        // get leader board request path GET
        leaderBoardPath: 'leaderBoard'
      },
      // search params
      search: {
        // userName on leader board
        userName: '',
        // current leader board
        leaderBoard: ''
      },
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
      },
      // leader board data
      leaderBoard: [
        {
          userName: 'JiaMing',
          score: '1200',
          time: '2018-03-21 19:22'
        },
        {
          userName: 'ZhangSan',
          score: '1100',
          time: '2018-03-21 11:22'
        },
        {
          userName: 'JiaMing',
          score: '800',
          time: '2018-04-21 19:22'
        }
      ],
      // pagination data
      pagination: {
        total: 0,
        pageSize: 15,
        pageIndex: 1,
        pageCount: 1
      }
    }
  },
  methods: {
    /**
     * @description           Get leader board data
     * @return     {Promise}  Async Promise
     */
    async getLeaderBoardData () {
      const { getLeaderBoardDataReqParams, sendGetLeaderBoardDataReq, dealGetLeaderBoardDataReqRes } = this

      const params = getLeaderBoardDataReqParams()

      if (!params.status) return

      const res = await sendGetLeaderBoardDataReq(params.params)

      if (!res.status) return false

      dealGetLeaderBoardDataReqRes(res.res)
    },
    /**
     * @description          Get leader board data's request params
     * @return     {Object}  Request params
     */
    getLeaderBoardDataReqParams () {
      const { search } = this

      return {
        status: true,
        params: {
          ...search
        }
      }
    },
    /**
     * @description           Send get leader board data request
     * @return     {Promise}  Request Promise
     */
    sendGetLeaderBoardDataReq (params) {
      const { $http: { get }, path: { leaderBoardPath }, $notify } = this

      return get(leaderBoardPath, params).then(res => ({
        status: true,
        res
      })).catch(e => {
        $notify()

        return { status: false }
      })
    },
    /**
     * @description            Deal get leader board data req response
     * @return    {undefined}  no return value
     */
    dealGetLeaderBoardDataReqRes (res) {
      const { $notify } = this

      if (res.code === 'success') {
        this.leaderBoard = res.data
      } else {
        $notify('fail', res.msg)
      }
    }
  },
  created () {
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

#leader-board {
  width: 400px;
  box-shadow: 0 0 3px @main-color;
  overflow: hidden;

  .top-title {
    color: @main-color;
  }

  .search-input {
    margin-bottom: 10px;
  }

  .leader-board-choose {
    margin-bottom: 10px;
  }
}
</style>
