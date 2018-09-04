<template>
  <div id="online-chat">
    <div class="content">

    </div>
    <div class="inputer">
      <textarea class="textarea" placeholder="Please input message"></textarea>
      <div class="footer">
        Click / Enter To Send
        <div class="send-btn">Send</div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'OnlineChat',
  data () {
    return {}
  },
  computed: {
    ...mapState(['chatMessage'])
  },
  methods: {

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
