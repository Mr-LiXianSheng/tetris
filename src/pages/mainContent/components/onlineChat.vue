<template>
  <div id="online-chat">
    <div class="content">

      <template v-for="message in chatMessage">
        <div class="online" v-if="message.type === 'online'" :key="message.unique">
          <span class="info">{{ message.text }}</span>
        </div>

        <div class="offline" v-if="message.type === 'offline'" :key="message.unique">
          <span class="info">{{ message.text }}</span>
        </div>

        <div class="other" v-if="message.type === 'other'" :key="message.unique">
          <div class="header">
            <span class="user-name">{{ message.USERNAME }}</span>
            <span class="time">{{ message.time }}</span>
          </div>
          <div class="info">
            <span class="text">{{ message.text }}</span>
          </div>
        </div>

        <div class="self" v-if="message.type === 'self'" :key="message.unique">
          <div class="header">
            <span class="time">{{ message.time }}</span>
            <span class="user-name">{{ message.USERNAME }}</span>
          </div>
          <div class="info">
            <span class="text">{{ message.text }}</span>
          </div>
        </div>
      </template>

    </div>
    <div class="inputer">
      <textarea class="textarea"
        v-model="userInput"
        placeholder="Please input message"
        :disabled="!websocket"
        @keydown.enter="sendChatMessage"/>
      <div class="footer">
        Click / Enter To Send
        <div class="send-btn" @click="sendChatMessage">Send</div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'

export default {
  name: 'OnlineChat',
  data () {
    return {
      // user input message
      userInput: ''
    }
  },
  computed: {
    ...mapState(['chatMessage', 'websocket', 'userBaseInfo'])
  },
  methods: {
    sendChatMessage () {
      const { userInput, $nextTick, userBaseInfo: { UID, USERNAME } } = this

      const { sendWSMessage } = this

      if (!userInput) return false

      const message = {
        type: 'chat',
        UID,
        USERNAME,
        text: userInput
      }

      sendWSMessage(message)

      $nextTick(e => {
        this.userInput = ''
      })
    },
    ...mapMutations(['sendWSMessage'])
  }
}
</script>

<style lang="less">
@import '../../../assets/style/color.less';

#online-chat {
  display: flex;
  flex-direction: column;
  flex: 1;

  .content {
    flex: 1;
    overflow-y: scroll;
    &::-webkit-scrollbar {
      width: 0px;
      height: 0px;
    }

    .online, .offline {
      height: 40px;
      line-height: 40px;
      text-align: center;

      .info {
        padding: 5px 10px;
        box-shadow: 0 0 3px @main-color;
        background-color: @main-color;
        color: #fff;
        font-size: 13px;
      }
    }

    .offline {
      .info {
        box-shadow: 0 0 3px @deep-color;
        background-color: @deep-color;
      }
    }

    .other, .self {
      box-sizing: border-box;
      padding: 0 10px;
      margin-bottom: 10px;

      .header {
        height: 20px;
        line-height: 20px;
        font-size: 12px;
        color: @font-thin;
        // margin-bottom: 5px;

        .user-name {
          margin-right: 20px;
        }

        .time {
          line-height: 22px;
        }
      }

      .info {

        .text {
          display: inline-block;
          word-break: break-all;
          max-width: 80%;
          background-color: @deep-color;
          border-radius: 10px;
          box-sizing: border-box;
          padding: 5px;
          color: #fff;
          font-size: 14px;
        }
      }
    }

    .self {
      .header {
        text-align: right;

        .user-name {
          margin-right: 0px;
          margin-left: 20px;
        }
      }

      .info {
        text-align: right;

        .text {
          text-align: left;
          background-color: @addi-color;
        }
      }
    }
  }

  .inputer {
    .textarea {
      width: 100%;
      border: 0px;
      resize: none;
      box-shadow: 0 0 3px @deep-color;
      height: 100px;
      outline: none;
      text-indent: 10px;
      margin-bottom: -3px;
      line-height: 30px;

      &::-webkit-input-placeholder {
        color: fade(@deep-color, 100);
      }
    }

    .footer {
      height: 40px;
      line-height: 40px;
      text-align: right;
      color: fade(@deep-color, 50);
      font-size: 13px;

      .send-btn {
        width: 100px;
        float: right;
        text-align: center;
        box-shadow: 0 0 3px @deep-color;
        color: @deep-color;
        margin-left: 10px;
        font-size: 15px;
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
  }
}
</style>
