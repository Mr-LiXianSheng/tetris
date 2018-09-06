<template>
  <div id="confirm-container" ref="confirm">
    <div class="container">
      <div class="title">this is title</div>
      <div class="message">asdasdasdasdadzx</div>
      <div class="action">
        <div class="cancle">Cancle</div>
        <div class="confirm">Confirm</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Confirm',
  data () {
    return {}
  },
  methods: {}
}
</script>

<style lang="less">
@import '../assets/style/color.less';

#confirm-container {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  background-color: fade(gray, 30);

  .container {
    position: absolute;
    width: 400px;
    top: 30%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    box-shadow: 0 0 3px @main-color;
    border-radius: 10px;
    overflow: hidden;
    color: #fff;

    .title {
      height: 30px;
      background-color: @addi-color;
      font-weight: bold;
      font-size: 16px;
      line-height: 30px;
      text-align: center;
    }

    .message {
      position: relative;
      text-align: center;
      color: @input-color;
      font-weight: bold;
      background-color: #fff;
      box-sizing: border-box;
      padding: 20px 20px;
      word-wrap: break-word;
    }

    .action {
      height: 30px;
      text-align: center;
      line-height: 30px;
      font-weight: bold;

      .cancle, .confirm {
        float: left;
        width: 50%;
        cursor: pointer;
        box-shadow: 0 0 3px @addi-color;
      }

      .cancle {
        color: @addi-color;
        background-color: #fff;
      }

      .confirm {
        color: #fff;
        background-color: @deep-color;
      }
    }
  }
}
</style>
