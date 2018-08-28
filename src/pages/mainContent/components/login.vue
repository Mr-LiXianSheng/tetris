<template>
  <div id="login">
    <div class="input-container">
      <input v-model="userName" @dblclick="userName = ''" type="text" placeholder="Please input your userName">
      <input v-model="passWord" @dblclick="passWord = ''" type="password" placeholder="Please input your passWord">
    </div>

    <div class="remember-password" @click="remember = !remember">
      <i :class="remember ? 'tetris-lock' : 'tetris-unlock'" />Remeber PassWord
    </div>

    <div class="login-button" @click="login">Login</div>
  </div>
</template>

<script>
import md5 from 'md5'

export default {
  name: 'Login',
  data () {
    return {
      // request path
      path: {
        loginPath: 'api/login'
      },
      // user name
      userName: '',
      // pass word
      passWord: '',
      // weather remember user's password
      remember: false,
      // temp user account info
      tempUserAccountInfo: {}
    }
  },
  methods: {
    /**
     * @description          get user's account and password from localstorage
     * @return     {Object}  local user account info
     */
    getUserAccoutInfoFromLocalStorage () {
      const userName = localStorage.getItem('userName')
      const passWord = localStorage.getItem('passWord')
      const remember = localStorage.getItem('remember')

      if (remember && userName && passWord) {
        return {
          status: true,
          account: {
            userName,
            passWord,
            remember
          }
        }
      }

      return { status: false }
    },
    /**
     * @description             set user's account and password from localstorage
     * @return     {undefined}  no return
     */
    setUserAccountInfoFromLocalStorage () {
      const { getUserAccoutInfoFromLocalStorage } = this

      const localStorageAccount = getUserAccoutInfoFromLocalStorage()

      if (!localStorageAccount.status) return

      const { userName, passWord, remember } = this

      this.userName = userName
      this.passWord = passWord
      this.remember = remember
    },
    /**
     * @description             delete user account info from local storage
     * @return     {undefined}  no return
     */
    deleteUserAccountInfoFromLocalStorage () {
      localStorage.removeItem('userName')
      localStorage.removeItem('passWord')
      localStorage.removeItem('remember')
    },
    /**
     * @description             set user account info to local storage
     * @return     {undefined}  no return
     */
    setUserAccountInfoToLocalStorage ({ userName, passWord }) {
      localStorage.removeItem('userName', userName)
      localStorage.removeItem('passWord', passWord)
      localStorage.removeItem('remember', true)
    },
    /**
     * @description           login
     * @return     {Promise}  Async Promise
     */
    async login () {
      const { getLoginReqParams, sendLoginReq, dealLoginReqRes, updateLocalStorageUserAccount } = this

      const params = getLoginReqParams()

      if (!params.status) return

      this.tempUserAccountInfo = params.params

      const res = await sendLoginReq(params.params)

      if (!res.status) return

      updateLocalStorageUserAccount()

      dealLoginReqRes(res.res)
    },
    /**
     * @description          get login request params
     * @return     {Object}  params
     */
    getLoginReqParams () {
      const { checkLoginParamsIsValid, getUserAccoutInfoFromLocalStorage, $notify } = this

      if (!checkLoginParamsIsValid()) {
        $notify('Warn', 'Please input your userName and passWord!')

        return { status: false }
      }

      const { userName, passWord } = this

      const localStorageAccount = getUserAccoutInfoFromLocalStorage()

      const params = { userName, passWord }
      const md5Params = { userName, passWord: md5(passWord) }

      // no localstorage account info
      if (!localStorageAccount.status) {
        return {
          status: true,
          params: md5Params
        }
      } else {
        if (localStorageAccount.userName === this.userName || localStorageAccount.passWord === this.passWord) {
          return {
            status: true,
            params
          }
        } else {
          return {
            status: true,
            params: md5Params
          }
        }
      }
    },
    /**
     * @description           check login params is completed
     * @return     {Boolean}  completed status
     */
    checkLoginParamsIsValid () {
      const { userName, passWord } = this

      return userName && passWord
    },
    /**
     * @description           send login request
     * @param      {Object}   user account info
     * @return     {Promise}  http request
     */
    sendLoginReq (params) {
      const { $http: { post }, path: { loginPath } } = this

      return post(loginPath, params, true)
    },
    /**
     * @description             deal login request response
     * @param      {Response}   request response
     * @return     {undefined}  no return
     */
    dealLoginReqRes (res) {
    },
    /**
     * @description             update user account info in local storage
     * @return     {undefined}  no return
     */
    updateLocalStorageUserAccount () {
      const { remember, tempUserAccountInfo } = this

      const { deleteUserAccountInfoFromLocalStorage, setUserAccountInfoToLocalStorage } = this

      remember ? setUserAccountInfoToLocalStorage(tempUserAccountInfo) : deleteUserAccountInfoFromLocalStorage()
    }
  },
  created () {
    const { setUserAccountInfoFromLocalStorage } = this

    setUserAccountInfoFromLocalStorage()
  }
}
</script>

<style lang="less">
@import '../../../assets/style/color.less';
@import '../../../assets/style/class.less';

#login {
  .input-container {
    margin-bottom: 10px;

    input {
      display: block;
      outline: none;
      border: 0px;
      box-shadow: 0 0 3px @addi-color;
      height: 40px;
      width: 100%;

      &:first-child {
        margin-bottom: 10px;
      }

      &::-webkit-input-placeholder {
        color: @addi-color;
      }
    }
  }

  .remember-password {
    height: 20px;
    box-shadow: 0 0 3px @addi-color;
    text-align: center;
    line-height: 18px;
    font-size: 13px;
    color: @addi-color;
    cursor: pointer;
    margin-bottom: 10px;

    &:hover {
      .text-shadow-small(@addi-color);
    }

    &:active {
      text-shadow: 0 0 0px;
    }
  }

  .login-button {
    position: relative;
    height: 40px;
    line-height: 40px;
    box-shadow: 0 0 3px @addi-color;
    text-align: center;
    color: @addi-color;
    font-weight: bold;
    box-sizing: border-box;
    cursor: pointer;
    transition: all 0.3s;

    &::before, &::after {
      position: absolute;
      display: block;
      content: '-';
      top: 0px;
      visibility: hidden;
      transition: all 0.3s;
    }

    &::before {
      left: 10px;
    }

    &::after {
      right: 10px;
    }

    &:hover {
      background-color: @addi-color;
      color: #fff;

      &::before {
        visibility: visible;
        left: 150px;
      }

      &::after {
        visibility: visible;
        right: 150px;
      }
    }

    &:active {
      border: 1px solid @addi-color;
    }
  }
}
</style>
