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
          prop="userName"
          label="UserName">
        </table-column>

        <!-- userName -->
        <table-column
          label="Action">
        </table-column>

      </table-with-slot>

      <!-- pagination -->
      <pagination
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
import onlineChat from './components/onlineChat'

export default {
  name: 'OnlineBoard',
  components: {
    onlineChat
  },
  data () {
    return {
      // request path
      path: {
        // get leader board request path GET
        leaderBoardPath: '/api/register/aa'
      },
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
    margin-bottom: 10px;
    box-shadow: 0 0 3px @deep-color;
    color: @deep-color;

    .nav-bar {
      box-shadow: 0 0 3px @deep-color;

      &:hover {
        background-color: @deep-color;
      }
    }

    .active {
      background-color: @deep-color;
    }
  }

  .online-user-table {

    .table-column {

      .title, .content {
        color: @deep-color;
        border-top: 1px solid fade(@deep-color, 50);
        border-bottom: 1px solid fade(@deep-color, 50);
      }
    }

    .table-no-data {
      color: @deep-color;
      box-shadow: 0 0 3px @deep-color;
    }
  }
}
</style>
