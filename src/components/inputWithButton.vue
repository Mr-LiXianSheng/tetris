<template>
  <div class="input-with-button">
    <input class="input"
      type="text"
      :value="value"
      :placeholder="placeholder"
      @input="$emit('input', $event.target.value)"
      @dblclick="$emit('input', '')"
      @keyup.enter="$emit('enter')"/>
    <div class="button">
      <slot name="button-content"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InputWithButton',
  props: [
    'value',
    'placeholder'
  ],
  methods: {
  }
}
</script>

<style lang="less">
@import '../assets/style/color.less';

.input-with-button {
  position: relative;

  .input {
    outline: none;
    border: 0px;
    display: block;
    box-shadow: 0 0 3px @main-color;
    height: 40px;
    width: 100%;

    &::-webkit-input-placeholder {
      color: fade(@main-color, 80);
    }
  }

  .button {
    position: absolute;
    width: 0px;
    height: 40px;
    padding: 0px;
    top: 0px;
    right: 0px;
    box-shadow: 0 0 3px @main-color;
    line-height: 40px;
    text-align: center;
    color: @main-color;
    box-sizing: border-box;
    cursor: pointer;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s;

    i {
      font-size: 20px;
      vertical-align: middle;
      font-weight: bold;
    }

    &:hover {
      background-color: @main-color;
      color: #fff;
    }

    &:active {
      border: 1px solid darken(@main-color, 10);
    }
  }

  &:hover .button {
    visibility: visible;
    opacity: 1;
    width: auto;
    padding: 0 40px;
  }
}
</style>
