import Vue from 'vue'
import Vuex from 'vuex'

// const $http = Vue.prototype.$http

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // interactive token, get it by login response
    userBaseInfo: {},
    // leaderBoardList Data update time stamp
    leaderBoardListUpdateTime: '',
    // leaderBoardHistoryList data
    leaderBoardHistoryList: [],
    // leaderBoardMonthList data
    leaderBoardMonthList: [],
    // leaderBoardWeekList data
    leaderBoardWeekList: [],
    // onlineBoardList data
    onlineBoardList: [],
    // chat message data
    chatMessage: []
  },
  mutations: {
    // set user base info, get it by login response
    setUserBaseInfo (state, token) {
      state.userBaseInfo = token
    },
    // set leader board history list data
    setLeaderBoardHistoryList (state, list) {
      state.leaderBoardHistoryList = list

      state.leaderBoardListUpdateTime = (new Date()).getTime()
    },
    // set leader board month list data
    setLeaderBoardMonthList (state, list) {
      state.leaderBoardMonthList = list

      state.leaderBoardListUpdateTime = (new Date()).getTime()
    },
    // set leader board week list data
    setLeaderBoardWeekList (state, list) {
      state.leaderBoardWeekList = list

      state.leaderBoardListUpdateTime = (new Date()).getTime()
    },
    // set online board list data
    setOnlineBoardList (state, list) {
      state.onlineBoardList = list
    },
    // push new message
    pushCharMessage (state, message) {
      state.chatMessage.push(message)
    }
  },
  actions: {
  },
  getters: {
    // get leader board data
    getLeaderBoardData (state) {
      return params => {
        const { userName, leaderBoard, pageSize, pageIndex } = params

        const response = {
          code: 'error',
          msg: 'Abnormal Data!',
          data: [],
          page: {
            total: 0,
            pageSize,
            pageIndex,
            pageCount: 0
          }
        }

        if (!leaderBoard) return response

        let tempLeaderBoard = []

        if (leaderBoard === 'history') {
          tempLeaderBoard = state.leaderBoardHistoryList
        } else if (leaderBoard === 'month') {
          return response
        } else if (leaderBoard === 'week') {
          return response
        }

        tempLeaderBoard = tempLeaderBoard.filter(({USERNAME: tempUserName}) => {
          if (!userName) return true

          return tempUserName.indexOf(userName) !== -1
        })

        response.code = 'success'
        response.msg = 'success!'
        response.data = tempLeaderBoard.slice(pageSize * (pageIndex - 1), pageSize * pageIndex)
        response.page.total = tempLeaderBoard.length
        response.page.pageCount = Math.ceil(tempLeaderBoard.length / pageSize)

        return response
      }
    },
    // get online board data
    getOnlineBoardData (state) {
      return params => {
        const { userName, pageSize, pageIndex } = params

        const response = {
          code: 'error',
          msg: 'Abnormal Data!',
          data: [],
          page: {
            total: 0,
            pageSize,
            pageIndex,
            pageCount: 0
          }
        }

        let tempOnlineBoard = state.onlineBoardList

        tempOnlineBoard = tempOnlineBoard.filter(({USERNAME: tempUserName}) => {
          if (!userName) return true

          return tempUserName.indexOf(userName) !== -1
        })

        response.code = 'success'
        response.msg = 'success!'
        response.data = tempOnlineBoard.slice(pageSize * (pageIndex - 1), pageSize * pageIndex)
        response.page.total = tempOnlineBoard.length
        response.page.pageCount = Math.ceil(tempOnlineBoard.length / pageSize)

        return response
      }
    }
  }
})
