<template>
  <div id="online-board">

    <div class="top-title">online-board</div>

    <!-- search input -->
    <input-with-button class="search-input" placeholder="Please input userName" v-model="search.userName">
      <template slot="button-content">
        <i class="el-icon-search" />
      </template>
    </input-with-button>

    <!-- nav bar -->
    <row-nav-bar v-model="currentBoard" class="online-interactive-choose" :rowNavBar="rowNavBar" />

    <!-- online user table -->
    <template v-if="currentBoard === 'online'">

      <!-- online user table -->
      <table-with-slot
        class="online-user-table"
        :tableData="onlineBoard">

        <!-- ranking -->
        <table-column
          prop="USERNAME"
          label="UserName">
        </table-column>

        <!-- userName -->
        <table-column
          label="Action">
          <template slot-scope="scope">
            <div class="button" @click="interActive(scope.row)">{{ getInterActiveType(scope.row.status) }}</div>
          </template>
        </table-column>

      </table-with-slot>

      <!-- pagination -->
      <pagination
        class="online-board-pagination"
        v-if="pagination.total"
        :total="pagination.total"
        :pageSize="pagination.pageSize"
        :currentPage="pagination.pageIndex"
        @change="turnPage"/>
    </template>

    <template class="online-user-chat" v-else>
      <online-chat />
    </template>

  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

import onlineChat from './components/onlineChat'

const statusToInterActiveTypeMap = new Map([
  ['online', 'Fight'],
  ['fight', 'View'],
  ['play', 'View']
])

export default {
  name: 'OnlineBoard',
  components: {
    onlineChat
  },
  data () {
    return {
      // search params
      search: {
        // userName on online board
        userName: ''
      },
      // current board type online / chat
      currentBoard: '',
      // row nav bar data
      rowNavBar: {
        buttons: [
          {
            label: 'OnLine',
            value: 'online'
          },
          {
            label: 'Chat',
            value: 'chat'
          }
        ],
        activeIndex: 0
      },
      // online user board
      onlineBoard: [],
      // pagination data
      pagination: {
        total: 0,
        pageSize: 15,
        pageIndex: 1
      }
    }
  },
  computed: {
    ...mapState(['onlineBoardList'])
  },
  watch: {
    onlineBoardList () {
      const { getOnlineBoardListData } = this

      getOnlineBoardListData()
    }
  },
  methods: {
    /**
     * @description           Get online board data
     * @return     {Promise}  Async Promise
     */
    async getOnlineBoardListData (init = true) {
      const { initPagination, getOnlineBoardDataReqParams, sendGetOnlineBoardDataReq, dealGetOnlineBoardDataReqRes } = this

      if (init) initPagination()

      const params = getOnlineBoardDataReqParams()

      if (!params.status) return

      const res = await sendGetOnlineBoardDataReq(params.params)

      if (!res.status) return

      dealGetOnlineBoardDataReqRes(res.res)
    },
    /**
     * @description          Get online board data's request params
     * @return     {Object}  Request params
     */
    getOnlineBoardDataReqParams () {
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
     * @description           Send get online board data request
     * @return     {Promise}  Request Promise
     */
    sendGetOnlineBoardDataReq (params) {
      const { getOnlineBoardData, $notify } = this

      return new Promise(resolve => {
        const res = getOnlineBoardData()(params)

        if (res.code === 'success') {
          resolve({
            status: true,
            res
          })
        } else {
          $notify('Fail', res.msg, 'error')

          this.onlineBoard = []

          resolve({ status: false })
        }
      })
    },
    /**
     * @description            Deal get online board data req response
     * @return    {undefined}  no return value
     */
    dealGetOnlineBoardDataReqRes (res) {
      this.onlineBoard = res.data

      const { updatePagination } = this

      updatePagination(res.page)
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
     * @description             init pagination
     * @return     {undefined}  no return
     */
    initPagination () {
      const { pagination } = this

      pagination.pageIndex = 1
    },
    /**
     * @description           turn to page index
     * @return     {Promise}  Async Promise
     */
    turnPage (index) {
      const { pagination } = this

      pagination.pageIndex = index

      const { getOnlineBoardListData } = this

      return getOnlineBoardListData(false)
    },
    /**
     * @description             get inter active type
     * @param      {string}     user current status
     * @return     {undefined}  no return
     */
    getInterActiveType (status) {
      if (statusToInterActiveTypeMap.has(status)) {
        return statusToInterActiveTypeMap.get(status)
      } else {
        return 'ERROR'
      }
    },
    /**
     * @description             inter active with others
     * @return     {undefined}  no return
     */
    interActive ({UID, USERNAME, status}) {
    },
    ...mapGetters(['getOnlineBoardData'])
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

#online-board {
  flex: 1;
  margin: 0 20px;
  box-shadow: 0 0 3px @deep-color;
  overflow: hidden;
  display: flex;
  flex-direction: column;

  .top-title {
    color: @deep-color;
  }

  .search-input {
    margin-bottom: 10px;

    .input {
      box-shadow: 0 0 3px @deep-color;

      &::-webkit-input-placeholder {
        color: @deep-color;
      }
    }

    .button {
      color: @deep-color;
      box-shadow: 0 0 3px @deep-color;

      &:hover {
        color: #fff;
        background-color: @deep-color;
      }

      &:active {
        border: 1px solid @deep-color;
      }
    }
  }

  .online-interactive-choose {
    box-shadow: 0 0 3px @deep-color;
    color: @deep-color;

    .nav-bar {
      box-shadow: 0 0 3px @deep-color;

      &:hover {
        background-color: @deep-color;
      }

      &:active {
        border: 1px solid fade(@deep-color, 50);
      }
    }

    .active {
      background-color: @deep-color;
    }
  }

  .online-user-table {
    margin-top: 10px;
    margin-bottom: 10px;

    .table-column {

      .title, .content {
        color: @deep-color;
        border-bottom: 1px solid fade(@deep-color, 50);
      }

      .title {
        border-top: 1px solid fade(@deep-color, 50);
      }
    }

    .table-no-data {
      color: @deep-color;
      box-shadow: 0 0 3px @deep-color;
    }

    .button {
      cursor: pointer;
      box-sizing: border-box;
      transition: all 0.3s;

      &:hover {
        color: #fff;
        background-color: @deep-color;
      }

      &:active {
        border: 1px solid fade(@deep-color, 50);
      }
    }
  }

  .online-board-pagination {
    box-shadow: 0 0 3px @deep-color;

    .hover {
      &:hover {
        color: @deep-color;
        text-shadow: 0 0 1px #fff,
        0 0 2px #fff,
        0 0 3px #fff,
        0 0 4px @deep-color,
        0 0 5px @deep-color;

        &::after {
          box-shadow: 0 0 3px @deep-color;
        }
      }
    }

    .prev, .next {
      color: @deep-color;
      .hover;
    }

    .pagers {

      div {
        color: @deep-color;
        .hover;
      }
    }

    .first, .last {
      color: @deep-color;
      .hover;
    }
  }
}
</style>
