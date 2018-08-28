<template>
  <div id="register">
    <div class="input-container">
      <input v-model="userName" @dblclick="userName = ''" type="text" placeholder="Please input your userName (MaxLength-16)">
      <input v-model="passWord" @dblclick="passWord = ''" type="password" placeholder="Please input your passWord (MaxLength-16)">
      <input v-model="rePassWord" @dblclick="rePassWord = ''" type="password" placeholder="Please repeat input your passWord (MaxLength-16)">
      <input v-model="mail" @dblclick="mail = ''" type="text" placeholder="Please input your E-Mail (MaxLength-32)">
    </div>

    <div class="register-button" @click="register">Register</div>
  </div>
</template>

<script>
export default {
  name: 'Register',
  data () {
    return {
      path: {
        registerPath: 'api/registration/register'
      },
      // user name
      userName: '',
      // pass word
      passWord: '',
      // repeat pass word
      rePassWord: '',
      // user e mail
      mail: ''
    }
  },
  methods: {
    /**
     * @description           Register
     * @return     {Promise}  Async Promise
     */
    async register () {
      const { getRegisterReqParams, sendRegisterReq, dealRegisterReqRes } = this

      const params = getRegisterReqParams()

      if (!params.status) return

      const res = await sendRegisterReq(params.params)

      if (!res.status) return

      dealRegisterReqRes(res)
    },
    /**
     * @description          Get register request params
     * @return     {Object}  Request params
     */
    getRegisterReqParams () {
      const { checkRegisterReqParamsIsValid } = this

      if (!checkRegisterReqParamsIsValid()) return { status: false }

      let { userName, passWord, mail } = this

      userName = userName.trim()

      return {
        status: true,
        params: {
          userName,
          passWord,
          mail
        }
      }
    },
    /**
     * @description           check register request params weather valid
     * @return     {Boolean}  params completed
     */
    checkRegisterReqParamsIsValid () {
      const { userName, passWord, rePassWord, mail } = this

      const { $notify } = this

      if (!userName || !passWord || !rePassWord || !mail) {
        $notify('Warn', 'Please fill all info!')

        return false
      }

      if (userName.length > 16 || passWord.length > 16 || mail.length > 32) {
        $notify('Warn', 'Please input your info in appropriate length!')

        return false
      }

      if (passWord !== rePassWord) {
        $notify('Warn', 'Your passWord is inconsistent!')

        return false
      }

      if (passWord.length < 6) {
        $notify('Warn', 'Your passWord\'s length is too short!')

        return false
      }

      return true
    },
    /**
     * @description           Send register request
     * @param      {Object}   Request params
     * @return     {Promise}  Request Promise
     */
    sendRegisterReq (params) {
      const { $http: { post }, path: { registerPath } } = this

      return post(registerPath, params, true)
    },
    /**
     * @description             deal register request response
     * @param      {Response}
     * @return     {undefined}  no return
     */
    dealRegisterReqRes (res) {
    }
  }
}
</script>

<style lang="less">
@import '../../../assets/style/color.less';

#register {
  .input-container {
    margin-bottom: 10px;

    input {
      display: block;
      outline: none;
      border: 0px;
      box-shadow: 0 0 3px @addi-color;
      height: 40px;
      width: 100%;

      &:nth-child(2) {
        margin: 10px 0px;
      }

      &:last-child {
        margin-top: 10px;
      }

      &::-webkit-input-placeholder {
        color: @addi-color;
      }
    }
  }

  .register-button {
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
