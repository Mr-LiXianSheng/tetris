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
        label="Ranking">
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

    </table-with-slot>

    <!-- pagination -->
    <pagination
      v-if="pagination.total"
      :total="pagination.total"
      :pageSize="pagination.pageSize"
      :currentPage="pagination.pageIndex"
      @change="turnPage"/>
  </div>
</template>

<script>

export default {
  name: 'leader-board',
  data () {
    return {
      // request path
      path: {
        // get leader board request path GET
        leaderBoardPath: '/api/register/aa'
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
      leaderBoard: [],
      // pagination data
      pagination: {
        total: 0,
        pageSize: 15,
        pageIndex: 1
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

      return dealGetLeaderBoardDataReqRes(res.res)
    },
    /**
     * @description          Get leader board data's request params
     * @return     {Object}  Request params
     */
    getLeaderBoardDataReqParams () {
      const { search, pagination: { pageSize, pageIndex } } = this

      return {
        status: true,
        params: {
          ...search,
          pageSize,
          pageIndex
        }
      }
    },
    /**
     * @description           Send get leader board data request
     * @return     {Promise}  Request Promise
     */
    sendGetLeaderBoardDataReq (params) {
      const { $http: { get }, path: { leaderBoardPath } } = this

      return get(leaderBoardPath, params)
    },
    /**
     * @description            Deal get leader board data req response
     * @return    {undefined}  no return value
     */
    dealGetLeaderBoardDataReqRes (res) {
      this.leaderBoard = res.data

      const { updatePagination } = this

      updatePagination(res.data)
    },
    /**
     * @description             update pagination data
     * @return     {undefined}  no return
     */
    updatePagination ({ total, pageSize, pageIndex }) {
      const { pagination } = this

      pagination.total = total
      pagination.pageSize = pageSize
      pagination.pageIndex = pageIndex
    },
    /**
     * @description           turn to page index
     * @return     {Promise}  Async Promise
     */
    async turnPage (index) {
      const { pagination } = this

      pagination.pageIndex = index

      const { getLeaderBoardData } = this

      return getLeaderBoardData()
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

  .leader-board-table {
    margin-bottom: 10px;
  }
}
</style>
